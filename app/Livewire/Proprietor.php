<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Agency;
use App\Models\CardType;
use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class Proprietor extends Component
{
    use WithFileUploads;

    public $current_agency;
    public Collection $proprietors;
    public int $proprietors_count = 0;
    public Collection $countries;
    public Collection $cities;
    public Collection $card_types;
    public bool $show_form = false;
    public int $click_count = 2;

    /**
     * Refresh the proprietors list for the current agency
     */
    public function refreshThisAgencyProprietors(): void
    {
        $agency = Agency::with('_Proprietors')->findOrFail($this->current_agency['id']);
        $this->proprietors = $agency->_Proprietors;
        $this->proprietors_count = $this->proprietors->count();
    }

    /**
     * Initialize the component with required data
     */
    public function mount($agency): void
    {
        $this->current_agency = $agency;
        $this->refreshThisAgencyProprietors();
        
        // Load all required data in a single query for each model
        $this->countries = Country::all();
        $this->cities = City::all();
        $this->card_types = CardType::all();
    }

    /**
     * Render the component view
     */
    public function render()
    {
        return view('livewire.proprietor');
    }
}
