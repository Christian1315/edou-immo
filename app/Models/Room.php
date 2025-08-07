<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
// use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

/**
 * @property int $id
 * @property float $gardiennage
 * @property float $rubbish
 * @property float $vidange
 * @property float $cleaning
 * @property int $owner
 * @property int $house
 * @property int $nature
 * @property int $type
 */
class Room extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        "loyer" => "float",
    ];

    /**
     * Calculate the total locative charges
     */
    public function LocativeCharge(): float
    {
        return $this->gardiennage + $this->rubbish + $this->vidange + $this->cleaning;
    }

    /**
     * Get the owner of the room
     */
    public function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    /**
     * Get the house that owns the room
     */
    public function House(): BelongsTo
    {
        return $this->belongsTo(House::class, "house")
            ->where(["visible" => 1])
            ->with(["Proprietor"]);
    }

    /**
     * Get the nature of the room
     */
    public function Nature(): BelongsTo
    {
        return $this->belongsTo(RoomNature::class, "nature");
    }

    /**
     * Get the type of the room
     */
    public function Type(): BelongsTo
    {
        return $this->belongsTo(RoomType::class, "type");
    }

    /**
     * Get all locations for this room
     */
    public function Locations(): HasMany
    {
        return $this->hasMany(Location::class, "room")
            ->with(["Locataire", "House", "Room", "Type"]);
    }

    /**
     * Check if the room is currently occupied
     */
    public function buzzy(): bool
    {
        return $this->Locations()->exists();
    }
}
