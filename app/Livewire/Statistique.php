<?php

namespace App\Livewire;

use App\Models\House;
use Livewire\Component;

class Statistique extends Component
{
    public $houses = [];

    ###___HOUSES
    function refreshThisHousesHouses()
    {
        $this->houses = House::where("visible",1)->get();
    }

    function mount()
    {
        set_time_limit(0);

        $this->refreshThisHousesHouses();
    }

    public function render()
    {
        return view('livewire.statistique');
    }
}
