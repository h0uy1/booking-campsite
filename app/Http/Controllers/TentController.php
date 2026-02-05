<?php

namespace App\Http\Controllers;

use App\Models\Tent;
use Illuminate\Http\Request;

class TentController extends Controller
{
    public function index()
    {
        $tents = Tent::all();
        return view('tents.index', compact('tents'));
    }

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tents', 'public');
            $validated['image'] = $path;
        }

        Tent::create($validated);

        return redirect('/tents/create')->with('success', 'Tent created successfully!');
    }

    public function edit(Tent $tent)
    {
        return view('tents.edit', compact('tent'));
    }

    public function update(Request $request, Tent $tent)
    {
        $messages = [
            'image.uploaded' => 'The file size exceeds the server limit of 2MB.',
            'image.max' => 'The image must not be greater than 2MB.',
        ];

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], $messages);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tents', 'public');
            $validated['image'] = $path;
        }

        $tent->update($validated);

        return redirect('/tents')->with('success', 'Tent updated successfully!');
    }
}
