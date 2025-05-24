<?php

namespace App\Traits;

use App\Models\House;
use App\Models\Location;
use App\Models\StopHouseElectricityState;
use App\Models\LocationElectrictyFacture;
use App\Models\Room;

trait LocationStateTrait
{
    public function stopHouseStats(House $house, $userId)
    {
        if (count($house->Locations) == 0) {
            throw new \Exception("Cette maison ne dispose d'aucune location! Son arrêt d'état ne peut donc être éffectué");
        }

        $state = $this->createOrUpdateHouseState($house, $userId);
        $this->updateLocationsStates($house, $state, $userId);

        return $state;
    }

    protected function createOrUpdateHouseState(House $house, $userId)
    {
        $lastState = StopHouseElectricityState::orderBy("id", "desc")
            ->where(["house" => $house->id])
            ->first();

        if (!$lastState) {
            return StopHouseElectricityState::create([
                "house" => $house->id,
                "owner" => $userId,
                "state_stoped_day" => now()
            ]);
        }

        return StopHouseElectricityState::create([
            "owner" => $userId,
            "house" => $house->id,
            "state_stoped_day" => now()
        ]);
    }

    protected function updateLocationsStates(House $house, StopHouseElectricityState $state, $userId)
    {
        foreach ($house->Locations as $location) {
            $this->updateLocationElectricityState($location, $state, $userId);
        }
    }

    protected function updateLocationElectricityState(Location $location, StopHouseElectricityState $state, $userId)
    {
        $location_factures = $location->ElectricityFactures;
        $location_room = Room::find($location->Room->id);

        if (count($location_factures) > 0) {
            $last_facture = $location_factures[0];
            $location_room->electricity_counter_start_index = $last_facture->end_index;
            $location_room->save();
        }

        foreach ($location_factures as $facture) {
            $electricty_facture = LocationElectrictyFacture::find($facture->id);
            if (!$electricty_facture->state) {
                $electricty_facture->state = $state->id;
                $electricty_facture->save();
            }
        }

        $this->createStateFacture($location, $location_room, $state, $userId);
    }

    protected function createStateFacture(Location $location, Room $room, StopHouseElectricityState $state, $userId)
    {
        LocationElectrictyFacture::create([
            "owner" => $userId,
            "location" => $location->id,
            "end_index" => $room->electricity_counter_start_index,
            "amount" => 0,
            "state_facture" => 1,
            "state" => $state->id,
        ]);
    }

    public function getLocationsAfterStateStop(House $house)
    {
        $last_state = $house->States->last();
        
        if (!$last_state) {
            throw new \Exception("Aucun état n'a été arrêté dans cette maison!");
        }

        $state_stop_date = date("Y/m/d H:m:s", strtotime($last_state->stats_stoped_day));
        $last_state_factures = $last_state->Factures;
        
        return $this->filterLocationsByStateDate($last_state_factures, $state_stop_date, true);
    }

    public function getLocationsBeforeStateStop(House $house)
    {
        $last_state = $house->States->last();
        
        if (!$last_state) {
            throw new \Exception("Aucun état n'a été arrêté dans cette maison!");
        }

        $state_stop_date = date("Y/m/d H:m:s", strtotime($last_state->stats_stoped_day));
        $last_state_factures = $last_state->Factures;
        
        return $this->filterLocationsByStateDate($last_state_factures, $state_stop_date, false);
    }

    protected function filterLocationsByStateDate($factures, $state_stop_date, $after = true)
    {
        $filtered_locations = [];
        $total_amount = 0;

        foreach ($factures as $facture) {
            $location_payement_date = date("Y/m/d H:m:s", strtotime($facture->created_at));
            
            if (($after && $state_stop_date < $location_payement_date) || 
                (!$after && $location_payement_date < $state_stop_date)) {
                
                $data = [
                    "name" => $facture->Location->Locataire->name,
                    "prenom" => $facture->Location->Locataire->prenom,
                    "email" => $facture->Location->Locataire->email,
                    "phone" => $facture->Location->Locataire->phone,
                    "adresse" => $facture->Location->Locataire->adresse,
                    "comments" => $facture->Location->Locataire->comments,
                    "payement_date" => $location_payement_date,
                    "month" => $facture->created_at,
                    "amount_paid" => $facture->amount
                ];

                $total_amount += $data["amount_paid"];
                $filtered_locations[] = $data;
            }
        }

        return [
            "locations" => $filtered_locations,
            "total_amount" => $total_amount,
            "total_count" => count($filtered_locations)
        ];
    }
} 