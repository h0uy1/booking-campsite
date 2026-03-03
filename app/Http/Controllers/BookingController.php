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

    /**
     * Get tents available for the given check-in/check-out and minimum guests.
     */
    private function getAvailableTents(string $checkIn, string $checkOut, int $guests, int $adults = 0, string $sort = 'recommended')
    {
        $availabilityQuery = function ($query) use ($checkIn, $checkOut) {
            $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn)
                    ->where('status', 'confirmed');
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

        return view('user.index', compact('tents', 'currentDate', 'nextDate', 'adults', 'children', 'sort'));
    }

    public function show($id, Request $request)
    {
        ['currentDate' => $checkIn, 'nextDate' => $checkOut] = $this->getDateRange($request);

        $availabilityQuery = function ($query) use ($checkIn, $checkOut) {
            $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn);
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

        return view('user.show', compact('tent', 'checkIn', 'checkOut', 'guests', 'adults', 'children'));
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
        $viewers = array_filter($viewers, function($timestamp) {
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
        $viewers = array_filter($viewers, function($timestamp) {
            return $timestamp > now()->subMinutes(5)->timestamp;
        });
        
        return response()->json(['count' => count($viewers)]);
    }

    // checkAvailability method replaced by user method consolidation

    public function checkout(Request $request){
        $validated = $request->validate([
            'tent_id' => 'required|exists:tents,id',
            'room_count' => 'required|integer|min:1',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_price' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {

            $tentId = $validated['tent_id'];
            $roomCount = $validated['room_count'];
            $checkIn = $validated['check_in_date'];
            $checkOut = $validated['check_out_date'];

            $availableSlots = Slot::where('tent_id', $tentId)
                ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut) {
                    $query->where('check_in_date', '<', $checkOut)
                        ->where('check_out_date', '>', $checkIn)
                        ->where('status', 'confirmed');
                })
                ->lockForUpdate()
                ->take($roomCount)
                ->get();

            if ($availableSlots->count() < $roomCount) {
                throw new \Exception('Not enough slots available.');
            }

            $userId = auth()->id();
            $unitPrice = $validated['total_price'] / $roomCount;

            foreach ($availableSlots as $slot) {
                Booking::create([
                    'user_id' => $userId,
                    'slot_id' => $slot->id,
                    'check_in_date' => $checkIn,
                    'check_out_date' => $checkOut,
                    'status' => 'confirmed',
                    'total_price' => $unitPrice,
                ]);
            }

            return view('user.checkout');
        });
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

    public function adminIndex(Request $request)
    {
        $query = Booking::with('user', 'slot.tent');

        // Text Search
        if ($request->filled('search')) {
            $search = $request->search;
            $searchId = preg_replace('/[^0-9]/', '', $search);

            $query->where(function ($q) use ($search, $searchId) {
                if (!empty($searchId)) {
                    $q->where('id', (int)$searchId);
                }
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

        return view('admin.bookings.occupancy', compact('tents', 'dates', 'matrix', 'startDate', 'prevDate', 'nextDate'));
    }
}
