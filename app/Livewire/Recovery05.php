<?php

namespace App\Livewire;

use App\Models\Locataire;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class Recovery05 extends Component
{
    private const TARGET_DAY = '05';
    private const DATE_FORMAT = 'Y/m/d';

    public $agency;
    public $locators = [];
    public $houses = [];

    public function mount($agency): void
    {
        $this->agency = $agency;
        $this->refreshThisAgencyHouses();
        $this->refreshLocators();
    }

    public function refreshLocators(): void
    {
        $this->locators = $this->getLocatorsThatPaidAfterStateStopped();
    }

    public function refreshThisAgencyHouses(): void
    {
        $this->houses = $this->agency->_Houses;
    }

    private function getLocatorsThatPaidAfterStateStopped()
    {
        Log::info("Debut du chargement de getLocatorsThatPaidAfterStateStopped()");

        try {
            $houses = collect($this->houses)
                ->filter(fn($house) => $house->States->isNotEmpty());

            $locators = collect();

            $houses->each(function ($house) use ($locators) {
                $lastState = $house->States->last();
                $lastStateDate = Carbon::parse($lastState->stats_stoped_day)->format(self::DATE_FORMAT);

                $filterredFactures = $lastState->Factures
                    ->filter(function ($facture) use ($lastStateDate) {
                        $location = $facture->Location;
                        $echeanceDate = Carbon::parse($facture->echeance_date)->format(self::DATE_FORMAT);
                        $previousEcheanceDate = Carbon::parse($location->previous_echeance_date)->format(self::DATE_FORMAT);

                        return $this->isValidPaymentDate($lastStateDate, $echeanceDate, $previousEcheanceDate);
                    });

                /** */
                $filterredFactures
                    ->pluck("Location")
                    ->each(function ($location) use ($locators) {
                        $location->Locataire["locator_location"] = $location;
                        $locators[] = $location->Locataire;
                    });
            });

            return $locators;

            // return collect($this->houses)
            //     ->filter(fn($house) => $house->States->isNotEmpty())
            //     ->flatMap(function ($house) {
            //         $lastState = $house->States->last();
            //         $lastStateDate = Carbon::parse($lastState->stats_stoped_day)->format(self::DATE_FORMAT);

            //         return $lastState->Factures
            //             ->map(function ($facture) use ($lastStateDate) {
            //                 $location = $facture->Location;
            //                 $echeanceDate = Carbon::parse($facture->echeance_date)->format(self::DATE_FORMAT);
            //                 $previousEcheanceDate = Carbon::parse($location->previous_echeance_date)->format(self::DATE_FORMAT);

            //                 if ($this->isValidPaymentDate($lastStateDate, $echeanceDate, $previousEcheanceDate)) {
            //                     $location->Locataire["locator_location"] = $location;
            //                     return $location->Locataire;
            //                 }
            //                 return null;
            //             })
            //             ->filter()
            //             ->values();
            //     })
            //     ->unique()
                // ->values()
                // ->toArray();

        } catch (\Exception $e) {
            Log::error("Erreure lors du chargement de getLocatorsThatPaidAfterStateStopped() " . $e->getMessage());
        }

        Log::info("Fin chargement de getLocatorsThatPaidAfterStateStopped()");
    }

    private function isValidPaymentDate(string $stateDate, string $echeanceDate, string $previousEcheanceDate): bool
    {
        try {
            $dueDay = Carbon::parse($previousEcheanceDate)->format('d');
            return $stateDate > $echeanceDate
                && $echeanceDate <= $previousEcheanceDate
                && $dueDay === self::TARGET_DAY;
        } catch (\Exception $e) {
            Log::error("Erreure lors du chargement de isValidPaymentDate() " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.recovery05');
    }
}
