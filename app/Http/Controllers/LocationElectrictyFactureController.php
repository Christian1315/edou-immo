<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyAccount;
use App\Models\AgencyAccountSold;
use App\Models\House;
use App\Models\Location;
use App\Models\LocationElectrictyFacture;
use App\Models\Proprietor;
use App\Models\StopHouseElectricityState;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LocationElectrictyFactureController extends Controller
{
    private const DAYS_BETWEEN_STATE_STOPS = 5;
    private const SECONDS_IN_DAY = 86400;

    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    static function electricity_factures_rules(): array
    {
        return [
            'location' => ['required', "integer"],
            'end_index' => ['required', "numeric"],
        ];
    }

    static function electricity_factures_messages(): array
    {
        return [
            'location.required' => "Veillez préciser la location!",
            'location.integer' => "La location doit être un entier",

            'end_index.required' => "L'index de fin est réquis!",
            'end_index.numeric' => "L'index de fin doit être de type numérique!",
        ];
    }

    function _GenerateFacture(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $formData = $request->all();
            $this->validateElectricityFacture($formData);

            $location = $this->getLocation($formData['location']);
            if (!$location) {
                DB::rollBack();
                return $this->handleError("Cette location n'existe pas!");
            }

            $factureData = $this->prepareFactureData($formData, $location);
            if (!$factureData) {
                DB::rollBack();
                return back()->withInput();
            }

            LocationElectrictyFacture::create($factureData);

            DB::commit();
            alert()->success("Succès", "Facture d'électricité générée avec succès!!");

            return back()->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Erreur de base de données lors de la génération de facture: ' . $e->getMessage());
            return $this->handleError("Une erreur est survenue lors de la génération de la facture. Veuillez réessayer.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur inattendue lors de la génération de facture: ' . $e->getMessage());
            return $this->handleError("Une erreur inattendue est survenue. Veuillez réessayer.");
        }
    }


    ######____PAYEMENT DE FACTURE D'ELECTRICITE
    function _FacturePayement(Request $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $facture = $this->getFacture($id);
            if (!$facture) {
                DB::rollBack();
                return $this->handleError("Cette facture n'existe pas!");
            }

            $agency = $facture->Location->_Agency;
            $agencyAccount = $this->getAgencyAccount($agency->id);

            Log::debug("Agency", ["data" => $agency]);
            Log::debug("Agency Account", ["data" => $agencyAccount]);

            if (!$agencyAccount) {
                DB::rollBack();
                return $this->handleError("Ce compte d'agence n'existe pas! Vous ne pouvez pas le créditer!");
            }

            if (!$this->canCreditAccount($agencyAccount, $facture->amount)) {
                DB::rollBack();
                return back()->withInput();
            }

            $this->processPayment($facture, $agencyAccount);

            DB::commit();
            alert()->success("Succès", "La facture d'électricité de montant ({$facture->amount}) a été payée avec succès!!");

            return back()->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Erreur de base de données lors du paiement de facture: ' . $e->getMessage());
            return $this->handleError("Une erreur est survenue lors du paiement de la facture. Veuillez réessayer.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur inattendue lors du paiement de facture: ' . $e->getMessage());
            return $this->handleError("Une erreur inattendue est survenue. Veuillez réessayer.");
        }
    }

    ####____ ARRET D'ETAT
    function _StopStatsOfHouse(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            Log::debug('Debut d\'arrêt des états ', ["data" => $request->all()]);

            $formData = $request->all();
            $this->validateHouseData($formData);

            $house = $this->getHouse($formData['house']);
            if (!$house || $house->Locations->isEmpty()) {
                DB::rollBack();
                return $this->handleError("Cette maison n'existe pas ou n'appartient à aucune location!");
            }

            $state = $this->createOrUpdateHouseState($house, $formData);
            $this->updateHouseLocations($house, $state);

            DB::commit();
            alert()->success("Succès", "L'état en électricité de cette maison a été arrêté avec succès!");
            return back()->withInput();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Erreur de base de données lors de l\'arrêt des états: ' . $e->getMessage());
            return $this->handleError("Une erreur est survenue lors de l'arrêt des états. Veuillez réessayer.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur inattendue lors de l\'arrêt des états: ' . $e->getMessage());
            return $this->handleError("Une erreur inattendue est survenue. Veuillez réessayer.");
        }
    }

    /**
     * Filtre les locations par superviseur
     * 
     * @param Request $request
     * @param Agency $agency
     * @return \Illuminate\Http\RedirectResponse
     */
    function ElectricityFiltreBySupervisor(Request $request, Agency $agency)
    {
        try {
            // Validation du superviseur
            $supervisor = User::find($request->supervisor);
            if (!$supervisor) {
                throw new \Exception("Ce superviseur n'existe pas!");
            }

            // Récupération des locations avec eager loading
            $locations_filtred = $agency->_Locations()
                ->where('status', '!=', 3)
                ->whereHas('Room', function ($query) {
                    $query->where('electricity', true);
                })
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
    function ElectricityFiltreByHouse(Request $request, Agency $agency)
    {
        try {
            // Validation de la maison
            $house = House::find($request->house);
            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            // Récupération des locations avec eager loading
            $locations_filtred = $agency->_Locations()
                ->with(['House', 'Room', 'Locataire'])
                ->where('status', '!=', 3)
                ->where('house', $request->house)
                ->whereHas('Room', function ($query) {
                    $query->where('electricity', true);
                })
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
    function ElectricityFiltreByProprio(Request $request, Agency $agency)
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
                ->whereHas('Room', function ($query) {
                    $query->where('electricity', true);
                })
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

    private function validateElectricityFacture(array $data): void
    {
        try {
            Validator::make($data, self::electricity_factures_rules(), self::electricity_factures_messages())->validate();
        } catch (Exception $e) {
            Log::error('Erreur de validation des données de facture: ' . $e->getMessage());
            throw $e;
        }
    }

    private function validateHouseData(array $data): void
    {
        try {
            Validator::make($data, [
                'house' => ['required', 'integer'],
            ], [
                'house.required' => 'La maison est requise!',
                'house.integer' => "Ce champ doit être de type entier!",
            ])->validate();
        } catch (Exception $e) {
            Log::error('Erreur de validation des données de maison: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getLocation(int $locationId): ?Location
    {
        try {
            return Location::find($locationId);
        } catch (QueryException $e) {
            Log::error('Erreur lors de la récupération de la location: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getFacture(int $id): ?LocationElectrictyFacture
    {
        try {
            return LocationElectrictyFacture::where('visible', 1)->find($id);
        } catch (QueryException $e) {
            Log::error('Erreur lors de la récupération de la facture: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getHouse(int $houseId): ?House
    {
        try {
            return House::find($houseId);
        } catch (QueryException $e) {
            Log::error('Erreur lors de la récupération de la maison: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getAgencyAccount(int $agencyId): ?AgencyAccount
    {
        try {
            return AgencyAccount::firstWhere(['agency' => $agencyId,"account"=>env('ELECTRICITY_WATER_ACCOUNT_ID')]);
        } catch (QueryException $e) {
            Log::error('Erreur lors de la récupération du compte d\'agence: ' . $e->getMessage());
            throw $e;
        }
    }

    private function prepareFactureData(array $formData, Location $location): ?array
    {
        try {
            $factures = $location->ElectricityFactures;
            $startIndex = $factures->isNotEmpty()
                ? $factures->first()->end_index
                : $location->Room->electricity_counter_start_index;

            $consumption = $formData['end_index'] - $startIndex;
            if ($consumption <= 0) {
                $this->handleError("Désolé! L'index de fin doit être supérieur à celui de début");
                return null;
            }

            $amount = $consumption * $location->Room->electricity_unit_price;
            $user = request()->user();

            return [
                'location' => $formData['location'],
                'start_index' => $startIndex,
                'end_index' => $formData['end_index'],
                'consomation' => $consumption,
                'amount' => $amount,
                'comments' => "Génération de facture d'électricité pour le locataire << {$location->Locataire->name} {$location->Locataire->prenom}>> de la maison << {$location->House->name} >> à la date " . now() . " par << {$user->name}>>",
                'owner' => $user->id,
            ];
        } catch (Exception $e) {
            Log::error('Erreur lors de la préparation des données de facture: ' . $e->getMessage());
            throw $e;
        }
    }

    private function canCreditAccount(AgencyAccount $account, float $amount): bool
    {
        try {
            $agencyAccountSold = AgencyAccountSold::where(['agency_account' => $account->id, 'visible' => 1])->first();
            $currentSold = $agencyAccountSold ? $agencyAccountSold->sold : 0;
            $maxSold = $account->_Account->plafond_max;

            if ($currentSold >= $maxSold) {
                $this->handleError("Le sold de ce compte ({$account->_Account->name}) a déjà atteint son plafond!");
                return false;
            }

            if (($currentSold + $amount) > $maxSold) {
                $this->handleError("L'ajout de ce montant au sold de ce compte ({$account->_Account->name}) dépasserait son plafond!");
                return false;
            }

            return true;
        } catch (Exception $e) {
            Log::error('Erreur lors de la vérification du crédit du compte: ' . $e->getMessage());
            throw $e;
        }
    }

    private function processPayment(LocationElectrictyFacture $facture, AgencyAccount $agencyAccount): void
    {
        try {
            $facture->paid = true;
            $facture->save();

            $agencyAccountSold = AgencyAccountSold::where(['agency_account' => $agencyAccount->id, 'visible' => 1])->first();
            $currentSold = $agencyAccountSold ? $agencyAccountSold->sold : 0;

            if ($agencyAccountSold) {
                $agencyAccountSold->visible = 0;
                $agencyAccountSold->delete_at = now();
                $agencyAccountSold->save();
            }

            AgencyAccountSold::create([
                'agency_account' => $agencyAccount->id,
                'old_sold' => $currentSold,
                'sold' => $currentSold + $facture->amount,
                'sold_added' => $facture->amount,
                'description' => "Paiement de la facture d'électricité de montant ({$facture->amount}) pour la maison {$facture->Location->House->name}!!"
            ]);
        } catch (QueryException $e) {
            Log::error('Erreur lors du traitement du paiement: ' . $e->getMessage());
            throw $e;
        }
    }

    private function createOrUpdateHouseState(House $house, array $formData): StopHouseElectricityState
    {
        try {

            return StopHouseElectricityState::create([
                'house' => $house->id,
                'owner' => auth()->user()->id,
                'state_stoped_day' => now(),
            ]);
        } catch (QueryException $e) {
            Log::error('Erreur lors de la création/mise à jour de l\'état de la maison: ' . $e->getMessage());
            throw $e;
        }
    }

    private function updateHouseLocations(House $house, StopHouseElectricityState $state): void
    {
        try {
            $house->Locations->each(function ($location) use ($state) {
                if (!$location->Room) {
                    return;
                }

                $this->updateLocationRoom($location, $state);
                $this->updateLocationFactures($location, $state);
                $this->createStateFacture($location, $state);
            });
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour des locations de la maison: ' . $e->getMessage());
            throw $e;
        }
    }

    private function updateLocationRoom(Location $location, StopHouseElectricityState $state): void
    {
        try {
            $locationRoom = $location->Room;
            $lastFacture = $location->ElectricityFactures->first();

            if ($lastFacture) {
                $locationRoom->electricity_counter_start_index = $lastFacture->end_index;
                $locationRoom->save();
            }
        } catch (QueryException $e) {
            Log::error('Erreur lors de la mise à jour de la chambre: ' . $e->getMessage());
            throw $e;
        }
    }

    private function updateLocationFactures(Location $location, StopHouseElectricityState $state): void
    {
        try {
            $location->ElectricityFactures->each(function ($facture) use ($state) {
                if (!$facture->state) {
                    $facture->state = $state->id;
                    $facture->save();
                }
            });
        } catch (QueryException $e) {
            Log::error('Erreur lors de la mise à jour des factures: ' . $e->getMessage());
            throw $e;
        }
    }

    private function createStateFacture(Location $location, StopHouseElectricityState $state): void
    {
        try {
            LocationElectrictyFacture::create([
                'owner' => request()->user()->id,
                'location' => $location->id,
                'end_index' => $location->Room->electricity_counter_start_index,
                'amount' => 0,
                'state_facture' => 1,
                'state' => $state->id,
            ]);
        } catch (QueryException $e) {
            Log::error('Erreur lors de la création de la facture d\'état: ' . $e->getMessage());
            throw $e;
        }
    }

    private function handleError(string $message): RedirectResponse
    {
        alert()->error("Echec", $message);
        return back()->withInput();
    }
}
