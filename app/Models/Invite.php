<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Invite extends Model
{
    protected $table = 'invites';

    protected $fillable = [
        'code',
        'for',
        'max',
        'uses',
        'valid_until',
        'status',
        'created_by'
    ];

    protected $casts = [
        'valid_until' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        if ($this->valid_until && $this->valid_until->isPast()) {
            return true;
        }
        return false;
    }
}