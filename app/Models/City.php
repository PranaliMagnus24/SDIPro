<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $table = 'cities';
    protected $fillable = [
        'name','state_id','state_code','country_id','country_code','latitude','longitude','flag'
    ];

   public function ijtemaforms()
{
    return $this->hasMany(IjtemaForm::class, 'city');
}
  // In the City model
public function state()
{
    return $this->belongsTo(State::class, 'state_id'); // Ensure the correct foreign key is being used here
}
}
