<?php

namespace App\Http\Controllers;

use App\Models\Tent;
use Illuminate\Http\Request;

class TentController extends Controller
{
    public function create()
    {
        return view('tents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        Tent::create($validated);

        return redirect('/tents/create')->with('success', 'Tent created successfully!');
    }
}
