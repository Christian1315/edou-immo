<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Right extends Model
{
    use HasFactory;

    protected $fillable = [
        "module",
        "profil",
        "rang",
        "action",
        "description"
    ];

    #MANY TO MANY RELATIONSHIP(UN RIGHT PEUT APPARTENIR A PLUSIEURS RANGS)
    function _rang(): BelongsTo
    {
        return $this->belongsTo(Rang::class, 'rang');
    }

    function _action(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'action');
    }

    #MANY TO MANY RELATIONSHIP(UN DROIT PEUT APPARTENIR A PLUISIEURS PROFILS)
    function _profil(): BelongsTo
    {
        return $this->belongsTo(Profil::class, 'profil');
    }
}
