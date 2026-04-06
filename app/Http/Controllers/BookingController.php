<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tent;
use App\Models\Booking;
use App\Models\Price;
use App\Models\Slot;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Resolve date range for the index view: use provided dates or default to today/tomorrow.
     */
    private function getDateRange(Request $request): array
    {
        $checkIn = $request->query('check_in') ?? $request->input('check_in');
        $checkOut = $request->query('check_out') ?? $request->input('check_out');

        if ($checkIn && $checkOut) {
            $today = Carbon::today()->toDateString();
            if ($checkIn < $today) {
                $checkIn = $today;
                $checkOut = Carbon::parse($checkIn)->addDay()->toDateString();
            }
            return ['currentDate' => $checkIn, 'nextDate' => $checkOut];
        }
        return [
            'currentDate' => Carbon::today()->toDateString(),
            'nextDate' => Carbon::tomorrow()->toDateString(),
        ];
    }

    private function isDateBlocked($checkIn, $checkOut)
    {
        return \App\Models\BlockoutDate::where('start_date', '<', $checkOut)
            ->where('end_date', '>', $checkIn)
            ->exists();
    }

    /**
     * Get tents available for the given check-in/check-out and minimum guests.
     */
    private function getAvailableTents(string $checkIn, string $checkOut, int $guests, int $adults = 0, string $sort = 'recommended')
    {
        if ($this->isDateBlocked($checkIn, $checkOut)) {
            return Tent::whereRaw('1 = 0');
        }

        $availabilityQuery = function ($query) use ($checkIn, $checkOut) {
            $query->whereDoesntHave('pauses', function ($q) use ($checkIn, $checkOut) {
                      $q->where('start_date', '<', $checkOut)
                        ->where('end_date', '>', $checkIn);
                  })
                  ->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn)
                    ->where(function ($q) {
                        $q->where('status', 'confirmed')
                            ->orWhere(function ($q2) {
                                $q2->where('status', 'pending')
                                    ->where('expires_at', '>', now());
                            });
                    });
            });
        };

        $query = Tent::with(['slots' => $availabilityQuery, 'prices', 'images'])
            ->whereHas('slots', $availabilityQuery)
            ->withCount(['slots' => $availabilityQuery])
            ->where('max_capacity', '>=', $guests)
            ->where('min_capacity', '<=', $adults);

        if ($sort === 'price_low') {
            $query->addSelect([
                'min_price' => Price::selectRaw('MIN(CASE WHEN tents.pricing_type = "person" THEN adult_price ELSE price_weekday END)')
                    ->whereColumn('tent_id', 'tents.id')
            ])->orderBy('min_price', 'asc');
        } else {
            // Default: Recommended (can be defined by ID or a specific column if available)
            $query->orderBy('id', 'desc');
        }

        return $query;
    }

    private function computePrice($id, int $adults, int $children, string $checkIn, string $checkOut)
    {
        $tent = Tent::with('prices')->where('id', $id)->first();
        $startDate = Carbon::parse($checkIn);
        $endDate = Carbon::parse($checkOut);
        $nights = $startDate->diffInDays($endDate) ?: 1;

        $totalPrice = 0;
        if ($tent->pricing_type == 'person') {
            $price = $tent->prices->first();
            $totalAdultPrice = ($price->adult_price ?? 0) * $adults * $nights;
            $totalChildPrice = ($price->child_price ?? 0) * $children * $nights;
            $totalPrice = $totalAdultPrice + $totalChildPrice;
        } else {
            $matchedPrice = $tent->prices->where('capacity', '=', $adults)->first();
            if (!$matchedPrice) {
                $matchedPrice = $tent->prices->sortByDesc('capacity')->first();
            }

            // Calculate price night by night (Sat are weekends)
            for ($i = 0; $i < $nights; $i++) {
                $currentDay = $startDate->copy()->addDays($i);
                $isWeekend = in_array($currentDay->dayOfWeek, [Carbon::SATURDAY]);

                if ($isWeekend) {
                    $dailyPrice = $matchedPrice->price_weekend ?? 0;
                } else {
                    $dailyPrice = $matchedPrice->price_weekday ?? 0;
                }

                // Add child surcharge
                if ($children > 0 && isset($matchedPrice->child_price) && $matchedPrice->child_price > 0) {
                    $dailyPrice += ($matchedPrice->child_price * $children);
                }

                $totalPrice += $dailyPrice;
            }
        }
        return $totalPrice;
    }
    public function user(Request $request)
    {
        ['currentDate' => $currentDate, 'nextDate' => $nextDate] = $this->getDateRange($request);

        $adults = (int) $request->input('adults', $request->query('adults', 2));
        $children = (int) $request->input('children', $request->query('children', 0));
        $guests = $adults + $children;
        $sort = $request->query('sort', 'recommended');

        $tents = $this->getAvailableTents($currentDate, $nextDate, $guests, $adults, $sort)
            ->paginate(4)
            ->withQueryString();

        $upcomingBlockouts = \App\Models\BlockoutDate::where('end_date', '>=', today())
            ->orderBy('start_date', 'asc')
            ->get();

        return view('user.index', compact('tents', 'currentDate', 'nextDate', 'adults', 'children', 'sort', 'upcomingBlockouts'));
    }

    public function show($id, Request $request)
    {
        ['currentDate' => $checkIn, 'nextDate' => $checkOut] = $this->getDateRange($request);

        $isBlocked = $this->isDateBlocked($checkIn, $checkOut);

        $availabilityQuery = function ($query) use ($checkIn, $checkOut, $isBlocked) {
            if ($isBlocked) {
                $query->whereRaw('1 = 0');
                return;
            }
            $query->whereDoesntHave('pauses', function ($q) use ($checkIn, $checkOut) {
                      $q->where('start_date', '<', $checkOut)
                        ->where('end_date', '>', $checkIn);
                  })
                  ->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn)
                    ->where(function ($q) {
                        $q->where('status', 'confirmed')
                            ->orWhere(function ($q2) {
                                $q2->where('status', 'pending')
                                    ->where('expires_at', '>', now());
                            });
                    });
            });
        };

        $tent = Tent::with(['slots' => $availabilityQuery, 'prices', 'images'])
            ->withCount(['slots' => $availabilityQuery])
            ->findOrFail($id);

        $adults = (int) $request->query('adults', 2);
        $children = (int) $request->query('children', 0);
        $guests = $adults + $children;

        // Record current viewer
        $this->trackViewer($id);

        $upcomingBlockouts = \App\Models\BlockoutDate::where('end_date', '>=', today())
            ->orderBy('start_date', 'asc')
            ->get();

        return view('user.show', compact('tent', 'checkIn', 'checkOut', 'guests', 'adults', 'children', 'upcomingBlockouts'));
    }

    /**
     * Track and return real-time viewer count for a tent.
     */
    private function trackViewer($tentId)
    {
        $cacheKey = "tent_viewers_{$tentId}";
        $sessionId = session()->getId();
        $viewers = Cache::get($cacheKey, []);

        // Add or update current viewer with current timestamp
        $viewers[$sessionId] = now()->timestamp;

        // Remove viewers who haven't been active for more than 5 minutes
        $viewers = array_filter($viewers, function ($timestamp) {
            return $timestamp > now()->subMinutes(5)->timestamp;
        });

        Cache::put($cacheKey, $viewers, now()->addMinutes(10));

        return count($viewers);
    }

    public function getViewerCount($id)
    {
        $cacheKey = "tent_viewers_{$id}";
        $viewers = Cache::get($cacheKey, []);

        // Clean up expired (this helps keep it accurate when polled)
        $viewers = array_filter($viewers, function ($timestamp) {
            return $timestamp > now()->subMinutes(5)->timestamp;
        });

        return response()->json(['count' => count($viewers)]);
    }

    // checkAvailability method replaced by user method consolidation

    public function checkout(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }
        $expiredAt = now()->addMinute(30);
        $validated = $request->validate([
            'tent' => 'required|exists:tents,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'customer_name' => 'nullable|string',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'nullable|string',
            'customer_address' => 'nullable|string',
        ]);
        $totalPrice = $this->computePrice(
            $validated['tent'],
            (int) $request->input('adults', 2),
            (int) $request->input('children', 0),
            $validated['check_in_date'],
            $validated['check_out_date']
        );
        $validated['total_price'] = $totalPrice;
        $validated['expires_at'] = $expiredAt;

        $user = auth()->user();

        return DB::transaction(function () use ($validated, $totalPrice, $expiredAt, $user) {

            $tentId = $validated['tent'];
            $checkIn = $validated['check_in_date'];
            $checkOut = $validated['check_out_date'];

            if ($this->isDateBlocked($checkIn, $checkOut)) {
                return redirect()->back()->with('error', 'These dates are currently unavailable due to a campsite closure.');
            }

            $availableSlot = Slot::where('tent_id', $tentId)
                ->whereDoesntHave('pauses', function ($q) use ($checkIn, $checkOut) {
                    $q->where('start_date', '<', $checkOut)
                      ->where('end_date', '>', $checkIn);
                })
                ->lockForUpdate()
                ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
                    $query->where('check_in_date', '<', $checkOut)
                        ->where('check_out_date', '>', $checkIn)
                        ->where(function ($q) {
                            $q->where('status', 'confirmed')
                                ->orWhere(function ($q2) {
                                    $q2->where('status', 'pending')
                                        ->where('expires_at', '>', now());
                                });
                        });
                })
                ->orderBy('id')
                ->first();

            if (!$availableSlot) {
                abort(409, 'No available slot for selected dates.');
            }
            $tent = Tent::with('images')->findOrFail($tentId);
            $booking = Booking::create([
                'user_id' => $user->id,
                'slot_id' => $availableSlot->id,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'status' => 'pending',
                'total_price' => $totalPrice,
                'expires_at' => $expiredAt,
                'customer_email'=> $user->email,
                'customer_name'=> $user->name,
            ]);

            $amountCents = (int) round($totalPrice * 100);

            $productData = [
                'name' => $tent->name . " booking from " . $checkIn . " to " . $checkOut,
            ];

            if ($tent->images->isNotEmpty()) {
                $firstImage = $tent->images->first()->image_path;
                $imageUrl = filter_var($firstImage, FILTER_VALIDATE_URL) ? $firstImage : secure_asset('storage/' . $firstImage);
                
                // Stripe requires publicly accessible HTTPS URLs, so we avoid adding it on local dev (localhost) 
                // to prevent Stripe InvalidRequestException crashing the checkout process locally.
                if (str_starts_with($imageUrl, 'https://') && !str_contains($imageUrl, 'localhost') && !str_contains($imageUrl, '127.0.0.1')) {
                    $productData['images'] = [$imageUrl];
                }
            }

            $session = $user->checkout(
                [
                    [
                        'price_data' => [
                            'currency' => config('cashier.currency') ?: 'myr',
                            'product_data' => $productData,
                            'unit_amount' => $amountCents,
                        ],
                        'quantity' => 1,
                    ]
                ],
                [
                    'mode' => 'payment',
                    'expires_at' => $expiredAt->timestamp,
                    'metadata' => [
                        'booking_id' => $booking->id
                    ],
                    'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('checkout.cancel', ['booking' => $booking->id]),
                    'phone_number_collection' => [
                        'enabled' => true,
                    ]
                ]
            );
            $booking->update(['stripe_session_id' => $session->id]);
            return redirect()->away($session->url);
        });
    }

    public function checkoutSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('booking.index');
        }

        // Securely find the booking for the current user
        $booking = Booking::with('slot.tent')
            ->where('stripe_session_id', $sessionId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('checkout-success', compact('booking'));
    }

    public function checkoutCancel(Request $request)
    {
        $bookingId = $request->query('booking');
        if ($bookingId) {
            $booking = Booking::where('id', $bookingId)
                ->where('user_id', auth()->id())
                ->where('status', 'pending')
                ->first();

            if ($booking) {
                $booking->delete();
            }
        }

        return redirect()->route('booking.index')->with('error', 'Booking payment was cancelled. Your reservation has been removed.');
    }

    public function myBookings(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $query = Booking::with('slot.tent')
            ->where('user_id', auth()->id());

        // Search by Booking ID, User Name, or Campsite/Slot
        if ($request->filled('search')) {
            $search = $request->search;
            // Extract numbers from #BK-123456 format if present
            $searchId = preg_replace('/[^0-9]/', '', $search);

            $query->where(function ($q) use ($search, $searchId) {
                // If there's a numeric ID, search by Booking ID
                if (!empty($searchId)) {
                    $q->where('id', (int)$searchId);
                }

                // Search User Name / Email
                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });

                // Search Campsite Name or Slot Number
                $q->orWhereHas('slot.tent', function ($tentQuery) use ($search) {
                    $tentQuery->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('slot', function ($slotQuery) use ($search) {
                    $slotQuery->where('tent_number', 'like', '%' . $search . '%');
                });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Date (checks if date falls within the booking period)
        if ($request->filled('date')) {
            $date = $request->date;
            $query->where(function ($q) use ($date) {
                $q->where('check_in_date', '<=', $date)
                    ->where('check_out_date', '>=', $date);
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        return view('all', compact('bookings'));
    }

    public function showBooking($id)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $booking = Booking::with('slot.tent')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('booking-details', compact('booking'));
    }

    public function adminIndex(Request $request)
    {
        $query = Booking::with('user', 'slot.tent');

        // dd($query);
        // Text Search
        if ($request->filled('search')) {
            $search = $request->search;
            $searchId = preg_replace('/[^0-9]/', '', $search);

            $query->where(function ($q) use ($search, $searchId) {
                if (!empty($searchId)) {
                    $q->where('id', (int)$searchId);
                }
                $q->orWhere('customer_name', 'like', '%' . $search . '%');
                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
                $q->orWhereHas('slot.tent', function ($tentQuery) use ($search) {
                    $tentQuery->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('slot', function ($slotQuery) use ($search) {
                    $slotQuery->where('tent_number', 'like', '%' . $search . '%');
                });
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date Filter
        if ($request->filled('date')) {
            $date = $request->date;
            $query->where(function ($q) use ($date) {
                $q->where('check_in_date', '<=', $date)
                    ->where('check_out_date', '>=', $date);
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Mark all bookings on the current page as seen
        Booking::whereIn('id', $bookings->pluck('id'))->update(['is_seen' => true]);

        // High-level KPI Stats
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'revenue_30d' => Booking::where('status', 'confirmed')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->sum('total_price'),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function adminCreate()
    {
        return view('admin.bookings.create');
    }

    public function getTents(Request $request)
    {
        $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $checkIn = $request->check_in_date;
        $checkOut = $request->check_out_date;
        $excludeBookingId = $request->query('exclude_booking_id');

        if ($this->isDateBlocked($checkIn, $checkOut)) {
            return response()->json([]);
        }

        $availableTents = \App\Models\Tent::whereHas('slots', function ($query) use ($checkIn, $checkOut, $excludeBookingId) {
            $query->whereDoesntHave('pauses', function ($q) use ($checkIn, $checkOut) {
                      $q->where('start_date', '<', $checkOut)
                        ->where('end_date', '>', $checkIn);
                  })
                  ->whereDoesntHave('bookings', function ($q2) use ($checkIn, $checkOut, $excludeBookingId) {
                if ($excludeBookingId) {
                    $q2->where('id', '!=', $excludeBookingId);
                }
                $q2->where('check_in_date', '<', $checkOut)
                   ->where('check_out_date', '>', $checkIn)
                   ->where(function ($q3) {
                       $q3->where('status', 'confirmed')
                          ->orWhere(function ($q4) {
                              $q4->where('status', 'pending')
                                 ->where('expires_at', '>', now());
                          });
                   });
            });
        })->get(['id', 'name']);

        return response()->json($availableTents);
    }

    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'tent_id' => 'required|exists:tents,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $tentId = $validated['tent_id'];
        $checkIn = $validated['check_in_date'];
        $checkOut = $validated['check_out_date'];

        if ($this->isDateBlocked($checkIn, $checkOut)) {
            return back()->withInput()->with('error', 'Cannot create booking: The campsite is closed for the selected dates.');
        }

        $availableSlot = Slot::where('tent_id', $tentId)
            ->whereDoesntHave('pauses', function ($q) use ($checkIn, $checkOut) {
                $q->where('start_date', '<', $checkOut)
                  ->where('end_date', '>', $checkIn);
            })
            ->lockForUpdate()
            ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
                $query->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn)
                    ->where(function ($q) {
                        $q->where('status', 'confirmed')
                            ->orWhere(function ($q2) {
                                $q2->where('status', 'pending')
                                    ->where('expires_at', '>', now());
                            });
                    });
            })
            ->first();

        if (!$availableSlot) {
            return back()->withInput()->with('error', 'No slots available for the selected dates and campsite.');
        }

        $expiresAt = $validated['status'] === 'pending' ? now()->addDays(7) : null;

        $booking = Booking::create([
            'user_id' => null,
            'slot_id' => $availableSlot->id,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'status' => $validated['status'],
            'total_price' => $validated['total_price'],
            'expires_at' => $expiresAt,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'] ?? null,
            'customer_address' => $validated['customer_address'] ?? null,
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Manual booking created successfully.');
    }

    public function adminEdit(Booking $booking)
    {
        return view('admin.bookings.edit', compact('booking'));
    }

    public function adminUpdate(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'tent_id' => 'required|exists:tents,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
            'customer_address' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $tentId = $validated['tent_id'];
        $checkIn = $validated['check_in_date'];
        $checkOut = $validated['check_out_date'];

        // If dates or tent changed, we need to verify slot availability
        if ($booking->slot->tent_id != $tentId || $booking->check_in_date != $checkIn || $booking->check_out_date != $checkOut) {
            
            if ($this->isDateBlocked($checkIn, $checkOut)) {
                return back()->withInput()->with('error', 'Cannot reschedule: The campsite is closed for the requested dates.');
            }

            $availableSlot = Slot::where('tent_id', $tentId)
                ->whereDoesntHave('pauses', function ($q) use ($checkIn, $checkOut) {
                    $q->where('start_date', '<', $checkOut)
                      ->where('end_date', '>', $checkIn);
                })
                ->lockForUpdate()
                ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut, $booking) {
                    $query->where('id', '!=', $booking->id) // Ignore the current booking
                       ->where('check_in_date', '<', $checkOut)
                       ->where('check_out_date', '>', $checkIn)
                       ->where(function ($q3) {
                           $q3->where('status', 'confirmed')
                              ->orWhere(function ($q4) {
                                  $q4->where('status', 'pending')
                                     ->where('expires_at', '>', now());
                              });
                       });
                })
                ->first();

            if (!$availableSlot) {
                return back()->withInput()->with('error', 'No slots available for the selected dates and campsite to reschedule.');
            }
            $booking->slot_id = $availableSlot->id;
        }

        $expiresAt = $validated['status'] === 'pending' && $booking->status !== 'pending' ? now()->addDays(7) : $booking->expires_at;
        if ($validated['status'] === 'confirmed') {
            $expiresAt = null;
        }

        $booking->update([
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'status' => $validated['status'],
            'total_price' => $validated['total_price'],
            'expires_at' => $expiresAt,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'] ?? null,
            'customer_address' => $validated['customer_address'] ?? null,
        ]);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function adminUpdateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $booking->update(['status' => $validated['status']]);

        return back()->with('success', 'Booking status updated to ' . ucfirst($validated['status']) . ' successfully.');
    }

    public function adminOccupancy(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::today()->toDateString());
        $daysToShow = 14;
        $dates = [];
        for ($i = 0; $i < $daysToShow; $i++) {
            $dates[] = Carbon::parse($startDate)->addDays($i);
        }

        $endDate = end($dates)->toDateString();
        $prevDate = Carbon::parse($startDate)->subDays($daysToShow)->toDateString();
        $nextDate = Carbon::parse($startDate)->addDays($daysToShow)->toDateString();

        // Get all tents and slots, ordered naturally
        $tents = Tent::with(['slots' => function ($query) {
            $query->orderByRaw('LENGTH(tent_number) ASC, tent_number ASC');
        }])->orderBy('name', 'asc')->get();

        // Get confirmed bookings in this range
        $bookings = Booking::with('user', 'slot')
            ->where('status', 'confirmed')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('check_in_date', '<', Carbon::parse($endDate)->addDay())
                    ->where('check_out_date', '>', $startDate);
            })
            ->get();

        // Prepare matrix: [slot_id][date_string] = booking
        $matrix = [];
        foreach ($bookings as $booking) {
            $current = Carbon::parse($booking->check_in_date);
            $out = Carbon::parse($booking->check_out_date);

            while ($current->lt($out)) {
                $dateStr = $current->toDateString();
                if ($current->gte($startDate) && $current->lte($endDate)) {
                    $matrix[$booking->slot_id][$dateStr] = $booking;
                }
                $current->addDay();
            }
        }

        $blockouts = \App\Models\BlockoutDate::where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q) use ($startDate, $endDate) {
                      $q->where('start_date', '<=', $startDate)
                        ->where('end_date', '>=', $endDate);
                  });
        })->get();
        
        $blockoutMatrix = [];
        foreach ($blockouts as $block) {
            $curr = \Carbon\Carbon::parse($block->start_date);
            $out = \Carbon\Carbon::parse($block->end_date);
            while ($curr->lt($out)) {
                $blockoutMatrix[$curr->toDateString()] = $block;
                $curr->addDay();
            }
        }

        return view('admin.bookings.occupancy', compact('tents', 'dates', 'matrix', 'startDate', 'prevDate', 'nextDate', 'blockoutMatrix'));
    }
}
