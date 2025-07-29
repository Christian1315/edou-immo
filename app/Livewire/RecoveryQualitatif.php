<?php

namespace App\Livewire;

use Livewire\Component;

class RecoveryQualitatif extends Component
{
    public $agency;
    public $locators = [];
    public $houses = [];

    ###___HOUSES
    function refreshThisAgencyHouses()
    {
        $this->houses = $this->agency->_Houses;
    }

    function refreshLocators()
    {
        #####____locataires ayant payés après l'arrêt d'etat du dernier state dans toutes les maisons
        $locators_that_paid_after_state_stoped_day_of_all_houses = [];

        ###____PARCOURONS TOUTES LES MAISONS DE CETTE AGENCE, PUIS FILTRONS LES ETATS
        foreach ($this->agency->_Houses as $house) {
            ###___DERNIER ETAT D'ARRET DE CETTE MAISON
            $house_last_state = $house->States->last();
            if ($house_last_state) {
                ###__DATE DU DERNIER ARRET DES ETATS DE CETTE MAISON
                $house_last_state_date = date("Y/m/d", strtotime($house_last_state->stats_stoped_day));

                ###__LES FACTURES DE CET DERNIER ETAT
                $house_last_state_factures = $house_last_state->Factures;

                foreach ($house_last_state_factures as $facture) {
                    ###___Echéance date
                    $location_echeance_date = date("Y/m/d", strtotime($facture->Location->previous_echeance_date));

                    $location_payement_date = date("Y/m/d",  strtotime($facture->echeance_date));

                    ####___determinons le jour de la date d'écheance
                    $day_of_this_date = explode("/", $location_echeance_date)[2];
                    ###____
                    ###___on verifie si la date de paiement se trouve entre *la date d'arrêt* de l'etat et *la date d'échéance*
                    if ($house_last_state_date > $location_payement_date && $location_payement_date <= $location_echeance_date) {
                        ###___on verifie si le jour de la date d'écheance est le 05 ou le 10
                        if ($day_of_this_date == 05 || $day_of_this_date == 10) {
                            $facture->Location->Locataire["locator_location"] = $facture->Location;
                            array_push($locators_that_paid_after_state_stoped_day_of_all_houses, $facture->Location->Locataire);
                        }
                    }
                };
            }
        };

        #####____
        $this->locators = $locators_that_paid_after_state_stoped_day_of_all_houses;
    }

    function mount($agency)
    {
        set_time_limit(0);
        $this->agency = $agency;

        $this->refreshLocators();
        $this->refreshThisAgencyHouses();
    }

    public function render()
    {
        return view('livewire.recovery-qualitatif');
    }
}
