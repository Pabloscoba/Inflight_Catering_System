<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestMessage extends Model
{
    protected $fillable = [
        'request_id',
        'sender_id',
        'sender_role',
        'recipient_role',
        'message',
        'message_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForRole($query, $role)
    {
        return $query->where('recipient_role', $role);
    }

    public function scopeFromRole($query, $role)
    {
        return $query->where('sender_role', $role);
    }
}
