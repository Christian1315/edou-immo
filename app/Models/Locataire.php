<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Locataire extends Model
{
    use HasFactory;
    use SoftDeletes;

    // protected  $guarded = [];
    protected $fillable = [
        "agency",
        "email",
        "sexe",
        "prenom",
        "phone",
        "mandate_contrat",
        "comments",
        "name",
        "card_id",
        "adresse",
        "owner",
        "card_type",
        "departement",
        "country",
        "prorata",
        "prorata_date",
        "discounter",
        "kilowater_price",
        "visible",
    ];

    function avaliseur(): HasOne
    {
        return $this->hasOne(LocatorAvalisor::class, "locator");
    }

    function _Agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, "agency");
    }

    function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function CardType(): BelongsTo
    {
        return $this->belongsTo(CardType::class, "card_type");
    }

    function Departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, "departement");
    }

    function Country(): BelongsTo
    {
        return $this->belongsTo(Country::class, "country");
    }

    function Locations(): HasMany
    {
        return $this->hasMany(Location::class, "locataire")->where(["visible" => 1])->with(["Owner", "House", "Locataire", "Type", "Room", "Factures"]);
    }
}
