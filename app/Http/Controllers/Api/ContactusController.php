<?php

namespace App\Http\Controllers\Api;


use App\Models\Contactus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactusController extends Controller
{
        // عرض جميع الاتصالات
        public function index()
        {
            $contacts = Contactus::all();
            return response()->json([
                'message' => 'success',
                'status' => 200,
                'data' => $contacts
            ]);
        }

        // عرض اتصال محدد
        public function show($id)
        {
            $contact = Contactus::find($id);
            if (!$contact) {
                return response()->json(['message' => 'Contact not found'], 404);
            }
            return response()->json($contact);
        }

        // إنشاء اتصال جديد
        public function store(Request $request)
        {
            $validatedData = $request->validate([
                'address_en' => 'required|string',
                'address_ar' => 'required|string',
                'tel' => 'required|string',
                'email' => 'required|email',
                'lat' => 'required|string',
                'lng' => 'required|string',
                'type' => 'required|in:enquire,cert,other',
            ]);

            $contact = new Contactus();
            $contact->setTranslations('address', [
                'en' => $request->input('address_en'),
                'ar' => $request->input('address_ar'),
            ]);
            $contact->tel = $request->input('tel');
            $contact->email = $request->input('email');
            $contact->lat = $request->input('lat');
            $contact->lng = $request->input('lng');
            $contact->type = $request->input('type');
            $contact->save();

            return response()->json([
                'message' => 'success',
                'status' => 200,
                'data' => $contact
            ]);         }

        // تعديل اتصال
        public function update(Request $request, $id)
        {
            $contact = Contactus::find($id);
            if (!$contact) {
                return response()->json(['message' => 'Contact not found'], 404);
            }

            $validatedData = $request->validate([
                'address_en' => 'string',
                'address_ar' => 'string',
                'tel' => 'string',
                'email' => 'email',
                'lat' => 'string',
                'lng' => 'string',
                'type' => 'in:enquire,cert,other',
            ]);

            $contact->setTranslations('address', [
                'en' => $request->input('address_en', $contact->getTranslation('address', 'en')),
                'ar' => $request->input('address_ar', $contact->getTranslation('address', 'ar')),
            ]);
            $contact->tel = $request->input('tel', $contact->tel);
            $contact->email = $request->input('email', $contact->email);
            $contact->lat = $request->input('lat', $contact->lat);
            $contact->lng = $request->input('lng', $contact->lng);
            $contact->type = $request->input('type', $contact->type);
            $contact->save();

            return response()->json([
                'message' => 'success',
                'status' => 200,
                'data' => $contact
            ]);
        }

        // حذف اتصال
        public function destroy($id)
        {
            $contact = Contactus::find($id);
            if (!$contact) {
                return response()->json(['message' => 'Contact not found'], 404);
            }

            $contact->delete();

            return response()->json([
                'message' => 'success',
                'status' => 200,
            ]);
        }
}
