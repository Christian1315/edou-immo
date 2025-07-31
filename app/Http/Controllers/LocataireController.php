<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\CardType;
use App\Models\Country;
use App\Models\Departement;
use App\Models\House;
use App\Models\Locataire;
use App\Models\Location;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LocataireController extends Controller
{
    ##======== LOCATAIRE VALIDATION =======##
    static function locataire_rules(): array
    {
        return [
            'agency' => ['required', "integer"],
            'name' => ['required'],
            'prenom' => ['required'],
            // 'email' => ['required', "email"],
            'sexe' => ['required'],
            'phone' => ['required', "numeric"],
            // 'piece_number' => ['required'],
            // 'mandate_contrat' => ['required', "file"],
            // 'comments' => ['required'],
            'adresse' => ['required'],
            // 'card_id' => ['required'],
            'card_type' => ['required', "integer"],
            'departement' => ['required', "integer"],
            'country' => ['required', "integer"],
            // 'prorata' => ['required', "boolean"],
            // 'discounter' => ['required', "boolean"],
        ];
    }

    ###________
    static function locataire_messages(): array
    {
        return [
            'agency.required' => "Veillez préciser l'agence!",
            'agency.integer' => "L'agence doit être un entier",

            'name.required' => 'Le nom du locataire est réquis!',
            'prenom.required' => "Le prénom est réquis!",
            // 'email.required' => "Le mail est réquis!",
            // 'email.email' => "Ce champ doit être de format mail",
            'sexe.required' => "Le sexe est réquis",
            'phone.required' => "Le phone est réquis",
            'phone.numeric' => "Le phone doit être de type numéric",
            // 'piece_number.required' => "Le numéro de la pièce est réquise",
            // 'mandate_contrat.required' => "Le contrat du mandat est réquis",
            // 'mandate_contrat.file' => "Le contrat du mandat doit être un fichier",
            // 'comments.required' => "Le commentaire est réquis",
            'adresse.required' => "L'adresse est réquis!",
            // 'card_id.required' => "L'ID de la carte est réquis",
            'card_type.required' => "Le type de la carte est réquis",
            'card_type.integer' => 'Le type de la carte doit être de type entier!',

            'departement.required' => "Le departement est réquis",
            'departement.integer' => "Ce champ doit être de type entier",
            'country.required' => "Le pays est réquis",
            'country.integer' => "Ce champ doit être de type entier",

            // 'prorata.required' => "Veuillez préciser s'il s'agit d'un prorata ou pas!",
            // 'prorata.boolean' => "Ce champ doit être de type booléen",

            // 'discounter.required' => "Veuillez préciser s'il y a un décompteur ou pas!",
            // 'discounter.boolean' => "Ce champ doit être de type booléen",
        ];
    }

    ##########===== STATISTIC HELPERS ===============#############
    #####_____FILTRATGE
    static function _recovery05ToEcheanceDate($request, $agencyId, $inner_call = false)
    {
        try {
            // Find agency with eager loading of houses and their states
            $agency = Agency::where("visible", 1)
                ->with(['_Houses.States', '_Houses.States.Factures.Location.Locataire'])
                ->find($agencyId);

            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $result = [
                'paid' => [],
                'unpaid' => []
            ];

            // Process each house
            foreach ($agency->_Houses as $house) {
                $lastState = $house->States->last();

                if (!$lastState) {
                    continue;
                }

                $stateStopDate = date("Y/m/d", strtotime($lastState->stats_stoped_day));

                // Process each facture
                foreach ($lastState->Factures as $facture) {
                    $location = $facture->Location;
                    $echeanceDate = date("Y/m/d", strtotime($location->previous_echeance_date));
                    $paymentDate = date("Y/m/d", strtotime($facture->echeance_date));

                    // Check if payment is within valid period
                    if ($stateStopDate > $paymentDate && $paymentDate <= $echeanceDate) {
                        $dayOfEcheance = (int)date("d", strtotime($location->previous_echeance_date));

                        if ($dayOfEcheance === 5) {
                            if ($inner_call) {
                                $result['paid'][] = $location;
                            } else {
                                $result['paid'][] = $location->Locataire;
                            }
                        } else if ($inner_call) {
                            $result['unpaid'][] = $location;
                        }
                    }
                }
            }

            if ($inner_call) {
                return [
                    'locations_that_paid' => $result['paid'],
                    'locations_that_do_not_paid' => $result['unpaid']
                ];
            }

            return $result['paid'];
        } catch (\Exception $e) {
            \Log::error('Error in _recovery05ToEcheanceDate: ' . $e->getMessage());
            throw $e;
        }
    }

    static function _recovery10ToEcheanceDate($request, $agencyId, $inner_call = false)
    {
        try {
            $agency = Agency::where(["visible" => 1])->find($agencyId);
            if (!$agency) {
                throw new \Exception("Agence non trouvée");
            }

            $result = [
                'paid' => [],
                'unpaid' => []
            ];

            foreach ($agency->_Houses->load("States") as $house) {
                $lastState = $house->States->last();

                if (!$lastState) {
                    continue;
                }

                $stateStopDate = date("Y/m/d", strtotime($lastState->stats_stoped_day));

                // Process each facture
                foreach ($lastState->Factures as $facture) {
                    $location = $facture->Location;
                    $echeanceDate = date("Y/m/d", strtotime($location->previous_echeance_date));
                    $paymentDate = date("Y/m/d", strtotime($facture->echeance_date));

                    // Check if payment is within valid period
                    if ($stateStopDate > $paymentDate && $paymentDate <= $echeanceDate) {
                        $dayOfEcheance = (int)date("d", strtotime($location->previous_echeance_date));

                        if ($dayOfEcheance === 10) {
                            if ($inner_call) {
                                $result['paid'][] = $location;
                            } else {
                                $result['paid'][] = $location->Locataire;
                            }
                        } else if ($inner_call) {
                            $result['unpaid'][] = $location;
                        }
                    }
                }
            }

            if ($inner_call) {
                return [
                    'locations_that_paid' => $result['paid'],
                    'locations_that_do_not_paid' => $result['unpaid']
                ];
            }

            return $result['paid'];
        } catch (\Exception $e) {
            \Log::error('Error in _recovery10ToEcheanceDate: ' . $e->getMessage());
            throw $e;
        }
    }

    function _recoveryQualitatif($request, $agencyId, $inner_call = false)
    {
        try {
            $agency = Agency::where(["visible" => 1])->find($agencyId);

            $result = [
                'paid' => [],
                'unpaid' => []
            ];

            foreach ($agency->_Houses->load("States") as $house) {
                $lastState = $house->States->last();

                if (!$lastState) {
                    continue;
                }

                $stateStopDate = date("Y/m/d", strtotime($lastState->stats_stoped_day));

                // Process each facture
                foreach ($lastState->Factures as $facture) {
                    $location = $facture->Location;
                    $echeanceDate = date("Y/m/d", strtotime($location->previous_echeance_date));
                    $paymentDate = date("Y/m/d", strtotime($facture->echeance_date));

                    // Check if payment is within valid period
                    if ($stateStopDate > $paymentDate && $paymentDate <= $echeanceDate) {
                        $dayOfEcheance = (int)date("d", strtotime($location->previous_echeance_date));

                        if ($dayOfEcheance === 5 || $dayOfEcheance === 10) {
                            if ($inner_call) {
                                $result['paid'][] = $location;
                            } else {
                                $result['paid'][] = $location->Locataire;
                            }
                        } else if ($inner_call) {
                            $result['unpaid'][] = $location;
                        }
                    }
                }
            }

            if ($inner_call) {
                return [
                    'locations_that_paid' => $result['paid'],
                    'locations_that_do_not_paid' => $result['unpaid']
                ];
            }

            return $result['paid'];
        } catch (\Exception $e) {
            \Log::error('Error in _recoveryQualitatif: ' . $e->getMessage());
            throw $e;
        }
    }

    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth:'])->except(["ShowAgencyTaux05", "ShowAgencyTaux10", "ShowAgencyTauxQualitatif"]);
    }

    private function validateAgency($agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            throw new \Exception("Cette agence n'existe pas");
        }
        return $agency;
    }

    private function validateSupervisor($supervisorId)
    {
        $supervisor = User::where("visible", 1)->find($supervisorId);
        if (!$supervisor) {
            throw new \Exception("Ce superviseur n'existe pas");
        }
        return $supervisor;
    }

    private function validateHouse($houseId)
    {
        $house = House::where("visible", 1)->find($houseId);
        if (!$house) {
            throw new \Exception("Cette maison n'existe pas");
        }
        return $house;
    }

    private function handleException($e, $message = "Une erreur est survenue")
    {
        Log::error($e->getMessage());
        alert()->error("Echec", $message . ": " . $e->getMessage());
        return back()->withInput();
    }

    function _AddLocataire(Request $request)
    {
        try {
            DB::beginTransaction();

            $formData = $request->all();
            $rules = self::locataire_rules();
            $messages = self::locataire_messages();

            Validator::make($formData, $rules, $messages)->validate();

            $user = request()->user();
            $cardType = CardType::find($formData["card_type"]);
            $departement = Departement::find($formData["departement"]);
            $country = Country::find($formData["country"]);
            $agency = Agency::find($formData["agency"]);

            if (!$cardType || !$departement || !$country || !$agency) {
                throw new \Exception("Données invalides: Type de carte, département, pays ou agence non trouvé");
            }

            if ($request->get("prorata")) {
                Validator::make(
                    $formData,
                    ["prorata_date" => ["required", "date"]],
                    [
                        "prorata_date.required" => "Veuillez préciser la date du prorata!",
                        "prorata_date.date" => "Ce champ est de type date",
                    ]
                )->validate();
            }

            if ($request->file("mandate_contrat")) {
                $img = $request->file("mandate_contrat");
                $imgName = $img->getClientOriginalName();
                $img->move("mandate_contrats", $imgName);
                $formData["mandate_contrat"] = asset("mandate_contrats/" . $imgName);
            }

            $formData["prorata"] = $request->prorata ? 1 : 0;
            if ($user) {
                $formData["owner"] = $user->id;
            }

            $locator = Locataire::create($formData);

            if ($request->avalisor) {
                $locator->avaliseur()->create([
                    "ava_name" => $request->ava_name,
                    "ava_prenom" => $request->ava_prenom,
                    "ava_phone" => $request->ava_phone,
                    "ava_parent_link" => $request->ava_parent_link,
                ]);
            }

            DB::commit();
            alert()->success("Succès", "Locataire ajouté avec succès!");
            return back()->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());
            return $this->handleException($e, "Erreur lors de l'ajout du locataire");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de l'ajout du locataire");
        }
    }

    function UpdateLocataire(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = request()->user();
            $formData = $request->all();
            $locataire = Locataire::where(["visible" => 1])->find($id);

            if (!$locataire) {
                throw new \Exception("Ce locataire n'existe pas!");
            }

            if (!auth()->user()->is_master && !auth()->user()->is_admin) {
                if ($locataire->owner != $user->id) {
                    throw new \Exception("Ce locataire ne vous appartient pas!");
                }
            }

            if ($request->get("card_type")) {
                $type = CardType::find($request->get("card_type"));
                if (!$type) {
                    throw new \Exception("Ce type de carte n'existe pas!");
                }
            }

            if ($request->get("departement")) {
                $departement = Departement::find($request->get("departement"));
                if (!$departement) {
                    throw new \Exception("Ce departement n'existe pas!");
                }
            }

            if ($request->get("country")) {
                $country = Country::find($request->get("country"));
                if (!$country) {
                    throw new \Exception("Ce pays n'existe pas!");
                }
            }

            $locataire->update($formData);

            DB::commit();
            alert()->success("Succès", "Locataire modifié avec succès!");
            return back()->withInput();
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de la modification du locataire");
        }
    }

    function DeleteLocataire(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = request()->user();
            $locataire = Locataire::find(deCrypId($id));

            if (!$locataire) {
                throw new \Exception("Ce locataire n'existe pas!");
            }

            if (count($locataire->Locations) > 0) {
                throw new \Exception("Ce locataire dispose de locations(s)! Veuillez bien les supprimer d'abord");
            }

            if (!auth()->user()->is_master && !auth()->user()->is_admin) {
                if ($locataire->owner != $user->id) {
                    throw new \Exception("Ce locataire ne vous appartient pas!");
                }
            }

            $locataire->delete();

            DB::commit();
            alert()->success("Succès", "Locataire supprimé avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de la suppression du locataire");
        }
    }

    #####___FILTRE PAR SUPERVISEUR
    function FiltreBySupervisor(Request $request, Agency $agency)
    {
        try {
            DB::beginTransaction();

            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $supervisor = User::find($request->supervisor);
            if (!$supervisor) {
                throw new \Exception("Ce superviseur n'existe pas!");
            }

            $locators = $agency->_Locataires;
            $locators_filtred = $locators->filter(function ($locator) use ($supervisor) {
                return $locator->Locations->contains(function ($location) use ($supervisor) {
                    return $location->House->Supervisor->id === $supervisor->id;
                });
            })->values();

            if ($locators_filtred->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            DB::commit();
            alert()->success("Succès", "Locataires filtrés avec succès!");
            return back()->withInput()->with(["locators_filtred" => $locators_filtred]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors du filtrage par superviseur");
        }
    }

    #####___FILTRE PAR MAISON
    function FiltreByHouse(Request $request, Agency $agency)
    {
        try {
            DB::beginTransaction();

            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $house = House::find($request->house);
            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            $locators = $agency->_Locataires;
            $locators_filtred = $locators->filter(function ($locator) use ($house) {
                return $locator->Locations->contains(function ($location) use ($house) {
                    return $location->House->id === $house->id;
                });
            })->values();

            if ($locators_filtred->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            DB::commit();
            alert()->success("Succès", "Locataires filtrés avec succès!");
            return back()->withInput()->with(["locators_filtred" => $locators_filtred]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors du filtrage par maison");
        }
    }

    private function getLocatorsThatPaidAfterStateStopped($agency, $isPaid = true)
    {
        try {
            // Get the current date in Y/m/d format
            $now = now()->format('Y/m/d');

            // Get all locations for the agency with their related data
            $locations = $agency->_Locations()
                ->with(['House.Supervisor', 'Locataire'])
                ->get();

            // Filter locations based on payment status and date
            return $locations->filter(function ($location) use ($now, $isPaid) {
                $echeanceDate = $location->echeance_date->format('Y/m/d');
                return $isPaid ? $echeanceDate > $now : $echeanceDate < $now;
            })->values();
        } catch (\Exception $e) {
            Log::error('Error in getLocatorsThatPaidAfterStateStopped: ' . $e->getMessage());
            throw new \Exception("Erreur lors de la récupération des locations: " . $e->getMessage());
        }
    }

    #LOCATAIRES A JOUR PAR SUPERVISEUR
    function PaidFiltreBySupervisor(Request $request, $agency)
    {
        try {
            DB::beginTransaction();

            $agency = Agency::find($agency);
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $supervisor = User::find($request->supervisor);
            if (!$supervisor) {
                throw new \Exception("Ce superviseur n'existe pas!");
            }

            $paidLocations = $this->getLocatorsThatPaidAfterStateStopped($agency, true);
            $filteredLocations = $this->filterLocationsBySupervisor($paidLocations, $supervisor->id);

            if ($filteredLocations->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            DB::commit();
            session()->flash("filteredLocators", $filteredLocations);
            alert()->success("Succès", "Locataire filtré par superviseur avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors du filtrage des locataires payés par superviseur");
        }
    }

    #LOCATAIRES A JOUR PAR MAISON
    function PaidFiltreByHouse(Request $request, $agency)
    {
        try {
            DB::beginTransaction();

            $agency = Agency::find($agency);
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $house = House::find($request->house);
            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            $paidLocations = $this->getLocatorsThatPaidAfterStateStopped($agency, true);
            $filteredLocations = $this->filterLocationsByHouse($paidLocations, $house->id);

            if ($filteredLocations->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            DB::commit();
            session()->flash("filteredLocators", $filteredLocations);
            alert()->success("Succès", "Locataire filtré par maison avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors du filtrage des locataires payés par maison");
        }
    }

    #LOCATAIRES NON A JOUR PAR SUPERVISEUR
    function UnPaidFiltreBySupervisor(Request $request, $agency)
    {
        try {
            DB::beginTransaction();

            $agency = Agency::find($agency);
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $supervisor = User::find($request->supervisor);
            if (!$supervisor) {
                throw new \Exception("Ce superviseur n'existe pas!");
            }

            $unpaidLocations = $this->getLocatorsThatPaidAfterStateStopped($agency, false);
            $filteredLocations = $this->filterLocationsBySupervisor($unpaidLocations, $supervisor->id);

            if ($filteredLocations->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            DB::commit();
            session()->flash("filteredLocators", $filteredLocations);
            alert()->success("Succès", "Locataire impayés filtré par superviseur avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors du filtrage des locataires impayés par superviseur");
        }
    }

    #LOCATAIRES NON A JOUR PAR MAISON
    function UnPaidFiltreByHouse(Request $request, $agency)
    {
        try {
            DB::beginTransaction();

            $agency = Agency::find($agency);
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $house = House::find($request->house);
            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            $unpaidLocations = $this->getLocatorsThatPaidAfterStateStopped($agency, false);
            $filteredLocations = $this->filterLocationsByHouse($unpaidLocations, $house->id);

            if ($filteredLocations->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            DB::commit();
            session()->flash("filteredLocators", $filteredLocations);
            alert()->success("Succès", "Locataire impayés filtré par maison avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors du filtrage des locataires impayés par maison");
        }
    }

    private function getRemovedLocations($agency)
    {
        try {
            return $agency->_Locations->where("status", 3);
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération des locations supprimées: " . $e->getMessage());
        }
    }

    function RemovedFiltreBySupervisor(Request $request, $agency)
    {
        try {
            DB::beginTransaction();

            $agency = Agency::find($agency);
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $supervisor = User::find($request->supervisor);
            if (!$supervisor) {
                throw new \Exception("Ce superviseur n'existe pas!");
            }

            $removedLocations = $this->getRemovedLocations($agency);
            $filteredLocations = $this->filterLocationsBySupervisor($removedLocations, $supervisor->id);

            if ($filteredLocations->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            DB::commit();
            session()->flash("filteredLocators", $filteredLocations);
            alert()->success("Succès", "Locataire démenagé filtré par superviseur avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors du filtrage des locataires démenagés par superviseur");
        }
    }

    #LOCATAIRES DEMENAGES PAR MAISON
    function RemovedFiltreByHouse(Request $request, $agency)
    {
        try {
            DB::beginTransaction();

            $agency = Agency::find($agency);
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            $house = House::find($request->house);
            if (!$house) {
                throw new \Exception("Cette maison n'existe pas!");
            }

            $removedLocations = $this->getRemovedLocations($agency);
            $filteredLocations = $this->filterLocationsByHouse($removedLocations, $house->id);

            if ($filteredLocations->isEmpty()) {
                throw new \Exception("Aucun résultat trouvé");
            }

            DB::commit();
            session()->flash("filteredLocators", $filteredLocations);
            alert()->success("Succès", "Locataire demenagés filtré par maison avec succès!");
            return back()->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors du filtrage des locataires démenagés par maison");
        }
    }

    private function getRecoveryLocations($request, $agencyId, $recoveryType)
    {
        try {
            $agency = $this->validateAgency($agencyId);

            switch ($recoveryType) {
                case '05':
                    $recovery_locations = self::_recovery05ToEcheanceDate($request, $agency->id, true);
                    break;
                case '10':
                    $recovery_locations = self::_recovery10ToEcheanceDate($request, $agency->id, true);
                    break;
                case 'qualitatif':
                    $recovery_locations = self::_recoveryQualitatif($request, $agency->id, true);
                    break;
                default:
                    throw new \Exception("Type de récupération invalide");
            }


            return [
                'agency' => $agency,
                'locations_that_paid' => $recovery_locations["locations_that_paid"],
                'locations_that_do_not_paid' => $recovery_locations["locations_that_do_not_paid"],
                // 'total_of_both_of_them' => count($recovery_locations["locations_that_paid"]) + count($recovery_locations["locations_that_do_not_paid"])
            ];
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors de la récupération des locations: " . $e->getMessage());
        }
    }

    private function renderRecoveryView($locations, $action, $agency, $supervisor, $house, $locations_that_do_not_paid, $viewType)
    {
        set_time_limit(0);
        try {
            $view = match ($viewType) {
                '05' => 'recovery05_locators',
                '10' => 'recovery10_locators',
                'qualitatif' => 'recovery_qualitatif_locators',
                default => throw new \Exception("Type de vue invalide")
            };

            if ($supervisor) {
                $locationsThatDoNotPaid = Collection::make($locations_that_do_not_paid)
                    ->filter(fn($location) => $location->House->supervisor == $supervisor->id);
            } elseif ($house) {
                $locationsThatDoNotPaid = Collection::make($locations_that_do_not_paid)
                    ->filter(fn($location) => $location->house == $house->id);
            } else {
                $locationsThatDoNotPaid = $locations_that_do_not_paid;
            }

            /** En cas d'impression pdf */
            if (request()->get("imprimer") || $agency) {
                $pdf = Pdf::loadView($view, [
                    "locations" => $locations,
                    "action" => $action,
                    "agency" => $agency,
                    "supervisor" => $supervisor,
                    "house" => $house,
                    "locations_that_do_not_paid" => $locationsThatDoNotPaid,
                    "total_of_both_of_them" => collect($locations)->count() + collect($locationsThatDoNotPaid)->count()
                ]);

                /** Set PDF orientation to landscape*/
                $pdf->setPaper('a4', 'landscape');
                return $pdf->stream();
            }

            if ($viewType == "05") {
                $locators_filtered = session("recovery05Locators")->filter(function ($locator) use ($supervisor, $house) {
                    if ($supervisor) {
                        return $locator->locator_location->House->supervisor == $supervisor->id;
                    } elseif ($house) {
                        return $locator->locator_location->house == $house->id;
                    }
                });
            } elseif ($viewType == "10") {
                $locators_filtered = session("recovery10Locators")->filter(function ($locator) use ($supervisor, $house) {
                    if ($supervisor) {
                        return $locator->locator_location->House->supervisor == $supervisor->id;
                    } elseif ($house) {
                        return $locator->locator_location->house == $house->id;
                    }
                });
            } elseif ($viewType == "qualitatif") {
                $locators_filtered = session("recoveryQualitatifLocators")->filter(function ($locator) use ($supervisor, $house) {
                    if ($supervisor) {
                        return $locator->locator_location->House->supervisor == $supervisor->id;
                    } elseif ($house) {
                        return $locator->locator_location->house == $house->id;
                    }
                });
            }

            Session::flash("locators_filtered", $locators_filtered);

            if ($supervisor) {
                alert()->info("Opération éffectué!", "Filtrage éffectué pour le superviseur ($supervisor->name) avec succès");
            } elseif ($house) {
                alert()->info("Opération éffectué!", "Filtrage éffectué pour la maison ($house->name) avec succès");
            } elseif ($viewType == "qualitatif") {
                alert()->info("Opération éffectué!", "Filtrage qualitatif éffectué avec succès");
            }
            return back();
        } catch (\Exception $e) {
            Log::info("Ligne de l'Erreure du renderRecoveryView", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw new \Exception("Erreur lors du rendu de la vue: " . $e->getMessage());
        }
    }

    function _ShowAgencyTaux05_Simple(Request $request, $agencyId)
    {
        try {
            DB::beginTransaction();

            $recoveryData = $this->getRecoveryLocations($request, $agencyId, '05');

            $supervisor = null;
            $house = null;
            $action = "agency";
            $locations = $recoveryData['locations_that_paid'];

            DB::commit();

            return $this->renderRecoveryView(
                $locations,
                $action,
                $recoveryData['agency'],
                $supervisor,
                $house,
                $recoveryData['locations_that_do_not_paid'],
                '05'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de l'affichage des taux 05");
        }
    }

    function _ShowAgencyTaux05_By_Supervisor(Request $request, $agencyId)
    {
        try {
            DB::beginTransaction();

            $recoveryData = $this->getRecoveryLocations($request, $agencyId, '05');
            $supervisor = $this->validateSupervisor($request->supervisor);

            $locations = $this->filterLocationsBySupervisor(
                collect($recoveryData['locations_that_paid']),
                $supervisor->id
            );

            $action = "supervisor";
            $house = null;
            $agency = null;

            DB::commit();
            return $this->renderRecoveryView(
                $locations,
                $action,
                $agency,
                $supervisor,
                $house,
                $recoveryData['locations_that_do_not_paid'],
                '05'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de l'affichage des taux 05 par superviseur");
        }
    }

    function _ShowAgencyTaux05_By_House(Request $request, $agencyId)
    {
        try {
            DB::beginTransaction();

            $recoveryData = $this->getRecoveryLocations($request, $agencyId, '05');
            $house = $this->validateHouse($request->house);

            $locations = $this->filterLocationsByHouse(
                collect($recoveryData['locations_that_paid']),
                $house->id
            );

            $action = "house";
            $supervisor = null;
            $agency = null;

            DB::commit();
            return $this->renderRecoveryView(
                $locations,
                $action,
                $agency,
                $supervisor,
                $house,
                $recoveryData['locations_that_do_not_paid'],
                '05'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info("Erreure venant de la _ShowAgencyTaux05_By_House() du LocataireController", ["error" => $e->getMessage()]);
            // return back();
            return $this->handleException($e, "Erreur lors de l'affichage des taux 05 par maison");
        }
    }

    function _ShowAgencyTaux10_Simple(Request $request, $agencyId)
    {
        try {
            DB::beginTransaction();

            $recoveryData = $this->getRecoveryLocations($request, $agencyId, '10');

            $supervisor = null;
            $house = null;
            $action = "agency";
            $locations = $recoveryData['locations_that_paid'];

            DB::commit();
            return $this->renderRecoveryView(
                $locations,
                $action,
                $recoveryData['agency'],
                $supervisor,
                $house,
                $recoveryData['locations_that_do_not_paid'],
                // $recoveryData['total_of_both_of_them'],
                '10'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de l'affichage des taux 10");
        }
    }

    function _ShowAgencyTaux10_By_Supervisor(Request $request, $agencyId)
    {
        try {
            DB::beginTransaction();

            $recoveryData = $this->getRecoveryLocations($request, $agencyId, '10');
            $supervisor = $this->validateSupervisor($request->supervisor);

            $locations = $this->filterLocationsBySupervisor(
                collect($recoveryData['locations_that_paid']),
                $supervisor->id
            );

            $action = "supervisor";
            $house = null;
            $agency = null;

            DB::commit();
            return $this->renderRecoveryView(
                $locations,
                $action,
                $agency,
                $supervisor,
                $house,
                $recoveryData['locations_that_do_not_paid'],
                // $recoveryData['total_of_both_of_them'],
                '10'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de l'affichage des taux 10 par superviseur");
        }
    }

    function _ShowAgencyTaux10_By_House(Request $request, $agencyId)
    {
        try {
            DB::beginTransaction();

            $recoveryData = $this->getRecoveryLocations($request, $agencyId, '10');
            $house = $this->validateHouse($request->house);

            $locations = $this->filterLocationsByHouse(
                collect($recoveryData['locations_that_paid']),
                $house->id
            );

            $action = "house";
            $supervisor = null;
            $agency = null;

            DB::commit();
            return $this->renderRecoveryView(
                $locations,
                $action,
                $agency,
                $supervisor,
                $house,
                $recoveryData['locations_that_do_not_paid'],
                // $recoveryData['total_of_both_of_them'],
                '10'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de l'affichage des taux 10 par maison");
        }
    }

    function _ShowAgencyTauxQualitatif_Simple(Request $request, $agencyId)
    {
        try {
            DB::beginTransaction();

            $recoveryData = $this->getRecoveryLocations($request, $agencyId, 'qualitatif');

            $supervisor = null;
            $house = null;
            $action = "agency";
            $locations = $recoveryData['locations_that_paid'];

            DB::commit();
            return $this->renderRecoveryView(
                $locations,
                $action,
                $recoveryData['agency'],
                $supervisor,
                $house,
                $recoveryData['locations_that_do_not_paid'],
                // $recoveryData['total_of_both_of_them'],
                'qualitatif'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de l'affichage des taux qualitatifs");
        }
    }

    function _ShowAgencyTauxQualitatif_By_Supervisor(Request $request, $agencyId)
    {
        try {
            DB::beginTransaction();

            $recoveryData = $this->getRecoveryLocations($request, $agencyId, 'qualitatif');
            $supervisor = $this->validateSupervisor($request->supervisor);

            $locations = $this->filterLocationsBySupervisor(
                collect($recoveryData['locations_that_paid']),
                $supervisor->id
            );

            $action = "supervisor";
            $house = null;
            $agency = null;

            DB::commit();
            return $this->renderRecoveryView(
                $locations,
                $action,
                $agency,
                $supervisor,
                $house,
                $recoveryData['locations_that_do_not_paid'],
                // $recoveryData['total_of_both_of_them'],
                'qualitatif'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de l'affichage des taux qualitatifs par superviseur");
        }
    }

    function _ShowAgencyTauxQualitatif_By_House(Request $request, $agencyId)
    {
        try {
            DB::beginTransaction();

            $recoveryData = $this->getRecoveryLocations($request, $agencyId, 'qualitatif');
            $house = $this->validateHouse($request->house);

            $locations = $this->filterLocationsByHouse(
                collect($recoveryData['locations_that_paid']),
                $house->id
            );

            $action = "house";
            $supervisor = null;
            $agency = null;

            DB::commit();
            return $this->renderRecoveryView(
                $locations,
                $action,
                $agency,
                $supervisor,
                $house,
                $recoveryData['locations_that_do_not_paid'],
                // $recoveryData['total_of_both_of_them'],
                'qualitatif'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e, "Erreur lors de l'affichage des taux qualitatifs par maison");
        }
    }

    // #####
    function Recovery05ToEcheanceDate(Request $request, $agencyId)
    {
        return $this->_recovery05ToEcheanceDate($request, $agencyId);
    }

    function Recovery10ToEcheanceDate(Request $request, $agencyId)
    {
        return $this->_recovery10ToEcheanceDate($request, $agencyId);
    }

    function RecoveryQualitatif(Request $request, $agencyId)
    {
        return $this->_recoveryQualitatif($request, $agencyId);
    }

    private function filterLocationsBySupervisor($locations, $supervisorId)
    {
        try {
            return $locations->filter(function ($location) use ($supervisorId) {
                return $location->House->Supervisor->id === $supervisorId;
            })->values();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors du filtrage des locations par superviseur: " . $e->getMessage());
        }
    }

    private function filterLocationsByHouse($locations, $houseId)
    {
        try {
            return $locations->filter(function ($location) use ($houseId) {
                return $location->House->id === $houseId;
            })->values();
        } catch (\Exception $e) {
            throw new \Exception("Erreur lors du filtrage des locations par maison: " . $e->getMessage());
        }
    }
}
