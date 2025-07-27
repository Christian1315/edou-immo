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

            // Locations
            $this->locations = $this->current_agency
                ->_Locations
                ->where("status", "!=", 3)
                ->filter(function ($location) use ($user) {
                    return $location->House->supervisor == $user->id;
                });

            $this->locations_count = $this->locations
                ->count();

            // Locataires 
            $this->locators_count = $this->locations
                ->pluck("Locataire")
                ->count();

            //types
            $this->types = LocationType::get();

            // Factures et Paiements
            $this->factures_count = $this->locations->flatMap
                ->Factures
                ->where("state_facture", false) //on tient pas comptes des factures generée pour clotuer un état
                ->count();

            $this->paiement_count = 0;

            // Chambres
            $this->rooms_count = $this->agency->_Proprietors
                ->flatMap->houses
                ->where("supervisor", $user->id)
                ->flatMap->rooms->count();
        } elseif ($user->hasRole("Gestionnaire de compte")) {
            /**Ses superviseurs */
            $supervisorsIds = $user->supervisors->pluck("id")
                ->toArray();

            // Propriétaires
            $proprietors = $this->agency->_Proprietors()
                ->whereHas("houses", function ($house) use ($supervisorsIds) {
                    $house->whereIn("supervisor", $supervisorsIds);
                });
            $this->proprietors_count = $proprietors->count();

            // Maisons
            $this->houses_count = $this->agency->_Proprietors
                ->flatMap->houses
                ->whereIn("supervisor", $supervisorsIds)
                ->count();

            // Locations
            $this->locations = $this->current_agency
                ->_Locations
                ->where("status", "!=", 3)
                ->filter(function ($location) use ($supervisorsIds) {
                    return in_array($location->House->supervisor, $supervisorsIds);
                });

            $this->locations_count = $this->locations->count();

            // Locataires 
            $this->locators_count = $this->locations
                ->pluck("Locataire")
                ->count();

            //types
            $this->types = LocationType::get();

            // Factures et Paiements
            $this->factures_count = $this->locations
                ->flatMap
                ->Factures
                ->where("state_facture", false) //on tient pas comptes des factures generée pour clotuer un état
                ->count();

            $this->paiement_count = 0;

            // Chambres
            $this->rooms_count = $this->agency->_Proprietors
                ->flatMap->houses
                ->whereIn("supervisor", $supervisorsIds)
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
            $this->factures_count = $this->agency->_Locations
                ->flatMap
                ->Factures
                ->where("state_facture", false) //on tient pas comptes des factures generée pour clotuer un état
                ->count();
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
