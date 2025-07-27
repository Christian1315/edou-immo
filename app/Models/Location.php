<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Location
 * @package App\Models
 * 
 * @property int $id
 * @property string $agency
 * @property int $house
 * @property int $room
 * @property int $locataire
 * @property int $type
 * @property int $status
 * @property string $payment_mode
 * @property int $moved_by
 * @property string $move_date
 * @property string $move_comments
 * @property int $suspend_by
 * @property string $suspend_date
 * @property string $suspend_comments
 * @property string $next_loyer_date
 * @property float $caution_bordereau
 * @property float $loyer
 * @property float $pre_paid
 * @property float $post_paid
 * @property float $water_counter
 * @property float $electric_counter
 * @property float $frais_peiture
 * @property float $prestation
 * @property string $numero_contrat
 * @property string $comments
 * @property string $img_contrat
 * @property float $caution_water
 * @property string $echeance_date
 * @property string $latest_loyer_date
 * @property string $effet_date
 * @property string $img_prestation
 * @property float $caution_electric
 * @property string $integration_date
 * @property int $owner
 * @property bool $visible
 * @property string $delete_at
 * @property string $caution_number
 * @property float $total_amount
 * @property float $discounter
 * @property float $kilowater_price
 * @property float $water_unpaid
 * @property float $electric_unpaid
 * @property string $previous_echeance_date
 * @property int $prorata_days
 * @property float $prorata_amount
 */
class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "locationsnew";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        // Location basic info
        'agency', 'house', 'room', 'locataire', 'type', 'status', 'payment_mode',
        'numero_contrat', 'comments', 'visible',

        // Financial information
        'caution_bordereau', 'loyer', 'pre_paid', 'post_paid', 'prestation',
        'caution_water', 'caution_electric', 'total_amount', 'discounter',
        'kilowater_price', 'water_unpaid', 'electric_unpaid', 'prorata_amount',

        // Dates
        'next_loyer_date', 'echeance_date', 'latest_loyer_date', 'effet_date',
        'integration_date', 'previous_echeance_date',

        // Counters and measurements
        'water_counter', 'electric_counter', 'frais_peiture', 'prorata_days',

        // Documents and images
        'img_contrat', 'img_prestation', 'caution_number',

        // Move related
        'moved_by', 'move_date', 'move_comments',

        // Suspension related
        'suspend_by', 'suspend_date', 'suspend_comments',

        // Other
        'owner', 'delete_at'
    ];

    /**
     * Check if the location is in unpaid status
     *
     * @return int 1 if unpaid, 0 if paid
     */
    public function paid(): int
    {
        return strtotime($this->echeance_date) < strtotime(now()) ? 1 : 0;
    }

    /**
     * Get the agency that owns the location
     */
    public function _Agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, "agency")
            ->where(["visible" => 1])
            ->orderBy("id", "desc");
    }

    /**
     * Get the owner of the location
     */
    public function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner");
    }

    /**
     * Get the house associated with the location
     */
    public function House(): BelongsTo
    {
        return $this->belongsTo(House::class, "house")
            ->with([
                "Owner", "Proprietor", "Type", "Supervisor",
                "City", "Country", "Departement", "Quartier", "Zone"
            ]);
    }

    /**
     * Get the tenant of the location
     */
    public function Locataire(): BelongsTo
    {
        return $this->belongsTo(Locataire::class, "locataire")
            ->with(["Owner", "CardType", "Departement", "Country"])
            ;
    }

    /**
     * Get the type of the location
     */
    public function Type(): BelongsTo
    {
        return $this->belongsTo(LocationType::class, "type");
    }

    /**
     * Get the status of the location
     */
    public function Status(): BelongsTo
    {
        return $this->belongsTo(LocationStatus::class, "status");
    }

    /**
     * Get the room associated with the location
     */
    public function Room(): BelongsTo
    {
        return $this->belongsTo(Room::class, "room")
            ->with(["Owner", "House", "Nature", "Type"])
            ;
    }

    /**
     * Get all unpaid invoices for the location
     */
    public function Factures(): HasMany
    {
        return $this->hasMany(Facture::class, "location")
            ->whereNull("state")
            ->with(["Owner", "Location", "Type", "Status", "State"])
            ->orderBy("id", "desc");
    }

    /**
     * Get all state invoices for the location
     */
    public function StateFactures(): HasMany
    {
        return $this->hasMany(Facture::class, "location")
            ->whereNotNull("state")
            ->whereNull("state_facture")
            ->with(["Owner", "Location", "Type", "Status", "State"])
            ->orderBy("id", "desc");
    }

    /**
     * Get all invoices for the location
     */
    public function AllFactures(): HasMany
    {
        return $this->hasMany(Facture::class, "location")
            ->with(["Owner", "Location", "Type", "Status", "State"]);
    }

    /**
     * Get all payments for the location
     */
    public function Paiements(): HasMany
    {
        return $this->hasMany(Payement::class, "location")
            ->with(["Module", "Type", "Status", "Facture"]);
    }

    /**
     * Get all water invoices for the location
     */
    public function WaterFactures(): HasMany
    {
        return $this->hasMany(LocationWaterFacture::class, "location")
            ->with(["Location"])
            ->whereNull(["state"])
            ->where(["state_facture" => 0])
            ->orderBy("id", "desc");
    }

    /**
     * Get all electricity invoices for the location
     */
    public function ElectricityFactures(): HasMany
    {
        return $this->hasMany(LocationElectrictyFacture::class, "location")
            ->with(["Location"])
            ->whereNull(["state"])
            ->where(["state_facture" => 0])
            ->orderBy("id", "desc");
    }

    /**
     * Get the user who moved the location
     */
    public function MovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, "moved_by");
    }
}
