<?php

namespace App\Http\Controllers;
use App\Models\RamzanCollection;
use Illuminate\Http\Request;
use App\Models\DonationCategory;
use App\Models\User;
use App\Models\General;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
class RamzanCollectionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = RamzanCollection::query();
    
        if (!empty($user->roles) && isset($user->roles[0]) && $user->roles[0]->name !== 'Admin') {
            $query->where('user_id', $user->id);
        }
    
        // Filters
        $query->when($request->name, fn($q) => $q->where('name', 'like', "%{$request->name}%"))
              ->when($request->contact, fn($q) => $q->where('contact', 'like', "%{$request->contact}%"))
              ->when($request->receipt_book, fn($q) => $q->where('receipt_book', 'like', "%{$request->receipt_book}%"))
              ->when($request->donationcategory, fn($q) => $q->where('donationcategory', $request->donationcategory))
              ->when($request->payment_mode, fn($q) => $q->where('payment_mode', $request->payment_mode))
              ->when($request->collected_by, fn($q) => $q->where('user_id', $request->collected_by));
    
        // Sorting
        $allowedSortFields = ['date', 'name', 'amount'];
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
    
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }
    
        $collections = $query->paginate(10)->appends($request->all());
        $categories = DonationCategory::all();
        $collectedUsers = User::all();
    
        return view('ramzan.collectionlist', compact('collections', 'categories', 'collectedUsers'));
    }
    

    public function create()
    {
        // Get the latest collection entry based on ID
        $latestCollection = RamzanCollection::orderBy('id', 'desc')->first();
    
        // Generate next receipt book ID (start from 1000 if no records exist)
        $receiptBookId = $latestCollection ? $latestCollection->id + 1 : 1000;
    
        // Fetch all donation categories
        $categories = DonationCategory::all();
    
        return view('ramzan.collection', compact('categories', 'receiptBookId'));
    }
    
  
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'receipt_book' => 'nullable|string|max:255',
        'contact' => 'required|numeric|digits:10',
        'date' => 'required|date',
        'address' => 'nullable|string|max:255',
        'note' => 'nullable|string|max:255',
        'donationcategory' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'payment_mode' => 'required|string|max:255',
        'transaction_id' => 'nullable|string|max:255',
       'msg_send' => 'nullable|boolean',
        'user_id' => 'nullable|exists:users,id'
    ]);

    $data = $request->all();
    $data['user_id'] = Auth::id();
    $data['msg_send'] = 0;

    // केवल यूजर द्वारा दिया गया Receipt Book नंबर सेव करें
    $collection = RamzanCollection::create($data);
 
    $logoPath = public_path('logourdu.png');
    $qrPath = public_path('qrcode.jpg');
    $dailyPattiPath = public_path('DailyPatti.png');

    // Generate PDF
    $pdf = Pdf::loadView('ramzan.view', compact('collection', 'logoPath', 'qrPath', 'dailyPattiPath'))
              ->setPaper('A5', 'portrait');

    // 🔹 Ensure "pdfs/" Folder Exists
    $pdfFolder = public_path('pdfs');
    if (!file_exists($pdfFolder)) {
        mkdir($pdfFolder, 0777, true);
    }

    $filename = 'SDI_Ramadan2025_Receipt_'.$collection->id.'.pdf';

    $pdfPath = "{$pdfFolder}/".$filename;
    $pdf->save($pdfPath);

    $pdfUrl = asset("pdfs/".$filename);


    // $this->sendWhatsAppMessage($request->contact, $pdfUrl);
    $whatsappResponse = $this->sendWhatsAppMessage($request->contact, $pdfUrl);

 
    $responseData = json_decode($whatsappResponse, true);
    
 
    // dd($responseData);
    
    if (isset($responseData['status']) && $responseData['status'] == "success" && $responseData['statuscode'] == 200) {
        $collection->msg_send = 1;
    } else {
        $collection->msg_send = 0;
    }
    
    $collection->save();
    
    

    return redirect()->route('collectionlist')->with('success', 'Collection created & PDF sent successfully.');
}

    
private function sendWhatsAppMessage($mobile, $pdfUrl)
{
    $apiKey = "68400ea593ee4cc098ef960d9c5c5c47"; 
    $apiUrl = "https://whatsappnew.bestsms.co.in/wapp/v2/api/send";

    $postData = [
        'apikey' => $apiKey,
        'mobile' => $mobile,  
        //'msg' => "आप ने तहेरीक की इमदाद फरमाई हम आपका शुक्रिया अदा करते है. अल्लाह आपके इमदाद को कुबुल फरमाए और आपको बेहतर सिलाह अता फरमाए आमीन जज़ाकल्लाहु खैर: $pdfUrl",
        'msg' => "आप ने तहेरीक सुन्नी दावते इस्लामी की इमदाद फरमाई हम आपका शुक्रिया अदा करते है. अल्लाह आपके इमदाद को कुबुल फरमाए और आपको बेहतर सिलाह अता फरमाए आमीन
जज़ाकल्लाहु खैर: $pdfUrl",
        'pdf' => $pdfUrl 
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

  
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'receipt_book'     => 'nullable|string|max:255',
            'contact'          => 'required|numeric|digits:10',
            'date'             => 'required|date',
            'address'          => 'nullable|string|max:255',
            'note'             => 'nullable|string|max:255',
            'donationcategory' => 'required|string|max:255',
            'amount'           => 'required|numeric|min:0',
            'payment_mode'     => 'required|string|max:255',
            'transaction_id'   => 'nullable|string|max:255',
            'msg_send'         => 'nullable|boolean',
            'user_id'          => 'nullable|exists:users,id'
        ]);
    
        // 1. पुराना रिकॉर्ड लाएँ
        $collection = RamzanCollection::findOrFail($id);
    
        // 2. इनपुट डेटा अपडेट करें
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['msg_send'] = 0;
        $collection->update($data);
        $collection->refresh();  // सुनिश्चित करें कि अपडेटेड डेटा लोड हो गया है
    
        // 3. PDF जनरेट करने के लिए ज़रूरी Path
        $logoPath       = public_path('logourdu.png');
        $qrPath         = public_path('qrcode.jpg');
        $dailyPattiPath = public_path('DailyPatti.png');
    
        // 4. नया PDF जनरेट करें (A5 साइज़, Portrait)
        $pdf = Pdf::loadView('ramzan.view', [
            'collection'     => $collection,
            'logoPath'       => $logoPath,
            'qrPath'         => $qrPath,
            'dailyPattiPath' => $dailyPattiPath
        ])->setPaper('A5', 'portrait');
    
        // 5. pdfs/ फोल्डर सुनिश्चित करें
        $pdfFolder = public_path('pdfs');
        if (!file_exists($pdfFolder)) {
            mkdir($pdfFolder, 0777, true);
        }
    
     
        $filename = 'SDI_Ramadan2025_Receipt_' . $collection->id . '_' . time() . '.pdf';
        $pdfPath = $pdfFolder . '/' . $filename;
  
        if (file_exists($pdfPath)) {
            unlink($pdfPath);
        }
        $pdf->save($pdfPath);
    
        $pdfUrl = asset('pdfs/' . $filename);
    
 
        $whatsappResponse = $this->sendWhatsAppMessage($collection->contact, $pdfUrl);
        // \Log::info('WhatsApp Response on UPDATE: ' . $whatsappResponse);
        // dd($whatsappResponse); // Debug: देखें API से क्या response मिल रहा है
    
        $responseData = json_decode($whatsappResponse, true);
        if (isset($responseData['status']) && $responseData['status'] === "success" && $responseData['statuscode'] == 200) {
            $collection->msg_send = 1;
        } else {
            $collection->msg_send = 0;
        }
        $collection->save();
    
        return redirect()->route('collectionlist')
                         ->with('success', 'Collection updated & PDF sent successfully.');
    }
    

 // Delete a collection
 public function destroy($id)
 {
     $collection = RamzanCollection::findOrFail($id); 
     if (Auth::id() !== $collection->user_id && 
         (!isset(Auth::user()->roles[0]) || Auth::user()->roles[0]->name !== 'Admin')) {
         return redirect()->route('collectionlist')->with('error', 'Unauthorized access.');
     }
 
     $collection->delete(); 
 
     return redirect()->route('collectionlist')->with('success', 'Collection deleted successfully.');
 }
 

