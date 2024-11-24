<?php

namespace App\Http\Controllers\Api;

use App\Models\Training;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
     // إرجاع جميع سجلات التدريبات
     public function index()
     {
         $trainings = Training::all();
         return response()->json([
            'status' => 200,
            'data' => $trainings
        ]);
     }

     // إرجاع تدريب معين حسب الـ id
     public function show($id)
     {
         $training = Training::find($id);
         if (!$training) {
             return response()->json(['message' => 'Training not found'], 404);
         }
         return response()->json($training);
     }

     // إضافة تدريب جديد
     public function store(Request $request)
     {
         $validatedData = $request->validate([
             'training_en' => 'required|string',
             'training_ar' => 'required|string',
             'description_en' => 'required|string',
             'description_ar' => 'required|string',
             'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

         ]);

           // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('training_images', 'public');
        }
         $training = new Training();
         $training->setTranslations('training', [
             'en' => $request->input('training_en'),
             'ar' => $request->input('training_ar'),
         ]);
         $training->setTranslations('description', [
             'en' => $request->input('description_en'),
             'ar' => $request->input('description_ar'),
         ]);
         $training->image = $imagePath;

         $training->save();

         return response()->json([
            'status' => 200,
            'data' => $training
        ]);
     }

     // تعديل تدريب موجود
     public function update(Request $request, $id)
     {
         $training = Training::find($id);
         if (!$training) {
             return response()->json(['message' => 'Training not found'], 404);
         }

         $validatedData = $request->validate([
             'training_en' => 'string',
             'training_ar' => 'string',
             'description_en' => 'string',
             'description_ar' => 'string',
             'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

         ]);

       // Handle image update
       if ($request->hasFile('image')) {
        if ($training->image) {
            // Delete the old image
            Storage::disk('public')->delete($training->image);
        }
        $imagePath = $request->file('image')->store('inspections_images', 'public');
    } else {
        $imagePath = $training->image;
    }

         $training->setTranslations('training', [
             'en' => $request->input('training_en', $training->getTranslation('training', 'en')),
             'ar' => $request->input('training_ar', $training->getTranslation('training', 'ar')),
         ]);
         $training->setTranslations('description', [
             'en' => $request->input('description_en', $training->getTranslation('description', 'en')),
             'ar' => $request->input('description_ar', $training->getTranslation('description', 'ar')),
         ]);
         $training->image = $imagePath;

         $training->save();

         return response()->json([
            'status' => 200,
            'data' => $training
        ]);
     }

     // حذف تدريب
     public function destroy($id)
     {
         $training = Training::find($id);
         if (!$training) {
             return response()->json(['message' => 'Training not found'], 404);
         }

         $training->delete();

         return response()->json([
            'status' => 'deleted',
        ]);
     }
}
