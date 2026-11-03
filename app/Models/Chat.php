<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Chat extends Model
{
    protected $fillable = [
        'type',
        'name',
        'avatar',
        'created_by',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'chat_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'chat_participants', 'chat_id', 'user_id')
            ->withPivot('role', 'last_read_at')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'chat_id');
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class, 'chat_id')->latestOfMany();
    }

    public function isGroup(): bool
    {
        return $this->type === 'group';
    }

    public function isDirect(): bool
    {
        return $this->type === 'direct';
    }

    /**
     * Boshqa foydalanuvchini olish (1 ga 1 yozishmada)
     */
    public function getOtherUser(?User $viewer = null): ?User
    {
        $viewerId = $viewer ? $viewer->id : auth()->id();
        return $this->users->firstWhere('id', '!==', $viewerId) ?? $this->users->first();
    }

    /**
     * Chat ko'rinadigan nomi
     */
    public function getDisplayName(?User $viewer = null): string
    {
        if ($this->isGroup()) {
            return $this->name ?: 'Guruh';
        }

        $other = $this->getOtherUser($viewer);
        return $other ? ($other->full_name ?: $other->name) : 'Yozishma';
    }

    /**
     * Chat rasmi / avatari
     */
    public function getDisplayAvatar(?User $viewer = null): string
    {
        if ($this->isGroup()) {
            if ($this->avatar) {
                return str_starts_with($this->avatar, 'http') ? $this->avatar : asset('storage/' . $this->avatar);
            }
            return asset('assets/images/group-icon.png');
        }

        $other = $this->getOtherUser($viewer);
        return $other ? $other->avatar_url : asset('assets/images/8214212.png');
    }

    /**
     * O'qilmagan xabarlar soni
     */
    public function unreadCountFor(?User $viewer = null): int
    {
        $viewerId = $viewer ? $viewer->id : auth()->id();
        $participant = $this->participants->firstWhere('user_id', $viewerId);

        if (!$participant) {
            return 0;
        }

        $query = $this->messages()->where('user_id', '!=', $viewerId);

        if ($participant->last_read_at) {
            $query->where('created_at', '>', $participant->last_read_at);
        }

        return $query->count();
    }
}
