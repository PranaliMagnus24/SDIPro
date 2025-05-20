<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qurbani2024 extends Model
{
    protected $table = "qurbanis_2024";
    protected $fillable = [
        'contact_name', 'mobile','payment_type','payment_status','transaction_number','aqiqah','gender','hissa','upload_payment','receipt_book','is_approved', 'user_id', 'msg_send','qurbani_days',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_id');
    }
    public function details2024()
    {
        return $this->hasMany(QurbaniHisse2024::class, 'qurbani_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function hissas()
{
    return $this->hasMany(QurbaniHisse::class, 'qurbani_id');
}
}
