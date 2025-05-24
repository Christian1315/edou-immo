<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgencyAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        "account",
        "agency"
    ];

    function _Agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, "agency");
    }

    function _Account(): BelongsTo
    {
        return $this->belongsTo(ImmoAccount::class, "account");
    }


    function AgencyAccountSolds(): HasMany
    {
        return $this->hasMany(AgencyAccountSold::class, "agency_account");
    }

    function AgencyCurrentSold(): HasOne
    {
        return $this->hasOne(AgencyAccountSold::class, "agency_account")->where(["visible" => 1]);
    }
}
