<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbaniHisse2024 extends Model
{
    protected $table = "qurbani_hisses_2024";
    protected $fillable = [
        'user_id', 'qurbani_id', 'name', 'aqiqah', 'gender', 'hissa',
    ];
}
