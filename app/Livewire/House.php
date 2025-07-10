<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\City;
use App\Models\Country;
use App\Models\Departement;
use App\Models\HouseType;
use App\Models\Quarter;
use App\Models\Zone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class House extends Component
{
    use WithFileUploads;

    public Agency $current_agency;
    public Collection $houses;
    public ?int $currentHouseId = null;
    public int $houses_count = 0;

    public Collection $countries;
    public Collection $proprietors;
    public Collection $cities;
    public Collection $house_types;
    public Collection $departements;
    public Collection $quartiers;
    public Collection $zones;

    private const DELETE_HOUSE_TITLE = 'Suppression d\'une maison!';
    private const DELETE_HOUSE_TEXT = 'Voulez-vous vraiment supprimer cette maison?';

    /**
     * Initialize the component with the given agency
     */
    public function mount(Agency $agency): void
    {
        $this->current_agency = $agency;
        $this->loadAllData();
    }

    /**
     * Load all required data for the component
     */
    private function loadAllData(): void
    {
        $this->loadAgencyData();
        $this->loadGeographicData();
        $this->loadPropertyData();
    }

    /**
     * Load agency-specific data
     */
    private function loadAgencyData(): void
    {
        $user = Auth::user();

        if ($user->hasRole("Gestionnaire de compte")) {
            /** Pour une Gestionnaire de compte, on recupère juste les 
             * maisons de ses superviseurs
             */
            $supervisorsIds = $user->supervisors->pluck("id")
                ->toArray();
            $this->houses = $this->current_agency
                ->_Houses->whereIn("supervisor", $supervisorsIds);
        } else {
            $this->houses = $this->current_agency->_Houses;
        }

        $this->proprietors = $this->current_agency->_Proprietors;
        $this->houses_count = $this->houses->count();
    }

    /**
     * Load geographic data (countries, cities, departments, etc.)
     */
    private function loadGeographicData(): void
    {
        $this->countries = Country::all();
        $this->cities = City::all();
        $this->departements = Departement::all();
        $this->quartiers = Quarter::all();
        $this->zones = Zone::all();
    }

    /**
     * Load property-related data
     */
    private function loadPropertyData(): void
    {
        $this->house_types = HouseType::all();
    }

    /**
     * Refresh agency proprietors
     */
    public function refreshThisAgencyProprietors(): void
    {
        $this->proprietors = $this->current_agency->_Proprietors;
    }

    /**
     * Refresh agency houses with confirmation dialog
     */
    public function refreshThisAgencyHouses(): void
    {
        confirmDelete(self::DELETE_HOUSE_TITLE, self::DELETE_HOUSE_TEXT);

        $this->houses = $this->current_agency->_Houses;
        $this->houses_count = $this->houses->count();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.house');
    }
}
