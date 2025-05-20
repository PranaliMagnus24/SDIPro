<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbaniDay extends Model
{
    protected $table = "qurbani_days";
    protected $fillable = ['day_one', 'day_two'];
}
