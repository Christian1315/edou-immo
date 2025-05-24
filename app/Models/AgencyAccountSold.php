<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyAccountSold extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "agency_account",
        "sold",
        "description",
        "deleted_at",
        "visible",
        "sold_added",
        "old_sold",

        // POUR LA CAISSE CDR
        "location",

        "water_facture",
        "electricity_facture",

        // POUR LA CAISSE EAU-ELECTRICITE
        "house",
        "sold_retrieved",
    ];

    function WaterFacture(): BelongsTo
    {
        return $this->belongsTo(LocationWaterFacture::class, "water_facture");
    }

    function ElectricityFacture(): BelongsTo
    {
        return $this->belongsTo(LocationElectrictyFacture::class, "electricity_facture");
    }

    function House(): BelongsTo
    {
        return $this->belongsTo(House::class, "house");
    }

    function _Account(): BelongsTo
    {
        return $this->belongsTo(AgencyAccount::class, "agency_account")->with(["_Account"]);
    }
}
