<?php

namespace App\Livewire;

use App\Models\Agency as ModelsAgency;
use App\Models\City;
use App\Models\Country;
use App\Models\House;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class Agency extends Component
{
    use WithFileUploads;

    public $agencies = [];
    public $showPrestations = false;

    // 
    public Collection $countries;
    public Collection $cities;

    public $generalError = "";
    public $generalSuccess = "";

    public $showCautions = false;
    public $cautions_link = "";

    // 
    function mount()
    {
        set_time_limit(0);
        $this->agencies = ModelsAgency::all();
        $this->countries = Country::all();
        $this->cities = City::all();
    }

    public function render()
    {
        return view('livewire.agency');
    }
}
