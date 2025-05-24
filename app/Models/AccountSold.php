<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountSold extends Model
{
    use HasFactory;

    protected $table = "account_solds";
    protected $fillable = [
        "owner",
        "account",
        "sold",
        "delete_at",
        "visible",
        "description"
    ];

    function Manager(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function Account(): BelongsTo
    {
        return $this->belongsTo(ImmoAccount::class, "account");
    }
}
