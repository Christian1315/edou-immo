<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

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
        $this->houses = $this->current_agency->_Houses;
    }

    /**
     * Refresh the list of unpaid locators for the current agency
     * 
     * @return void
     */
    public function refreshThisAgencyLocators(): void
    {
        $now = Carbon::now()->startOfDay();

        $now = Carbon::now()->startOfDay();
        $this->locators = $this->current_agency->_Locations
            ->filter(function ($location) use ($now) {
                return $now > Carbon::parse($location->echeance_date);
            })
            ->values();

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
