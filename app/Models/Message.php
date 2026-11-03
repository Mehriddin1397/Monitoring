<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'chat_id',
        'user_id',
        'body',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'file_mime',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isFile(): bool
    {
        return in_array($this->type, ['file', 'image']);
    }

    public function isImage(): bool
    {
        return $this->type === 'image' || (
            $this->file_mime && str_starts_with($this->file_mime, 'image/')
        );
    }

    /**
     * Fayl hajmini inson tushunadigan formatda ko'rsatish
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if (!$bytes) {
            return '';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }

    /**
     * Fayl URL manzili
     */
    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }
        return asset('storage/' . $this->file_path);
    }

    /**
     * Xabar boshqa ishtirokchilar tomonidan o'qilganmi?
     * (Boshqa foydalanuvchining last_read_at >= created_at)
     */
    public function getIsReadAttribute(): bool
    {
        $chat = $this->chat;
        if (!$chat) {
            return false;
        }

        if ($chat->isDirect()) {
            $otherParticipant = $chat->participants->firstWhere('user_id', '!=', $this->user_id);
            if ($otherParticipant && $otherParticipant->last_read_at) {
                return $otherParticipant->last_read_at->gte($this->created_at);
            }
            return false;
        }

        // Guruh chatida kamida bitta boshqa a'zo o'qigan bo'lsa
        return $chat->participants
            ->where('user_id', '!=', $this->user_id)
            ->contains(function ($p) {
                return $p->last_read_at && $p->last_read_at->gte($this->created_at);
            });
    }
}
