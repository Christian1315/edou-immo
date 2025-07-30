<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $agency
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 * @property string|null $comments
 * @property int $proprietor
 * @property int $type
 * @property int $supervisor
 * @property int $city
 * @property int $country
 * @property int $departement
 * @property int $quartier
 * @property int $zone
 * @property int $owner
 * @property string|null $proprio_payement_echeance_date
 * @property string|null $geolocalisation
 * @property float $commission_percent
 * @property string|null $image
 * @property float $locative_commission
 * @property float $pre_paid
 * @property float $post_paid
 * @property string|null $recovery_date
 * @property \Carbon\Carbon|null $delete_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class House extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;
    public const STATUS_DELETED = 3;

    protected $fillable = [
        "agency",
        "name",
        "latitude",
        "longitude",
        "comments",
        "proprietor",
        "type",
        "supervisor",
        "city",
        "country",
        "departement",
        "quartier",
        "zone",
        "owner",
        "proprio_payement_echeance_date",
        "geolocalisation",
        "commission_percent",
        "image",
        "locative_commission",
        "pre_paid",
        "post_paid",
        "recovery_date",
        "delete_at"
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'commission_percent' => 'float',
        'locative_commission' => 'float',
        'pre_paid' => 'float',
        'post_paid' => 'float',
        'delete_at' => 'datetime',
    ];

    /**
     * Calculate total locative charges for active locations
     */
    public function LocativeCharge(): float
    {
        return $this->Locations()
            ->where('status', '!=', self::STATUS_DELETED)
            ->get()
            ->sum(fn($location) => $location->Room?->LocativeCharge());
    }

    public function _Agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, "agency")->withDefault();
    }

    public function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner")->withDefault();
    }

    public function Proprietor(): BelongsTo
    {
        return $this->belongsTo(Proprietor::class, "proprietor")
            ->with(["Agency"])
            ->withDefault();
    }

    public function Type(): BelongsTo
    {
        return $this->belongsTo(HouseType::class, "type")->withDefault();
    }

    public function Supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, "supervisor")->withDefault();
    }

    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class, "city")->withDefault();
    }

    public function Country(): BelongsTo
    {
        return $this->belongsTo(Country::class, "country")->withDefault();
    }

    public function Departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, "departement")->withDefault();
    }

    public function Quartier(): BelongsTo
    {
        return $this->belongsTo(Quarter::class, "quartier")->withDefault();
    }

    public function Zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, "proprietor")->withDefault();
    }

    public function Rooms(): HasMany
    {
        return $this->hasMany(Room::class, "house")
            ->with(["Owner", "Nature", "Type", "House"]);
    }

    public function Locations(): HasMany
    {
        return $this->hasMany(Location::class, "house")
            ->with([
                "Locataire",
                "Type",
                "Status",
                "Room",
                "Factures",
                "AllFactures",
                "WaterFactures",
                "ElectricityFactures"
            ]);
    }

    public function States(): HasMany
    {
        return $this->hasMany(HomeStopState::class, "house")
            ->with(["Owner", "CdrAccountSolds", "Factures"]);
    }

    public function CurrentDepenses(): HasMany
    {
        return $this->hasMany(AgencyAccountSold::class, "house")
            ->whereNull("state");
    }

    public function AllStatesDepenses(): HasMany
    {
        return $this->hasMany(AgencyAccountSold::class, "house");
    }

    public function PayementInitiations(): HasMany
    {
        return $this->hasMany(PaiementInitiation::class, "house");
    }

    public function ElectricityFacturesStates(): HasMany
    {
        return $this->hasMany(StopHouseElectricityState::class, "house")
            ->orderBy("id", "desc");
    }

    public function WaterFacturesStates(): HasMany
    {
        return $this->hasMany(StopHouseWaterState::class, "house");
    }
}
