<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class RemovedLocators extends Component
{
    // Agency related properties
    public Agency $current_agency;
    public Agency $agency;

    // Locator data
    public Collection $locators;
    public int $locators_count = 0;

    // Filter related properties
    public Collection $supervisors;
    public ?User $supervisor = null;
    public Collection $houses;
    public ?string $house = null;

    /**
     * Refresh the list of houses for the current agency
     * @return void
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
     * Refresh the list of removed locators for the current agency
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

            $locations = $query->where(function ($location) use ($supervisorsIds) {
                $location->House->whereIn("supervisor", $supervisorsIds);
            });
        } else {
            $locations = $query;
        }

        $locataires = $locations
            ->where("status", 3)
            ->map(function ($query) {
                return $query;
            });


        Session::forget("filteredLocators");

        $this->locators_count = count($locataires);
        $this->locators = $locataires;
    }

    /**
     * Initialize the component
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
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.removed-locators');
    }
}