// View a collection
public function view($id)
{
    $collection = RamzanCollection::findOrFail($id);
    $general = General::first();
    if (Auth::id() !== $collection->user_id && 
        (!isset(Auth::user()->roles[0]) || Auth::user()->roles[0]->name !== 'Admin')) {
        return redirect()->route('collectionlist')->with('error', 'Unauthorized access.');
    }

    return view('ramzan.view', compact('collection', 'general'));
}

// Generate PDF
public function generatePDF($id)
{
    $collection = RamzanCollection::findOrFail($id);
     $general = General::first();
    // Define image paths
    $logoPath = public_path('logourdu.png'); // Ensure image is in public/
    $qrPath = public_path('qrcode.jpg');
    $dailyPattiPath = public_path('DailyPatti.png');

    // Load the Blade view and pass variables
    $pdf = Pdf::loadView('ramzan.view', compact('collection', 'logoPath', 'qrPath', 'dailyPattiPath', 'general'))
        ->setPaper('A5', 'portrait');

    return $pdf->stream('collection.pdf');
}
public function export(Request $request)
{
    if (!isset(Auth::user()->roles[0]) || Auth::user()->roles[0]->name !== 'Admin') {
        abort(403, 'Unauthorized Action.');
    }

    $fileName = 'ramadan_collection_list.csv';
    $query = RamzanCollection::query();

    // ✅ Apply Filters Based on Request
    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }
    if ($request->filled('contact')) {
        $query->where('contact', 'like', '%' . $request->contact . '%');
    }
    if ($request->filled('receipt_book')) {
        $query->where('receipt_book', 'like', '%' . $request->receipt_book . '%');
    }
    if ($request->filled('donationcategory')) {
        $query->where('donationcategory', $request->donationcategory);
    }
    if ($request->filled('payment_mode')) {
        $query->where('payment_mode', $request->payment_mode);
    }
    // ✅ Get Filtered Data
    $collections = $query->with('user')
        ->select('name', 'contact', 'date', 'donationcategory', 'payment_mode', 'amount', 'user_id')
        ->get();

    // ✅ Calculate Totals from Filtered Data
    $totalReceipts = $collections->count();
    $totalAmount = $collections->sum('amount');

    $cashCollections = $collections->where('payment_mode', 'Cash');
    $onlineCollections = $collections->where('payment_mode', 'Online');
    $notSelectedCollections = $collections->whereNull('payment_mode');

    $totalCashAmount = $cashCollections->sum('amount');
    $totalCashCount = $cashCollections->count();

    $totalOnlineAmount = $onlineCollections->sum('amount');
    $totalOnlineCount = $onlineCollections->count();

    $totalNotSelectedAmount = $notSelectedCollections->sum('amount');
    $totalNotSelectedCount = $notSelectedCollections->count();

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
    ];

    $callback = function () use ($collections, $totalReceipts, $totalAmount, $totalCashAmount, $totalCashCount, $totalOnlineAmount, $totalOnlineCount, $totalNotSelectedAmount, $totalNotSelectedCount) {
        $file = fopen('php://output', 'w');

        // ✅ CSV Headers
        fputcsv($file, ['S.No', 'Name', 'Contact', 'Date', 'Donation Category', 'Payment Mode', 'Amount', 'Collected By']);

        // ✅ Export Filtered Data
        $serial = 1;
        foreach ($collections as $collection) {
            fputcsv($file, [
                $serial++,  
                $collection->name,  
                "\t" . $collection->contact,  
                date('d-m-Y', strtotime($collection->date)),  
                $collection->donationcategory,
                $collection->payment_mode ?? 'N/A',
                sprintf("%.2f", $collection->amount),  // ✅ Fixed large number display issue
                $collection->user->name ?? 'N/A',
            ]);
            
        }

        // ✅ Add Totals Row (Properly formatted with spacing)
        fputcsv($file, []); // Empty Row for Separation
        fputcsv($file, ['Ramadan Receipts', '', '', $totalReceipts]);  
        fputcsv($file, ['Total Amount', '', '', sprintf("%.2f", $totalAmount)]);


        fputcsv($file, []); // Empty row for spacing
        fputcsv($file, ['Cash Amount', '', '', number_format($totalCashAmount, 2) . " ($totalCashCount)"]);  
        fputcsv($file, ['Online Amount', '', '', number_format($totalOnlineAmount, 2) . " ($totalOnlineCount)"]);  
        fputcsv($file, ['No Payment Amount', '', '', number_format($totalNotSelectedAmount, 2) . " ($totalNotSelectedCount)"]);  

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
// View a collection
public function show($id)
{
    $collection = RamzanCollection::findOrFail($id);
    
    return view('ramzan.show', compact('collection'));
}
}



