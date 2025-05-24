<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocatorAvalisor extends Model
{
    use HasFactory;

    protected $fillable = [
        "ava_name",
        "ava_prenom",
        "ava_phone",
        "ava_parent_link",
    ];

    function locataire(): BelongsTo
    {
        return $this->belongsTo(Locataire::class, "locator");
    }
}
