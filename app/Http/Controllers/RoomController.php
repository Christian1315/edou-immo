<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomNature;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    private const ERROR_MESSAGES = [
        'room_not_found' => 'Cette Chambre n\'existe pas!',
        'house_not_found' => 'Cette maison n\'existe pas!',
        'nature_not_found' => 'Cette nature de chambre n\'existe pas!',
        'type_not_found' => 'Ce type de chambre n\'existe pas!',
        'agency_not_found' => 'Cette agence n\'existe pas!',
        'supervisor_not_found' => 'Ce superviseur n\'existe pas!',
        'no_results' => 'Aucun résultat trouvé',
        'unauthorized' => 'Cette Chambre n\'a pas été crée par vous! Vous ne pouvez pas la modifier',
    ];

    private const VALIDATION_RULES = [
        'room_type' => [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ],
        'room' => [
            'house' => ['required', 'integer', 'exists:houses,id'],
            'nature' => ['required', 'integer', 'exists:room_natures,id'],
            'type' => ['required', 'integer', 'exists:room_types,id'],
            'loyer' => ['required', 'numeric', 'min:0'],
            'number' => ['required', 'string', 'max:50'],
        ],

        // eau
        'water_discounter' => [
            'unit_price' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'water_counter_start_index' => ['required', 'numeric', 'min:0', 'max:1000000'],
        ],
        'water_conventionnal_counter' => [
            'water_counter_number' => ['required', 'string', 'max:50', 'unique:rooms,water_counter_number'],
            'water_conventionnel_counter_start_index' => ['required', 'numeric', 'min:0', 'max:1000000'],
        ],
        'forage' => [
            'forfait_forage' => ['required', 'numeric', 'min:0', 'max:1000000'],
        ],

        // electricite
        'electricity_discounter' => [
            'electricity_unit_price' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'electricity_counter_number' => ['required', 'string', 'max:50', 'unique:rooms,electricity_counter_number'],
            'electricity_counter_start_index' => ['required', 'numeric', 'min:0', 'max:1000000'],
        ],
    ];

    private const VALIDATION_MESSAGES = [
        'name.required' => 'Le nom du type de la chambre est réquis!',
        'name.string' => 'Le nom doit être une chaîne de caractères!',
        'name.max' => 'Le nom ne doit pas dépasser 255 caractères!',

        'description.required' => 'La description du type de la chambre est réquise!',
        'description.string' => 'La description doit être une chaîne de caractères!',

        'house.required' => 'Veuillez préciser la maison!',
        'house.integer' => 'La maison doit être un identifiant valide!',
        'house.exists' => 'La maison sélectionnée n\'existe pas!',

        'nature.required' => 'Veuillez préciser la nature de la chambre!',
        'nature.integer' => 'La nature doit être un identifiant valide!',
        'nature.exists' => 'La nature sélectionnée n\'existe pas!',

        'type.required' => 'Veuillez préciser le type de la chambre!',
        'type.integer' => 'Le type doit être un identifiant valide!',
        'type.exists' => 'Le type sélectionné n\'existe pas!',

        'loyer.required' => 'Le loyer est réquis!',
        'loyer.numeric' => 'Le loyer doit être un nombre!',
        'loyer.min' => 'Le loyer ne peut pas être négatif!',

        'number.required' => 'Le numéro de la chambre est réquis!',
        'number.string' => 'Le numéro doit être une chaîne de caractères!',
        'number.max' => 'Le numéro ne doit pas dépasser 50 caractères!',

        'unit_price.required' => 'Le prix unitaire du décompteur est réquis!',
        'unit_price.numeric' => 'Le prix unitaire doit être un nombre!',
        'unit_price.min' => 'Le prix unitaire ne peut pas être négatif!',
        'unit_price.max' => 'Le prix unitaire ne peut pas dépasser 1,000,000!',

        'water_counter_start_index.required' => 'L\'index de début du décompteur est réquis!',
        'water_counter_start_index.numeric' => 'L\'index de début doit être un nombre!',
        'water_counter_start_index.min' => 'L\'index de début ne peut pas être négatif!',
        'water_counter_start_index.max' => 'L\'index de début ne peut pas dépasser 1,000,000!',

        'water_counter_number.required' => 'Le numéro du compteur est réquis!',
        'water_counter_number.string' => 'Le numéro du compteur doit être une chaîne de caractères!',
        'water_counter_number.max' => 'Le numéro du compteur ne doit pas dépasser 50 caractères!',
        'water_counter_number.unique' => 'Ce numéro de compteur est déjà utilisé!',

        'water_conventionnel_counter_start_index.required' => 'L\'index de début du compteur conventionnel est réquis!',
        'water_conventionnel_counter_start_index.numeric' => 'L\'index de début doit être un nombre!',
        'water_conventionnel_counter_start_index.min' => 'L\'index de début ne peut pas être négatif!',
        'water_conventionnel_counter_start_index.max' => 'L\'index de début ne peut pas dépasser 1,000,000!',

        'forfait_forage.required' => 'Le forfait forage est réquis!',
        'forfait_forage.numeric' => 'Le forfait forage doit être un nombre!',
        'forfait_forage.min' => 'Le forfait forage ne peut pas être négatif!',
        'forfait_forage.max' => 'Le forfait forage ne peut pas dépasser 1,000,000!',

        'electricity_counter_number.required' => 'Le numéro du compteur d\'electricité est réquis!',
        'electricity_unit_price.required' => 'Le prix unitaire du compteur d\'electricité est réquis!',
        'electricity_counter_start_index.required' => 'L\'index de début du compteur est réquis!',
    ];

    public function AddRoomType(Request $request)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES['room_type'], self::VALIDATION_MESSAGES);
            RoomType::create($request->all());
            return $this->successResponse('Type de chambre ajouté avec succès!');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function AddRoomNature(Request $request)
    {
        try {
            $this->validate($request, self::VALIDATION_RULES['room_type'], self::VALIDATION_MESSAGES);
            RoomNature::create($request->all());
            return $this->successResponse('Nature de chambre ajoutée avec succès!');
        } catch (ValidationException $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function AddRoom(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validation de base de la chambre
            $this->validate($request, self::VALIDATION_RULES['room'], self::VALIDATION_MESSAGES);

            // Validation des options d'eau sélectionnées
            if ($request->forage) {
                $this->validate($request, self::VALIDATION_RULES['forage'], self::VALIDATION_MESSAGES);
            }

            if ($request->water_discounter) {
                $this->validate($request, self::VALIDATION_RULES['water_discounter'], self::VALIDATION_MESSAGES);
            }

            if ($request->water_conventionnal_counter) {
                $this->validate($request, self::VALIDATION_RULES['water_conventionnal_counter'], self::VALIDATION_MESSAGES);
            }

            // Validation des options d'electricité sélectionnées
            if ($request->electricity_discounter) {
                $this->validate($request, self::VALIDATION_RULES['electricity_discounter'], self::VALIDATION_MESSAGES);
            }

            // Vérification de l'unicité du numéro de chambre
            if ($this->isRoomNumberExists($request->number, $request->house)) {
                return $this->errorResponse('Cette chambre existe déjà dans cette maison!');
            }

            $formData = $this->prepareRoomData($request);
            Room::create($formData);

            DB::commit();
            return $this->successResponse('Chambre ajoutée avec succès!');
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Une erreur est survenue: ');
        }
    }

    public function UpdateRoom(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $room = Room::with('Locations')->findOrFail($id);

            if (!$this->canModifyRoom($room)) {
                return $this->errorResponse(self::ERROR_MESSAGES['unauthorized']);
            }

            $formData = $this->prepareRoomData($request);

            $room->update(array_merge($formData, [
                "electricity_counter_number" => $request->electricity_counter_number ??
                    $room->electricity_counter_number
            ]));

            $this->updateRelatedLocations($room);

            DB::commit();
            return $this->successResponse('Chambre modifiée avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Une erreur est survenue');
        }
    }

    public function DeleteRoom(Request $request, $id)
    {
        try {
            $room = Room::findOrFail(deCrypId($id));

            if (!$this->canModifyRoom($room)) {
                return $this->errorResponse(self::ERROR_MESSAGES['unauthorized']);
            }

            $room->delete();
            return $this->successResponse('Chambre supprimée avec succès!');
        } catch (\Exception $e) {
            return $this->errorResponse('Une erreur est survenue: ' . $e->getMessage());
        }
    }

    private function isRoomNumberExists($number, $houseId): bool
    {
        return Room::where(['number' => $number, 'house' => $houseId])->exists();
    }

    private function canModifyRoom(Room $room): bool
    {
        return auth()->user()->hasRole("Super Administrateur") ||
            auth()->user()->hasRole("Master") ||
            $room->owner === auth()->id();
    }

    private function prepareRoomData(Request $request): array
    {
        $data = $request->all();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $this->handlePhotoUpload($request->file('photo'));
        }

        // Calculate total amount
        $data['total_amount'] = $this->calculateTotalAmount($data);

        // eau
        ($request->water_discounter ||
            $request->water_conventionnal_counter ||
            $request->forage ||
            $request->unit_price ||
            $request->forfait_forage ||
            $request->water_counter_start_index) ?
            $data["water"] = 1 : null;

        $data["forfait_forage"] = $request->forfait_forage ?? 0;
        $data["water_counter_number"] = $request->water_counter_number ?? 0;
        $data["comments"] = $request->comments ?? '--';
        $data["water_counter_start_index"] = $request->water_counter_start_index ?? 0;
       
        // electricite
        ($request->electricity_discounter ||
            $request->electricity_card_counter ||
            $request->electricity_conventionnal_counter ||
            $request->electricity_counter_number ||
            $request->electricity_unit_price ||
            $request->electricity_counter_start_index) ?
            $data["electricity"] = 1 : null;

        $data["electricity_discounter"] = $request->electricity_discounter ?? 0;
        $data["electricity_card_counter"] = $request->electricity_card_counter ?? 0;
        $data["electricity_conventionnal_counter"] = $request->electricity_conventionnal_counter ?? 0;
        $data["electricity_counter_number"] = $request->electricity_counter_number ?? 0;
        $data["electricity_unit_price"] = $request->electricity_unit_price ?? 0;
        $data["electricity_counter_start_index"] = $request->electricity_counter_start_index ?? 0;

        // Set fields
        $booleanFields = [
            'water',
            'water_discounter',
            'water_conventionnal_counter',
            'forage',

            'electricity',
            'electricity_discounter',
            'electricity_card_counter',
            'electricity_conventionnal_counter',
        ];

        foreach ($booleanFields as $field) {
            $data[$field] = $request->has($field) ? 1 : 0;
        }

        return $data;
    }

    private function handlePhotoUpload($photo): string
    {
        $photoName = $photo->getClientOriginalName();
        $photo->move('room_images', $photoName);
        return asset('room_images/' . $photoName);
    }

    private function calculateTotalAmount(array $data): float
    {
        return array_sum([
            $data['loyer'] ?? 0,
            $data['gardiennage'] ?? 0,
            $data['rubbish'] ?? 0,
            $data['vidange'] ?? 0,
            $data['cleaning'] ?? 0
        ]);
    }

    private function updateRelatedLocations(Room $room): void
    {
        $room->Locations()->update(['loyer' => $room->total_amount]);
    }

    private function successResponse(string $message)
    {
        alert()->success('Succès', $message);
        return back()->withInput();
    }

    private function errorResponse(string $message)
    {
        alert()->error('Echec', $message);
        return back()->withInput();
    }
}
