<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'user_id',
        'promoter_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    // Relations
    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function promoter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'promoter_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class)->latest();
    }

    public function unreadMessages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class)->where('is_read', false);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForPromoter($query, $promoterId)
    {
        return $query->where('promoter_id', $promoterId);
    }

    public function scopeWithUnreadMessages($query, $userId)
    {
        return $query->whereHas('unreadMessages', function ($q) use ($userId) {
            $q->where('sender_id', '!=', $userId);
        });
    }

    // Methods
    public function getOtherUser($currentUserId)
    {
        return $this->user_id === $currentUserId ? $this->promoter : $this->user;
    }

    public function getLastMessage()
    {
        return $this->messages()->first();
    }

    public function getUnreadCountForUser($userId)
    {
        return $this->unreadMessages()
            ->where('sender_id', '!=', $userId)
            ->count();
    }

    public function markMessagesAsReadForUser($userId)
    {
        $this->unreadMessages()
            ->where('sender_id', '!=', $userId)
            ->update(['is_read' => true]);
    }

    public function updateLastMessageAt()
    {
        $this->update(['last_message_at' => now()]);
    }
}
