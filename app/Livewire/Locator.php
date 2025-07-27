<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Agency;
use App\Models\CardType;
use App\Models\Country;
use App\Models\Departement;
use App\Models\Locataire;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Locator extends Component
{
    use WithFileUploads;

    public Agency $current_agency;
    public ?Agency $agency = null;

    /** @var Collection<int, Locataire> */
    public Collection $locators;
    /** @var Collection<int, Locataire> */
    public Collection $old_locators;
    public int $locators_count = 0;

    /** @var Collection<int, CardType> */
    public Collection $card_types;
    /** @var Collection<int, Country> */
    public Collection $countries;
    /** @var Collection<int, Departement> */
    public Collection $departements;

    /** @var array<int, mixed> */
    public array $proprietors = [];
    /** @var array<int, mixed> */
    public Collection $houses;
    public mixed $house;

    /** @var array<int, string> */
    public array $cities = [];
    /** @var array<int, string> */
    public array $locator_types = [];
    /** @var array<int, string> */
    public array $locator_natures = [];
    /** @var array<int, string> */
    public array $quartiers = [];
    /** @var array<int, string> */
    public array $zones = [];
    /** @var array<int, mixed> */
    public array $supervisors = [];

    /** @var array<string, string> */
    public array $headers = [];

    /** @var array<int, mixed> */
    public array $locator_houses = [];
    /** @var array<int, mixed> */
    public array $locator_rooms = [];
    /** @var array<string, mixed> */
    public array $current_locator = [];
    public bool $current_locator_boolean = false;
    /** @var array<string, mixed> */
    public array $current_locator_for_room = [];

    public bool $display_locators_options = false;
    public bool $show_locators_by_supervisor = false;
    public bool $show_locators_by_house = false;

    public function displayLocatorsOptions(): void
    {
        $this->display_locators_options = !$this->display_locators_options;
        $this->show_locators_by_house = false;
        $this->show_locators_by_supervisor = false;
    }

    public function refreshThisAgencyLocators(): void
    {
        confirmDelete('Suppression de locataire', "Voulez-vous vraiment supprimer ce locataire");

        $user = Auth::user();

        if ($user->hasRole("Gestionnaire de compte")) {
            /**Ses superviseurs */
            $supervisorsIds = $user->supervisors->pluck("id")
                ->toArray();

            // Locations
            $locations = $this->current_agency
                ->_Locations
                ->where("status", "!=", 3)
                ->filter(function ($location) use ($supervisorsIds) {
                    return in_array($location->House->supervisor, $supervisorsIds);
                });

            // Locataires 
            $agency_locators = $locations
                ->pluck("Locataire");
        } elseif ($user->hasRole("Superviseur")) {
            // Locations
            $locations = $this->current_agency
                ->_Locations
                ->where("status", "!=", 3)
                ->whereHas("House", function ($query) use ($user) {
                    $query->where("supervisor", $user->id);
                });

            // Locataires 
            $agency_locators = $locations
                ->pluck("Locataire");
        }else {
            // Locataires 
            $agency_locators = $this->current_agency->_Locataires;
        }

        $this->locators_count = $agency_locators->count();
        $this->locators = $agency_locators;
        $this->old_locators = $agency_locators;
    }


    public function mount(Agency $agency): void
    {
        $this->current_agency = $agency;

        $this->refreshThisAgencyLocators();

        $this->card_types = CardType::all();
        $this->countries = Country::all();
        $this->departements = Departement::all();
    }

    public function render()
    {
        return view('livewire.locator');
    }
}
