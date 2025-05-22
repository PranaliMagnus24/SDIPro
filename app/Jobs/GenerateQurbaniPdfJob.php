<?php

namespace App\Jobs;

use App\Models\Qurbani;
use App\Models\QurbaniHisse;
use App\Models\User;
use App\Models\General;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use App\Jobs\SendWhatsAppMessageJob;
use App\Jobs\SendSMSMessageJob;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateQurbaniPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $qurbaniId;
    protected $mobile;

    public function __construct($qurbaniId, $mobile)
    {
        $this->qurbaniId = $qurbaniId;
        $this->mobile = $mobile;
    }

    public function handle()
    {
        $qurbani = Qurbani::findOrFail($this->qurbaniId);
        $qurbanihisse = QurbaniHisse::where('qurbani_id', $qurbani->id)->get();
        $users = User::find($qurbani->user_id);
        $general = General::first();

        $logoPath = public_path('logourdu.png');
        $qrPath = public_path('qrcode.jpg');
        $footerImgPath = public_path('DailyPatti.png');

          $pdf = Pdf::loadView('pdfview', compact('qurbani', 'logoPath', 'qrPath', 'footerImgPath', 'general','qurbanihisse'))
              ->setPaper('A5', 'portrait');

        $pdfFolder = public_path('pdfs');
        if (!File::exists($pdfFolder)) {
            File::makeDirectory($pdfFolder, 0777, true);
        }

        $pdfPath = "{$pdfFolder}/qurbani_{$qurbani->id}.pdf";
        $pdf->save($pdfPath);

        $pdfUrl = asset("pdfs/qurbani_{$qurbani->id}.pdf");

        // Now dispatch the WhatsApp & SMS Jobs
        SendWhatsAppMessageJob::dispatch($this->mobile, $pdfUrl);
        SendSMSMessageJob::dispatch($this->mobile, $qurbani);
    }
}
