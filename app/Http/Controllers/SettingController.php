<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\General;
use App\Models\State;
use App\Models\City;
class SettingController extends Controller
{
    public function updateGeneralSettings(Request $request)
{
    $updateData = [];
    $timestamp = time(); // Single timestamp for consistency

    // Upload: Logo
    if ($request->hasFile('logo')) {
        $logoFile = $request->file('logo');
        $logoName = $timestamp . '_logo.' . $logoFile->getClientOriginalExtension();
        $logoFile->move(public_path('general'), $logoName);
        $updateData['logo'] = $logoName; // Just filename
    }

    // Upload: Favicon
    if ($request->hasFile('favicon')) {
        $faviconFile = $request->file('favicon');
        $faviconName = $timestamp . '_favicon.' . $faviconFile->getClientOriginalExtension();
        $faviconFile->move(public_path('general'), $faviconName);
        $updateData['favicon'] = $faviconName;
    }

    // Upload: QR Code
    if ($request->hasFile('uploadqrcode')) {
        $qrcodeFile = $request->file('uploadqrcode');
        $qrcodeName = $timestamp . '_qrcode.' . $qrcodeFile->getClientOriginalExtension();
        $qrcodeFile->move(public_path('general'), $qrcodeName);
        $updateData['uploadqrcode'] = $qrcodeName;
    }

    // Upload: Footer Logo
    if ($request->hasFile('footerlogo')) {
        $footerFile = $request->file('footerlogo');
        $footerName = $timestamp . '_footerlogo.' . $footerFile->getClientOriginalExtension();
        $footerFile->move(public_path('general'), $footerName);
        $updateData['footerlogo'] = $footerName;
    }

    // Text fields
    $updateData['heading']                 = $request->input('heading');
    $updateData['email']                   = $request->input('email');
    $updateData['address']                 = $request->input('address');
    $updateData['state']                   = $request->input('state');
    $updateData['city']                    = $request->input('city');
    $updateData['title']                   = $request->input('title');
    $updateData['subtitle']                = $request->input('subtitle');
    $updateData['contact']                 = $request->input('contact');
    $updateData['bankdetail']              = $request->input('bankdetail');
    $updateData['link']                    = $request->input('link');
    $updateData['note']                    = $request->input('note');
    $updateData['footer']                  = $request->input('footer');
    $updateData['trust_register_number']   = $request->input('trust_register_number');

    // Save to DB
    DB::table('general')->where('id', 1)->update($updateData);

    // Clear related cache
    $cacheKeys = [
        'general_logo', 'general_favicon', 'general_email',
        'general_address', 'general_state', 'general_city',
        'general_trust_register_number'
    ];
    foreach ($cacheKeys as $key) {
        Cache::forget($key);
    }

    return back()->with('success', 'General settings updated successfully.');
}

    
 public function getCities($state_id)
 {
     $cities = City::where('state_id', $state_id)->pluck('name', 'id');
     return response()->json($cities);
 }
 public function updateWhatsappSettings(Request $request)
 {
     // Insert default if table is empty
     $exists = DB::table('whatsapp')->count();
 
     if ($exists == 0) {
         DB::table('whatsapp')->insert([
             'apikey' => '',
             'status' => 'inactive'
         ]);
     }
 
     // Now update
     DB::table('whatsapp')->update([
         'apikey' => $request->apikey,
         'status' => $request->status,
     ]);
 
     return back()->with('success', 'WhatsApp settings updated successfully.');
 }
 public function updatesmsSettings(Request $request)
 {
     // Insert default if table is empty
     $exists = DB::table('sms')->count();
 
     if ($exists == 0) {
         DB::table('sms')->insert([
             'apikey' => '',
             'status' => 'inactive'
         ]);
     }
 
     // Now update
     DB::table('sms')->update([
         'apikey' => $request->apikey,
         'status' => $request->status,
     ]);
 
     return back()->with('success', 'sms settings updated successfully.');
 }

 public function updatePaymentSettings(Request $request)
{
    DB::table('payment')->updateOrInsert(
        ['id' => 1],
        [
            'apikey' => $request->apikey,
            'secretkey' => $request->secretkey,
            'payment_option' => $request->payment_option ?? 'razorpay',
            'status' => $request->status,
        ]
    );

    return back()->with('success', 'Payment settings updated successfully.');
}

}
