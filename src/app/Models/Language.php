<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function applies()
    {
        return $this->belongsToMany(Apply::class, 'guide_languages', 'language_id', 'guide_id');
    }
}
