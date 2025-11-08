<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterialDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id',
        'title',
        'file_url',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function folder()
    {
        return $this->belongsTo(StudyMaterialFolder::class, 'folder_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
