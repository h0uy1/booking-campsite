<?php

namespace App\Http\Controllers;

use App\Models\BlockoutDate;
use Illuminate\Http\Request;

class BlockoutDateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blockouts = BlockoutDate::orderBy('start_date', 'asc')->paginate(15);
        return view('admin.blockouts.index', compact('blockouts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blockouts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'reason' => 'nullable|string|max:255',
        ]);

        BlockoutDate::create($request->all());

        return redirect()->route('admin.blockouts.index')->with('success', 'Blockout dates successfully added. The campsite is now closed for these dates.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlockoutDate $blockout)
    {
        $blockout->delete();
        return redirect()->route('admin.blockouts.index')->with('success', 'Blockout dates successfully removed.');
    }
}
