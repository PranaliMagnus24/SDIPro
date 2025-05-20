<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbaniHisse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "qurbani_hisses";
    protected $fillable = [
        'user_id', 'qurbani_id', 'name', 'aqiqah', 'gender', 'hissa','paigambar_name',
    ];
}
