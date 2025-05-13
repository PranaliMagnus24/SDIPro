<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Causes extends Model
{
    protected $table = 'causes';
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'amount',
        'category',
        'deadline',
        'metatitle',
        'metatag',
        'metadescription',
        'ogmetatitle',
        'ogmetadescription',
        'status',
        'upload_image',
        'ogmetaimage',
        'attachment',
        'updated_at',
        'created_at',
    ];
    
    public function donationcategory()
    {
        return $this->hasMany(DonationCategory::class);
    }
}
