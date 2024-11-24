<?php

namespace App\Http\Controllers\Api;

use App\Models\About;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    // Fetch all records
    public function index()
    {
        $about = About::all();
        return response()->json([
            'status' => 200,
            'data' => $about
        ]);
    }

    // Fetch a single record by ID
    public function show($id)
    {
        $about = About::find($id);
        if (!$about) {
            return response()->json(['message' => 'About not found'], 404);
        }
        return response()->json($about);
    }

    // Store a new record
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'about' => 'required|array',
            'icon_subdesc' => 'required|array',
            'icon_desc' => 'required|array',
        ]);

        // Handle image uploads
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('about_images', 'public');
        } else {
            return response()->json(['error' => 'Image is required'], 400);
        }

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('icons', 'public');
        } else {
            return response()->json(['error' => 'Icon is required'], 400);
        }

        $about = About::create([
            'image' => $imagePath,
            'icon' => $iconPath,
            'about' => $validatedData['about'],
            'icon_subdesc' => $validatedData['icon_subdesc'],
            'icon_desc' => $validatedData['icon_desc'],
        ]);

        return response()->json($about, 201);
    }

    // Update an existing record
    public function update(Request $request, $id)
    {
        $about = About::find($id);
        if (!$about) {
            return response()->json(['message' => 'About not found'], 404);
        }

        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'about' => 'required|array',
            'icon_subdesc' => 'required|array',
            'icon_desc' => 'required|array',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            if ($about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $imagePath = $request->file('image')->store('about_images', 'public');
        } else {
            $imagePath = $about->image;
        }

        // Handle icon update
        if ($request->hasFile('icon')) {
            if ($about->icon) {
                Storage::disk('public')->delete($about->icon);
            }
            $iconPath = $request->file('icon')->store('icons', 'public');
        } else {
            $iconPath = $about->icon;
        }

        // Update the record
        $about->update([
            'image' => $imagePath,
            'icon' => $iconPath,
            'about' => $validatedData['about'],
            'icon_subdesc' => $validatedData['icon_subdesc'],
            'icon_desc' => $validatedData['icon_desc'],
        ]);

        return response()->json([
            'message' => 'Record updated successfully',
            'data' => $about->fresh(),
        ], 200);
    }

    // Delete a record
    public function destroy($id)
    {
        $about = About::find($id);
        if (!$about) {
            return response()->json(['message' => 'About not found'], 404);
        }

        // Delete associated images
        Storage::disk('public')->delete($about->image);
        Storage::disk('public')->delete($about->icon);

        $about->delete();

        return response()->json(['message' => 'About deleted successfully'], 200);
    }
}
