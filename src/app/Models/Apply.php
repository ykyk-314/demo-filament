<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apply extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'guide_languages', 'guide_id');
    }

    public function scopeSearchByName($query, $value)
    {
        return $query->orWhere('name_kana', 'LIKE', "%{$value}%");
    }

    public function withTrashed()
    {
        return 1;
    }

    public function onlyTrashed()
    {
        return 2;

    }

    public function withoutTrashed()
    {
        return 3;

    }
}
