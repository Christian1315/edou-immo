<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyAccountSold;
use App\Models\City;
use App\Models\Country;
use App\Models\Departement;
use App\Models\Facture;
use App\Models\HomeStopState;
use App\Models\House;
use App\Models\HouseType;
use App\Models\Location;
use App\Models\Proprietor;
use App\Models\Quarter;
use App\Models\User;
use App\Models\Zone;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HouseController extends Controller
{
    private const SUPERVISOR_ROLE_ID = 3;
    private const DEFAULT_COMMISSION = 10;
    private const STATE_STOP_DAYS = 5;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware(['auth']);
        set_time_limit(0);
    }

    /**
     * Get house validation rules
     * 
     * @return array
     */
    public static function house_rules(): array
    {
        return [
            'agency' => ['required'],
            'name' => ['required'],
            'proprio_payement_echeance_date' => ['required', "date"],
            'proprietor' => ['required', "integer"],
            'type' => ['required', "integer"],
            'city' => ['required', "integer"],
            'country' => ['required', "integer"],
            'departement' => ['required', "integer"],
            'quartier' => ['required', "integer"],
            'zone' => ['required', "integer"],
            'supervisor' => ['required', "integer"],
        ];
    }

    /**
     * Get house validation messages
     * 
     * @return array
     */
    public static function house_messages(): array
    {
        return [
            'agency.required' => "L'agence est réquise",
            'proprio_payement_echeance_date.required' => "La date d'écheance du payement du propriétaire est réquise!",
            'proprio_payement_echeance_date.date' => "Ce champ doit être de type date",
            'proprietor.required' => "Le propriétaire est réquis",
            'type.required' => "Le type de la chambre est réquis",
            'city.required' => "La ville est réquise",
            'country.required' => "Le Pays est réquis",
            'departement.required' => "Le departement est réquis",
            'quartier.required' => "Le quartier est réquis",
            'zone.required' => "La zone est réquise",
            'supervisor.required' => "Le superviseur est réquis",
            'proprietor.integer' => 'Ce champ doit être de type entier!',
            'type.integer' => 'Ce champ doit être de type entier!',
            'city.integer' => 'Ce champ doit être de type entier!',
            'country.integer' => 'Ce champ doit être de type entier!',
            'departement.integer' => 'Ce champ doit être de type entier!',
            'quartier.integer' => 'Ce champ doit être de type entier!',
            'zone.integer' => 'Ce champ doit être de type entier!',
            'supervisor.integer' => 'Ce champ doit être de type entier!',
        ];
    }

    /**
     * Add a new house
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function _AddHouse(Request $request)
    {
        DB::beginTransaction();
        try {
            $formData = $this->validateHouseData($request);
            $user = $request->user();

            $this->validateRelatedEntities($formData);

            $formData = $this->processHouseData($request, $formData, $user);

            House::create($formData);

            DB::commit();
            alert()->success("Succès", "Maison ajoutée avec succès");
            return redirect()->back()->withInput();
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validation adding house: ' . $e->getMessage());
            alert()->error("Echec", "Erreure de validation");
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding house: ' . $e->getMessage());
            alert()->error("Echec", "Une erreur est survenue lors de l'ajout de la maison. " . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Validate house data
     * 
     * @param Request $request
     * @return array
     */
    private function validateHouseData(Request $request): array
    {
        return Validator::make(
            $request->all(),
            self::house_rules(),
            self::house_messages()
        )->validate();
    }

    /**
     * Validate related entities
     * 
     * @param array $formData
     * @throws \Exception
     */
    private function validateRelatedEntities(array $formData): void
    {
        $entities = [
            'proprietor' => Proprietor::class,
            'type' => HouseType::class,
            'city' => City::class,
            'country' => Country::class,
            'departement' => Departement::class,
            'quartier' => Quarter::class,
            'zone' => Zone::class,
            'supervisor' => User::class,
        ];

        foreach ($entities as $key => $model) {
            $entity = $model::find($formData[$key]);
            if (!$entity) {
                throw new \Exception("L'entité {$key} n'existe pas!");
            }

            if ($key === 'supervisor' && !$entity->hasRole("Superviseur")) {
                throw new \Exception("L'utilisateur choisi comme superviseur ne dispose pas du rôle superviseur!");
            }
        }
    }

    /**
     * Process house data
     * @param Request $request
     * @param array $formData
     * @param User $user
     * @return array
     */

    private function processHouseData(Request $request, array $formData, User $user): array
    {
        if ($request->pre_paid == $request->post_paid) {
            throw new \Exception("Veuillez choisir soit l'option *prepayée* ou *postpayée*");
        }

        $formData["pre_paid"] = $request->pre_paid ? true : false;
        $formData["post_paid"] = $request->post_paid ? true : false;
        $formData["commission_percent"] = $request->commission_percent ?? self::DEFAULT_COMMISSION;
        $formData["locative_commission"] = $request->locative_commission ?? self::DEFAULT_COMMISSION;
        $formData["owner"] = $user->id;

        if ($request->hasFile('image')) {
            $image = $request->file("image");
            $image_name = $image->getClientOriginalName();
            $image->move("houses_images", $image_name);
            $formData["image"] = asset("houses_images/" . $image_name);
        }

        return $formData;
    }

    /**
     * Filter houses by supervisor
     * @param Request $request
     * @param int $agency
     * @return \Illuminate\Http\RedirectResponse
     */

    public function FiltreHouseBySupervisor(Request $request, $agency)
    {
        try {
            $agency = Agency::findOrFail($agency);
            $user = Auth::user();

            if ($user->hasRole("Gestionnaire de compte")) {
                /** Pour une Gestionnaire de compte, on recupère juste les 
                 * maisons de ses superviseurs
                 */

                $supervisorsIds = $user->supervisors->pluck("id")
                    ->toArray();

                $houses = $agency->_Houses
                    ->whereIn("supervisor", $supervisorsIds);
            } else {
                $houses = $agency->_Houses;
            }

            /** */
            $houses = $houses->where("supervisor", $request->supervisor);

            if ($houses->isEmpty()) {
                alert()->error("Echèc", "Aucun résultat trouvé");
                return back()->withInput();
            }

            session()->flash("filteredHouses", $houses);
            alert()->success("Succès", "Maisons filtrées par superviseur avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            Log::error('Error filtering houses by supervisor: ' . $e->getMessage());
            alert()->error("Echec", "Une erreur est survenue lors du filtrage des maisons");
            return back()->withInput();
        }
    }

    ###___FILTRE BY PERIOD
    function FiltreHouseByPeriod(Request $request, $agency)
    {
        try {
            $user = request()->user();
            $agency = Agency::find($agency);

            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $user = Auth::user();
            if ($user->hasRole("Gestionnaire de compte")) {
                /** Pour une Gestionnaire de compte, on recupère juste les 
                 * maisons de ses superviseurs
                 */
                $supervisorsIds = $user->supervisors->pluck("id")
                    ->toArray();

                $houses = $agency->_Houses
                    ->whereIn("supervisor", $supervisorsIds);
            } else {
                $houses = $agency->_Houses;
            }

            $houses = $houses->whereBetween("created_at", [$request->debut, $request->fin]);

            if (count($houses) == 0) {
                throw new \Exception("Aucun résultat trouvé");
            }

            session()->flash("filteredHouses", $houses);

            $debut = \Carbon\Carbon::parse($request->debut)->locale('fr')->isoFormat('MMMM YYYY');
            $fin = \Carbon\Carbon::parse($request->fin)->locale('fr')->isoFormat('MMMM YYYY');
            $msg = "Maisons filtrées par période du $debut au $fin avec succès!";
            alert()->success("Succès", $msg);
            return back()->withInput();
        } catch (\Exception $e) {
            Log::error('Error filtering houses by period: ' . $e->getMessage());
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    ###___ADD HOUSE TYPE
    function AddHouseType(Request $request)
    {
        Validator::make(
            $request->all(),
            [
                "name" => 'required',
                "description" => 'required',
            ],
            [
                "name.required" => "Le nom est requis",
                "description.required" => "La description est requise"
            ]
        )->validate();

        HouseType::create($request->all());

        alert()->success("Succès", "Type de maison ajouté avec succès");
        return redirect()->back()->withInput();
    }

    #GENERER CAUTIONS PAR PERIODE
    function GenerateCautionByPeriod(Request $request, $houseId)
    {
        try {
            $house = House::find($houseId);
            if (!$house) {
                throw new \Exception("Désolé! Cette maison n'existe pas!");
            }

            ##__
            $formData = $request->all();

            ###__
            Validator::make(
                $formData,
                [
                    "first_date" => ["required", "date"],
                    "last_date" => ["required", "date"],
                ],
                [
                    "first_date.required" => "Ce Champ est réquis!",
                    "last_date.required" => "Ce Champ est réquis!",

                    "first_date.date" => "Ce Champ est une date!",
                    "last_date.date" => "Ce Champ est une date!",
                ]
            )->validate();

            ##__
            $data["caution_html_url"] = env("APP_URL") . "/$house->id/" . $formData['first_date'] . "/" . $formData['last_date'] . "/caution_html_for_house_by_period";

            alert()->success("Succès", "Cautions generées en pdf avec succès!");
            alert()->html('<b>Succès</b>', "Cautions generées en pdf avec succès, <a target='__blank' href=" . $data['caution_html_url'] . ">Ouvrez le lien ici</a>", 'success');

            return back()->withInput();
        } catch (\Exception $e) {
            Log::error('Error generating caution by period: ' . $e->getMessage());
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    #ARRETER LES ETATS DES MAISON
    function PostStopHouseState(Request $request, $houseId)
    {
        DB::beginTransaction();
        try {
            $user = request()->user();
            $formData = $request->all();
            $formData["owner"] = $user->id;

            $house = House::with(["Locations", "Proprietor"])
                ->where(["visible" => 1])
                ->find(deCrypId($houseId));

            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            if (count($house->Locations) == 0) {
                throw new \Exception("Cette maison n'appartient à aucune location! Son arrêt d'état ne peut donc être éffectué");
            }

            $state = $this->createOrUpdateHouseState($formData, $house);
            $this->updateFacturesAndAccounts($house, $state);
            $this->sendNotificationToProprietor($house, $formData["recovery_rapport"]);

            DB::commit();
            alert()->success("Succès", "Arrêt d'état effectué avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error stopping house state: ' . $e->getMessage());
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Create or update house state
     * @param array $formData
     * @param House $house
     * @return HomeStopState
     */
    private function createOrUpdateHouseState(array $formData, House $house): HomeStopState
    {
        $this_house_state = HomeStopState::orderBy("id", "desc")
            ->where(["house" => $house->id])
            ->first();

        if (!$this_house_state) {
            return HomeStopState::create([
                "owner" => $formData["owner"],
                "house" => $house->id,
                "recovery_rapport" => $formData["recovery_rapport"],
                "stats_stoped_day" => now()
            ]);
        }

        // $precedent_arret_date = strtotime($this_house_state->stats_stoped_day);
        // $now = strtotime(now());
        // $twenty_days = self::STATE_STOP_DAYS * 24 * 3600;

        // if ($now < ($precedent_arret_date + $twenty_days)) {
        //     throw new \Exception("La précedente date d'arrêt des états de cette maison ne depasse pas encore " . self::STATE_STOP_DAYS . " jours!");
        // }

        return HomeStopState::create([
            "owner" => $formData["owner"],
            "house" => $house->id,
            "recovery_rapport" => $formData["recovery_rapport"],
            "stats_stoped_day" => now()
        ]);
    }

    /**
     * Update factures and accounts
     * 
     * @param House $house
     * @param HomeStopState $state
     * @return void
     */

    private function updateFacturesAndAccounts(House $house, HomeStopState $state): void
    {
        foreach ($house->Locations as $location) {
            // Update factures
            foreach ($location->Factures as $facture) {
                if (!$facture->state) {
                    $facture->state = $state->id;
                    $facture->save();
                }
            }

            // Create state facture
            Facture::create([
                "owner" => $state->owner,
                "location" => $location->id,
                "amount" => 0,
                "state_facture" => 1,
                "state" => $state->id,
            ]);

            // Update account movements
            foreach ($house->AllStatesDepenses as $account) {
                if (!$account->state) {
                    $account->state = $state->id;
                    $account->save();
                }
            }
        }
    }

    /**
     * Send notification to proprietor
     * 
     * @param House $house
     * @param string $recoveryRapport
     * @return void
     */
    private function sendNotificationToProprietor(House $house, string $recoveryRapport): void
    {
        try {
            Send_Notification_Via_Mail(
                $house->Proprietor->email,
                "Etat de récouvrement",
                "L'état de récouvrement de la maison " . $house->name . " vient d'être arrêté! Voici un rapport de recouvrement qui l'accompagne :" . $recoveryRapport
            );
        } catch (\Exception $e) {
            Log::error('Error sending notification to proprietor: ' . $e->getMessage());
        }
    }

    function UpdateHouse(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = request()->user();
            $formData = $request->all();

            $house = House::where(["visible" => 1])->find($id);
            if (!$house) {
                throw new \Exception("Cette Maison n'existe pas!");
            }

            $data = $this->processHouseData($request, $formData, $user);
            $house->update($data);

            // suppression des caches liés au chatgement de la page Paiements propriétaires
            Cache::forget('house_detail_last_state_' . $house->id);

            DB::commit();
            alert()->success("Succès", "Maison modifiée avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating house: ' . $e->getMessage());
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    function DeleteHouse(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $house = House::find(deCrypId($id));
            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            if (count($house->Rooms) > 0) {
                throw new \Exception("Cette maison dispose des chambres");
            }

            if (count($house->Locations) > 0) {
                throw new \Exception("Cette maison dispose de location(s)! Veuillez bien les supprimer d'abord");
            }

            $house->delete();

            DB::commit();
            alert()->success("Succès", "Maison supprimée avec succès!");
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting house: ' . $e->getMessage());
            alert()->error("Echec", $e->getMessage());
            return back();
        }
    }

    ####____SHOW HOUSE STOP PAGE
    function StopHouseState(Request $request, $houseId, $agencyId)
    {
        $agency = Agency::where("visible", true)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
            return back();
        }

        $house = House::where("visible", true)->find(deCrypId($houseId));
        if (!$house) {
            alert()->error("Echec", "Cette maison n'existe pas!");
            return back();
        }

        ####_____
        return view("admin.stop-house-state", compact(["house", "agency"]));
    }

    /**
     * Show house state print
     * 
     * @param Request $request
     * @param int $houseId
     * @return mixed
     */

    public function ShowHouseStateImprimeHtml(Request $request, $houseId)
    {
        try {
            set_time_limit(3600);

            $house = $this->getHouseWithRelations($houseId);
            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            $data = $this->prepareHouseStateData($house);

            /**locataires ayant payés dans l'etat */
            $paid_locataires = collect($data["paid_locataires"]);

            /**locataires a jour */

            /**locataires non payer mais à jour */
            $getNonPayerEtAJour = collect($data["getNonPayerEtAJour"]);

            /**locataires payés et (locataires non payé mais ajour) */
            $unPaidLocatairesPlusLocataireAjour = $paid_locataires->concat($getNonPayerEtAJour)
                // ->unique()
            ;

            $pdf = Pdf::loadView('house-state', array_merge($data, [
                "house" => $data["house"],
                "locations" => $data["locations"],
                "state" => $data["state"],
                "unPaidLocatairesPlusLocataireAjour" => $unPaidLocatairesPlusLocataireAjour->count(),
                // "un_paid_locataires" => $data["un_paid_locataires"],
                "free_rooms" => $data["free_rooms"],
            ]));

            // Set PDF orientation to landscape
            $pdf->setPaper('a4', 'landscape');

            return $pdf->stream();
        } catch (\Exception $e) {
            Log::error('Error showing house state: ' . $e->getMessage());
            alert()->error("Echec", "Une erreur est survenue lors de l'affichage de l'état de la maison " . $e->getMessage());
            return back();
        }
    }

    /**
     * Get house with relations
     * 
     * @param int $houseId
     * @return House|null
     */

    private function getHouseWithRelations($houseId): ?House
    {
        return House::with([
            "Locations",
            "Rooms",
            "PayementInitiations",
            "Proprietor",
            "Supervisor",
            "CurrentDepenses",
            "PayementInitiations",
        ])
            ->where("visible", 1)
            ->find(deCrypId($houseId));
    }

    /**
     * Prepare house state data
     * 
     * @param House $house
     */
    private function prepareHouseStateData(House $house): array
    {
        $free_rooms = $house->Rooms->filter(fn($room) => count($room->Locations) == 0);
        $house_last_state = $house->States->last();

        if (!$this->validateHouseState($house, $house_last_state)) {
            throw new \Exception("Cette maison ne dispose d'aucun arrêt d'état");
        }

        $locations = $house->Locations->where("status", "!=", 3);
        $stateData = $this->calculateStateData($house, $locations, $house_last_state);

        $data = array_merge($stateData, [
            "house" => $house,
            "locations" => $locations,
            "state" => $house_last_state,
            "state" => $house_last_state,
            "free_rooms" => $free_rooms
        ]);

        return $data;
    }

    /**
     * Validate house state
     * 
     * @param House $house
     * @param HomeStopState|null $lastState
     * @return bool
     */
    private function validateHouseState(House $house, ?HomeStopState $lastState): bool
    {
        if (!$lastState) {
            if ($house->PayementInitiations->last() && $house->PayementInitiations->last()->state) {
                return false;
            }
            return false;
        }
        return true;
    }

    /**
     * Calculate state data
     * 
     * @param House $house
     * @param Collection $locations
     * @param HomeStopState $lastState
     * @return array
     */
    private function calculateStateData(House $house, $locations, HomeStopState $lastState): array
    {
        $stateData = $this->calculateFinancialData($house, $locations, $lastState);
        $stateData['paid_locataires'] = $this->getPaidLocataires($locations, $lastState);
        $stateData['un_paid_locataires'] = $this->getUnpaidLocataires($locations, $lastState);
        $stateData["paidLocators"] = $this->paidLocators($locations, $lastState);
        $stateData["getNonPayerEtAJour"] = $this->getNonPayerEtAJour($locations, $lastState);

        return $stateData;
    }

    /**
     * LOCATAIRES A JOUR
     * @param Collection $locations
     * @return array
     */

    function paidLocators($locations, HomeStopState $lastState): array
    {
        return $locations
            ->filter(function ($location) use ($lastState) {
                return Carbon::parse($location->latest_loyer_date)->format("m/Y") <= Carbon::parse($lastState->stats_stoped_day)->format("m/Y");
            })->toArray();

        // return [];
    }

    /**
     * Calculate financial data for a house
     * 
     * @param House $house
     * @param Collection $locations
     * @param HomeStopState $lastState
     * @return array
     */
    private function calculateFinancialData(House $house, $locations, HomeStopState $lastState)
    {
        try {
            $totalRevenue = 0;
            $totalExpenses = 0;
            $totalCommission = 0;
            $locativeCharge = 0;

            // Calculate totals from locations
            foreach ($locations as $location) {
                // Calculate revenue from factures using collection methods
                $factures = $this->getStateFactures(
                    $lastState,
                    $location,
                    $house
                );
                $totalRevenue += $factures->sum('amount');
            }

            // transforme $locations
            $locations->map(function ($location) use ($lastState, $house, $totalRevenue) {
                // Calculate revenue from factures using collection methods
                $factures = $this->getStateFactures(
                    $lastState,
                    $location,
                    $house
                );

                $location["_locataire"] = $location->Locataire;
                $location["nbr_facture_amount_paid"] = $factures->count();
                $location["facture_amount_paid"] = $factures->sum("amount");
                return $location;
            });

            // Calculate expenses from account movements
            $totalExpenses += $lastState->CdrAccountSolds->sum("sold_retrieved");

            // dd($house->Locations);
            // Locative charge
            $locativeCharge += $house->LocativeCharge();

            // Calculate commission
            $totalCommission = ($totalRevenue * $house->commission_percent) / 100;

            // dd($house->locative_commission);
            // Calculate commission charge locative
            $chargeCommission = ($locativeCharge * $house->locative_commission) / 100;

            // Calculate net amount
            // $netAmount = $totalRevenue - ($totalExpenses + $totalCommission + $chargeCommission);
            $netAmount = $totalRevenue - ($totalExpenses + $totalCommission);

            return [
                'total_revenue' => $totalRevenue,
                'total_expenses' => $totalExpenses,
                'total_commission' => $totalCommission,
                'locative_commission' => $chargeCommission,
                'net_amount' => $netAmount,
                'locativeCharge' => $locativeCharge
            ];
        } catch (\Exception $e) {
            Log::error('Error calculating financial data: ' . $e->getMessage());
            throw new \Exception("Erreur lors du calcul des données financières: " . $e->getMessage());
        }
    }

    /**
     * Get list of tenants who have paid their bills
     * 
     * @param HomeStopState $state
     * @param Location $location
     * @param House $house
     * @return Collection
     */
    private function getStateFactures(HomeStopState $state, Location $location, House $house): Collection
    {
        if ($state) {
            $factures = Facture::where([
                "location" => $location->id,
                "state" => $state->id,
                "state_facture" => 0,
                "status" => 2, //seules les factures validées
            ])->get();
        } else {
            $factures = Facture::where([
                "location" => $location->id,
                "old_state" => $house->PayementInitiations->last()?->old_state,
                "state_facture" => 0,
                "status" => 2, //seules les factures validées
            ])->get();
        }

        return $factures;
    }

    /**
     * Get list of tenants who have paid their bills
     * @param Collection $locations
     * @param HomeStopState $lastState
     */

    private function getPaidLocataires($locations, HomeStopState $lastState): array
    {
        try {
            return $locations->filter(function ($location) use ($lastState) {
                return $this->getStateFactures($lastState, $location, $lastState->House)->count() > 0;
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error getting paid tenants: ' . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des locataires payés: " . $e->getMessage());
        }
    }

    /**
     * Get list of tenants who have not paid their bills
     * 
     * @param Collection $locations
     * @param HomeStopState $lastState
     */
    private function getUnpaidLocataires($locations, HomeStopState $lastState): array
    {
        try {
            return $locations->filter(function ($location) use ($lastState) {
                return $this->getStateFactures($lastState, $location, $lastState->House)->count() == 0;
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error getting unpaid tenants: ' . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des locataires impayés: " . $e->getMessage());
        }
    }

    /**
     * Non payés mais à jour
     * 
     * @param Collection $locations
     * @param HomeStopState $lastState
     */
    private function getNonPayerEtAJour($locations, HomeStopState $lastState): array
    {
        try {
            return $locations->filter(function ($location) use ($lastState) {
                return (
                    /**Locations non payés & Locations à jour */
                    $this->getStateFactures($lastState, $location, $lastState->House)
                    ->count() == 0 &&
                    (Carbon::parse($location->latest_loyer_date)->format("m/Y") >= Carbon::parse($lastState->stats_stoped_day)->format("m/Y"))
                );
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error getting unpaid tenants: ' . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des locataires impayés: " . $e->getMessage());
        }
    }
}
