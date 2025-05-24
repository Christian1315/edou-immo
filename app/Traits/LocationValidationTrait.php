<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use App\Models\Room;
use App\Models\Location;

trait LocationValidationTrait
{
    const STATUS_MOVED = 3;

    public function validateLocationData(array $formData)
    {
        Validator::make($formData, $this->location_rules(), $this->location_messages())->validate();
        
        $this->validateLocationEntities($formData);
        $this->validateRoomAvailability($formData);
    }

    protected function validateLocationEntities(array $formData)
    {
        $room = Room::find($formData["room"]);
        if ($room->House->id != $formData["house"]) {
            throw new \Exception("Cette chambre n'appartient pas à la maison choisie");
        }
    }

    protected function validateRoomAvailability(array $formData)
    {
        $room_locations = Location::where([
            "room" => $formData["room"], 
            "house" => $formData["house"]
        ])->get()->filter(function ($location) {
            return $location->status != self::STATUS_MOVED;
        });

        if (count($room_locations) > 0) {
            throw new \Exception("Cette chambre est déjà occupée!");
        }
    }

    protected function location_rules(): array
    {
        return [
            'agency' => ['required', "integer","exists:agencies,id"],
            'house' => ['required', "integer","exists:houses,id"],
            'room' => ['required', "integer","exists:rooms,id"],
            'locataire' => ['required', "integer","exists:locataires,id"],
            'type' => ['required', "integer","exists:location_types,id"],
            'caution_number' => ['required', 'integer'],
            'prestation' => ['required', "numeric"],
            'numero_contrat' => ['required'],
            'caution_electric' => ['required', "numeric"],
            'effet_date' => ['required', "date"],
        ];
    }

    protected function location_messages(): array
    {
        return [
            'agency.required' => 'Veuillez préciser l\'agence!',
            'agency.integer' => "L'agence doit être de type entier",
            'house.required' => 'La maison est réquise!',
            'house.integer' => 'Ce champ doit être de type integer',
            'room.required' => "Le chambre est réquise!",
            'room.integer' => 'Ce champ doit être de type integer',
            'locataire.required' => "Le location est réquis!",
            'locataire.integer' => 'Ce champ doit être de type integer',
            'type.required' => "Le type de location est réquis!",
            'type.integer' => 'Ce champ doit être de type integer',
            'caution_number.required' => "Le nombre de caution est réquise!",
            'caution_number.integer' => "Le nombre de caution doit être de type integer!",
            'prestation.required' => "La prestation est réquise",
            'numero_contrat.required' => "Le numéro du contrat est réquis!",
            'caution_electric.required' => "La caution d'electricité est réquise!",
            'caution_electric.numeric' => 'Le type de la caution d\'electricité doit être de type numéric!',
            'effet_date.required' => "La date d'effet est réquise!",
            'effet_date.date' => "Ce champ est de type date",
        ];
    }
} 