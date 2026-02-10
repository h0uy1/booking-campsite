<?php

namespace App\Http\Controllers;

use App\Models\Tent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TentController extends Controller
{
    public function index()
    {
        $tents = Tent::with('prices')->get();
        return view('tents.index', compact('tents'));
    }

    public function create()
    {
        return view('tents.create');
    }

    public function store(Request $request)
    {
        // 1. Common Validation
        $rules = [
            'name' => 'required|string|max:255',
            'pricing_type' => 'required|in:person,base',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            'max_capacity' => 'required|integer|min:1',
        ];

        // 2. Conditional Validation Rules
        if ($request->pricing_type === 'person') {
            $rules['adult_price'] = 'required|numeric|min:0';
            $rules['child_price'] = 'required|numeric|min:0';
        } else { // base
            $rules['base_prices'] = [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $capacities = array_column($value, 'capacity');
                    if (count($capacities) !== count(array_unique($capacities))) {
                        $fail('The capacity values must be unique for each price tier.');
                    }
                },
            ];
            $rules['base_prices.*.capacity'] = [
                'required',
                'integer',
                'min:1',
                // Custom rule: capacity <= max_capacity
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->max_capacity) {
                        $fail("The capacity cannot be greater than the tent's max capacity ({$request->max_capacity}).");
                    }
                },
            ];
            $rules['base_prices.*.price_weekday'] = 'required|numeric|min:0';
            $rules['base_prices.*.price_weekend'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $request) {
            // Pick the first image as the main image (thumbnail)
            $mainImagePath = null;
            if ($request->hasFile('images')) {
                $mainImagePath = $request->file('images')[0]->store('tents', 'public');
            }

            $tent = Tent::create([
                'name' => $validated['name'],
                'pricing_type' => $validated['pricing_type'],
                'max_capacity' => $validated['max_capacity'],
                'image' => $mainImagePath, // Keep backward compatibility for now
            ]);

            // Save all images to tent_images table
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                     
                     $path = $image->store('tents', 'public');
                     $tent->images()->create(['image_path' => $path]);
                     if ($index === 0) {
                        $tent->update(['image' => $path]);
                    }
                }
            }

            // Prepare price data based on type
            if ($validated['pricing_type'] === 'person') {
                $tent->prices()->create([
                    'price_weekday' => 0,
                    'price_weekend' => 0,
                    'capacity' => 0, 
                    'adult_price' => $validated['adult_price'],
                    'child_price' => $validated['child_price'],
                ]);
            } else {
                // Loop through base prices
                foreach ($validated['base_prices'] as $priceData) {
                    $tent->prices()->create([
                        'price_weekday' => $priceData['price_weekday'],
                        'price_weekend' => $priceData['price_weekend'],
                        'capacity' => $priceData['capacity'],
                        'adult_price' => 0, // Not applicable for base
                        'child_price' => 0, // Not applicable for base
                    ]);
                }
            }
        });

        return redirect('/tents')->with('success', 'Tent and pricing configuration created successfully!');
    }

    public function edit(Tent $tent)
    {
        return view('tents.edit', compact('tent'));
    }

    public function update(Request $request, Tent $tent)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'pricing_type' => 'required|in:person,base',
            'max_capacity' => 'required|integer|min:1',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Validate new images
        ];

        // 2. Conditional Validation Rules (Same as store)
        if ($request->pricing_type === 'person') {
            $rules['adult_price'] = 'required|numeric|min:0';
            $rules['child_price'] = 'required|numeric|min:0';
        } else { // base
            $rules['base_prices'] = [
                'required',
                'array',
                'min:1',
                function ($attribute, $value, $fail) {
                    $capacities = array_column($value, 'capacity');
                    if (count($capacities) !== count(array_unique($capacities))) {
                        $fail('The capacity values must be unique for each price tier.');
                    }
                },
            ];
            $rules['base_prices.*.capacity'] = [
                'required',
                'integer',
                'min:1',
                // Custom rule: capacity <= max_capacity
                function ($attribute, $value, $fail) use ($request) {
                    if ($value > $request->max_capacity) {
                        $fail("The capacity cannot be greater than the tent's max capacity ({$request->max_capacity}).");
                    }
                },
            ];
            $rules['base_prices.*.price_weekday'] = 'required|numeric|min:0';
            $rules['base_prices.*.price_weekend'] = 'required|numeric|min:0';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $request, $tent) {
            // Handle image removal if any
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $imageId) {
                    $image = $tent->images()->find($imageId);
                    if ($image) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
            }

            // Handle new images
            if ($request->hasFile('new_images')) {
                foreach ($request->file('new_images') as $image) {
                    $path = $image->store('tents', 'public');
                    $tent->images()->create(['image_path' => $path]);
                }
            }
            
            // Refresh main image if needed (e.g. if main image was deleted, pick next available, or if new image uploaded and no image existed)
            // For simplicity, let's ensure the 'image' column in 'tents' table points to the first image in 'tent_images'
            $firstImage = $tent->images()->first();
            $mainImagePath = $firstImage ? $firstImage->image_path : null;

            $tent->update([
                'name' => $validated['name'],
                'pricing_type' => $validated['pricing_type'],
                'max_capacity' => $validated['max_capacity'],
                'image' => $mainImagePath,
            ]);

            // Clear existing prices
            $tent->prices()->delete();

            // Re-create prices based on type
            if ($validated['pricing_type'] === 'person') {
                $tent->prices()->create([
                    'price_weekday' => 0,
                    'price_weekend' => 0,
                    'capacity' => 0, 
                    'adult_price' => $validated['adult_price'],
                    'child_price' => $validated['child_price'],
                ]);
            } else {
                // Loop through base prices
                foreach ($validated['base_prices'] as $priceData) {
                    $tent->prices()->create([
                        'price_weekday' => $priceData['price_weekday'],
                        'price_weekend' => $priceData['price_weekend'],
                        'capacity' => $priceData['capacity'],
                        'adult_price' => 0, 
                        'child_price' => 0, 
                    ]);
                }
            }
        });

        return redirect('/tents')->with('success', 'Tent updated successfully!');
    }

    public function destroy(Tent $tent){
        // Delete all associated gallery images from storage
        foreach ($tent->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Delete legacy/cover image if it exists
        if ($tent->image) {
            Storage::disk('public')->delete($tent->image);
        }

        $tent->delete();
        return redirect('/tents')->with('success', 'Tent deleted successfully!');
    }
}
