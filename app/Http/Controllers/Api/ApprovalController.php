<?php

namespace App\Http\Controllers\Api;

use App\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{

 // إرجاع جميع السجلات
 public function index()
 {
     $approvals = Approval::all();
     return response()->json([
        'message' => 'success',
        'status' => 200,
        'data' => $approvals
    ]);
 }

 // إرجاع سجل معين حسب الـ id
 public function show($id)
 {
     $approval = Approval::find($id);
     if (!$approval) {
         return response()->json(['message' => 'Approval not found'], 404);
     }
     return response()->json($approval);
 }

 // إضافة سجل جديد
 public function store(Request $request)
 {
     $validatedData = $request->validate([
         'approvals_en' => 'required|string',
         'approvals_ar' => 'required|string',
         'image' => 'required', // Validate that 'image' exists
         'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
     ]);


              $imagePaths = [];
              if ($request->hasFile('image')) {
                  foreach ($request->file('image') as $image) {
                      $imagePath = $image->store('approvals_images', 'public');
                      $imagePaths[] = $imagePath; // Add each image path to array
                  }
              }


     $approval = new Approval();
     $approval->setTranslations('approvals', [
         'en' => $request->input('approvals_en'),
         'ar' => $request->input('approvals_ar'),
     ]);

         // Store image paths as JSON
    $approval->image = json_encode($imagePaths); // Convert array to JSON

     $approval->save();

     return response()->json([
        'message' => 'success',
        'status' => 200,
        'data' => $approval
    ]);
 }


 // تعديل سجل موجود

 public function update(Request $request, $id)
 {
     // Find the approval by ID
     $approval = Approval::find($id);

     if (!$approval) {
         return response()->json(['message' => 'Approval not found'], 404);
     }

     // Validate the request
     $validatedData = $request->validate([
         'approvals_en' => 'required|string',
         'approvals_ar' => 'required|string',
         'image' => 'nullable', // Image is optional for updates
         'image.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
     ]);

     // Initialize an array to hold the new image paths
     $imagePaths = [];

     // Check if new images are being uploaded
     if ($request->hasFile('image')) {
         // Delete old images if they exist
         if ($approval->image) {
             foreach (json_decode($approval->image) as $oldImage) {
                 Storage::disk('public')->delete($oldImage);
             }
         }

         // Store new images and update the image paths
         foreach ($request->file('image') as $image) {
             $imagePath = $image->store('approvals_images', 'public');
             $imagePaths[] = $imagePath; // Add each new image path to array
         }
     } else {
         // If no new images are uploaded, retain the existing images
         $imagePaths = json_decode($approval->image, true); // Decode existing images
     }

     // Update the translations
     $approval->setTranslations('approvals', [
         'en' => $request->input('approvals_en'),
         'ar' => $request->input('approvals_ar'),
     ]);

     // Only update the image field if there are new images
     if (!empty($imagePaths)) {
         $approval->image = json_encode($imagePaths); // Save new images
     }

     // Save the changes
     $approval->save();

     // Return the updated record
     return response()->json([
         'message' => 'Record updated successfully',
         'status' => 200,
         'data' => [
             'id' => $approval->id,
             'approvals' => $approval->getTranslations('approvals'),
             'image' => json_decode($approval->image), // Decode to show image paths
             'created_at' => $approval->created_at,
             'updated_at' => $approval->updated_at,
         ]
     ], 200);
 }






 // حذف سجل
 public function destroy($id)
 {
     $approval = Approval::find($id);
     if (!$approval) {
         return response()->json(['message' => 'Approval not found'], 404);
     }

     $approval->delete();
     return response()->json([
        'message' => 'success',
        'status' => 200,
    ]);
 }


}
