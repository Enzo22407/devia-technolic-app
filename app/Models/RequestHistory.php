<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestHistory extends Model
{
    use HasFactory;

    protected $table = 'request_history';

    protected $fillable = [
        'student_request_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'comment',
    ];

    public function studentRequest()
    {
        return $this->belongsTo(StudentRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
