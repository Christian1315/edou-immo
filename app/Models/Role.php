<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        "label",
        "description",
        "owner"
    ];

    protected $table = "_roles";

    function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }
}
