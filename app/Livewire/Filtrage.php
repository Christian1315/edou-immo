<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Exception;
use Log;
use App\Models\Agency;
use App\Models\House;
use App\Models\Location;

class Filtrage extends Component
{
    public Agency $agency;
    public Collection $proprietors;
    public Collection $locators;
    public Collection $locations;
    public Collection $rooms;
    public Collection $houses;
    public Collection $factures;
    public Collection $moved_locators;
    public float $factures_total_amount;

    public function mount(Agency $agency): void
    {
        try {
            set_time_limit(0);
            $this->agency = $agency;
            $this->refreshThisAgencyBilan();
        } catch (Exception $e) {
            Log::error('Erreur lors du montage du composant Filtrage: ' . $e->getMessage());
            session()->flash('error', 'Une erreur est survenue lors du chargement des données.');
        }
    }

    public function refreshThisAgencyBilan(): void
    {
        try {
            $this->initializeCollections();
            $this->processHousesData();
            $this->updateComponentProperties();
        } catch (Exception $e) {
            Log::error('Erreur lors du rafraîchissement du bilan: ' . $e->getMessage());
            session()->flash('error', 'Une erreur est survenue lors du rafraîchissement des données.');
        }
    }

    private function initializeCollections(): void
    {
        $this->locations = collect();
        $this->locators = collect();
        $this->moved_locators = collect();
        $this->factures = collect();
        $this->rooms = collect();
        $this->factures_total_amount = 0.0;
    }

    private function processHousesData(): void
    {
        $this->agency->_Houses->each(function (House $house) {
            $this->processLocations($house);
        });
    }

    private function processLocations(House $house): void
    {
        $house->Locations->each(function (Location $location) {
            $this->locations->push($location);
            $this->locators->push($location->Locataire);
            $this->rooms->push($location->Room);

            if ($location->move_date) {
                $this->moved_locators->push($location->Locataire);
            }

            $this->processFactures($location);
        });
    }

    private function processFactures(Location $location): void
    {
        $unpaidFactures = $location->AllFactures->where('state_facture', false);
        
        $this->factures = $this->factures->concat($unpaidFactures);
        $this->factures_total_amount += $unpaidFactures->sum('amount');
    }

    private function updateComponentProperties(): void
    {
        $this->proprietors = collect($this->agency->_Proprietors);
        $this->houses = collect($this->agency->_Houses);
        $this->locators = collect($this->agency->_Locataires);
        $this->agency->moved_locators = $this->moved_locators;
        $this->agency->rooms = $this->rooms;
    }

    public function render()
    {
        return view('livewire.filtrage');
    }
}
