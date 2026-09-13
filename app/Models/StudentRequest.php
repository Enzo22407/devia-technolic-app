<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_code',
        'user_id',
        'request_type_id',
        'subject',
        'reason',
        'payload',
        'status',
        'pedagogical_opinion',
        'pedagogical_comment',
        'pedagogical_reviewer_id',
        'decision_comment',
        'decided_by',
        'decided_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'decided_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requestType()
    {
        return $this->belongsTo(RequestType::class);
    }

    public function attachments()
    {
        return $this->hasMany(RequestAttachment::class);
    }

    public function history()
    {
        return $this->hasMany(RequestHistory::class)->orderBy('created_at', 'desc');
    }

    public function pedagogicalReviewer()
    {
        return $this->belongsTo(User::class, 'pedagogical_reviewer_id');
    }

    public function decider()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'en_attente' => 'En attente',
            'en_instruction' => 'En cours d\'instruction',
            'avis_pedagogique_requis' => 'Avis Pédagogique requis',
            'approuvee' => 'Approuvée',
            'rejetee' => 'Rejetée',
            default => $this->status,
        };
    }
}
