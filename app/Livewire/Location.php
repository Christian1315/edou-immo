<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\FactureStatus;
use App\Models\Location as ModelsLocation;
use App\Models\LocationType;
use App\Models\PaiementType;
use App\Models\Agency;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Location extends Component
{
    public Agency $current_agency;

    /** @var Collection<ModelsLocation> */
    public Collection $locations;
    public int $locations_count = 0;

    // /** @var Collection */
    public $rooms;

    /** @var Collection */
    public Collection $card_types;

    /** @var Collection */
    public Collection $countries;

    /** @var Collection */
    public Collection $departements;

    /** @var Collection */
    public Collection $proprietors;

    /** @var Collection */
    public Collection $houses;

    /** @var Collection */
    public Collection $locators;

    /** @var Collection */
    public Collection $locator_types;

    /** @var Collection */
    public Collection $cities;

    /** @var Collection */
    public Collection $location_types;

    /** @var Collection */
    public Collection $location_natures;

    /** @var Collection */
    public Collection $quartiers;

    /** @var Collection */
    public Collection $zones;

    /** @var Collection */
    public Collection $location_factures;

    /** @var Collection */
    public Collection $location_rooms;

    /** @var array */
    public Collection $current_location;

    /** @var Collection */
    public Collection $paiements_types;

    /** @var Collection */
    public Collection $factures_status;

    public function refreshThisAgencyHouses(): void
    {
        $this->houses = $this->current_agency->_Houses;
    }

    public function refreshThisAgencyLocators(): void
    {
        $title = 'Suppression de location';
        $text = "Voulez-vous vraiment supprimer ce locataire";
        confirmDelete($title, $text);

        $this->locators = $this->current_agency->_Locataires;
    }

    public function refreshThisAgencyRooms(): void
    {
        $this->rooms = $this->current_agency->_Proprietors
            ->flatMap(fn($proprio) => $proprio->Houses)
            ->flatMap(fn($house) => $house->Rooms);
    }

    public function refreshThisAgencyLocations(): void
    {
        $user = auth()->user();

        /**
         * un superviseur ne vera que ces 
         * locations à lui
         */

        if ($user->hasRole("Gestionnaire de compte")) {
            /** Pour un Gestionnaire de compte, on recupère juste les 
             * locations ayant les maisons de ses superviseurs
             */

            $supervisorsIds = $user->supervisors->pluck("id")
                ->toArray();

            $this->locations = $this->current_agency
                ->_Locations
                ->where("status", "!=", 3)
                ->filter(function ($location) use ($supervisorsIds) {
                    return in_array($location->House->supervisor, $supervisorsIds);
                });

        } else {
            $this->locations = $user->hasRole("Superviseur") ?
                $this->current_agency->_Locations
                ->where("status", "!=", 3)
                ->filter(fn($location) => $location->House->supervisor == $user->id) :
                $this->current_agency->_Locations
                ->where("status", "!=", 3);
        }

        $this->locations_count = $this->locations->count();
    }

    public function refreshLocationTypes(): void
    {
        $this->location_types = LocationType::all();
    }

    public function refreshPaiementTypes(): void
    {
        $this->paiements_types = PaiementType::all();
    }

    public function refreshFactureStatus(): void
    {
        $this->factures_status = FactureStatus::all();
    }

    public function mount(Agency $agency): void
    {
        set_time_limit(0);
        $this->current_agency = $agency;

        $this->refreshThisAgencyLocations();
        $this->refreshThisAgencyRooms();
        $this->refreshThisAgencyHouses();
        $this->refreshThisAgencyLocators();
        $this->refreshPaiementTypes();
        $this->refreshLocationTypes();
        $this->refreshFactureStatus();
    }

    public function render()
    {
        return view('livewire.location');
    }
}
