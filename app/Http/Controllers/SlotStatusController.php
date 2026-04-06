<?php

namespace App\Http\Controllers;

use App\Models\Slot;
use App\Models\Tent;
use App\Models\SlotPause;
use Illuminate\Http\Request;

class SlotStatusController extends Controller
{
    public function index()
    {
        // Load all tents with their associated slots and active pauses
        $tents = Tent::with(['slots' => function ($query) {
            $query->with(['pauses' => function($q) {
                $q->where('end_date', '>=', today());
            }])->orderBy('tent_number', 'asc');
        }])->get();

        return view('admin.slots.index', compact('tents'));
    }

    public function updateState(Request $request)
    {
        $validated = $request->validate([
            'slot_id' => 'required|exists:slots,id',
            'action' => 'required|in:active,pause',
            'start_date' => 'required_if:action,pause|date|nullable',
            'end_date' => 'required_if:action,pause|date|after_or_equal:start_date|nullable',
            'reason' => 'nullable|string',
        ]);

        $slot = Slot::findOrFail($validated['slot_id']);

        if ($validated['action'] === 'active') {
            // Unpause by clearing future blocks
            $slot->pauses()->where('end_date', '>=', today())->delete();
            return response()->json(['success' => true, 'message' => 'Slot activated successfully.']);
        } else {
            // Avoid overlaps
            if ($slot->pauses()->where('start_date', '<=', $validated['end_date'])
                              ->where('end_date', '>=', $validated['start_date'])->exists()) {
                return response()->json(['success' => false, 'message' => 'Overlapping pause exists for this slot.'], 422);    
            }

            $slot->pauses()->create([
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'reason' => $validated['reason'] ?? 'Administrator Pause',
            ]);

            return response()->json(['success' => true, 'message' => 'Slot paused successfully.']);
        }
    }
}
