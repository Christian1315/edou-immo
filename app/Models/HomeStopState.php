<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Termwind\Components\Hr;

class HomeStopState extends Model
{
    use HasFactory;

    protected $table = "home_stop_states";
    protected $fillable = [
        "owner",
        "house",
        "stats_stoped_day",
        "proprietor_paid",
        "recovery_rapport"
    ];

    function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function House(): BelongsTo
    {
        return $this->belongsTo(House::class, "house")->with(["Proprietor", "Type", "Supervisor", "Locations"]);
    }

    function CdrAccountSolds(): HasMany
    {
        return $this->hasMany(AgencyAccountSold::class, "state");
    }

    function Factures(): HasMany
    {
        return $this->hasMany(Facture::class, "state")->where(["state_facture" => 0]);
    }

    function AllFactures(): HasMany
    {
        return $this->hasMany(Facture::class, "state");
    }

    function PaiementInitiations(): HasMany
    {
        return $this->hasMany(PaiementInitiation::class, "state");
    }
}
