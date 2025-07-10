<?php

namespace App\Livewire;

use App\Models\RoomNature;
use App\Models\RoomType;
use App\Models\Agency;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Room extends Component
{
    use WithFileUploads;

    private const DELETE_ROOM_TITLE = 'Suppression de la chambre!';
    private const DELETE_ROOM_TEXT = 'Voulez-vous vraiment supprimer cette chambre?';

    public Agency $agency;
    public Agency $current_agency;

    public Collection $rooms;
    public int $rooms_count = 0;

    public Collection $countries;
    public Collection $proprietors;
    public Collection $houses;
    public Collection $cities;
    public Collection $room_types;
    public Collection $room_natures;
    public Collection $departements;
    public Collection $quartiers;
    public Collection $zones;

    public function mount(Agency $agency): void
    {
        set_time_limit(0);
        $this->current_agency = $agency;

        $this->initializeCollections();
        $this->loadAgencyData();
    }

    private function initializeCollections(): void
    {
        $this->rooms = collect();
        $this->countries = collect();
        $this->proprietors = collect();
        $this->houses = collect();
        $this->cities = collect();
        $this->room_types = RoomType::all();
        $this->room_natures = RoomNature::all();
        $this->departements = collect();
        $this->quartiers = collect();
        $this->zones = collect();
    }

    private function loadAgencyData(): void
    {
        $this->refreshThisAgencyRooms();
        $this->refreshThisAgencyHouses();
    }

    public function refreshThisAgencyRooms(): void
    {
        confirmDelete(self::DELETE_ROOM_TITLE, self::DELETE_ROOM_TEXT);
        $user = Auth::user();

        if ($user->hasRole("Gestionnaire de compte")) {
            /** Pour une Gestionnaire de compte, on recupère juste les 
             * maisons de ses superviseurs
             */
            $supervisorsIds = $user->supervisors->pluck("id")
                ->toArray();
            $houses = $this->current_agency->_Proprietors
                ->flatMap(fn($proprio) => $proprio->Houses
                    ->whereIn("supervisor", $supervisorsIds));
        } else {
            $houses = $this->current_agency->_Proprietors
                ->flatMap(fn($proprio) => $proprio->Houses);
        }

        $this->rooms = $houses
            ->flatMap(fn($house) => $house->Rooms);

        $this->rooms_count = $this->rooms->count();
    }

    public function refreshThisAgencyHouses(): void
    {
        $user = Auth::user();

        if ($user->hasRole("Gestionnaire de compte")) {
            /** Pour une Gestionnaire de compte, on recupère juste les 
             * maisons de ses superviseurs
             */
            $supervisorsIds = $user->supervisors->pluck("id")
                ->toArray();
            $this->houses = $this->current_agency
                ->_Houses->whereIn("supervisor", $supervisorsIds);
        } else {
            $this->houses = $this->current_agency->_Houses;
        }
    }

    public function render()
    {
        return view('livewire.room');
    }
}
