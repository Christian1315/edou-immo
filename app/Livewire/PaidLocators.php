<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PaidLocators extends Component
{
    public $current_agency;
    public $agency;

    public Collection $locators;
    public int $locators_count = 0;

    public Collection $houses;

    public array $display_filtre_options = [];

    /**
     * Refresh the houses list for the current agency
     */
    public function refreshThisAgencyHouses(): void
    {
        $user = Auth::user();

        if ($user->hasRole("Gestionnaire de compte")) {
            /** Pour une Gestionnaire de compte, on recupère juste les 
             * maisons de ses superviseurs
             */

            $supervisorsIds = $user->supervisors->pluck("id")
                ->toArray();
            $this->houses = $this->current_agency
                ->_Houses->whereIn("supervisor", $supervisorsIds);
        } else {
            $this->houses = $this->current_agency->_Houses;
        }
    }

    /**
     * Refresh the active locators list for the current agency
     */
    public function refreshThisAgencyLocators(): void
    {
        $now = Carbon::now()->startOfDay();
        $user = Auth::user();
        $query = $this->current_agency->_Locations;


        if ($user->hasRole("Gestionnaire de compte")) {
            /** Pour un Gestionnaire de compte, on recupère juste les 
             * locations ayant les maisons de ses superviseurs
             */

            $supervisorsIds = $user->supervisors->pluck("id")
                ->toArray();

            $this->locators = $query->where(function ($location) use ($supervisorsIds) {
                $location->House->whereIn("supervisor", $supervisorsIds);
            })
                ->filter(function ($location) use ($now) {
                    return $now < Carbon::parse($location->echeance_date);
                })
                ->values();
        } else {
            $this->locators = $query
                ->filter(function ($location) use ($now) {
                    return $now < Carbon::parse($location->echeance_date);
                })
                ->values();
        }


        $this->locators_count = count($this->locators);
    }

    /**
     * Initialize the component with the given agency
     *
     * @param mixed $agency
     */
    public function mount($agency): void
    {
        set_time_limit(0);
        $this->current_agency = $agency;

        $this->refreshThisAgencyLocators();
        $this->refreshThisAgencyHouses();
    }

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.paid-locators');
    }
}
