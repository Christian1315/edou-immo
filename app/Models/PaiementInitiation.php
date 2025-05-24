<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaiementInitiation extends Model
{
    use HasFactory;

    protected $fillable = [
        "manager",
        "status",
        "amount",
        "comments",
        "rejet_comments",
        "proprietor",
        "house",
        "agency",
        "state"
    ];

    function Manager(): BelongsTo
    {
        return $this->belongsTo(User::class, "manager");
    }

    function Status(): BelongsTo
    {
        return $this->belongsTo(PaiementInitiationStatus::class, "status");
    }

    function Proprietor(): BelongsTo
    {
        return $this->belongsTo(Proprietor::class, "proprietor");
    }

    function House(): BelongsTo
    {
        return $this->belongsTo(House::class, "house");
    }
}
