<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Facture;
use App\Models\HomeStopState;
use App\Models\House;
use App\Models\Locataire;
use App\Models\Location;
use App\Models\LocationElectrictyFacture;
use App\Models\LocationStatus;
use App\Models\LocationType;
use App\Models\LocationWaterFacture;
use App\Models\PaiementType;
use App\Models\Payement;
use App\Models\Proprietor;
use App\Models\Room;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Traits\LocationValidationTrait;
use App\Traits\LocationPaymentTrait;
use App\Traits\LocationStateTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    use LocationValidationTrait, LocationPaymentTrait, LocationStateTrait;
    // Constants
    const STATUS_MOVED = 3;
    const STATUS_SUSPENDED = 2;
    const STATUS_ACTIVE = 1;

    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth'])->except(["_ManageCautions", "_ShowCautionsForHouseByPeriod", "_ShowCautionsByPeriod"]);
    }

    ########==================== ROOM TYPE VALIDATION ===================#####
    static function room_type_rules(): array
    {
        return [
            "name" => ["required"],
            "description" => ["required"],
        ];
    }

    static function room_type_messages(): array
    {
        return [
            "name.required" => "Le nom du type de la location est réquis!",
            "description.required" => "La description du type de la location est réquise!",
        ];
    }

    ##======== LOCATION VALIDATION =======##
    static function location_rules(): array
    {
        return [
            'agency' => ['required', "integer"],
            'house' => ['required', "integer"],
            'room' => ['required', "integer"],
            'locataire' => ['required', "integer"],
            'type' => ['required', "integer"],

            // 'water_counter' => ['required'],
            // 'electric_counter' => ['required'],

            'caution_number' => ['required', 'integer'],

            'prestation' => ['required', "numeric"],
            'numero_contrat' => ['required'],

            'caution_electric' => ['required', "numeric"],
            'effet_date' => ['required', "date"],
        ];
    }

    static function location_messages(): array
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

            'caution_bordereau.file' => "Le bordereau de la caution doit être un fichier!",


            'caution_number.required' => "Le nombre de caution est réquise!",
            'caution_number.integer' => "Le nombre de caution doit être de type integer!",

            'frais_peiture.required' => "Les frais de reprise de peinture sont réquis!",
            'frais_peiture.numeric' => "Ce champ  doit être de caractère numérique!",

            // 'water_counter.required' => "Le numéro du compteur d'eau est réquis",
            // 'electric_counter.required' => "Le numéro du compteur électrique est réquis",

            'prestation.required' => "La prestation est réquise",
            'prestation.file' => "La prestation doit être un fichier",

            'numero_contrat.required' => "Le numéro du contrat est réquis!",
            'comments.required' => "Le commentaire est réquis",

            // 'img_contrat.required' => "L'image du contrat est réquise",
            // 'img_contrat.file' => "L'image du contrat doit être un fichier",

            'pre_paid.boolean' => "Le champ doit être un booléen!",
            'post_paid.boolean' => "Le champ doit être un booléen",

            'discounter.required' => "Le decompteur est réquis!",
            'discounter.boolean' => "Ce champ doit être de type booléen",

            'img_prestation.file' => "L'image de la prestation doit être un fichier",

            'caution_electric.required' => "La caution d'electricité est réquise!",
            'caution_electric.numeric' => 'Le type de la caution d\'electricité doit être de type numéric!',

            'effet_date.required' => "La date d'effet est réquise!",
            'effet_date.date' => "Ce champ est de type date",
        ];
    }

    ########################===================== DEBUT DES METHODES ======================###############

    ####____AJOUT D'UN TYPE DE LOCATION
    static function _AddType(Request $request)
    {
        try {
            DB::beginTransaction();

            // validation
            $formData = $request->all();
            Validator::make($request->all(), self::room_type_rules(), self::room_type_messages())->validate();

            $type = LocationType::create($formData);

            DB::commit();

            alert()->success("Succès", "Type de location ajouté avec succès!");
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error("Echec", $e->getMessage());
            return back();
        }
    }

    /**
     * Gère les cautions pour une agence spécifique
     * 
     * @param Request $request
     * @param string $agencyId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    function _ManageCautions(Request $request, $agencyId)
    {
        try {
            // Validation de l'agence
            $agency = Agency::where("visible", 1)
                ->find(deCrypId($agencyId));

            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            // Récupération des locations avec eager loading pour optimiser les performances
            $locations = $agency->_Locations()
                ->with(['Room', 'Locataire', 'House'])
                ->get();

            if ($locations->isEmpty()) {
                throw new \Exception("Aucune location trouvée pour cette agence!");
            }

            // Calcul des cautions en utilisant les collections
            $cautions = $locations->map(function ($location) {
                return [
                    'electricity' => $location->caution_electric,
                    'water' => $location->caution_water,
                    'waterPluselectricity' => $location->caution_water + $location->caution_water,
                    'cautionLoyer' => $location->caution_number * $location->loyer
                ];
            });

            // Calcul des totaux
            $totals = [
                'electricity' => $cautions->sum('electricity'),
                'water' => $cautions->sum('water'),
                'waterPluselectricity' => $cautions->sum("waterPluselectricity"),
                'cautionLoyer' => $cautions->sum('cautionLoyer')
            ];

            return view("cautions", compact("locations", "cautions", "totals"));
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back();
        }
    }

    /**
     * Gère les cautions pour une location spécifique
     * 
     * @param Request $request
     * @param int $locationId
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    function _ManageLocationCautions(Request $request, $locationId)
    {
        try {
            // Validation de la location
            $location = Location::with("Locataire")
                ->find($locationId);

            if (!$location) {
                throw new \Exception("Cette location n'existe pas!");
            }

            // Calcul des différentes cautions
            $cautions = [
                'eau' => $location->caution_water,
                'electricite' => $location->caution_electric,
                'loyer' => $location->caution_number * $location->loyer
            ];

            // Vérification des valeurs négatives
            foreach ($cautions as $type => $montant) {
                if ($montant < 0) {
                    throw new \Exception("La caution {$type} ne peut pas être négative!");
                }
            }

            // Calcul du total des cautions
            $total_cautions = array_sum($cautions);

            alert()->success('Succès', "Cautions générées avec succès!");

            $pdf = Pdf::loadView("location_cautions", compact("location", "cautions", "total_cautions"));

            // Set PDF orientation to landscape
            $pdf->setPaper('a4', 'landscape');

            return $pdf->stream();
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back();
        }
    }

    // LOCATIONS ETATS PRORATA
    function _ManageLocationProrata(Request $request, $locationId)
    {
        try {
            // Validate location exists and is active
            $location = Location::with(['Room', 'Locataire', 'House'])
                ->where('visible', 1)
                ->find($locationId);

            if (!$location) {
                throw new \Exception("Cette location n'existe pas ou n'est plus active!");
            }

            // Check if location has required relationships
            if (!$location->Room || !$location->Locataire || !$location->House) {
                throw new \Exception("Données de location incomplètes!");
            }

            // Calculate prorata if needed
            $prorataData = [
                'location' => $location,
                'prorata_amount' => $this->calculateProrataAmount($location),
                'calculation_date' => now()->format('Y-m-d')
            ];

            return view("etat_prorata", compact("prorataData"));
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back();
        }
    }

    /**
     * Calculate prorata amount for a location
     * 
     * @param Location $location
     * @return float
     */
    private function calculateProrataAmount(Location $location): float
    {
        // Get the number of days in the current month
        $daysInMonth = now()->daysInMonth;

        // Get the day of the month when the location started
        $startDay = $location->effet_date->day;

        // Calculate the number of days the location is active this month
        $activeDays = $daysInMonth - $startDay + 1;

        // Calculate daily rate
        $dailyRate = $location->loyer / $daysInMonth;

        // Calculate prorata amount
        return round($dailyRate * $activeDays, 2);
    }

    #####___GENERATION DES PRESTATION PAR PERIODE
    function _ManagePrestationStatistiqueForAgencyByPeriod(Request $request, $agencyId)
    {
        try {
            // Validation des dates
            $validator = Validator::make($request->all(), [
                'first_date' => 'required|date',
                'last_date' => 'required|date|after_or_equal:first_date'
            ], [
                'first_date.required' => 'La date de début est requise',
                'first_date.date' => 'La date de début doit être une date valide',
                'last_date.required' => 'La date de fin est requise',
                'last_date.date' => 'La date de fin doit être une date valide',
                'last_date.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début'
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            // Récupération de l'agence avec eager loading
            $agency = Agency::where("visible", 1)
                ->find(deCrypId($agencyId));

            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            // Récupération des locations avec eager loading
            $locations = $agency->_Locations()
                ->with(['Room', 'Locataire', 'House'])
                ->whereBetween("created_at", [
                    $request->first_date,
                    $request->last_date
                ])
                ->get();

            if ($locations->isEmpty()) {
                throw new \Exception("Aucune location trouvée pour cette période!");
            }

            return view("prestation-statistique", compact("locations", "agency"));
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    function _AddLocation(Request $request)
    {
        try {
            DB::beginTransaction();

            $formData = $request->all();
            $this->validateLocationData($formData);

            $user = request()->user();
            $locationData = $this->prepareLocationData($request, $formData, $user);

            $location = Location::create($locationData);

            $this->updateLocationDates($request, $locationData, $location);

            DB::commit();

            alert()->success("Succès", "Location ajoutée avec succès!!");
            return back()->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Prépare les données de location pour la création
     * 
     * @param array $formData Les données du formulaire
     * @param User $user L'utilisateur qui crée la location
     * @return array Les données préparées pour la création
     */
    private function prepareLocationData($request, array $formData, User $user): array
    {
        $room = Room::findOrFail($request->room);
        $formData["discounter"] = $room->electricity ? true : false;
        $formData["kilowater_price"] = $room->electricity ? ($room->electricity_unit_price ?? 0) : 0;
        ###___

        if ($request->pre_paid == $request->post_paid) {
            throw new \Exception("Veuillez choisir soit l'option pré-payé, soit le post-payé!");
        }

        // Traitement des fichiers
        if (isset($formData['caution_bordereau']) && $formData['caution_bordereau']) {
            $caution_bordereau = $formData['caution_bordereau'];
            $caution_bordereauName = $caution_bordereau->getClientOriginalName();
            $caution_bordereau->move("caution_bordereaus", $caution_bordereauName);
            $formData["caution_bordereau"] = asset("caution_bordereaus/" . $caution_bordereauName);
        }

        if (isset($formData['img_contrat']) && $formData['img_contrat']) {
            $img_contrat = $formData['img_contrat'];
            $img_contratName = $img_contrat->getClientOriginalName();
            $img_contrat->move("img_contrats", $img_contratName);
            $formData["img_contrat"] = asset("img_contrats/" . $img_contratName);
        }

        if (isset($formData['img_prestation']) && $formData['img_prestation']) {
            $img_prestation = $formData['img_prestation'];
            $img_prestationName = $img_contrat->getClientOriginalName();
            $img_prestation->move("img_prestations", $img_prestationName);
            $formData["img_prestation"] = asset("img_prestations/" . $img_prestationName);
        }

        // Ajout des informations supplémentaires
        $formData['owner'] = $user->id;
        $formData["loyer"] = $room->total_amount;
        $formData["pre_paid"] = $request->pre_paid ? true : false;
        $formData["post_paid"] = $request->post_paid ? true : false;
        $formData["comments"] = $request->comments ? $request->comments : "---";
        $formData["frais_peiture"] = $request->frais_peiture ? $request->frais_peiture : 0;
        $formData['status'] = self::STATUS_ACTIVE;

        // Nettoyage des données
        $formData = array_filter($formData, function ($value) {
            return $value !== null && $value !== '';
        });

        return $formData;
    }

    /**
     * Met à jour les dates importantes d'une location
     * 
     * @param Request $request
     * @param Location $location
     * @return void
     */
    private function updateLocationDates($request, $locationData, Location $location)
    {
        try {
            DB::beginTransaction();

            $effetDate = Carbon::parse($request->effet_date);

            // Calcul de la date d'échéance selon le type de paiement
            $echeanceDate = $this->calculateEcheanceDate($effetDate, $locationData["pre_paid"]);

            // Calcul de la prochaine date de loyer
            $nextLoyerDate = $this->calculateNextLoyerDate($effetDate);

            // Mise à jour des dates dans la location
            $location->update([
                'effet_date' => $effetDate,
                'echeance_date' => $echeanceDate,
                'integration_date' => $effetDate,
                'previous_echeance_date' => $echeanceDate,
                'latest_loyer_date' => $effetDate,
                'next_loyer_date' => $nextLoyerDate,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return alert()->error("Erreure lors de la mise à jour des dates", $e->getMessage());
        }
    }

    /**
     * Calcule la date d'échéance selon le type de paiement
     * 
     * @param Carbon $effetDate
     * @param bool $isPrePaid
     * @return Carbon
     */
    private function calculateEcheanceDate(Carbon $effetDate, bool $isPrePaid): Carbon
    {
        try {
            return $isPrePaid
                ? $effetDate->copy()->addMonth()  // Pre-payé: échéance = date d'effet + 1 mois
                : $effetDate->copy()->addMonths(2); // Post-payé: échéance = date d'effet + 2 mois
        } catch (\Exception $e) {
            return alert()->error("Erreure", $e->getMessage());
        }
    }

    /**
     * Calcule la prochaine date de loyer
     * 
     * @param Carbon $effetDate
     * @return Carbon
     */
    private function calculateNextLoyerDate(Carbon $effetDate): Carbon
    {
        return $effetDate->copy()->addMonth();
    }

    ###___MODIFIER UNE LOCATION
    function UpdateLocation(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = request()->user();
            $formData = $request->all();
            $location = Location::find($id);

            if (!$location) {
                throw new \Exception("Cette location n'existe pas!");
            }

            if (!auth()->user()->hasRole("Super Administrateur") && !auth()->user()->hasRole("Master")) {
                if ($location->owner != $user->id) {
                    throw new \Exception("Cette location ne vous appartient pas!");
                }
            }

            ####____TRAITEMENT DU HOUSE
            if ($request->get("house")) {
                $house = House::find($request->get("house"));
                if (!$house) {
                    throw new \Exception("Cette location maison n'existe pas!");
                }
            }

            ####____TRAITEMENT DE LA CHAMBRE
            if ($request->get("room")) {
                $room = Room::find($request->get("room"));
                if (!$room) {
                    throw new \Exception("Cette chambre n'existe pas!");
                }
            }

            ####____TRAITEMENT DU LOCATAIRE
            if ($request->get("locataire")) {
                $locataire = Locataire::find($request->get("locataire"));
                if (!$locataire) {
                    throw new \Exception("Ce locataire n'existe pas!");
                }
            }

            ####____TRAITEMENT DU TYPE DE LOCATION
            if ($request->get("type")) {
                $type = LocationType::find($request->get("type"));
                if (!$type) {
                    throw new \Exception("Ce type de location n'existe pas!");
                }
            }

            ####____TRAITEMENT DU CAUTION BORDEREAU
            if ($request->file("caution_bordereau")) {
                $caution_bordereau = $request->file("caution_bordereau");
                $caution_bordereauName = $caution_bordereau->getClientOriginalName();
                $caution_bordereau->move("caution_bordereaus", $caution_bordereauName);
                $formData["caution_bordereau"] = asset("caution_bordereaus/" . $caution_bordereauName);
            } else {
                $formData["caution_bordereau"] = $location->caution_bordereau;
            }

            ####____TRAITEMENT DE L'IMAGE DU CONTRAT
            if ($request->file("img_contrat")) {
                $img_contrat = $request->file("img_contrat");
                $img_contratName = $img_contrat->getClientOriginalName();
                $img_contrat->move("img_contrats", $img_contratName);
                $formData["img_contrat"] = asset("img_contrats/" . $img_contratName);
            } else {
                $formData["img_contrat"] = $location->img_contrat;
            }

            ####____TRAITEMENT DE L'IMAGE DE LA PRESTATION
            if ($request->file("img_prestation")) {
                $img_prestation = $request->file("img_prestation");
                $img_prestationName = $img_contrat->getClientOriginalName();
                $img_prestation->move("img_prestations", $img_prestationName);
                $formData["img_prestation"] = asset("img_prestations/" . $img_prestationName);
            } else {
                $formData["img_prestation"] = $location->img_prestation;
            }

            ####____TRAITEMENT DU STATUS DE LOCATION
            if ($request->get("status")) {
                $status = LocationStatus::find($request->get("status"));
                if (!$status) {
                    throw new \Exception("Ce status de location n'existe pas!");
                }

                #===SI LE STATUS EST **SUSPEND**=====#
                if ($request->get("status") == 2) {
                    if (!$request->get("suspend_comments")) {
                        throw new \Exception("Veuillez préciser la raison de suspenssion de cette location!");
                    }
                    $formData["suspend_date"] = now();
                    $formData["suspend_by"] = $user->id;
                }

                #===SI LE STATUS EST **MOVED**=====#
                if ($request->get("status") == 3) {
                    if (!$request->get("move_comments")) {
                        throw new \Exception("Veuillez préciser la raison de demenagement de cette location!");
                    }
                    $formData["move_date"] = now();
                    $formData["visible"] = 0;
                    $formData["delete_at"] = now();
                }
            }

            $data = array_merge($request->all(), $formData);
            $location->update($data);

            DB::commit();

            alert()->success("Succès", "Location modifiée avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Gère le déménagement d'un locataire
     * 
     * @param Request $request
     * @param int $locationId
     * @return \Illuminate\Http\RedirectResponse
     */
    function DemenageLocation(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validation des données
            $validator = Validator::make($request->all(), [
                'move_comments' => 'required|string'
            ], [
                'move_comments.required' => 'Le commentaire est requis!',
                'move_comments.string' => 'Le commentaire doit être une chaîne de caractères'
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            // Récupération de la location avec ses relations
            $location = Location::with(['House', 'Locataire'])
                ->where('visible', 1)
                ->find($request->locationId);

            if (!$location) {
                throw new \Exception("Cette location n'existe pas!");
            }

            // Vérification des paiements après arrêt des états
            $lastStateStop = HomeStopState::where('house', $location->house)
                ->latest()
                ->first();

            if ($lastStateStop) {
                $hasPaymentsAfterStop = Payement::where('location', $location->id)
                    ->where('created_at', '>', $lastStateStop->stats_stoped_day)
                    ->exists();

                if ($hasPaymentsAfterStop) {
                    throw new \Exception("Ce locataire a effectué des paiements après l'arrêt des états! Vous ne pouvez pas le démenager!");
                }
            }

            // Mise à jour de la location
            $location->update([
                'move_date' => now(),
                'status' => self::STATUS_MOVED,
                'moved_by' => auth()->id(),
                'room' => null,
                'move_comments' => $request->move_comments
            ]);

            DB::commit();
            alert()->success("Succès", "Locataire déménagé avec succès");
            return back()
                ->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    ###____ENCAISSEMENT
    function _AddPaiement(Request $request)
    {
        try {
            DB::beginTransaction();

            $formData = $request->all();
            $user = request()->user();

            // Validation des données de paiement
            $this->validatePaymentData($formData);

            // Récupération de la location avec ses relations
            $location = $this->getLocationWithRelations($formData["location"]);

            // Vérification du type de paiement
            $type = PaiementType::find($formData["type"]);
            if (!$type) {
                throw new \Exception("Ce type de paiement n'existe pas!");
            }

            // Préparation des données de paiement
            $paymentData = $this->preparePaymentData($formData, $location, $user);

            // Création de la facture
            $facture = $this->createFacture($paymentData);

            ### désormais le solde ne sera touché qu'après validation de la facture
            // Mise à jour du compte de l'agence
            // $this->updateAgencyAccount($paymentData, $location);

            // Mise à jour de la location après le paiement
            // $this->updateLocationAfterPayment($location, $formData);

            DB::commit();

            alert()->success("Succès", "Paiement ajouté avec succès!");
            return back()->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    ###____Traitement des factures
    function FactureTraitement(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $facture = Facture::findOrFail($id);

            $formData = $facture->toArray();

            // Validation des données de paiement
            $request->validate([
                "status" => "required|integer"
            ]);

            // Le solde n'est affecté que pour les factures validées
            if ($request->status == 2) {
                // Récupération de la location avec ses relations
                $location = $this->getLocationWithRelations($formData["location"]);

                // Mise à jour du compte de l'agence
                $this->updateAgencyAccount($formData, $location);

                // Mise à jour de la location après le paiement
                $this->updateLocationAfterPayment($location, $formData);
            }

            // Mise à jour du status de la facture
            $facture->update([
                "status" => $request->status,
                "facture_code" => $facture->facture_code . ' ' . 'updated'
            ]);

            DB::commit();

            alert()->success("Succès", "Traitement éffectué avec succès!");
            return back()->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Met à jour l'index de fin et calcule la consommation pour une facture d'électricité
     * 
     * @param Request $request
     * @param int $factureId
     * @return \Illuminate\Http\RedirectResponse
     */
    function ElectricityUpdateEndIndex(Request $request, $factureId)
    {
        try {
            DB::beginTransaction();

            // Validation des données
            $validator = Validator::make($request->all(), [
                'end_index' => 'required|numeric|min:0',
                'start_index' => 'required|numeric|min:0'
            ], [
                'end_index.required' => "L'index de fin est requis",
                'end_index.numeric' => "L'index de fin doit être un nombre",
                'end_index.min' => "L'index de fin ne peut pas être négatif",
                'start_index.required' => "L'index de début est requis",
                'start_index.numeric' => "L'index de début doit être un nombre",
                'start_index.min' => "L'index de début ne peut pas être négatif"
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            // Récupération de la facture avec ses relations
            $facture = LocationElectrictyFacture::with(['Location.Room'])
                ->find($factureId);

            if (!$facture) {
                throw new \Exception("Cette facture n'existe pas!");
            }

            // Vérification que l'index de fin est supérieur à l'index de début
            if ($request->end_index < $request->start_index) {
                throw new \Exception("L'index de fin ne peut pas être inférieur à l'index de début!");
            }

            // Calcul de la consommation et du montant
            $consomation = $request->end_index - $request->start_index;
            $unitPrice = (int) $facture->Location->Room->electricity_unit_price;
            $amount = $consomation * $unitPrice;

            // Mise à jour de la facture
            $facture->update([
                "end_index" => $request->end_index,
                "consomation" => $consomation,
                "amount" => $amount
            ]);

            DB::commit();

            alert()->success("Succès", "Index de fin de modifié avec succès pour la dernière facture!");
            return back()->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors($e->errors());;
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Met à jour l'index de fin et calcule la consommation pour une facture d'eau
     * 
     * @param Request $request
     * @param int $factureId
     * @return \Illuminate\Http\RedirectResponse
     */
    function WaterUpdateEndIndex(Request $request, $factureId)
    {
        try {
            DB::beginTransaction();

            // Validation des données
            $validator = Validator::make($request->all(), [
                'end_index' => 'required|numeric|min:0',
                'start_index' => 'required|numeric|min:0'
            ], [
                'end_index.required' => "L'index de fin est requis",
                'end_index.numeric' => "L'index de fin doit être un nombre",
                'end_index.min' => "L'index de fin ne peut pas être négatif",
                'start_index.required' => "L'index de début est requis",
                'start_index.numeric' => "L'index de début doit être un nombre",
                'start_index.min' => "L'index de début ne peut pas être négatif"
            ]);

            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            // Récupération de la facture avec ses relations
            $facture = LocationWaterFacture::with(['Location.Room'])
                ->find($factureId);

            if (!$facture) {
                throw new \Exception("Cette facture n'existe pas!");
            }

            // Vérification que l'index de fin est supérieur à l'index de début
            if ($request->end_index < $request->start_index) {
                throw new \Exception("L'index de fin ne peut pas être inférieur à l'index de début!");
            }

            // Calcul de la consommation et du montant
            $consomation = $request->end_index - $request->start_index;
            $unitPrice = (int) $facture->Location->Room->unit_price;
            $amount = $consomation * $unitPrice;

            // Mise à jour de la facture
            $facture->update([
                "end_index" => $request->end_index,
                "consomation" => $consomation,
                "amount" => $amount
            ]);

            DB::commit();

            alert()->success("Succès", "Index de fin modifié avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Filtre les locations par superviseur
     * 
     * @param Request $request
     * @param Agency $agency
     * @return \Illuminate\Http\RedirectResponse
     */
    function FiltreBySupervisor(Request $request, Agency $agency)
    {
        try {
            // Validation du superviseur
            $supervisor = User::find($request->supervisor);
            if (!$supervisor) {
                throw new \Exception("Ce superviseur n'existe pas!");
            }

            // Récupération des locations avec eager loading
            $locations_filtred = $agency->_Locations()
                ->with(['House.Supervisor', 'Room', 'Locataire'])
                ->where('status', '!=', 3)
                ->whereHas('House.Supervisor', function ($query) use ($request) {
                    $query->where('id', $request->supervisor);
                })
                ->get();

            if ($locations_filtred->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            alert()->success("Succès", "Locations filtrées par superviseur avec succès!");
            return back()->withInput()->with(["locations_filtred" => $locations_filtred]);
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Filtre les locations par maison
     * 
     * @param Request $request
     * @param Agency $agency
     * @return \Illuminate\Http\RedirectResponse
     */
    function FiltreByHouse(Request $request, Agency $agency)
    {
        try {
            $user = Auth::user();

            // Validation de la maison
            $house = House::find($request->house);
            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            $query = $agency->_Locations();

            // Récupération des locations avec eager loading
            $locations_filtred = $query
                ->with(['House', 'Room', 'Locataire'])
                ->where('status', '!=', 3)
                ->where('house', $request->house)
                ->get();

            if ($locations_filtred->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            alert()->success("Succès", "Locations filtrées par maison avec succès!");
            return back()->withInput()->with(["locations_filtred" => $locations_filtred]);
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Filtre les locations par propriétaire
     * 
     * @param Request $request
     * @param Agency $agency
     * @return \Illuminate\Http\RedirectResponse
     */
    function FiltreByProprio(Request $request, Agency $agency)
    {
        try {
            // Validation du propriétaire
            $proprietor = Proprietor::find($request->proprio);
            if (!$proprietor) {
                throw new \Exception("Ce propriétaire n'existe pas!");
            }

            // Récupération des locations avec eager loading
            $locations_filtred = $agency->_Locations()
                ->with(['House.Proprietor', 'Room', 'Locataire'])
                ->where('status', '!=', 3)
                ->whereHas('House.Proprietor', function ($query) use ($request) {
                    $query->where('id', $request->proprio);
                })
                ->get();

            if ($locations_filtred->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            alert()->success("Succès", "Locations filtrées par propriétaire avec succès!");
            return back()->withInput()->with(["locations_filtred" => $locations_filtred]);
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Imprime toutes les locations par superviseur
     * 
     * @param Request $request
     * @param string $agencyId
     * @return \Illuminate\View\View
     */
    function PrintAllLocationBySupervisor(Request $request, $agencyId)
    {
        try {
            Session::forget("imprimUnPaidLocations");

            $agency = Agency::find(deCrypId($agencyId));
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $superviseur = User::find($request->supervisor);
            if (!$superviseur) {
                throw new \Exception("Ce superviseur n'existe pas!");
            }

            $locations = Location::with(['House.Supervisor', 'Room', 'Locataire'])
                ->where('visible', 1)
                ->whereHas('House.Supervisor', function ($query) use ($request) {
                    $query->where('id', $request->supervisor);
                })
                ->get();

            if ($locations->isEmpty()) {
                throw new \Exception("Aucune location trouvée pour ce superviseur");
            }

            return view("imprimer_locations", compact("locations", "superviseur"));
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    // IMPRESSION
    function Imprimer(Request $request, $locationId)
    {
        try {
            // Validation de l'ID de location
            $location = Location::with(['Room', 'Locataire', 'House', 'Type', 'Status'])
                ->where("visible", 1)
                ->find(deCrypId($locationId));

            if (!$location) {
                throw new \Exception("Cette location n'existe pas ou n'est plus active!");
            }

            // Vérification des relations nécessaires
            if (!$location->Room || !$location->Locataire || !$location->House) {
                throw new \Exception("Données de location incomplètes!");
            }

            // Génération du PDF
            $pdf = Pdf::loadView('imprimer_location', compact("location"));

            // Configuration du PDF pour un meilleur rendu
            // Set PDF orientation to landscape
            $pdf->setPaper('a4', 'landscape');

            return $pdf->stream();
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Imprime les locations impayées par superviseur
     * 
     * @param Request $request
     * @param string $agencyId
     * @return \Illuminate\View\View
     */
    function PrintUnPaidLocationBySupervisor(Request $request, $agencyId)
    {
        try {
            $agency = Agency::find(deCrypId($agencyId));
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $superviseur = User::find($request->supervisor);
            if (!$superviseur) {
                throw new \Exception("Ce superviseur n'existe pas!");
            }

            $now = now();
            $locations = Location::with(['House.Supervisor', 'Room', 'Locataire'])
                ->where('visible', 1)
                ->where('status', '!=', 3)
                ->whereHas('House.Supervisor', function ($query) use ($request) {
                    $query->where('id', $request->supervisor);
                })
                ->where('echeance_date', '<', $now)
                ->get();

            if ($locations->isEmpty()) {
                throw new \Exception("Aucune location impayée trouvée pour ce superviseur");
            }

            Session::put("imprimUnPaidLocations", true);
            return view("imprimer_locations", compact("locations", "superviseur"));
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Filtre les paiements après la date d'arrêt des états
     * 
     * @param Request $request
     * @param string $houseId
     * @return \Illuminate\View\View
     */
    function FiltreAfterStateDateStoped(Request $request, $houseId)
    {
        try {
            // Récupération de la maison avec ses relations
            $house = House::with(['States.Factures' => function ($query) {
                $query->with(['Location.Locataire']);
            }])->find(deCrypId($houseId));

            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            $last_state = $house->States->last();
            if (!$last_state) {
                throw new \Exception("Aucun état n'a été arrêté dans cette maison!");
            }

            $state_stop_date = $last_state->stats_stoped_day;

            // Utilisation des collections pour un traitement plus efficace
            $factures = $last_state->Factures->filter(function ($facture) use ($state_stop_date) {
                return $facture->created_at > $state_stop_date;
            });

            // Transformation des données avec map
            $locators_that_paid_after_state_stoped_day = $factures->map(function ($facture) {
                return [
                    "name" => $facture->Location->Locataire->name,
                    "prenom" => $facture->Location->Locataire->prenom,
                    "email" => $facture->Location->Locataire->email,
                    "phone" => $facture->Location->Locataire->phone,
                    "adresse" => $facture->Location->Locataire->adresse,
                    "comments" => $facture->Location->Locataire->comments,
                    "payement_date" => $facture->created_at,
                    "month" => $facture->created_at,
                    "amount_paid" => $facture->amount
                ];
            })->values();

            // Calcul des totaux avec les méthodes de collection
            $amount_total_to_paid_after = $locators_that_paid_after_state_stoped_day->sum('amount_paid');

            $locationsFiltered = [
                "afterStopDate" => $locators_that_paid_after_state_stoped_day,
                "afterStopDateTotal_to_paid" => $amount_total_to_paid_after,
                "total_locators" => $locators_that_paid_after_state_stoped_day->count()
            ];

            // Pour imprimer
            if ($request->imprimer) {
                set_time_limit(0);
                $pdf = Pdf::loadView("imprimer-locators-after-stop-state", [
                    "locationsFiltered" => $locationsFiltered,
                    "house" => $house
                ]);

                // Set PDF orientation to landscape
                $pdf->setPaper('a4', 'landscape');

                return $pdf->stream();
            }

            return view("locators.locator-after-stop-date", compact("locationsFiltered", "house"));
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Filtre les paiements avant la date d'arrêt des états
     * 
     * @param Request $request
     * @param string $houseId
     * @return \Illuminate\View\View
     */
    function FiltreBeforeStateDateStoped(Request $request, $houseId)
    {
        try {
            // Récupération de la maison avec ses relations
            $house = House::with(['States.Factures' => function ($query) {
                $query->with(['Location.Locataire']);
            }])->find(deCrypId($houseId));

            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            $last_state = $house->States->last();
            if (!$last_state) {
                throw new \Exception("Aucun état n'a été arrêté dans cette maison!");
            }

            $state_stop_date = $last_state->stats_stoped_day;

            // Utilisation des collections pour un traitement plus efficace
            $factures = $last_state->Factures->filter(function ($facture) use ($state_stop_date) {
                return $facture->created_at < $state_stop_date;
            });

            // Transformation des données avec map
            $locators_that_paid_before_state_stoped_day = $factures->map(function ($facture) {
                return [
                    "name" => $facture->Location->Locataire->name,
                    "prenom" => $facture->Location->Locataire->prenom,
                    "email" => $facture->Location->Locataire->email,
                    "phone" => $facture->Location->Locataire->phone,
                    "adresse" => $facture->Location->Locataire->adresse,
                    "comments" => $facture->Location->Locataire->comments,
                    "payement_date" => $facture->created_at,
                    "month" => $facture->created_at,
                    "amount_paid" => $facture->amount
                ];
            })->values();

            // Calcul des totaux avec les méthodes de collection
            $amount_total_to_paid_before = $locators_that_paid_before_state_stoped_day->sum('amount_paid');

            $locationsFiltered = [
                "beforeStopDate" => $locators_that_paid_before_state_stoped_day,
                "beforeStopDateTotal_to_paid" => $amount_total_to_paid_before,
                "total_locators" => $locators_that_paid_before_state_stoped_day->count()
            ];

            // Pour imprimer
            if ($request->imprimer) {
                set_time_limit(0);
                // dd($request->imprimer);
                $pdf = Pdf::loadView("imprimer-locators-before-stop-state", [
                    "locationsFiltered" => $locationsFiltered,
                    "house" => $house
                ]);

                // Set PDF orientation to landscape
                $pdf->setPaper('a4', 'landscape');

                return $pdf->stream();
            }

            return view("locators.locator-before-stop-date", compact("locationsFiltered", "house"));
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Affiche les statistiques de prestation
     * 
     * @param Request $request
     * @param string $agencyId
     * @return \Illuminate\View\View
     */

    function _ShowPrestationStatistique(Request $request, $agencyId)
    {
        try {
            $agency = Agency::find($agencyId);
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $locations = $agency->_Locations()
                ->with(['Owner', 'House', 'Locataire', 'Type', 'Status', 'Room'])
                ->get();

            $prestations = $locations->pluck('prestation')->filter()->values();

            // return view("prestation-statistique", compact("locations", "prestations", "agency"));
            $pdf = Pdf::loadView("prestation-statistique", compact("locations", "prestations", "agency"));

            // Set PDF orientation to landscape
            $pdf->setPaper('a4', 'landscape');

            return $pdf->stream();
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Affiche les cautions par agence
     * 
     * @param Request $request
     * @param string $agencyId
     * @return \Illuminate\View\View
     */

    function _ShowCautionsByAgency(Request $request, $agencyId)
    {
        try {
            $query = Location::with(['Owner', 'House', 'Locataire', 'Type', 'Status', 'Room']);

            if ($agencyId !== "admin") {
                $query->where('agency', $agencyId);
            }

            $locations = $query->get();

            $cautions = [
                'eau' => $locations->pluck('caution_water')->sum(),
                'electricity' => $locations->pluck('caution_electric')->sum(),
                'loyer' => $locations->sum(function ($location) {
                    return $location->caution_number * $location->loyer;
                })
            ];

            // return view("cautions", compact("locations", "cautions"));

            $pdf = Pdf::loadView("cautions", compact("locations", "cautions"));

            // Set PDF orientation to landscape
            $pdf->setPaper('a4', 'landscape');

            return $pdf->stream();
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Affiche les cautions par période
     * 
     * @param Request $request
     * @param string $first_date
     * @param string $last_date
     * @return \Illuminate\View\View
     */
    function _ShowCautionsByPeriod(Request $request, $first_date, $last_date)
    {
        try {
            $locations = Location::with(['Owner', 'House', 'Locataire', 'Type', 'Status', 'Room'])
                ->whereBetween('created_at', [$first_date, $last_date])
                ->get();

            $cautions = [
                'eau' => $locations->pluck('caution_water')->sum(),
                'electricity' => $locations->pluck('caution_electric')->sum(),
                'loyer' => $locations->sum(function ($location) {
                    return $location->caution_number * $location->loyer;
                })
            ];

            return view("cautions", compact("locations", "cautions"));
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Affiche les cautions par maison
     * 
     * @param Request $request
     * @param string $houseId
     * @return \Illuminate\View\View
     */
    function _ShowCautionsByHouse(Request $request, $houseId)
    {
        try {
            $house = House::find($houseId);
            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            $locations = $house->Locations()
                ->with(['Owner', 'House', 'Locataire', 'Type', 'Status', 'Room'])
                ->get();

            $cautions = [
                'eau' => $locations->pluck('caution_water')->sum(),
                'electricity' => $locations->pluck('caution_electric')->sum(),
                'loyer' => $locations->sum(function ($location) {
                    return $location->caution_number * $location->loyer;
                })
            ];

            return view("cautions", compact("locations", "cautions"));
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Affiche les cautions par maison et période
     * 
     * @param Request $request
     * @param string $houseId
     * @param string $first_date
     * @param string $last_date
     * @return \Barryvdh\DomPDF\Facade\Pdf
     */
    function _ShowCautionsForHouseByPeriod(Request $request, $houseId, $first_date, $last_date)
    {
        try {
            $locations = Location::with(['Owner', 'House', 'Locataire', 'Type', 'Status', 'Room'])
                ->where('house', $houseId)
                ->whereBetween('created_at', [$first_date, $last_date])
                ->get();

            $cautions = [
                'eau' => $locations->pluck('caution_water')->sum(),
                'electricity' => $locations->pluck('caution_electric')->sum(),
                'loyer' => $locations->sum(function ($location) {
                    return $location->caution_number * $location->loyer;
                })
            ];

            $pdf = Pdf::loadView('cautions', compact("locations", "cautions"));

            // Set PDF orientation to landscape
            $pdf->setPaper('a4', 'landscape');

            return $pdf->stream();
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }
}
