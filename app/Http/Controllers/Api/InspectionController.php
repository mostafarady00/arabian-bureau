<?php
namespace App\Http\Controllers\Api;

use App\Models\Inspection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
    // عرض جميع الفحوصات
    public function index()
    {
        $inspections = Inspection::all();
        return response()->json([
            'status' => 200,
            'data' => $inspections
        ]);
    }

    // عرض فحص واحد بناءً على ID
    public function show($id)
    {
        $inspection = Inspection::find($id);

        if (!$inspection) {
            return response()->json(['message' => 'Inspection not found'], 404);
        }

        return response()->json($inspection, 200);
    }

    // إنشاء فحص جديد
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'inspections_en' => 'required|string|max:255',
            'inspections_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'description_en' => 'required|string',
            'description_ar' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inspections_images', 'public');
        }

        // إنشاء فحص جديد باستخدام اللغات المختلفة
        $inspection = new Inspection();
        $inspection->setTranslations('inspections', [
            'en' => $request->input('inspections_en'),
            'ar' => $request->input('inspections_ar'),
        ]);
        $inspection->setTranslations('title', [
            'en' => $request->input('title_en'),
            'ar' => $request->input('title_ar'),
        ]);
        $inspection->setTranslations('description', [
            'en' => $request->input('description_en'),
            'ar' => $request->input('description_ar'),
        ]);
        $inspection->image = $imagePath;
        $inspection->save();

        return response()->json([
            'status' => 200,
            'data' => $inspection
        ]);
    }

    // تحديث فحص
    public function update(Request $request, $id)
    {
        $inspection = Inspection::find($id);

        if (!$inspection) {
            return response()->json(['message' => 'Inspection not found'], 404);
        }

        $validatedData = $request->validate([
            'inspections_en' => 'required|string|max:255',
            'inspections_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image update
        if ($request->hasFile('image')) {
            if ($inspection->image) {
                // Delete the old image
                Storage::disk('public')->delete($inspection->image);
            }
            $imagePath = $request->file('image')->store('inspections_images', 'public');
        } else {
            $imagePath = $inspection->image;
        }

        // Update the 'about', 'icon_subdesc', and 'icon_desc' fields
        $inspection->setTranslations('inspections', [
            'en' => $request->input('inspections_en'),
            'ar' => $request->input('inspections_ar'),
        ]);
        $inspection->setTranslations('title', [
            'en' => $request->input('title_en', $inspection->getTranslation('title', 'en')),
            'ar' => $request->input('title_ar', $inspection->getTranslation('title', 'ar')),
        ]);
        $inspection->setTranslations('description', [
            'en' => $request->input('description_en', $inspection->getTranslation('description', 'en')),
            'ar' => $request->input('description_ar', $inspection->getTranslation('description', 'ar')),
        ]);
        $inspection->image = $imagePath;
        $inspection->save();

        return response()->json([
            'status' => 200,
            'data' => $inspection->fresh()  // Ensure the latest data is retrieved from the database
        ]);
    }

    // حذف فحص
    public function destroy($id)
    {
        $inspection = Inspection::find($id);

        if (!$inspection) {
            return response()->json(['message' => 'Inspection not found'], 404);
        }

        // Delete the associated image
        if ($inspection->image) {
            Storage::disk('public')->delete($inspection->image);
        }

        $inspection->delete();
        return response()->json([
            'status' => 'deleted success',
        ]);
    }
}
