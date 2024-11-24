<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\Company_profile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class Company_profileController extends Controller
{
    public function index()
    {
        $companyProfiles = Company_profile::all();
        return response()->json([
            'message' => 'success',
            'status' => 200,
            'data' => $companyProfiles
        ]);
    }

    // جلب سجل واحد بناءً على الـ id
    public function show($id)
    {
        $companyProfile = Company_profile::find($id);
        if (!$companyProfile) {
            return response()->json(['message' => 'Company Profile not found'], 404);
        }
        return response()->json($companyProfile);
    }

    // إنشاء سجل جديد
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'organization_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company_profile' => 'required|array',
            'business_interest' => 'required|array',
            'organization_subdesc' => 'required|array',
            'organization_desc' => 'required|array',
        ]);


         // Handle image upload
  if ($request->hasFile('main_image')) {
    $imagePath = $request->file('main_image')->store('companyprofile_images', 'public');
}
  // Handle image upload
  if ($request->hasFile('icon')) {
    $imagePath1 = $request->file('icon')->store('companyprofile_images', 'public');
}


// Handle image upload
if ($request->hasFile('organization_image')) {
  $imagePath2 = $request->file('organization_image')->store('companyprofile_images', 'public');
}

        $companyProfile = new Company_profile();

        // التعامل مع الترجمة
        $companyProfile->setTranslations('company_profile', $request->company_profile);
        $companyProfile->setTranslations('business_interest', $request->business_interest);
        $companyProfile->setTranslations('organization_subdesc', $request->organization_subdesc);
        $companyProfile->setTranslations('organization_desc', $request->organization_desc);
        $companyProfile->main_image = $imagePath;
        $companyProfile->icon = $imagePath1;
        $companyProfile->organization_image = $imagePath2;
        $companyProfile->save();

        return response()->json([
            'message' => 'Company Profile created successfully',
            'data' => $companyProfile
        ], 201);
    }

    // تعديل سجل موجود
    public function update(Request $request, $id)
    {
        $companyProfile = Company_profile::find($id);
        if (!$companyProfile) {
            return response()->json(['message' => 'Company Profile not found'], 404);
        }

        $validatedData = $request->validate([
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'organization_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company_profile' => 'array',
            'business_interest' => 'array',
            'organization_subdesc' => 'array',
            'organization_desc' => 'array',
        ]);



  // Handle image update
  if ($request->hasFile('main_image')) {
    if ($companyProfile->image) {
        // Delete the old image
        Storage::disk('public')->delete($companyProfile->main_image);
    }
    $imagePath = $request->file('main_image')->store('inspections_images', 'public');
} else {
    $imagePath = $companyProfile->main_image;
}




  // Handle image update
  if ($request->hasFile('icon')) {
    if ($companyProfile->image) {
        // Delete the old image
        Storage::disk('public')->delete($companyProfile->icon);
    }
    $imagePath1 = $request->file('icon')->store('inspections_images', 'public');
} else {
    $imagePath1 = $companyProfile->icon;
}



  // Handle image update
  if ($request->hasFile('organization_image')) {
    if ($companyProfile->image) {
        // Delete the old image
        Storage::disk('public')->delete($companyProfile->organization_image);
    }
    $imagePath2 = $request->file('organization_image')->store('inspections_images', 'public');
} else {
    $imagePath2 = $companyProfile->organization_image;
}



        // تحديث الترجمة
        if ($request->has('company_profile')) {
            $companyProfile->setTranslations('company_profile', $request->company_profile);
        }

        if ($request->has('business_interest')) {
            $companyProfile->setTranslations('business_interest', $request->business_interest);
        }

        if ($request->has('organization_subdesc')) {
            $companyProfile->setTranslations('organization_subdesc', $request->organization_subdesc);
        }

        if ($request->has('organization_desc')) {
            $companyProfile->setTranslations('organization_desc', $request->organization_desc);
        }
        $companyProfile->image = $imagePath;
        $companyProfile->icon = $imagePath1;
        $companyProfile->organization_image = $imagePath2;

        $companyProfile->save();

        return response()->json([
            'message' => 'Company Profile updated successfully',
            'data' => $companyProfile
        ], 200);
    }

    // حذف سجل
    public function destroy($id)
    {
        $companyProfile = Company_profile::find($id);
        if (!$companyProfile) {
            return response()->json(['message' => 'Company Profile not found'], 404);
        }

        $companyProfile->delete();

        return response()->json(['message' => 'Company Profile deleted successfully'], 200);
    }
}
