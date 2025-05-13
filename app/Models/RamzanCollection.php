<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class RamzanCollection extends Model
{
    use HasFactory, SoftDeletes;
    protected $table ='ramzancollections';
    protected $fillable = [
        'user_id',
        'name',
        'receipt_book',
        'contact',
        'date',
        'address',
        'note',
        'donationcategory',
        'amount',
        'payment_mode',
        'transaction_id',
         'updated_at',
        'created_at',
        'deleted_at',
        ];
        public function donationcategory()
        {
            return $this->hasMany(DonationCategory::class);
        }
        public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

}
