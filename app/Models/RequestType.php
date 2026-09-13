<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'requires_attachment',
        'requires_pedagogical_review',
        'form_fields',
        'is_active',
    ];

    protected $casts = [
        'requires_attachment' => 'boolean',
        'requires_pedagogical_review' => 'boolean',
        'form_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function studentRequests()
    {
        return $this->hasMany(StudentRequest::class);
    }
}
