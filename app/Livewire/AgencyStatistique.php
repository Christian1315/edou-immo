<?php

namespace App\Livewire;

use App\Models\Agency;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class AgencyStatistique extends Component
{

    public Agency $agency;

    public Collection $houses;

    function mount($agency) {
        $this->houses = $agency->_Houses;
    }

    public function render()
    {
        return view('livewire.agency-statistique');
    }
}
