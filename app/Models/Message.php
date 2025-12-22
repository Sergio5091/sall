<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'salle_id',
        'title',
        'content',
        'type',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'type' => 'string',
        'status' => 'string',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBySalle($query, $salleId)
    {
        return $query->where('salle_id', $salleId);
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isSent()
    {
        return $this->status === 'sent';
    }

    public function markAsSent()
    {
        $this->status = 'sent';
        $this->sent_at = now();
        $this->save();
    }

    public function getRecipients()
    {
        // Get all users who are subscribed to this salle
        return $this->salle->subscribers()->where('status', 'active')->get();
    }

    public function sendToSubscribers()
    {
        if ($this->isDraft()) {
            $recipients = $this->getRecipients();
            
            foreach ($recipients as $recipient) {
                // Here you would implement the actual sending logic
                // For example: email, notification, etc.
            }
            
            $this->markAsSent();
        }
    }
}
