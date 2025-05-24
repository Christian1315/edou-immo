<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Graph extends Component
{
    public $proprietors_count = 0;
    public $houses_count = 0;
    public $locators_count = 0;
    public $locations_count = 0;
    public $rooms_count = 0;
    public $paiement_count = 0;
    public $factures_count = 0;
    public $accountSold_count = 0;
    public $initiation_count = 0;

    public function render()
    {
        return view('livewire.graph');
    }
}
