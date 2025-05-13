<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $table = 'general'; // टेबल का नाम

    protected $fillable = [
        'logo',
        'favicon',
        'email',
        'address',
        'state',
        'city',
        'title',
        'subtitle',
        'contact',
        'uploadqrcode',
        'bankdetail',
        'footerlogo',
        'link',
        'note',
        'footer',
        'trust_register_number',
    ];

}
