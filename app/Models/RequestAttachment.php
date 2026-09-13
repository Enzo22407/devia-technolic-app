<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_request_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function studentRequest()
    {
        return $this->belongsTo(StudentRequest::class);
    }
}
