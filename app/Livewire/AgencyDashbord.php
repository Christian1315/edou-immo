<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\LocationType;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class AgencyDashbord extends Component
{
    public Agency $agency;
    public Agency $current_agency;

    public Collection $locations;
    public Collection $types;

    public int $proprietors_count = 0;
    public int $houses_count = 0;
    public int $locators_count = 0;
    public int $locations_count = 0;
    public int $rooms_count = 0;
    public int $paiement_count = 0;
    public int $factures_count = 0;
    public int $accountSold_count = 0;
    public int $initiation_count = 0;

    public function mount(Agency $agency): void
    {
        $this->current_agency = $agency;

        $this->agency = Agency::with([
            '_Proprietors.houses.rooms',
            '_Locataires',
            '_Locations.Factures',
            '_Locations.Paiements'
        ])->findOrFail($this->current_agency['id']);

        $this->calculateStatistics();
    }

    private function calculateStatistics(): void
    {
        $user = auth()->user();

        if ($user->hasRole("Superviseur")) {

            // Propriétaires
            $this->proprietors_count = $this->agency->_Proprietors()
                ->whereHas("houses", function ($house) use ($user) {
                    $house->where("supervisor", $user->id);
                })->count();

            // Maisons
            $this->houses_count = $this->agency->_Proprietors
                ->flatMap->houses
                ->where("supervisor", $user->id)
                ->count();

            // Locataires 
            $this->locators_count = $this->agency->_Locataires->count();

            // Locations
            $this->locations = $this->agency->_Locations
                ->where("status", "!=", 3);

            //types
            $this->types = LocationType::get();

            // $this->locations = $this->current_agency->_Locations
            //     ->filter(fn($location) => $location->House->supervisor == $user->id);

            // $this->locations_count = $this->locations
            //     ->filter(fn($location) => $location->House->supervisor == $user->id)
            //     ->count();

            // Factures et Paiements
            $this->factures_count = $this->locations->flatMap
                ->Factures
                ->count();

            $this->paiement_count = 0;

            // Chambres
            $this->rooms_count = $this->agency->_Proprietors
                ->flatMap->houses
                ->where("supervisor", $user->id)
                ->flatMap->rooms->count();
        } else {
            // Propriétaires
            $this->proprietors_count = $this->agency->_Proprietors->count();

            // Maisons
            $houses = $this->agency->_Proprietors->flatMap->houses;
            $this->houses_count = $houses->count();

            // Locataires et Locations
            $this->locations = $this->agency->_Locations;

            $this->locators_count = $this->agency->_Locataires->count();
            $this->locations_count = $this->locations->count();

            // Factures et Paiements
            $this->factures_count = $this->agency->_Locations->flatMap->Factures->count();
            $this->paiement_count = $this->houses_count;

            // Chambres
            $this->rooms_count = $houses->flatMap->rooms->count();
        }
    }

    public function render()
    {
        return view('livewire.agency-dashbord');
    }
}
