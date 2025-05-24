<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationWaterFacture extends Model
{
    use HasFactory;

    protected $fillable = [
        "owner",
        "location",
        "start_index",
        "end_index",
        "comments",
        "amount",
        "paid",
        "visible",
        "consomation",
        "state",
        "state_facture"
    ];

    function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function Location(): BelongsTo
    {
        return $this->belongsTo(Location::class, "location")->with(["House", "Room", "Locataire"])->where(["visible" => 1]);
    }

    function State(): BelongsTo
    {
        return $this->belongsTo(StopHouseElectricityState::class, "state");
    }
}
