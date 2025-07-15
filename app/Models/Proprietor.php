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
 * @property string $firstname
 * @property string $lastname
 * @property string $phone
 * @property string $email
 * @property string $sexe
 * @property string $piece_number
 * @property string $mandate_contrat
 * @property string|null $comments
 * @property string $adresse
 * @property int $city
 * @property int $country
 * @property int $card_type
 * @property int $owner
 * @property string|null $piece_file
 * @property-read User $Owner
 * @property-read City $City
 * @property-read Country $Country
 * @property-read CardType $TypeCard
 * @property-read Agency $Agency
 * @property-read House[] $Houses
 */
class Proprietor extends Model
{
    use HasFactory;

    private const VISIBLE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'agency',
        'firstname',
        'lastname',
        'phone',
        'email',
        'sexe',
        'piece_number',
        'mandate_contrat',
        'comments',
        'adresse',
        'city',
        'country',
        'card_type',
        'owner',
        'piece_file'
    ];

    /**
     * Get the owner that owns the proprietor.
     */
    public function Owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner');
    }

    /**
     * Get the city associated with the proprietor.
     */
    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city');
    }

    /**
     * Get the country associated with the proprietor.
     */
    public function Country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country');
    }

    /**
     * Get the card type associated with the proprietor.
     */
    public function TypeCard(): BelongsTo
    {
        return $this->belongsTo(CardType::class, 'card_type');
    }

    /**
     * Get the agency associated with the proprietor.
     */
    public function Agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency')
            ->where('visible', self::VISIBLE);
    }

    /**
     * Get the houses associated with the proprietor.
     */
    public function Houses(): HasMany
    {
        return $this->hasMany(House::class, 'proprietor')
            ->with(['Rooms', 'Locations', 'Type', 'Supervisor', 'Proprietor']);
    }
}
