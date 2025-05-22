<?php

namespace App\Jobs;

use App\Models\Qurbani;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSMSMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobile;
    protected $qurbani;

    public function __construct($mobile, Qurbani $qurbani)
    {
        $this->mobile = $mobile;
        $this->qurbani = $qurbani;
    }

    public function handle()
    {
        $authKey = "74499AuRsBHOF65828953P1";
        $senderId = "NSKFST";
        $templateId = "1207162399931698582";

        $message = "Dear {$this->qurbani->contact_name},\nYour Qurbani has been successfully registered on Eid.\nThank you\nCall\n-Nashik First";

        $apiUrl = "http://control.bestsms.co.in/api/sendhttp.php";
        $postdata = [
            'authkey'    => $authKey,
            'mobiles'    => $this->mobile,
            'sender'     => $senderId,
            'route'      => 4,
            'country'    => 91,
            'DLT_TE_ID'  => $templateId,
            'message'    => $message
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            \Log::error("SMS CURL error: " . curl_error($ch));
        }
        curl_close($ch);

        \Log::info("SMS API Response: " . $response);
    }
}

