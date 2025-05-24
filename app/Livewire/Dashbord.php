<?php

namespace App\Livewire;

use App\Models\Facture;
use App\Models\House;
use App\Models\ImmoAccount;
use App\Models\Locataire;
use App\Models\Location;
use App\Models\PaiementInitiation;
use App\Models\Payement;
use App\Models\Proprietor;
use App\Models\Room;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Dashbord extends Component
{
    public $proprietors_count = 0;
    public $houses_count = 0;
    public $locators_count = 0;
    public $locations_count = 0;
    public $rooms_count = 0;
    public $paiement_count = 0;
    public $factures_count = 0;
    public $accountSold_count = 0;
    public $initiation_count = 0;

    function mount()
    {
        set_time_limit(0);
        // PROPRETAIRES
        $proprietors = Proprietor::all(); ## Http::withHeaders($hearders)->get($BASE_URL . "immo/proprietor/all")->json();
        $this->proprietors_count = count($proprietors);

        // MAISONS
        $houses = House::all(); ## Http::withHeaders($hearders)->get($BASE_URL . "immo/house/all")->json();
        $this->houses_count = count($houses);

        // LOCATAIRES
        $locators = Locataire::all(); ## Http::withHeaders($hearders)->get($BASE_URL . "immo/locataire/all")->json();
        $this->locators_count = count($locators);

        // LOCATIONS
        $locations = Location::all(); ## Http::withHeaders($hearders)->get($BASE_URL . "immo/location/all")->json();
        $this->locations_count = count($locations);

        // ROOMS
        $rooms = Room::all(); ## Http::withHeaders($hearders)->get($BASE_URL . "immo/room/all")->json();
        $this->rooms_count = count($rooms);

        // PAIEMENTS
        $paiements = Payement::all(); ## Http::withHeaders($hearders)->get($BASE_URL . "immo/paiement/all")->json();
        $this->paiement_count = count($paiements);

        // FACTURES
        $factures = Facture::all(); ## Http::withHeaders($hearders)->get($BASE_URL . "immo/facture/all")->json();
        $this->factures_count = count($factures);

        // COMPTES & SOLDES
        $accountSolds = ImmoAccount::all(); ## Http::withHeaders($hearders)->get($BASE_URL . "immo/account/all")->json();
        $this->accountSold_count = count($accountSolds);

        // INITIATIONS
        $initiations = PaiementInitiation::all(); ## Http::withHeaders($hearders)->get($BASE_URL . "immo/payement_initiation/all")->json();
        $this->initiation_count = count($initiations);
    }

    public function render()
    {
        return view('livewire.dashbord');
    }
}
