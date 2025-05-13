<?php

namespace App\Http\Controllers;

use App\Models\Qurbani;
use App\Models\User;
use App\Models\QurbaniHisse;

use App\Models\RamzanCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // {{--public function index()
    // {
    //     $query = User::select('users.id', 'users.name', DB::raw('COUNT(qurbanis.user_id) as qurbani_booked'));
    //     $query->leftJoin('qurbanis', 'users.id', '=', 'qurbanis.user_id');
    //     if (!in_array(Auth::user()->roles[0]->name,['Admin'])){
    //         $query->where('qurbanis.user_id',Auth::id());
    //     }
        
    //     $query->groupBy('users.id', 'users.name');
    //     $usersWithQurbaniCount =  $query->get();
    //         // echo "<pre>";
    //         // print_r($usersWithQurbaniCount);
    //     //return view('home',compact('usersWithQurbaniCount'));
    //     $query = Qurbani::query();
    //     if (!in_array(Auth::user()->roles[0]->name,['Admin'])){
    //         $query->where('user_id',Auth::id());
    //     }
    //     $receiptcount = $query->count();

    //     $query = QurbaniHisse::query();
    //     if (!in_array(Auth::user()->roles[0]->name,['Admin'])){
    //         $query->where('user_id',Auth::id());
    //     }
    //     $qurbanihisse = $query->count();
    //         // echo "<pre>";
    //         // print_r($usersWithQurbaniCount);
    //     return view('home',compact('usersWithQurbaniCount','receiptcount','qurbanihisse'));
    // } --}}
    public function index()
    {
        $user = Auth::user();
        $users = collect([]);
        $totalAmount = 0;
        $cashAmount = 0;
        $onlineAmount = 0;
        $unselectedAmount = 0;
        $cashReceipts = 0;
        $onlineReceipts = 0;
        $unselectedReceipts = 0;
        $ramadanReceiptCount = 0;
    
        if (isset($user->roles[0]) && $user->roles[0]->name === 'Admin') {
            // ✅ Fetch data for Admin (EXCLUDING deleted records)
            $users = User::whereHas('ramzancollections', function ($query) {
                    $query->whereNull('deleted_at'); // ✅ Exclude deleted records
                })
                ->select('users.id', 'users.name')
                ->leftJoin('ramzancollections', function ($join) {
                    $join->on('users.id', '=', 'ramzancollections.user_id')
                         ->whereNull('ramzancollections.deleted_at'); // ✅ Exclude deleted records
                })
                ->groupBy('users.id', 'users.name')
                ->selectRaw('
                    COUNT(ramzancollections.id) as receipt_count,
                    COUNT(CASE WHEN ramzancollections.payment_mode = "cash" THEN 1 ELSE NULL END) as cash_receipts,
                    COUNT(CASE WHEN ramzancollections.payment_mode = "online" THEN 1 ELSE NULL END) as online_receipts,
                    COUNT(CASE WHEN ramzancollections.payment_mode IS NULL OR ramzancollections.payment_mode = "" THEN 1 ELSE NULL END) as unselected_receipts,
                    COALESCE(SUM(CASE WHEN ramzancollections.payment_mode = "cash" THEN ramzancollections.amount ELSE 0 END), 0) as cash_amount,
                    COALESCE(SUM(CASE WHEN ramzancollections.payment_mode = "online" THEN ramzancollections.amount ELSE 0 END), 0) as online_amount,
                    COALESCE(SUM(CASE WHEN ramzancollections.payment_mode IS NULL OR ramzancollections.payment_mode = "" THEN ramzancollections.amount ELSE 0 END), 0) as unselected_amount,
                    COALESCE(SUM(ramzancollections.amount), 0) as total_amount
                ')
                ->orderByDesc('total_amount')
                ->get();
    
            // ✅ Ensure totals are summed correctly
            $ramadanReceiptCount = $users->sum('receipt_count');
            $cashAmount = $users->sum('cash_amount');
            $onlineAmount = $users->sum('online_amount');
            $unselectedAmount = $users->sum('unselected_amount');
            $totalAmount = $cashAmount + $onlineAmount + $unselectedAmount;
    
            $cashReceipts = $users->sum('cash_receipts');
            $onlineReceipts = $users->sum('online_receipts');
            $unselectedReceipts = $users->sum('unselected_receipts');
    
        } else {
            // ✅ Fetch data for User (EXCLUDING deleted records)
            $users = User::whereHas('ramzancollections', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->whereNull('deleted_at'); // ✅ Exclude deleted records
                })
                ->select('users.id', 'users.name')
                ->leftJoin('ramzancollections', function ($join) use ($user) {
                    $join->on('users.id', '=', 'ramzancollections.user_id')
                         ->where('ramzancollections.user_id', $user->id)
                         ->whereNull('ramzancollections.deleted_at'); // ✅ Exclude deleted records
                })
                ->groupBy('users.id', 'users.name')
                ->selectRaw('
                    COUNT(ramzancollections.id) as receipt_count,
                    COUNT(CASE WHEN ramzancollections.payment_mode = "cash" THEN 1 ELSE NULL END) as cash_receipts,
                    COUNT(CASE WHEN ramzancollections.payment_mode = "online" THEN 1 ELSE NULL END) as online_receipts,
                    COUNT(CASE WHEN ramzancollections.payment_mode IS NULL OR ramzancollections.payment_mode = "" THEN 1 ELSE NULL END) as unselected_receipts,
                    COALESCE(SUM(CASE WHEN ramzancollections.payment_mode = "cash" THEN ramzancollections.amount ELSE 0 END), 0) as cash_amount,
                    COALESCE(SUM(CASE WHEN ramzancollections.payment_mode = "online" THEN ramzancollections.amount ELSE 0 END), 0) as online_amount,
                    COALESCE(SUM(CASE WHEN ramzancollections.payment_mode IS NULL OR ramzancollections.payment_mode = "" THEN ramzancollections.amount ELSE 0 END), 0) as unselected_amount,
                    COALESCE(SUM(ramzancollections.amount), 0) as total_amount
                ')
                ->orderByDesc('total_amount')
                ->get();
    
            // ✅ Ensure totals are summed correctly
            $ramadanReceiptCount = $users->sum('receipt_count');
            $cashAmount = $users->sum('cash_amount');
            $onlineAmount = $users->sum('online_amount');
            $unselectedAmount = $users->sum('unselected_amount');
            $totalAmount = $cashAmount + $onlineAmount + $unselectedAmount;
    
            $cashReceipts = $users->sum('cash_receipts');
            $onlineReceipts = $users->sum('online_receipts');
            $unselectedReceipts = $users->sum('unselected_receipts');
        }
    
        return view('home', compact(
            'totalAmount', 'cashAmount', 'onlineAmount', 'unselectedAmount',
            'cashReceipts', 'onlineReceipts', 'unselectedReceipts', 'ramadanReceiptCount', 'users'
        ));
    }
    
    
}
