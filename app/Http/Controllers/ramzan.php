<?php

namespace App\Http\Controllers;
use App\Models\RamzanCollection;
use Illuminate\Http\Request;
use App\Models\DonationCategory;
use Barryvdh\DomPDF\Facade\Pdf;
class RamzanCollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = RamzanCollection::query();

    if ($request->has('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }
    if ($request->has('contact')) {
        $query->where('contact', 'like', '%' . $request->contact . '%');
    }
    if ($request->has('receipt_book')) {
        $query->where('receipt_book', 'like', '%' . $request->receipt_book . '%');
    }
    if ($request->has('donationcategory') && $request->donationcategory != '') {
        $query->where('donationcategory', 'like', '%' . $request->donationcategory . '%');
    }
    $categories = DonationCategory::all(); 
    $collections = $query->paginate(10);
    return view('ramzan.collectionlist', compact('collections', 'categories'));
    }

    public function create()
{
    $latestCollection = RamzanCollection::latest()->first();
    
    $receiptBookId = $latestCollection ? $latestCollection->id : 1000; 

    $categories = DonationCategory::all(); 
    return view('ramzan.collection', compact('categories', 'receiptBookId'));
}

  
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'contact' => 'required|string|max:255',
        'date' => 'required|date',
        'address' => 'nullable|string|max:255',
        'note' => 'nullable|string|max:255',
        'donationcategory' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'payment_mode' => 'required|string|max:255',
        'transaction_id' => 'nullable|string|max:255',
        'user_id' => 'nullable|exists:users,id'
    ]);

    // 🔹 Pehle record insert karenge bina receipt_book ke
    $collection = RamzanCollection::create([
        'name' => $request->name,
        'contact' => $request->contact,
        'date' => $request->date,
        'address' => $request->address,
        'note' => $request->note,
        'donationcategory' => $request->donationcategory,
        'amount' => $request->amount,
        'payment_mode' => $request->payment_mode,
        'transaction_id' => $request->transaction_id,
        'user_id' => auth()->id(),
    ]);

     // ✅ Agar user ne Receipt Number enter kiya hai to use `( )` me add karein
     if (!empty($request->receipt_book)) {
        $receiptBookId = "RB" . $collection->id . " (" . $request->receipt_book . ")";
    } else {
        // ✅ Agar user ne kuch enter nahi kiya to sirf RB + ID set karein
        $receiptBookId = "RB" . $collection->id;
    }

    // 🔹 Receipt Book Update Karein
    $collection->update(['receipt_book' => $receiptBookId]);
    // 🔹 Generate PDF with Fixed Size
    $pdf = Pdf::loadView('ramzan.view', compact('collection'))
              ->setPaper([0, 0, 500, 380], 'portrait'); // **Same size as generatePDF()**

    // 🔹 Ensure "pdfs/" Folder Exists
    $pdfFolder = public_path('pdfs');
    if (!file_exists($pdfFolder)) {
        mkdir($pdfFolder, 0777, true);
    }

    // 🔹 Save PDF File
    $pdfPath = "{$pdfFolder}/collection_{$collection->id}.pdf";
    $pdf->save($pdfPath);

    // 🔹 Generate Public URL
    $pdfUrl = asset("pdfs/collection_{$collection->id}.pdf");

    // 🔹 Send WhatsApp Message with Correct Sized PDF
    $this->sendWhatsAppMessage($request->contact, $pdfUrl);

    return redirect()->route('collectionlist')->with('success', 'Collection created & PDF sent successfully.');
}

    
private function sendWhatsAppMessage($mobile, $pdfUrl)
{
    $apiKey = "772600866b1740438f3d04be57e285e6"; 
    $apiUrl = "https://whatsappnew.bestsms.co.in/wapp/v2/api/send";

    $postData = [
        'apikey' => $apiKey,
        'mobile' => $mobile,  
        'msg' => "Here is your receipt PDF: $pdfUrl",
        'pdf' => $pdfUrl // Correct Dynamic PDF URL
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    $response = curl_exec($ch);
    
   


    if (curl_errno($ch)) {
        return "Curl error: " . curl_error($ch);
    }

    curl_close($ch);

    return $response;
}

    
    

    // Show the form for editing an existing FAQ
    public function edit($id)
    {
        $categories = DonationCategory::all(); 
        $collection = RamzanCollection::findOrFail($id); // Find the FAQ by ID
        return view('ramzan.collectionedit', compact('collection', 'categories')); // Pass data to the edit view
    }

    // Update an existing collection
  // Update an existing collection
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'receipt_book' => 'nullable|string|max:255',
        'contact' => 'required|string|max:255',
        'date' => 'required|date',
        'address' => 'nullable|string|max:255',
        'note' => 'nullable|string|max:255',
        'donationcategory' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'user_id' => 'nullable|exists:users,id'
    ]);


    $collection = RamzanCollection::findOrFail($id);
    $collection->update($request->all());

    
    $pdf = Pdf::loadView('ramzan.view', compact('collection'));
    $pdfPath = public_path("pdfs/collection_{$collection->id}.pdf");
    $pdf->save($pdfPath); 

  
    $pdfUrl = asset("pdfs/collection_{$collection->id}.pdf");


    $this->sendWhatsAppMessage($request->contact, $pdfUrl);

    return redirect()->route('collectionlist')->with('success', 'Collection updated & PDF sent successfully.');
}


    // Delete an collection
    public function destroy($id)
    {
        $collection = RamzanCollection::findOrFail($id); // Find the collection by ID
        $collection->delete(); // Delete the collection

        return redirect()->route('collectionlist')->with('success', 'collection deleted successfully.');
    }
    public function view($id)
{
    $collection = RamzanCollection::findOrFail($id);
    return view('ramzan.view', compact('collection'));
}
public function generatePDF($id)
{
    $collection = RamzanCollection::findOrFail($id); 
    
    $pdf = Pdf::loadView('ramzan.view', compact('collection'))->setPaper([0, 0, 500, 380], 'portrait'); 
    return $pdf->stream('collection.pdf'); 
}

}
