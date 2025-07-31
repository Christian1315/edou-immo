<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Agency;
use App\Models\House;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Performance extends Component
{
    private const DATE_FORMAT = 'Y/m/d';
    private const FIRST_MONTH_PERIOD = '+1 month';

    public Agency $agency;

    public Collection $houses;

    ###___chambres occupées
    public array $all_busy_rooms = [];
    ###___chambres libre
    public array $all_frees_rooms_at_first_month = [];

    ###___HOUSES
    public function refreshThisAgencyHouses(): void
    {
        $this->houses = $this->agency->_Houses;
    }

    public function mount(Agency $agency): void
    {
        set_time_limit(0);

        $this->agency = $agency;

        $this->GenerateAgencyPerformance();
        $this->refreshThisAgencyHouses();
    }

    ###___AGENCY PERFORMANCE
    public function GenerateAgencyPerformance(): void
    {
        $houses = $this->agency->_Houses;
        
        $processedHouses = $houses->map(function (House $house): House {
            $creationDate = Carbon::parse($house->created_at);
            $firstMonthPeriod = $creationDate->copy()->add(self::FIRST_MONTH_PERIOD);

            $rooms = $house->Rooms;
            $locations = $house->Locations;

            $roomStatus = $rooms->map(function (Room $room) use ($locations, $firstMonthPeriod): array {
                $location = $locations->firstWhere('Room.id', $room->id);
                
                if ($location) {
                    $locationCreateDate = Carbon::parse($location->created_at);
                    $isFirstMonth = $locationCreateDate->lt($firstMonthPeriod);
                    
                    return [
                        'room' => $room,
                        'is_busy' => true,
                        'is_first_month' => $isFirstMonth
                    ];
                }

                return [
                    'room' => $room,
                    'is_busy' => false,
                    'is_first_month' => false
                ];
            });

            $busy_rooms = $roomStatus->where('is_busy', true)->pluck('room')->toArray();
            $frees_rooms = $roomStatus->where('is_busy', false)->pluck('room')->toArray();
            $busy_rooms_at_first_month = $roomStatus->where('is_busy', true)->where('is_first_month', true)->pluck('room')->toArray();
            $frees_rooms_at_first_month = $roomStatus->where('is_busy', true)->where('is_first_month', false)->pluck('room')->toArray();

            $house->busy_rooms = $busy_rooms;
            $house->frees_rooms = $frees_rooms;
            $house->busy_rooms_at_first_month = $busy_rooms_at_first_month;
            $house->frees_rooms_at_first_month = $frees_rooms_at_first_month;

            return $house;
        });

        $this->all_busy_rooms = $processedHouses->pluck('busy_rooms')->toArray();
        $this->all_frees_rooms_at_first_month = $processedHouses->pluck('busy_rooms_at_first_month')->toArray();
    }

    ###____
    public function render()
    {
        return view('livewire.performance');
    }
}
