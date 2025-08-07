<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\House;
use App\Models\AgencyAccount;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

/**
 * Class Caisses
 * @package App\Livewire
 */
class Caisses extends Component
{
    /**
     * @var Agency
     */
    public Agency $agency;

    /**
     * @var array<AgencyAccount>
     */
    public Collection $agencyAccounts;

    /**
     * @var array<House>
     */
    public Collection $houses;
    
    /**
     * Mount the component
     *
     * @param Agency $agency
     * @return void
     */
    public function mount(Agency $agency): void
    {
        $this->agency = $agency;
        $this->houses = $agency->_Houses;
        $this->agencyAccounts = $agency->_AgencyAccounts;
        // dd($this->agencyAccounts);
    }

    /**
     * Render the component
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.caisses');
    }
}
