<x-admin>
    <style>
        .excel-grid {
            border-collapse: collapse;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .excel-grid th, .excel-grid td {
            border: 1px solid #e2e8f0;
            padding: 0;
            min-width: 120px;
            height: 60px;
        }
        .excel-header {
            background-color: #064e3b; /* deep forest green */
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            color: #ffffff;
            text-align: center;
            padding: 1rem 0.5rem !important;
            border: 1px solid #065f46 !important;
        }
        .sticky-col {
            position: sticky;
            left: 0;
            background-color: #f8fafc;
            z-index: 10;
            border-right: 3px solid #064e3b !important;
            font-weight: 700;
            padding: 0.75rem !important;
            color: #064e3b;
        }
        .occupied-cell {
            background-color: #ecfdf5; /* subtle emerald tint */
            border: 1px solid #10b981 !important; /* emerald-500 border */
            padding: 0.6rem !important;
            font-size: 0.75rem;
            line-height: 1.3;
            box-shadow: inset 0 0 0 1px rgba(16, 185, 129, 0.1);
        }
        .guest-name {
            font-weight: 800;
            color: #000;
        }
        .amenity-tag {
            font-size: 0.65rem;
            color: #4b5563;
            margin-top: 2px;
        }
        .total-row {
            background-color: #064e3b;
            color: white;
            font-weight: 700;
            border-top: 3px solid #059669 !important;
        }
        .total-row td {
            color: white !important;
            border-color: #065f46 !important;
        }
    </style>

    <div class="space-y-6 pb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Occupancy Matrix</h1>
                <p class="text-sm text-gray-500 mt-1">Excel-style view of tent availability and guest assignments.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <a href="{{ route('admin.bookings.occupancy', ['start_date' => $prevDate]) }}" 
                       class="p-2 border-r border-gray-100 hover:bg-gray-50 transition-colors text-gray-500 hover:text-green-600"
                       title="Previous 14 Days">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    
                    <form action="{{ route('admin.bookings.occupancy') }}" method="GET" class="px-2 flex items-center gap-2">
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               onchange="this.form.submit()"
                               class="text-xs font-black border-none focus:ring-0 p-0 cursor-pointer text-gray-700 bg-transparent uppercase tracking-widest">
                    </form>

                    <a href="{{ route('admin.bookings.occupancy', ['start_date' => $nextDate]) }}" 
                       class="p-2 border-l border-gray-100 hover:bg-gray-50 transition-colors text-gray-500 hover:text-green-600"
                       title="Next 14 Days">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-gray-200 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="excel-grid min-w-full">
                    <thead>
                        <tr>
                            <th class="excel-header sticky-col">Tent No.</th>
                            @foreach($dates as $date)
                                <th class="excel-header text-center">
                                    {{ $date->format('j/n/y') }}<br>
                                    <span class="text-[10px] opacity-60">({{ strtolower($date->format('D')) }})</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tents as $tent)
                            @foreach($tent->slots as $slot)
                                <tr>
                                    <td class="sticky-col text-sm text-gray-700 bg-gray-50/50">
                                        <div class="text-[10px] uppercase font-black text-emerald-800 opacity-70">{{ $tent->name }}</div>
                                        <div class="font-bold">{{ $slot->tent_number }}</div>
                                        <div class="text-[10px] text-gray-400">Max {{ $tent->max_capacity }} pax</div>
                                    </td>
                                    @foreach($dates as $date)
                                        @php
                                            $dateStr = $date->toDateString();
                                            $booking = $matrix[$slot->id][$dateStr] ?? null;
                                            $isBlocked = isset($blockoutMatrix[$dateStr]);
                                        @endphp
                                        @if($isBlocked)
                                            <td class="bg-red-50 border border-red-200" style="border: 1px solid #fca5a5 !important;" title="Campsite Closed: {{ $blockoutMatrix[$dateStr]->reason ?: 'Private Event' }}">
                                                <div class="text-[9px] font-black text-red-600 uppercase text-center px-1">{{ $blockoutMatrix[$dateStr]->reason ?: 'Private Event' }}</div>
                                            </td>
                                        @elseif($booking)
                                            <td class="occupied-cell">
                                                <div class="guest-name">{{ strtolower($booking->user->name ?? $booking->customer_name ?? 'Guest') }}</div>
                                                <div class="flex gap-2 mt-1 font-bold text-[10px]">
                                                    <span>Adults: {{ $booking->adults ?? 2 }}</span>
                                                    <span>Kids: {{ $booking->children ?? 0 }}</span>
                                                </div>
                                                {{-- Placeholder for aircond/toddlers if needed --}}
                                                {{-- <div class="amenity-tag">aircond</div> --}}
                                            </td>
                                        @else
                                            <td class="bg-white"></td>
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td class="sticky-col text-sm text-gray-900 uppercase tracking-wider">Total Pax</td>
                            @foreach($dates as $date)
                                @php
                                    $dateStr = $date->toDateString();
                                    $dailyAdults = 0;
                                    $dailyKids = 0;
                                    foreach($tents as $t) {
                                        foreach($t->slots as $s) {
                                            if(isset($matrix[$s->id][$dateStr])) {
                                                $b = $matrix[$s->id][$dateStr];
                                                $dailyAdults += ($b->adults ?? 2);
                                                $dailyKids += ($b->children ?? 0);
                                            }
                                        }
                                    }
                                @endphp
                                <td class="p-3 text-center">
                                    <div class="text-[10px] text-emerald-100 uppercase opacity-80">Adults: <span class="text-white font-black text-xs">{{ $dailyAdults }}</span></div>
                                    <div class="text-[10px] text-emerald-100 uppercase opacity-80">Kids: <span class="text-white font-black text-xs">{{ $dailyKids }}</span></div>
                                </td>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-admin>
