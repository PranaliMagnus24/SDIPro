<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IjtemaForm extends Model
{
    use HasFactory;
    protected $table ='ijtemaform';
    protected $fillable = [
       
        'name',
        'age',
        'gender',
        'email',
        'contact',
        'city',
        'note',
         'updated_at',
        'created_at',
        ];
    // In IjtemaForm model
// In IjtemaForm model
public function cities()
{
    return $this->belongsTo(City::class, 'city'); 
}

}
