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
        ]);
        $validated['status'] = 'confirmed';

        Booking::create($validated);

        return view('user.checkout');
    }

}
