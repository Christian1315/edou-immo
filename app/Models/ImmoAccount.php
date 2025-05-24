<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ImmoAccount extends Model
{
    use HasFactory;

    function Solds(): HasMany
    {
        return $this->hasMany(AccountSold::class, "account")->with(["Manager"]);
    }

    function ActiveSold(): HasOne
    {
        return $this->hasOne(AccountSold::class, "account")->where(["visible" => 1]);
    }
}
