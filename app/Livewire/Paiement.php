<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Agency;
use App\Models\House;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class Paiement extends Component
{
    /**
     * The current agency instance
     */
    public Agency $current_agency;

    /**
     * Collection of houses for the current agency
     */
    public Collection $houses;

    /**
     * Mount the component with the given agency
     */
    public function mount(Agency $agency): void
    {
        set_time_limit(0);
        $this->current_agency = $agency;
        $this->refreshHouses();
    }

    /**
     * Refresh the houses collection with their latest state
     */
    public function refreshHouses(): void
    {
        try {
            $this->houses = $this->current_agency->_Houses->map(function (House $house) {
                return GET_HOUSE_DETAIL_FOR_THE_LAST_STATE($house); 
            });

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du rafraîchissement des maisons: ' . $e->getMessage());
            $this->houses = collect();
        }
    }

    /**
     * Render the component
     */
    public function render(): View
    {
        return view('livewire.paiement');
    }
}
