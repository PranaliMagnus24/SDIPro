<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobile;
    protected $pdfUrl;

    public function __construct($mobile, $pdfUrl)
    {
        $this->mobile = $mobile;
        $this->pdfUrl = $pdfUrl;
    }

    public function handle()
    {
        $apiKey = "68400ea593ee4cc098ef960d9c5c5c47";
        $apiUrl = "https://whatsappnew.bestsms.co.in/wapp/v2/api/send";

        $postData = [
            'apikey' => $apiKey,
            'mobile' => $this->mobile,
            'msg' => "Here is your Qurbani Receipt PDF: {$this->pdfUrl}",
            'pdf' => $this->pdfUrl
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            \Log::error("WhatsApp CURL error: " . curl_error($ch));
        }
        curl_close($ch);

        \Log::info("WhatsApp API Response: " . $response);
    }
}
