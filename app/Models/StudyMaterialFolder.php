<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterialFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'faculty_id',
        'branch_id',
        'year',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function documents()
    {
        return $this->hasMany(StudyMaterialDocument::class, 'folder_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
