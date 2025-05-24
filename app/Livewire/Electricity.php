<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;

class Electricity extends Component
{
    public $current_agency;
    public Collection $locations;
    public Collection $houses;

    public function mount($agency): void
    {
        set_time_limit(0);
        $this->current_agency = $agency;
        $this->refreshThisAgencyLocations();
        $this->refreshThisAgencyHouses();
    }

    private function refreshThisAgencyLocations(): void
    {
        $locations = $this->getActiveLocations();
        $this->locations = Collection::make(
            $locations->map(function ($location) {
                return $this->processLocation($location);
            })->all()
        );

    }

    private function getActiveLocations(): Collection
    {
        return $this->current_agency->_Locations
            ->where("status", "!=", 3)
            ->filter(fn($location) => $location->Room?->electricity);
    }

    private function processLocation(Location $location): Location
    {
        $location->load([
            "_Agency","Owner","House","Locataire","Room",
            "Factures","StateFactures","AllFactures","Paiements","ElectricityFactures"
        ]);

        $location['house_name'] = $location->House->name;
        $location['locataire'] = $location->Locataire->name . " " . $location->Locataire->prenom;
        $location['electricity_factures'] = $location->ElectricityFactures;
        $location['electricity_factures_states'] = $location->House->ElectricityFacturesStates;
        $location['lastFacture'] = $location->ElectricityFactures()->first();
        $location['start_index'] = $this->getStartIndex($location);

        if ($location->ElectricityFactures->isNotEmpty()) {
            $latestFacture = $location->ElectricityFactures->first();
            $isLatestFactureStateFacture = $latestFacture->state_facture;

            $this->calculateFactureData($location, $latestFacture, $isLatestFactureStateFacture);
        } else {
            $this->getEmptyFactureData($location);
        }

        return $location;
    }

    private function getStartIndex(Location $location): ?int
    {
        if ($location->ElectricityFactures->isNotEmpty()) {
            return $location->ElectricityFactures->first()->end_index;
        }
        return $location->Room?->electricity_counter_start_index;
    }

    private function calculateFactureData(Location $location, $latestFacture, bool $isLatestFactureStateFacture): Location
    {
        $unpaidFactures = $location->ElectricityFactures
            ->where('id', '!=', $latestFacture->id)
            ->where('paid', false)
            ->where('state_facture', false);

        $paidFactures = $location->ElectricityFactures->where('paid', true);
        $totalFactures = $location->ElectricityFactures;

        $location['end_index'] = $latestFacture->end_index;
        $location['current_amount'] = $latestFacture->paid ? 0 : $latestFacture->amount;
        $location['nbr_un_paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $unpaidFactures->count();
        $location['un_paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $unpaidFactures->sum('amount');
        $location['paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $paidFactures->sum('amount');
        $location['total_un_paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $totalFactures->sum('amount');
        $location['rest_facture_amount'] = $isLatestFactureStateFacture ? 0 : ($totalFactures->sum('amount') - $paidFactures->sum('amount'));

        return $location;
    }

    private function getEmptyFactureData($location): Location
    {
        $location['end_index'] = 0;
        $location['current_amount'] = 0;
        $location['nbr_un_paid_facture_amount'] = 0;
        $location['un_paid_facture_amount'] = 0;
        $location['water_factures'] = [];
        $location['paid_facture_amount'] = 0;
        $location['total_un_paid_facture_amount'] = 0;
        $location['rest_facture_amount'] = 0;

        return $location;
    }

    private function refreshThisAgencyHouses(): void
    {
        $this->houses = Collection::make(
            $this->getActiveLocations()
                ->map(fn($location) => $location->House)
                ->unique()
                ->values()
                ->all()
        );
    }

    public function render()
    {
        return view('livewire.electricity');
    }
}
