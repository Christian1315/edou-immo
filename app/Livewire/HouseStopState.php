<?php

namespace App\Livewire;

use App\Models\House;
use Livewire\Component;

class HouseStopState extends Component
{
    public $agency;
    public House $house;

    function mount($agency, $house)
    {
        $this->house = GET_HOUSE_DETAIL($house);
    }

    public function render()
    {
        return view('livewire.house-stop-state');
    }
}
