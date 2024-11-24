<?php

namespace App\Http\Controllers\Api;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::all();
        return response()->json([
            'message' => 'success',
            'status' => 200,
            'data' => $galleries
        ]);
      }

    // عرض صورة محددة من الجاليري
    public function show($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response()->json(['message' => 'Gallery not found'], 404);
        }
        return response()->json($gallery);
    }

    // إنشاء صورة جديدة في الجاليري
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Gallery_en' => 'required|string',
            'Gallery_ar' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description_image_en' => 'required|string',
            'description_image_ar' => 'required|string',
        ]);


           // Handle image upload
           if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('training_images', 'public');
        }

        $gallery = new Gallery();
        $gallery->setTranslations('Gallery', [
            'en' => $request->input('Gallery_en'),
            'ar' => $request->input('Gallery_ar'),
        ]);
        $gallery->image = $request->input('image');
        $gallery->setTranslations('description_image', [
            'en' => $request->input('description_image_en'),
            'ar' => $request->input('description_image_ar'),
        ]);
        $gallery->image = $imagePath;
        $gallery->save();

        return response()->json([
            'message' => 'success',
            'status' => 200,
            'data' => $gallery
        ]);    }

    // تعديل صورة في الجاليري
    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response()->json(['message' => 'Gallery not found'], 404);
        }

        $validatedData = $request->validate([
            'Gallery_en' => 'string',
            'Gallery_ar' => 'string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description_image_en' => 'string',
            'description_image_ar' => 'string',
        ]);
         // Handle image update
       if ($request->hasFile('image')) {
        if ($gallery->image) {
            // Delete the old image
            Storage::disk('public')->delete($gallery->image);
        }
        $imagePath = $request->file('image')->store('inspections_images', 'public');
    } else {
        $imagePath = $gallery->image;
    }

        $gallery->setTranslations('Gallery', [
            'en' => $request->input('Gallery_en', $gallery->getTranslation('Gallery', 'en')),
            'ar' => $request->input('Gallery_ar', $gallery->getTranslation('Gallery', 'ar')),
        ]);
        $gallery->image = $request->input('image', $gallery->image);
        $gallery->setTranslations('description_image', [
            'en' => $request->input('description_image_en', $gallery->getTranslation('description_image', 'en')),
            'ar' => $request->input('description_image_ar', $gallery->getTranslation('description_image', 'ar')),
        ]);
        $gallery->image = $imagePath;

        $gallery->save();
        return response()->json([
            'message' => 'success',
            'status' => 200,
            'data' => $gallery
        ]);
    }

    // حذف صورة من الجاليري
    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return response()->json(['message' => 'Gallery not found'], 404);
        }

        $gallery->delete();

        return response()->json([
            'message' => 'success',
            'status' => 200,
        ]);    }
}
