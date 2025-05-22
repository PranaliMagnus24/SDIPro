<?php

namespace App\Http\Controllers;

use App\Models\Qurbani;
use App\Models\Qurbani2024;
use App\Models\General;
use Illuminate\View\View;
use App\Models\QurbaniHisse;
use App\Models\QurbaniHisse2024;
use App\Models\QurbaniDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use App\Jobs\GenerateQurbaniPdfJob;

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
        $rules = [
            'contact_name' => 'required|string|max:255',
            'receipt_book' => 'nullable|string|max:255',
            'mobile' => 'required|numeric|digits:10',
            'alternative_mobile' => 'nullable|numeric|digits:10',
            'payment_type' => 'required|in:Cash,RazorPay',
            'qurbani_days' => 'nullable|string',
            'transaction_number' => 'required_if:payment_type,RazorPay|string|nullable|max:255',
            'upload_payment' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:2048',
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
        $qurbani->alternative_mobile = $request->alternative_mobile;
        $qurbani->qurbani_days = $request->qurbani_days;
        $qurbani->receipt_book = $request->receipt_book;
        $qurbani->payment_type = $request->payment_type === 'RazorPay' ? 'Online' : 'Cash';
        $qurbani->payment_status = $paymentStatus[$request->payment_type];
        $qurbani->transaction_number = $request->transaction_number ?? null;
        $qurbani->total_amount = $request->total_amount;

        if ($request->hasFile('upload_payment')) {
        $file = $request->file('upload_payment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/payment_proofs'), $filename);
        $qurbani->upload_payment = 'uploads/payment_proofs/' . $filename;
    }

        if ($qurbani->save()) {
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

            GenerateQurbaniPdfJob::dispatch($qurbani->id, $request->mobile);

            return redirect()->route('qurbanis.index')->with('success', 'Qurbani Created Successfully!');
        }

        return redirect()->route('qurbanis.index')->with('error', 'Qurbani Creation Failed.');
    }

    ////Display Qurbani Data with Hissa
    public function show(Qurbani $qurbani): View
    {
        $hisses = QurbaniHisse::where('qurbani_id',$qurbani->id)->get();
        return view('qurbanis.show',compact('qurbani','hisses'));
    }


    ///Edit Qurbani Data with Hissa
    public function edit($id): View
    {
        $qurbani = Qurbani::findOrFail($id);
        $qurbanihisse = QurbaniHisse::where('qurbani_id',$qurbani->id)->get();
        $isEditMode = true;
        return view('qurbanis.edit',compact('qurbani','qurbanihisse','isEditMode'));
    }


    ////Update Qurbani Data with Hissa
  public function update(Request $request, $id): RedirectResponse
{
    $rules = [
        'contact_name' => 'required|string|max:255',
        'receipt_book' => 'nullable|string|max:255',
        'mobile' => 'required|numeric|digits:10',
        'alternative_mobile' => 'nullable|numeric|digits:10',
        'payment_type' => 'required|in:Cash,RazorPay',
        'qurbani_days' => 'nullable|string',
        'transaction_number' => 'required_if:payment_type,RazorPay|string|nullable|max:255',
        'upload_payment' => 'nullable|file|mimes:jpeg,png,pdf,jpg|max:2048',
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

    $paymentStatus = [
        'Cash' => 'Cash Paid',
        'RazorPay' => 'Paid Online',
    ];
    $qurbani = Qurbani::findOrFail($id);
    $qurbani->user_id = Auth::id() ?? 0;
    $qurbani->contact_name = $request->contact_name;
    $qurbani->mobile = $request->mobile;
    $qurbani->alternative_mobile = $request->alternative_mobile;
    $qurbani->qurbani_days = $request->qurbani_days;
    $qurbani->receipt_book = $request->receipt_book;
    $qurbani->payment_type = $request->payment_type === 'RazorPay' ? 'Online' : 'Cash';
    $qurbani->payment_status = $paymentStatus[$request->payment_type];
    $qurbani->transaction_number = $request->transaction_number ?? null;
    $qurbani->total_amount = $request->total_amount;

    if ($request->hasFile('upload_payment')) {
        $file = $request->file('upload_payment');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/payment_proofs'), $filename);
        $qurbani->upload_payment = 'uploads/payment_proofs/' . $filename;
    }

    if ($qurbani->save()) {

    QurbaniHisse::where('qurbani_id', $qurbani->id)->delete();

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

    GenerateQurbaniPdfJob::dispatch($qurbani->id, $request->mobile);
    return redirect()->route('qurbanis.index')->with('success', 'Qurbani Updated Successfully!');
    }
    return redirect()->route('qurbanis.index')->with('error', 'Qurbani Update Failed.');
}


   ////////Delete  Qurbani Data with Data
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

    //////Pdf Generate Manually
    public function generatePDF($qurbani_id)
    {
        $qurbaniid = base64_decode($qurbani_id);
        $qurbani = Qurbani::findOrFail($qurbaniid);
        $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbaniid)->get();
        $users = User::find($qurbani->user_id);
        $general = General::first();
        $logoPath = public_path('logourdu.png');
        $qrPath = public_path('qrcode.jpg');
        $footerImgPath = public_path('DailyPatti.png');

        $pdf = \PDF::loadView('pdfview', [
            'qurbani' => $qurbani,
            'qurbanihisse' => $qurbanihisse,
            'users' => $users,
            'general' => $general,
            'logoPath' => $logoPath,
            'qrPath' => $qrPath,
            'footerImgPath' => $footerImgPath,
        ])->setPaper('A5', 'portrait');

        return $pdf->download('qurbani.pdf');
    }


    /////AutoComplete data name mobile
public function suggest(Request $request)
{
    $search = $request->get('query');
    $field = $request->get('field');

    $query = Qurbani2024::query()->with('details2024');

    if ($field === 'contact_name') {
        $query->where('contact_name', 'LIKE', "%{$search}%");
    } elseif ($field === 'mobile') {
        $query->where('mobile', 'LIKE', "%{$search}%");
    }

    $results = $query->select('id', 'contact_name', 'mobile', 'payment_type', 'receipt_book')
        ->take(10)
        ->get();

    $formatted = $results->map(function ($item) use ($field) {
        return [
            'label' => $field === 'contact_name' ? $item->contact_name : $item->mobile,
            'value' => $field === 'contact_name' ? $item->contact_name : $item->mobile,
            'contact_name' => $item->contact_name,
            'mobile' => $item->mobile,
            'payment_type' => $item->payment_type,
            'receipt_book' => $item->receipt_book,
            'hisses' => $item->details2024,
        ];
    });

    return response()->json($formatted);
}





}
