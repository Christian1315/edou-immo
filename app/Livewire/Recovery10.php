<?php

namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Recovery10 extends Component
{
    public $agency;
    public $locators = [];
    public $houses = [];

    private const TARGET_DAY = '10';
    private const DATE_FORMAT = 'Y/m/d';

    /**
     * Récupère les locataires qui ont payé après l'arrêt d'état
     * et dont l'échéance est le 5 du mois
     */
    private function refreshLocators(): void
    {
        $locators = [];

        foreach ($this->agency->_Houses as $house) {
            $lastState = $house->States->last();

            if (!$lastState) {
                continue;
            }

            $stateStopDate = Carbon::parse($lastState->stats_stoped_day)->format(self::DATE_FORMAT);

            foreach ($lastState->Factures as $facture) {
                $location = $facture->Location;
                $paymentDate = Carbon::parse($facture->echeance_date)->format(self::DATE_FORMAT);
                $echeanceDate = Carbon::parse($location->previous_echeance_date)->format(self::DATE_FORMAT);
                
                if ($this->isValidPayment($stateStopDate, $paymentDate, $echeanceDate)) {
                    $location->Locataire["locator_location"] = $location;
                    $locators[] = $location->Locataire;
                }
            }
        }
        $this->locators = $locators;
    }

    /**
     * Vérifie si le paiement est valide selon les critères métier
     */
    private function isValidPayment(string $stateStopDate, string $paymentDate, string $echeanceDate): bool
    {
        $dueDay = Carbon::parse($echeanceDate)->format('d');

        return $stateStopDate > $paymentDate
            && $paymentDate <= $echeanceDate
            && $dueDay === self::TARGET_DAY;
    }

    /**
     * Récupère toutes les maisons de l'agence
     */
    private function refreshThisAgencyHouses(): void
    {
        $this->houses = $this->agency->_Houses;
    }

    /**
     * Initialise le composant
     */
    public function mount($agency): void
    {
        set_time_limit(0);
        $this->agency = $agency;
        $this->refreshThisAgencyHouses();
        $this->refreshLocators();
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        return view('livewire.recovery10');
    }
}
