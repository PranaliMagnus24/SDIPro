<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationCategory extends Model
{
    use HasFactory;
    protected $table ='donationcategory';
    protected $fillable = [
       
        'name',
        'description',
         'updated_at',
        'created_at',
        ];
        public function ramzancollection()
        {
            return $this->belongsTo(RamzanCollection::class);
        }
        public function causes()
        {
            return $this->belongsTo(Causes::class);
        }
}
