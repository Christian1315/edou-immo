<?php

namespace App\Livewire;

use App\Models\Facture;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class RecoveryAnyDate extends Component
{
    public $agency;

    public $locators;

    function mount($agency)
    {
        set_time_limit(0);
        $this->agency = $agency;
        // Filtrage des factures avec une requête plus efficace
        $factures = Facture::whereDate('created_at', now())->get();

        // Récupération des locations avec eager loading
        $locations = Location::where("agency", $this->agency->id)
            ->whereIn("id", $factures->pluck("location"))
            ->with('Locataire') // Eager loading pour éviter le N+1 problem
            ->get();

        // Récupération des locataires de manière plus efficace
        $this->locators = $locations->pluck('Locataire')
            ->filter()
            ->values()
            // ->toArray()
        ;

    }

    public function render()
    {
        return view('livewire.recovery-any-date');
    }
}
