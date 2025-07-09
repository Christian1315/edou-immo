<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Agency;
use App\Models\House;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class Paiement extends Component
{
    /**
     * The current agency instance
     */
    public $current_agency;

    /**
     * Collection of houses for the current agency
     */
    public $houses;

    /**
     * Collection de toutes les maisons
     */
    public Collection $allHouses;

    /**
     * Current house
     */
    public $house_got;

    /**
     * Current house traited
     */
    public $house;


    /**
     * Mount the component with the given agency
     */
    public function mount($agency, $house): void
    {
        $this->current_agency = $agency;
        $this->house_got = $house;

        $this->houses = House::get();
        $this->refreshHouses();
    }

    /**
     * Refresh the houses collection with their latest state
     */
    public function refreshHouses(): void
    {
        try {
            if ($this->house_got) {
                $this->house = EloquentCollection::make(GET_HOUSE_DETAIL_FOR_THE_LAST_STATE($this->house_got));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors du rafraîchissement des maisons: ' . $e->getMessage());
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
