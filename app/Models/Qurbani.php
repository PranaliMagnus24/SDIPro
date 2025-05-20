<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qurbani extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "qurbanis";
    protected $fillable = [
        'contact_name', 'mobile','payment_type','payment_status','transaction_number','aqiqah','gender','hissa','upload_payment','receipt_book','is_approved', 'user_id', 'msg_send','qurbani_days','alternative_mobile',
    ];


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_qurbani');
    }
    public function details()
    {
        return $this->hasMany(QurbaniHisse::class, 'qurbani_id');
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
