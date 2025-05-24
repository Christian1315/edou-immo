<?php

namespace App\Livewire;

use App\Models\Agency;
use App\Models\AgencyAccount;
use App\Models\AgencyAccountSold;
use App\Models\ImmoAccount;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Illuminate\View\View;

class CaisseMouvements extends Component
{
    public Agency $agency;
    public int $agency_account;
    public Collection $agencyAccountsSolds;
    public ImmoAccount $Account;

    public function refreshAgencyAccountSolds(): void
    {
        $agencyAccount = AgencyAccount::with('_Account')
            ->findOrFail($this->agency_account);

        $this->agencyAccountsSolds = AgencyAccountSold::with([
            '_Account',
            'WaterFacture',
            'House'
        ])
        ->where('agency_account', $this->agency_account)
        ->orderBy('visible', 'asc')
        ->get();

        $this->Account = $agencyAccount->_Account;
    }

    public function mount($agency, int $agency_account): void
    {
        $this->agency = $agency;
        $this->agency_account = $agency_account;
        $this->refreshAgencyAccountSolds();
    }

    public function render(): View
    {
        return view('livewire.caisse-mouvements');
    }
}
