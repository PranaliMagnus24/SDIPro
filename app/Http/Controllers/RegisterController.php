<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QurbaniHisse;
use App\Models\Qurbani;
use App\Models\General;
use Illuminate\Support\Facades\Auth;
class RegisterController extends Controller
{


    public function createqurbani(): \Illuminate\View\View
    {
        $general = General::first();
        $qurbani = new Qurbani();
        return view('qurbanis.register', compact('qurbani', 'general'));
    }

    public function storequrbani(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'contact_name' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits:10',
            'receipt_book' => 'nullable|string|max:100',
            'payment_type' => 'required|string|in:Cash,RazorPay',
            'transaction_number' => 'required_if:payment_type,RazorPay|max:255',
            'name.*' => 'required|string|max:255',
            'hissa.*' => 'required|integer|min:1',
            'upload_payment' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048', // Allow image/pdf
        ]);
    
        $uploadFileName = null;
    
        if ($request->hasFile('upload_payment')) {
            $uploadPath = public_path('uploads/payment_proofs/');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
    
            $file = $request->file('upload_payment');
            $uploadFileName = time() . '_' . $file->getClientOriginalName();
            $file->move($uploadPath, $uploadFileName);
        }
   // Save Qurbani main record
$qurbani = Qurbani::create([
    'user_id' => null, // Guest submission
    'contact_name' => $request->contact_name,
    'mobile' => $request->mobile,
    'receipt_book' => $request->receipt_book,
    'payment_type' => $request->payment_type,
    'transaction_number' => $request->payment_type === 'RazorPay' ? $request->transaction_number : null,
    'total_amount' => collect($request->hissa)->sum() * 1500,
    'upload_payment' => $uploadFileName,
    'is_approved' => 0, // mark as unapproved
]);

    
        // Save each Hissa record
        foreach ($request->name as $index => $name) {
            QurbaniHisse::create([
                'qurbani_id' => $qurbani->id,
                'name' => $name,
                'hissa' => $request->hissa[$index],
                'aqiqah' => $request->aqiqah[$index] ?? 0,
                'gender' => $request->gender[$index] ?? null,
                'user_id' => 1, // 👈 Add this line for guest submissions
            ]);
        }
    
        return redirect()->route('thankyouqurbani')->with('success', 'Your Qurbani registration was successful!');
    }
    


    public function thankyouqurbani(): \Illuminate\View\View
    {
        $general = General::first();
        return view('qurbanis.thankyou' ,compact('general'));
    }
    public function approveQurbani($id)
    {
        $qurbani = Qurbani::findOrFail($id);
    
        $qurbani->is_approved = 1;
    
        // Set user_id to the currently logged-in admin who is approving
        $qurbani->user_id = Auth::id();
    
        $qurbani->save();
    
        // Update all related hissas to same user_id
        QurbaniHisse::where('qurbani_id', $qurbani->id)->update(['user_id' => Auth::id()]);
    
        return redirect()->back()->with('success', 'Qurbani approved successfully.');
    }
    

    
}
