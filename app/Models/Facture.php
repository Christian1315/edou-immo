<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        "owner",
        "payement",
        "location",
        "type",
        "status",
        "facture",
        "comments",
        "amount",
        "begin_date",
        "end_date",
        "facture_code",
        "is_penality",
        "month",

        "state",
        "state_facture",
        "echeance_date",

        "prorata_days",
        "prorata_amount"
    ];

    function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function Location(): BelongsTo
    {
        return $this->belongsTo(Location::class, "location")->with(["House", "Locataire", "Room"]);
    }

    function Type(): BelongsTo
    {
        return $this->belongsTo(FactureType::class, "type");
    }

    function Status(): BelongsTo
    {
        return $this->belongsTo(FactureStatus::class, "status");
    }

    function Payement(): BelongsTo
    {
        return $this->belongsTo(Payement::class, "payement");
    }

    function State(): BelongsTo
    {
        return $this->belongsTo(HomeStopState::class, "state");
    }

    /**
     * Scope pour récupérer les factures d'un état donné pour une liste de locations
     */
    public function scopeForHouseLastState($query, $locationIds, $stateId) {
        return $query->whereIn('location', $locationIds)
                     ->where('state', $stateId)
                     ->where('state_facture', 0);
    }
}
