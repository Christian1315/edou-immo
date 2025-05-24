<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use App\Models\Location;

class EauLocation extends Component
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
            ->where('status', '!=', 3)
            ->filter(fn($location) => $location->Room?->water);
    }

    private function processLocation(Location $location): Location
    {
        $location->load([
            "_Agency","Owner","House","Locataire","Room",
            "Factures","StateFactures","AllFactures","Paiements","ElectricityFactures"
        ]);

        $location['house_name'] = $location->House->name;
        $location['locataire'] = $location->Locataire->name . ' ' . $location->Locataire->prenom;
        $location['water_factures'] = $location->WaterFactures;
        $location['water_factures_states'] = $location->House->WaterFacturesStates;
        $location['lastFacture'] = $location->WaterFactures()->first();
        $location['start_index'] = $this->getStartIndex($location);

        if ($location->WaterFactures->isNotEmpty()) {
            $latestFacture = $location->WaterFactures->first();
            $isLatestFactureStateFacture = $latestFacture->state_facture ?? false;
            $this->calculateFactureData($location, $latestFacture, $isLatestFactureStateFacture);
        } else {
            $this->getEmptyFactureData($location);
        }

        return $location;
    }

    private function getStartIndex(Location $location): ?int
    {
        if ($location->WaterFactures->isNotEmpty()) {
            return $location->WaterFactures->first()->end_index;
        }
        return $location->Room?->water_counter_start_index;
    }

    private function calculateFactureData(Location $location, $latestFacture, bool $isLatestFactureStateFacture): Location
    {
        $unpaidFactures = $location->WaterFactures
            ->where('id', '!=', $latestFacture->id)
            ->where('paid', false)
            ->where('state_facture', false);

        $paidFactures = $location->WaterFactures->where('paid', true);
        $totalFactures = $location->WaterFactures;

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
        return view('livewire.eau-location');
    }
}
