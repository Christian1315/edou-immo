<?php

namespace App\Livewire;
use App\Models\Agency;
use App\Models\ImmoAccount;
use Livewire\Component;

class AccountSold extends Component
{
    public $principalAccounts = false;

    public $agency;
    public $allAgenciesAccounts = [];

    function mount()
    {
        $agencies = Agency::all();
        $accountHandled = [];

        foreach ($agencies as $agencyKey => $agency) {

            foreach ($agency->_AgencyAccounts as $key => $agencyAccount) {

                if ($agencyKey == 0) {
                    $accountHandled["caisse" . $key] = [];
                    $accountHandled["caisse" . $key]["name"] = $agencyAccount->_Account?$agencyAccount->_Account->name:"";
                    $accountHandled["caisse" . $key]["description"] = $agencyAccount->_Account?$agencyAccount->_Account->description:"";
                    $accountHandled["caisse" . $key]["plafond_max"] = $agencyAccount->_Account?$agencyAccount->_Account->plafond_max:"";
                    $accountHandled["caisse" . $key]["agency_current_sold"] = $agencyAccount->AgencyCurrentSold ? $agencyAccount->AgencyCurrentSold->sold : 0;
                } else {
                    $previous_sold = $accountHandled["caisse" . $key]["agency_current_sold"];
                    $accountHandled["caisse" . $key]["agency_current_sold"] = $previous_sold + ($agencyAccount->AgencyCurrentSold ? $agencyAccount->AgencyCurrentSold->sold : 0);
                }
            }
        }

        $_allAgenciesAccounts = [];
        for ($i = 0; $i < 9; $i++) {
            if (count($accountHandled) != 0) {
                array_push($_allAgenciesAccounts, $accountHandled["caisse" . $i]);
            }
        }

        $this->allAgenciesAccounts = $_allAgenciesAccounts;

        ###____
        if (count($this->allAgenciesAccounts) == 0) {
            $this->principalAccounts = true;
            $this->allAgenciesAccounts = ImmoAccount::all();
        }
    }

    public function render()
    {
        return view('livewire.account-sold');
    }
}
