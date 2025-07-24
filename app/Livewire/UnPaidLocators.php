<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class UnPaidLocators extends Component
{
    public Agency $current_agency;
    public ?Agency $agency = null;

    public Collection $locators;
    public int $locators_count = 0;
    public ?User $supervisor = null;

    public Collection $houses;
    public ?object $house = null;

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
     * Refresh the list of unpaid locators for the current agency
     * 
     * @return void
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
                    return $now > Carbon::parse($location->echeance_date);
                })
                ->values();
        } else {
            $this->locators = $this->current_agency->_Locations
                ->filter(function ($location) use ($now) {
                    return $now > Carbon::parse($location->echeance_date);
                })
                ->values();
        }

        $this->locators_count = count($this->locators);
    }

    /**
     * Initialize the component with the given agency
     * 
     * @param Agency $agency
     * @return void
     */
    public function mount(Agency $agency): void
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
        return view('livewire.un-paid-locators');
    }
}
