<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "ifu",
        "rccm",
        "country",
        "city",
        "phone",
        "email",
        "ifu_file",
        "rccm_file",
        "number",
    ];

    function _AgencyAccounts(): HasMany
    {
        return $this->hasMany(AgencyAccount::class, "agency")->with(["_Account", "AgencyAccountSolds", "AgencyCurrentSold"]);
    }

    public function _Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    function _Proprietors(): HasMany
    {
        return $this->hasMany(Proprietor::class, "agency")->where(["visible" => 1]);
    }

    function _Users(): HasMany
    {
        return $this->hasMany(User::class, "agency");
    }

    function _City(): BelongsTo
    {
        return $this->belongsTo(City::class, "city", "name");
    }

    function _Country(): BelongsTo
    {
        return $this->belongsTo(Country::class, "country");
    }

    function _Locataires(): HasMany
    {
        return $this->hasMany(Locataire::class, "agency")->with(["Locations"]);
    }

    function _Locations(): HasMany
    {
        return $this->hasMany(Location::class, "agency")->with(["_Agency", "Owner", "House", "Locataire", "Type", "Room", "Status", "Factures", "Paiements", "WaterFactures", "ElectricityFactures"])->where(["visible"=>1]);
    }

    function _Houses(): HasMany
    {
        return $this->hasMany(House::class, "agency");
    }

    function _PayementInitiations():HasMany{
        return $this->hasMany(PaiementInitiation::class, "agency");
    }
}
