<?php
namespace App\Http\Controllers;
use App\Models\General;
use App\Models\User;
use App\Models\Qurbani;
use App\Models\QurbaniHisse;
use Illuminate\Http\Request;
use PDF; // Import the PDF facade

class PDFController extends Controller
{
    public function generatePDF($qurbani_id)
{
    $qurbaniid = base64_decode($qurbani_id);
    $qurbani = Qurbani::find($qurbaniid);
    $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbaniid)->get();
    $users = User::find($qurbani->user_id);
    $general = General::first();
    $pdfname = $qurbani->contact_name . "_" . $qurbani->id;
   // return view('pdfview',compact('qurbani', 'qurbanihisse', 'users', 'general'));
    $customPaper = 'A5';
    // return view('pdfview',compact('qurbani', 'qurbanihisse', 'users', 'general'));
    $pdf = \PDF::loadView('pdfview', [
        'qurbani' => $qurbani,
        'qurbanihisse' => $qurbanihisse,
        'users' => $users,
        'general' => $general
    ])
    ->setPaper('A5', 'portrait')
    ->setOptions([
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
    ]);
    

    return $pdf->download($pdfname . '.pdf'); // this opens in new tab
}

    // public function generatePDF($qurbani_id)
    // {
    //     $qurbaniid = base64_decode($qurbani_id);
    //     $qurbani = Qurbani::find($qurbaniid);
    //     $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbaniid)->get();
    //     $users = User::find($qurbani->user_id);
    //     $general = General::first();
    //     $pdfname = $qurbani->contact_name . "_" . $qurbani->id;
    
    //     $customPaper = 'A5';
    //     return view('pdfview', compact('qurbani', 'qurbanihisse', 'users', 'general')); 
    //     // $pdf = PDF::loadView('pdfview', [
    //     //     'qurbani' => $qurbani,
    //     //     'qurbanihisse' => $qurbanihisse,
    //     //     'users' => $users,
    //     //     'general' => General::first()
    //     // ])
    //     // ->setPaper($customPaper, 'portrait')
    //     // ->setOptions([
    //     //     'isRemoteEnabled' => true,
    //     // ]);
    
    //     // return $pdf->download($pdfname . '.pdf');
    // }
    
    public function generatefinallist($day)
    {
        
        // echo $day;
        // die();
        // echo $qurbani_id;
        // die();
        // $qurbaniid = base64_decode($qurbani_id);
        // $qurbani = Qurbani::find($qurbaniid);

        $query = QurbaniHisse::query();
        if($day==1){
            $query->where('created_at','<=',date('2024-06-16 21:59:59'));
        }else if($day==2){
            $query->where('created_at','>',date('2024-06-16 21:59:59'))
            ->where('created_at','<=',date('2024-06-17 23:59:59'));
        }
        $qurbanihisse = $query->get()->toArray();

        $columns = array_chunk($qurbanihisse, 7);
        //echo "<pre>"; echo  print_r($columns);
        // return view('finallist',compact('columns'));
        // die();
         
        $customPaper = 'A4';
        //$customPaper = [0, 0, 226.77, 567.00];
        $pdf = PDF::loadView('finallist', [
            'columns' => $columns           
        ])->setPaper($customPaper, 'portrait');

        // // $customPaper = array(0,0,567.00,283.80);
        
        // // $pdf = PDF::loadView('pdfview', $data)->setPaper($customPaper, 'landscape');

         return $pdf->download('Qurbani_final_list.pdf');
    }
}
