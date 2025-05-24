<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Models\Agency;
use App\Models\PaymentInitiation;
use App\Models\Proprietor;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PaiementInitiation
 * 
 * Component Livewire pour gérer les initiations de paiement
 */
class PaiementInitiation extends Component
{
    public Agency $agency;
    
    /** @var array<PaymentInitiation> */
    public Collection $initiations;
    
    public int $initiations_count = 0;
    
    /** @var array<Proprietor> */
    public Collection $proprietors;

    /**
     * Rafraîchit la liste des initiations de paiement
     */
    private function refreshInitiations(): void
    {
        $this->initiations = $this->agency->_PayementInitiations;
        $this->initiations_count = count($this->initiations);
    }

    /**
     * Rafraîchit la liste des propriétaires de l'agence
     */
    private function refreshThisAgencyProprietors(): void
    {
        $this->proprietors = $this->agency->_Proprietors;
    }

    /**
     * Initialise le composant
     * 
     * @param Agency $agency
     */
    public function mount(Agency $agency): void
    {
        set_time_limit(0);
        $this->agency = $agency;
        $this->refreshData();
    }

    /**
     * Rafraîchit toutes les données du composant
     * 
     * @param string $message
     */
    public function refresh(string $message): void
    {
        set_time_limit(0);
        $this->refreshData();
    }

    /**
     * Rafraîchit toutes les données
     */
    private function refreshData(): void
    {
        $this->refreshInitiations();
        $this->refreshThisAgencyProprietors();
    }

    /**
     * Rendu du composant
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.paiement-initiation');
    }
}
