<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zone extends Model
{
    use HasFactory;
    protected $table = "zones";


    function City(): BelongsTo
    {
        return $this->belongsTo(City::class, "city")->with(["country"]);
    }
}
