<?php
    
namespace App\Http\Controllers;
    
use App\Models\Qurbani;
use App\Models\General;
use Illuminate\View\View;
use App\Models\QurbaniHisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
    
class QurbaniController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:qurbani-list|qurbani-create|qurbani-edit|qurbani-delete', ['only' => ['index','show']]);
         $this->middleware('permission:qurbani-create', ['only' => ['create','store']]);
         $this->middleware('permission:qurbani-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:qurbani-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $user = auth()->user(); // 🔥 Fix added
    
        $name = $request->input('contact_name');
        $mobile = $request->input('mobile');
        $receiptbook = $request->input('receipt_book');
        $year = $request->input('year', 2025);
        $sortBy = $request->input('sort_by', 'id');
        $order = $request->input('order', 'desc');
    
        $query = Qurbani::with('details')
        ->whereYear('created_at', $year)
        ->whereNotNull('user_id') // ✅ Exclude incomplete guest entries
        ->where('is_approved', 1); // ✅ Only show approved (including guest with user_id = 1)
    
    
        $query = Qurbani::with('details')
        ->whereYear('created_at', $year)
        ->where(function ($q) {
            $q->whereNotNull('user_id') // admin entries (show all)
              ->orWhere(function ($sub) {
                  $sub->whereNull('user_id') // guest entries
                       ->where('is_approved', 1); // only approved guests
              });
        });
    
            
        if ($name) {
            $query->where('contact_name', 'like', "%$name%");
        }
    
        if ($mobile) {
            $query->where('mobile', 'like', "%$mobile%");
        }
    
        if ($receiptbook) {
            $query->where('receipt_book', 'like', "%$receiptbook%");
        }
    
        if ($request->filled('collected_by')) {
            $query->where('user_id', $request->collected_by);
        }
    
        if (in_array($sortBy, ['contact_name', 'mobile', 'id']) && in_array($order, ['asc', 'desc'])) {
            $query->orderBy($sortBy, $order);
        }
    
        $qurbanis = $query->get();
    
        if ($sortBy === 'hissa') {
            $qurbanis = $qurbanis->sortBy(function ($qurbani) {
                return $qurbani->details->sum('hissa');
            }, SORT_REGULAR, $order === 'desc');
        }
    
        // Pagination
        $page = $request->input('page', 1);
        $perPage = 50;
        $paginatedQurbanis = new \Illuminate\Pagination\LengthAwarePaginator(
            $qurbanis->slice(($page - 1) * $perPage, $perPage),
            $qurbanis->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    
        $collectedUsers = User::select('id', 'name')->get();
        $totalHissa = $qurbanis->pluck('details')->flatten()->sum('hissa');
    
        return view('qurbanis.index', compact('qurbanis', 'totalHissa', 'collectedUsers'))
            ->with('qurbanis', $paginatedQurbanis)
            ->with('i', ($page - 1) * $perPage);
    }
    
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $qurbani = new Qurbani();
        return view('qurbanis.create', compact('qurbani'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate input
        $rules = [
            'contact_name' => 'required|string|max:255',
            'receipt_book' => 'nullable|string|max:255',
            'mobile' => 'required|numeric|digits:10',
            'payment_type' => 'required|in:Cash,RazorPay',
            'transaction_number' => 'required_if:payment_type,RazorPay|string|nullable|max:255',
            'aqiqah' => 'nullable|array',
            'gender' => 'required|array',
            'hissa' => 'nullable|array',
            'name' => 'required|array',
            'name.*' => 'required|string|max:255',
        ];
    
        $messages = [
            'transaction_number.required_if' => 'Transaction number is required for Online payments.',
        ];
    
        $request->validate($rules, $messages);
    
        // Map RazorPay to Online
        $paymentStatus = [
            'Cash' => 'Cash Paid',
            'RazorPay' => 'Paid Online',
        ];
    
        // Create Qurbani
        $qurbani = new Qurbani();
        $qurbani->user_id = Auth::id() ?? 0;
        $qurbani->contact_name = $request->contact_name;
        $qurbani->mobile = $request->mobile;
        $qurbani->payment_type = $request->payment_type === 'RazorPay' ? 'Online' : 'Cash';

        $qurbani->payment_status = $paymentStatus[$request->payment_type];
        $qurbani->transaction_number = $request->transaction_number ?? null;
    
        if ($qurbani->save()) {
            // Receipt book format
            $receiptBookId = !empty($request->receipt_book)
                ? "RB{$qurbani->id} ({$request->receipt_book})"
                : "RB{$qurbani->id}";
            $qurbani->update(['receipt_book' => $receiptBookId]);
    
            // Save Hissa data
            foreach ($request->name as $key => $value) {
                $qurbanihisse = new QurbaniHisse();
                $qurbanihisse->user_id = Auth::id() ?? 0;
                $qurbanihisse->qurbani_id = $qurbani->id;
                $qurbanihisse->name = $value;
                $qurbanihisse->aqiqah = !empty($request->aqiqah[$key]) ? '1' : '0';
                $qurbanihisse->gender = $request->gender[$key] ?? null;
                $qurbanihisse->hissa = (int)($request->hissa[$key] ?? 1);
                $qurbanihisse->save();
            }
    
            // PDF generation
            $pdf = Pdf::loadView('qurbanis.view', compact('qurbani'))
                      ->setPaper([0, 0, 500, 380], 'portrait');
    
            $pdfFolder = public_path('pdfs');
            if (!file_exists($pdfFolder)) {
                mkdir($pdfFolder, 0777, true);
            }
    
            $pdfPath = "{$pdfFolder}/qurbani_{$qurbani->id}.pdf";
            $pdf->save($pdfPath);
    
            $pdfUrl = asset("pdfs/qurbani_{$qurbani->id}.pdf");
    
            // Send WhatsApp
            $this->WhatsAppMessage($request->mobile, $pdfUrl);
    
            return redirect()->route('qurbanis.index')->with('success', 'Qurbani Created & PDF Generated Successfully.');
        }
    
        return redirect()->route('qurbanis.index')->with('error', 'Qurbani Creation Failed.');
    }
    
    private function WhatsAppMessage($mobile, $pdfUrl)
{
    $apiKey = "772600866b1740438f3d04be57e285e6"; 
    $apiUrl = "https://whatsappnew.bestsms.co.in/wapp/v2/api/send";

    $postData = [
        'apikey' => $apiKey,
        'mobile' => $mobile,  
        'msg' => "Here is your Qurbani Receipt PDF: $pdfUrl",
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


        
    public function show(Qurbani $qurbani): View
    {
        $hisses = QurbaniHisse::where('qurbani_id',$qurbani->id)->get();
        //echo "<pre>"; print_r($hisse);
        return view('qurbanis.show',compact('qurbani','hisses'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Qurbani $qurbani): View
    {
        $qurbanihisse = QurbaniHisse::where('qurbani_id',$qurbani->id)->get();
        return view('qurbanis.edit',compact('qurbani','qurbanihisse'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Qurbani $qurbani): RedirectResponse
    {
         request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);
    
        $qurbani->update($request->all());
    
        return redirect()->route('qurbanis.index')
                        ->with('success','Qurbani hisse updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Qurbani $qurbani): RedirectResponse
    {
        $qurbani->delete();
        $qurbanihisse = QurbaniHisse::where('qurbani_id',$qurbani->id)->delete();
    
        return redirect()->route('qurbanis.index')
                        ->with('success','Qurbani Details deleted successfully');
    }
    public function showlist($listNumber)
{
    $allColumns = $this->getAllColumns(); // however you're getting the full dataset
    $perList = 200;

    $sliced = array_slice($allColumns, ($listNumber - 1) * $perList, $perList);

    return view('finallist', [
        'columns' => $sliced,
        'listNo' => $listNumber
    ]);
}
public function archive2024()
{
    $qurbanis = Qurbani::with('details')
                ->whereYear('created_at', 2024)
                ->latest()
                ->paginate(15);

    $collectedUsers = User::all();
    return view('qurbanis.archive', compact('qurbanis', 'collectedUsers'));
}

    // private function WhatsAppMessage($mobile, $pdfUrl)
    // {
    //     // Dummy function. Replace with your real implementation.
    //     // Example:
    //     // Http::post("https://api.whatsapp.com/send?phone=$mobile&text=$pdfUrl");
    // }
 
    public function guestSubmissions()
    {
        $qurbanis = Qurbani::whereNull('user_id')->where('is_approved', 0)->latest()->get();
        return view('qurbanis.guest_submissions', compact('qurbanis'));
    }
    public function approveGuest($id)
    {
        $qurbani = Qurbani::with('hissas')->findOrFail($id);
    
        $qurbani->user_id = Auth::id(); // Who approved it
        $qurbani->is_approved = 1;
        $qurbani->save();
    
        // Update all hissas also
        QurbaniHisse::where('qurbani_id', $qurbani->id)->update(['user_id' => Auth::id()]);
    
        return redirect()->back()->with('success', 'Approved Successfully');
    }
    public function generatePDF($qurbani_id)
    {
        $qurbaniid = base64_decode($qurbani_id);
        $qurbani = Qurbani::findOrFail($qurbaniid);
        $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbaniid)->get();
        $users = User::find($qurbani->user_id);
        $general = General::first();
    
        // Optional image paths if needed
        // $logoPath = public_path('logourdu.png');
        // $qrPath = public_path('qrcode.jpg');
        // $footerPath = public_path('DailyPatti.png');
  
        $pdf = \PDF::loadView('pdfview', [
            'qurbani' => $qurbani,
            'qurbanihisse' => $qurbanihisse,
            'users' => $users,
            'general' => $general,
            // 'logoPath' => $logoPath,
            // 'qrPath' => $qrPath,
            // 'footerPath' => $footerPath,
        ])->setPaper('A5', 'portrait');
    
        return $pdf->download('qurbani.pdf');
        // return view('pdfview', compact('qurbani', 'qurbanihisse', 'users', 'general'));
    }

}