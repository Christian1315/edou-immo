<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
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
     * Refresh the list of supervisors
     * @return void
     */
    public function refreshSupervisors(): void
    {
        $this->supervisors = User::get()
            ->filter(fn($user) => $user->hasRole("Superviseur"))
            ->unique()->values();
    }

    /**
     * Refresh the list of houses for the current agency
     * @return void
     */
    public function refreshThisAgencyHouses(): void
    {
        $this->houses = $this->current_agency->_Houses;
    }

    /**
     * Refresh the list of removed locators for the current agency
     * @return void
     */
    public function refreshThisAgencyLocators(): void
    {
        $agency = Agency::findOrFail($this->current_agency->id);

        $locataires = $agency->_Locations
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
        $this->refreshSupervisors();
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
