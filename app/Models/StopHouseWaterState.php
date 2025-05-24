<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StopHouseWaterState extends Model
{
    use HasFactory;
    protected $fillable = [
        "owner",
        "house",
        "state_stoped_day",
    ];

    function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function House(): BelongsTo
    {
        return $this->belongsTo(House::class, "house")->with(["Proprietor", "Type", "Supervisor", "Locations"]);
    }

    function StatesFactures(): HasMany
    {
        return $this->hasMany(LocationWaterFacture::class, "state")->with(["Location"]);
    }
}
