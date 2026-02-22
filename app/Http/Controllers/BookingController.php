<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tent;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Resolve date range for the index view: use provided dates or default to today/tomorrow.
     */
    private function getDateRange(?string $checkIn = null, ?string $checkOut = null): array
    {
        if ($checkIn && $checkOut) {
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
    private function getAvailableTents(string $checkIn, string $checkOut, int $guests, int $adults = null )
    {
        $availabilityQuery = function ($query) use ($checkIn, $checkOut) {
            $query->whereDoesntHave('bookings', function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn);
            });
        };

        return Tent::with(['slots' => $availabilityQuery, 'prices', 'images'])
            ->whereHas('slots', $availabilityQuery)
            ->withCount(['slots' => $availabilityQuery])
            ->where('max_capacity', '>=', $guests)
            ->where('min_capacity', '<=', $adults)
            ->get();
    }

    public function user(Request $request)
    {
        ['currentDate' => $currentDate, 'nextDate' => $nextDate] = $this->getDateRange();
        $adults = (int) $request->query('adults', 2);
        $children = (int) $request->query('children', 0);
        $guests = $adults + $children;

        $tents = $this->getAvailableTents($currentDate, $nextDate, $guests, $adults);

        return view('user.index', compact('tents', 'currentDate', 'nextDate', 'adults', 'children'));
    }

    public function show($id, Request $request)
    {
        ['currentDate' => $checkIn, 'nextDate' => $checkOut] = $this->getDateRange(
            $request->query('check_in'),
            $request->query('check_out')
        );

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

        return view('user.show', compact('tent', 'checkIn', 'checkOut', 'guests', 'adults', 'children'));
    }

    public function checkAvailability(Request $request)
    {
        $validated = $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|numeric|min:1',
            'children' => 'required|numeric|min:0',
        ]);

        $totalGuests = (int)$validated['adults'] + (int)$validated['children'];

        ['currentDate' => $currentDate, 'nextDate' => $nextDate] = $this->getDateRange(
            $validated['check_in'],
            $validated['check_out']
        );

        $tents = $this->getAvailableTents(
            $validated['check_in'],
            $validated['check_out'],
            $totalGuests,
            $validated['adults']
        );

        $adults = (int)$validated['adults'];
        $children = (int)$validated['children'];

        return view('user.index', compact('tents', 'currentDate', 'nextDate', 'adults', 'children'));
    }

    public function checkout(Request $request){
        $validated = $request->validate([
            'slot_id' => 'required|exists:slots,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_price' => 'required|numeric|min:0',
        ]);
        $validated['status'] = 'confirmed';

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        Booking::create($validated);

        return view('user.checkout');
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
}
