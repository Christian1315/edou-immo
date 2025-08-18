<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Facture;
use App\Models\FactureStatus;
use App\Models\House;
use App\Models\Location;
use App\Models\Room;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    private const TARGET_DAY = '05';
    private const DATE_FORMAT = 'Y/m/d';

    function __construct()
    {
        $this->middleware(['auth']);
    }

    function Admin(Request $request)
    {
        ###___
        $user = auth()->user();

        ###___VERIFIONS SI LE CE COMPTE A ETE ARCHIVE
        if ($user->is_archive) {
            // °°°°°°°°°°° DECONNEXION DU USER
            Auth::logout();

            // °°°°°°°°° SUPPRESION DES SESSIONS
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            alert()->error('Echec', "Ce compte a été archivé!");
            return redirect()->back()->withInput();
        };

        ###___VERIFIONS SI LE CE COMPTE EST ACTIF OU PAS
        if (!$user->visible) {
            // °°°°°°°°°°° DECONNEXION DU USER
            Auth::logout();

            // °°°°°°°°° SUPPRESION DES SESSIONS
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            alert()->error('Echec', "Ce compte a été Supprimé!");
            return redirect()->back()->withInput();
        };

        $current_agency_id = $user->user_agency;
        $current_agency_affected_id = $user->agency;

        $crypted_current_agency_id = Crypt::encrypt($current_agency_id);
        $crypted_current_agency_affected_id = Crypt::encrypt($current_agency_affected_id);

        ###__QUANT IL S'AGIT D'UNE AGENCE
        if ($current_agency_id) {
            return redirect("/$crypted_current_agency_id/manage-agency");
        }

        ###__QUANT IL S'AGIT D'UN USER AFFECTE A UNE AGENCE
        if ($current_agency_affected_id) {
            return redirect("/$crypted_current_agency_affected_id/manage-agency");
        }

        ###___
        return view("admin.dashboard");
    }

    function Agencies(Request $request)
    {
        return view("admin.agency");
    }

    function ManageAgency(Request $request, $id)
    {
        $id = Crypt::decrypt($id);

        $agency = Agency::where("visible", 1)->find($id);
        ####____
        ###___
        return view("admin.manage-agency", compact("agency"));
    }

    function Proprietor(Request $request, $agencyId)
    {
        $id = Crypt::decrypt($agencyId);
        $agency = Agency::where("visible", 1)->findOrFail($id);
        ####____
        return view("admin.proprietors", compact("agency"));
    }

    function House(Request $request, $agencyId)
    {
        $id = Crypt::decrypt($agencyId);

        $agency = Agency::where("visible", 1)->findOrFail($id);
        ####____
        return view("admin.houses", compact("agency"));
    }

    function Room(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        }
        ####____
        return view("admin.rooms", compact("agency"));
    }

    function Locator(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____
        return view("admin.locataires", compact("agency"));
    }

    function PaidLocator(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____
        return view("admin.paid-locators", compact("agency"));
    }

    function UnPaidLocator(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };

        ####____
        return view("admin.unpaid-locators", compact("agency"));
    }

    function RemovedLocators(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };

        ####____
        return view("admin.removed-locators", compact("agency"));
    }

    function Location(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };

        ####____
        return view("admin.locations", compact("agency"));
    }

    function AccountSold(Request $request)
    {
        return view("admin.count_solds");
    }

    function AgencyInitiation(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.agency-initiations", compact("agency"));
    }

    function Paiement(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };

        if ($request->isMethod("POST")) {
            if ($request->house) {
                $_house = House::find($request->house);
                if (!$_house) {
                    alert()->error("Echec", "Cette maison n'existe pas!");
                }

                $house = Collection::make(GET_HOUSE_DETAIL_FOR_THE_LAST_STATE($_house));
            }
        } else {
            $house = null;
        }

        $houses = House::get();
        ####____
        return view("admin.paiements", compact([
            'agency',
            'house',
            'houses'
        ]));
    }

    /**
     * Traitement des factures 
     * d'électricité
     */

    function Electricity(Request $request, $agencyId)
    {
        set_time_limit(0);
        try {
            $current_agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
            if (!$current_agency) {
                alert()->error("Echec", "Cette agence n'existe pas!");
            };

            // Chargement des locations
            $activeLocations = $current_agency
                ->_Locations
                ->where("status", "!=", 3)
                ->filter(fn($location) => $location->Room?->electricity);

            $debut = $request->debut;
            $fin = $request->fin;
            $owner = $request->owner;

            $locations = Collection::make(
                $activeLocations
                    ->map(function ($location) use ($debut, $fin, $owner) {
                        return $this->processLocation($location, $debut, $fin, $owner);
                    })->all()
            );

            $houses = Collection::make(
                $activeLocations
                    ->map(fn($location) => $location->House)
                    ->unique()
                    ->values()
                    ->all()
            );

            if ($debut || $fin) {
                $user = User::find($owner);
                alert()->info("Opération réussie", "Filtrage par période éffectué pour la période du $debut au $fin pour l'utilisateur $user->name");
            }

            /**Total à payer */
            $totalAmount = $locations->sum("total_un_paid_facture_amount");

            return view("admin.electricity", compact(
                "current_agency",
                "locations",
                "houses",
                "totalAmount"
            ));
        } catch (\Exception $e) {
            alert()->error("Erreure lors du chargement des factures d'électricité " . $e->getMessage());
            return back()->withInput();
        }
    }

    private function processLocation(Location $location, $debut, $fin, $owner): Location
    {
        $location->load([
            "_Agency",
            "Owner",
            "House",
            "Locataire",
            "Room",
            "Factures",
            "StateFactures",
            "AllFactures",
            "Paiements",
            "ElectricityFactures"
        ]);

        $location['house_name'] = $location->House?->name;
        $location['locataire'] = $location->Locataire?->name . " " . $location->Locataire?->prenom;

        $location['electricity_factures'] = ($debut || $fin || $owner) ?
            $location
            ->ElectricityFactures
            ->where("owner", $owner)
            ->whereBetween("created_at", [$debut, $fin]) :
            $location->ElectricityFactures;

        $location['electricity_factures_states'] = ($debut || $fin || $owner) ?
            $location->House
            ->ElectricityFacturesStates
            ->where("owner", $owner)
            ->whereBetween("created_at", [$debut, $fin]) :
            $location->House->ElectricityFacturesStates;

        $location['lastFacture'] = ($debut || $fin || $owner) ?
            $location
            ->ElectricityFactures
            ->where("owner", $owner)
            ->whereBetween("created_at", [$debut, $fin])
            ->first() :
            $location->ElectricityFactures();

        $location['start_index'] = $this->getStartIndex($location);

        $electricityFactures = ($debut || $fin || $owner) ?
            $location->ElectricityFactures
            ->where("owner", $owner)
            ->whereBetween("created_at", [$debut, $fin]) :
            $location->ElectricityFactures;

        if ($electricityFactures->isNotEmpty()) {
            $latestFacture = $electricityFactures->first();
            $isLatestFactureStateFacture = $latestFacture->state_facture;

            $this->calculateFactureData($location, $latestFacture, $isLatestFactureStateFacture, $electricityFactures);
        } else {
            $this->getEmptyFactureData($location);
        }

        return $location;
    }

    private function getStartIndex(Location $location): ?int
    {
        if ($location->ElectricityFactures->isNotEmpty()) {
            return $location->ElectricityFactures->first()->end_index;
        }
        return $location->Room?->electricity_counter_start_index;
    }

    private function calculateFactureData(Location $location, $latestFacture, bool $isLatestFactureStateFacture, $electricityFactures): Location
    {
        $unpaidFactures = $electricityFactures
            ->where('id', '!=', $latestFacture->id)
            ->where('paid', false)
            ->where('state_facture', false);

        $paidFactures = $electricityFactures->where('paid', true);
        $totalFactures = $electricityFactures;

        $location['end_index'] = $latestFacture->end_index;
        $location['current_amount'] = $latestFacture->paid ? 0 : $latestFacture->amount;
        $location['nbr_un_paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $unpaidFactures->count();
        $location['un_paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $unpaidFactures->sum('amount');
        $location['paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $paidFactures->sum('amount');
        $location['total_un_paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $totalFactures->sum('amount');
        $location['rest_facture_amount'] = $isLatestFactureStateFacture ? 0 : ($totalFactures->sum('amount') - $paidFactures->sum('amount'));

        return $location;
    }

    private function getEmptyFactureData($location): Location
    {
        $location['end_index'] = 0;
        $location['current_amount'] = 0;
        $location['nbr_un_paid_facture_amount'] = 0;
        $location['un_paid_facture_amount'] = 0;
        $location['water_factures'] = [];
        $location['paid_facture_amount'] = 0;
        $location['total_un_paid_facture_amount'] = 0;
        $location['rest_facture_amount'] = 0;

        return $location;
    }
    /**Fin du traitement des factures d'electricité */


    /**
     * Traitements des factures eau
     */

    function Eau(Request $request, $agencyId)
    {
        try {
            $current_agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
            if (!$current_agency) {
                alert()->error("Echec", "Cette agence n'existe pas!");
            };

            /** Chargement des locations */
            $activeLocations = $current_agency
                ->_Locations
                ->where("status", "!=", 3)
                ->filter(fn($location) => $location->Room?->electricity);

            $debut = $request->debut;
            $fin = $request->fin;
            $owner = $request->owner;

            $locations = Collection::make(
                ($debut || $fin || $owner) ?
                    $activeLocations
                    ->map(function ($location) use ($debut, $fin, $owner) {
                        return $this->waterProcessLocation($location, $debut, $fin, $owner);
                    })->all() :
                    $activeLocations
                    ->map(function ($location) use ($debut, $fin, $owner) {
                        return $this->waterProcessLocation($location, $debut, $fin, $owner);
                    })->all()
            );

            $houses = Collection::make(
                $activeLocations
                    ->map(fn($location) => $location->House)
                    ->unique()
                    ->values()
                    ->all()
            );

            if ($debut || $fin || $owner) {
                $user = User::find($owner);
                alert()->info("Opération réussie", "Filtrage par période éffectué pour la période du $debut au $fin pour l'utilisateur $user->name ");
            }

            /**Total à payer */
            $totalAmount = $locations->sum("total_un_paid_facture_amount");

            return view("admin.eau_locations", compact(
                "current_agency",
                "locations",
                "houses",
                "totalAmount",
            ));
        } catch (\Exception $e) {
            alert()->error("Echec", "Erreure lors du chargement des factures d'eau " . $e->getMessage());
            return back();
        }
    }

    private function waterProcessLocation(Location $location, $debut, $fin, $owner): Location
    {
        $location->load([
            "_Agency",
            "Owner",
            "House",
            "Locataire",
            "Room",
            "Factures",
            "StateFactures",
            "AllFactures",
            "Paiements",
            "ElectricityFactures"
        ]);

        $location['house_name'] = $location->House->name;
        $location['locataire'] = $location->Locataire->name . ' ' . $location->Locataire->prenom;

        $location['water_factures'] = ($debut || $fin || $owner) ? $location
            ->WaterFactures
            ->where("owner", $owner)
            ->whereBetween("created_at", [$debut, $fin]) :
            $location
            ->WaterFactures;

        $location['water_factures_states'] = ($debut || $fin || $owner) ? $location->House
            ->WaterFacturesStates
            ->where("owner", $owner)
            ->whereBetween("created_at", [$debut, $fin]) :
            $location->House
            ->WaterFacturesStates;

        $location['lastFacture'] = ($debut || $fin || $owner) ? $location
            ->WaterFactures()
            ->where("owner", $owner)
            ->whereBetween("created_at", [$debut, $fin])
            ->first() :
            $location->WaterFactures()->first();

        $location['start_index'] = $this->waterGetStartIndex($location);

        /** Water Factures */
        $waterFactures = ($debut || $fin || $owner) ? $location
            ->WaterFactures
            ->where("owner", $owner)
            ->whereBetween("created_at", [$debut, $fin]) :
            $location->WaterFactures;

        if ($waterFactures->isNotEmpty()) {
            $latestFacture = $waterFactures->first();
            $isLatestFactureStateFacture = $latestFacture->state_facture ?? false;
            $this->waterCalculateFactureData($location, $latestFacture, $isLatestFactureStateFacture, $waterFactures);
        } else {
            $this->waterGetEmptyFactureData($location);
        }

        return $location;
    }

    private function waterCalculateFactureData(Location $location, $latestFacture, bool $isLatestFactureStateFacture, $waterFactures): Location
    {
        $unpaidFactures = $waterFactures
            ->where('id', '!=', $latestFacture->id)
            ->where('paid', false)
            ->where('state_facture', false);

        $paidFactures = $waterFactures->where('paid', true);
        $totalFactures = $waterFactures;

        $location['end_index'] = $latestFacture->end_index;
        $location['current_amount'] = $latestFacture->paid ? 0 : $latestFacture->amount;
        $location['nbr_un_paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $unpaidFactures->count();
        $location['un_paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $unpaidFactures->sum('amount');
        $location['paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $paidFactures->sum('amount');
        $location['total_un_paid_facture_amount'] = $isLatestFactureStateFacture ? 0 : $totalFactures->sum('amount');
        $location['rest_facture_amount'] = $isLatestFactureStateFacture ? 0 : ($totalFactures->sum('amount') - $paidFactures->sum('amount'));

        return $location;
    }

    private function waterGetStartIndex(Location $location): ?int
    {
        if ($location->WaterFactures->isNotEmpty()) {
            return $location->WaterFactures->first()->end_index;
        }
        return $location->Room?->water_counter_start_index;
    }

    private function waterGetEmptyFactureData($location): Location
    {
        $location['end_index'] = 0;
        $location['current_amount'] = 0;
        $location['nbr_un_paid_facture_amount'] = 0;
        $location['un_paid_facture_amount'] = 0;
        $location['water_factures'] = [];
        $location['paid_facture_amount'] = 0;
        $location['total_un_paid_facture_amount'] = 0;
        $location['rest_facture_amount'] = 0;

        return $location;
    }
    /**Fin du traitement des factures d'eau */

    /**
     * Statistique avant arrêt des états
     */
    public function AgencyStatistiqueBeforeState(Request $request, $agencyId)
    {
        try {
            $agency = Agency::where("visible", 1)
                ->find(deCrypId($agencyId));

            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            /** Toutes les maisons liées à l'agence */
            $houseIds = $agency->_Houses->pluck("id")->toArray();

            $query = House::whereIn("id", $houseIds);

            /** */
            $supervisor = null;
            $gestionnaire = null;

            if ($request->supervisor) {
                $supervisor = User::find($request->supervisor);
                if (!$supervisor) {
                    throw new \Exception("Le superviseur n'existe pas");
                }

                $query->where("supervisor", $request->supervisor);
                alert()->info("Filtrage effectué", "Filtre par superviseur");
            } elseif ($request->gestionnaire) {
                $gestionnaire = User::find($request->gestionnaire);
                if (!$gestionnaire) {
                    throw new \Exception("Ce gestionnaire n'existe pas");
                }

                $supervisorIds = $gestionnaire->supervisors->pluck("id")->toArray();
                $query->whereIn("supervisor", $supervisorIds);
                alert()->info("Filtrage effectué", "Filtre par gestionnaire");
            }

            /** Filtrer uniquement les maisons ayant un dernier état */
            $houses = $query->get()
                ->filter(fn($house) => $house->States->last());

            $locatorsBefore = collect();

            foreach ($houses as $house) {
                $last_state = $house->States->last();

                if (!$last_state) {
                    continue;
                }

                /** Locations IDs */
                $locationsIds = $house->Locations
                    ->where("status", "!=", 3)
                    ->pluck("id")->toArray();

                /** Factures validées avant le dernier état */
                $houseFacturesQuery = Facture::whereIn("location", $locationsIds)
                    ->where("status", 2)
                    ->where('created_at', "<", $last_state->created_at);


                if ($request->month) {
                    $dateMonth = \Carbon\Carbon::createFromFormat('Y-m', $request->month);
                    $houseFacturesQuery->whereMonth("created_at", $dateMonth->month)
                        ->whereYear("created_at", $dateMonth->year);
                }

                $houseFactures = $houseFacturesQuery->get();

                /** Transformation en tableau formaté */
                $locatorFormatted = $houseFactures->map(function ($facture) use ($house, $last_state) {
                    return (object) [
                        "name"          => trim(($facture->Location->Locataire?->name ?? '') . ' ' . ($facture->Location->Locataire?->prenom ?? '')),
                        "house_name"    => $house->name,
                        "supervisor"    => $house->Supervisor?->name,
                        "phone"         => $facture->Location->Locataire?->phone,
                        "adresse"       => $facture->Location->Locataire?->adresse,
                        "comments"      => $facture->Location->Locataire?->comments,
                        "payement_date" => $facture->created_at,
                        "amount_paid"   => $facture->amount,
                        "loyer"         => $facture->Location?->loyer,
                        "last_state_date"         => $last_state->created_at,
                    ];
                });

                // Ajouter directement chaque élément à la collection
                $locatorsBefore = $locatorsBefore->concat($locatorFormatted);
            }

            // Pour imprimer
            if ($request->imprimer) {
                set_time_limit(0);
                // dd($request->imprimer);
                $pdf = Pdf::loadView("imprimer-locators-before-stop-state", [
                    "locators" => $locatorsBefore,
                    "supervisor" => $supervisor,
                    "gestionnaire" => $gestionnaire,
                ]);

                // Set PDF orientation to landscape
                $pdf->setPaper('a4', 'landscape');

                return $pdf->stream();
            }

            return view("admin.agency-statistique-before-state", [
                "agency"   => $agency,
                "locators" => $locatorsBefore
            ]);
        } catch (\Exception $e) {
            alert()->error("Opération échouée", $e->getMessage());
            return back();
        }
    }

    /**
     * Statistique après arrêt des états
     */
    function AgencyStatistiqueAfterState(Request $request, $agencyId)
    {
        try {
            $agency = Agency::where("visible", 1)
                ->find(deCrypId($agencyId));

            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            /** Toutes les maisons liées à l'agence */
            $houseIds = $agency->_Houses->pluck("id")->toArray();

            $query = House::whereIn("id", $houseIds);

            /** */
            $supervisor = null;
            $gestionnaire = null;

            if ($request->supervisor) {
                $supervisor = User::find($request->supervisor);
                if (!$supervisor) {
                    throw new \Exception("Le superviseur n'existe pas");
                }

                $query->where("supervisor", $request->supervisor);
                alert()->info("Filtrage effectué", "Filtre par superviseur");
            } elseif ($request->gestionnaire) {
                $gestionnaire = User::find($request->gestionnaire);
                if (!$gestionnaire) {
                    throw new \Exception("Ce gestionnaire n'existe pas");
                }

                $supervisorIds = $gestionnaire->supervisors->pluck("id")->toArray();
                $query->whereIn("supervisor", $supervisorIds);
                alert()->info("Filtrage effectué", "Filtre par gestionnaire");
            }


            /** Filtrer uniquement les maisons ayant un dernier état */
            $houses = $query->get()
                ->filter(fn($house) => $house->States->last());

            $locatorsBefore = collect();

            foreach ($houses as $house) {
                $last_state = $house->States->last();

                if (!$last_state) {
                    continue;
                }

                /** Locations IDs */
                $locationsIds = $house->Locations
                    ->where("status", "!=", 3)
                    ->pluck("id")->toArray();

                /** Factures validées avant le dernier état */
                $houseFacturesQuery = Facture::whereIn("location", $locationsIds)
                    ->where("status", 2)
                    ->where('created_at', ">", $last_state->created_at);

                if ($request->month) {
                    $dateMonth = \Carbon\Carbon::createFromFormat('Y-m', $request->month);
                    $houseFacturesQuery->whereMonth("created_at", $dateMonth->month)
                        ->whereYear("created_at", $dateMonth->year);
                }

                $houseFactures = $houseFacturesQuery->get();

                /** Transformation en tableau formaté */
                $locatorFormatted = $houseFactures->map(function ($facture) use ($house, $last_state) {
                    return (object) [
                        "name"          => trim(($facture->Location->Locataire?->name ?? '') . ' ' . ($facture->Location->Locataire?->prenom ?? '')),
                        "house_name"    => $house->name,
                        "supervisor"    => $house->Supervisor?->name,
                        "phone"         => $facture->Location->Locataire?->phone,
                        "adresse"       => $facture->Location->Locataire?->adresse,
                        "comments"      => $facture->Location->Locataire?->comments,
                        "payement_date" => $facture->created_at,
                        "amount_paid"   => $facture->amount,
                        "loyer"         => $facture->Location?->loyer,
                        "last_state_date"         => $last_state->created_at,
                    ];
                });

                // Ajouter directement chaque élément à la collection
                $locatorsBefore = $locatorsBefore->concat($locatorFormatted);
            }

            // Pour imprimer
            if ($request->imprimer) {
                set_time_limit(0);
                // dd($request->imprimer);
                $pdf = Pdf::loadView("imprimer-locators-after-stop-state", [
                    "locators" => $locatorsBefore,
                    "supervisor" => $supervisor,
                    "gestionnaire" => $gestionnaire,
                ]);

                // Set PDF orientation to landscape
                $pdf->setPaper('a4', 'landscape');

                return $pdf->stream();
            }

            return view("admin.agency-statistique-after-state", [
                "agency"   => $agency,
                "locators" => $locatorsBefore
            ]);
        } catch (\Exception $e) {
            alert()->error("Opération échouée", $e->getMessage());
            return back();
        }
    }

    #####____BILAN
    function Filtrage(Request $request, $agencyId)
    {
        try {

            $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
            if (!$agency) {
                alert()->error("Echec", "Cette agence n'existe pas!");
            };
            ####____

            $locations = collect();
            $moved_locators = collect();
            $factures = collect();
            $rooms = collect();
            $factures_total_amount = 0.0;

            $debut = $request->debut;
            $fin = $request->fin;

            $now = strtotime(date("Y/m/d", strtotime(now())));

            /**Rooms */
            $rooms = ($debut || $fin) ? $agency->_Houses
                ->flatMap
                ->Rooms->whereBetween("created_at", [$debut, $fin]) : $agency->_Houses
                ->flatMap
                ->Rooms;

            /**Chambres libres */
            $frees_rooms = $rooms->filter(fn($room) => !$room->buzzy());

            /**Proprietaires & Maisons */
            $proprietors = ($debut || $fin) ? $agency->_Proprietors
                ->whereBetween("created_at", [$debut, $fin]) : $agency->_Proprietors;

            $houses = ($debut || $fin) ? $agency->_Houses
                ->whereBetween("created_at", [$debut, $fin]) : $agency->_Houses;

            /**Locataires */
            $locators = ($debut || $fin) ? $agency->_Locataires
                ->whereBetween("created_at", [$debut, $fin]) : $agency->_Locataires;

            $locationQuery = ($debut || $fin) ? $agency->_Houses
                ->flatMap
                ->Locations
                ->whereBetween("created_at", [$debut, $fin]) : $agency->_Houses
                ->flatMap
                ->Locations;

            /**Locations */
            $locations = $locationQuery
                ->where("status", "!=", 3);

            /**Chambres impayées */
            $unpaid_locators = $locations->filter(function ($location) use ($now, $debut, $fin) {
                $location_echeance_date = strtotime(date("Y/m/d", strtotime($location->echeance_date)));
                if ($location_echeance_date < $now) {
                    return ($debut || $fin) ? $location->Locataire
                        ->whereBetween("created_at", [$debut, $fin]) : $location->Locataire;
                }
            });

            /**Locataires démenagés */
            $moved_locators = ($debut || $fin) ? $locationQuery
                ->where("status", 3)
                ->pluck("Locataire")
                ->whereBetween("created_at", [$debut, $fin]) :
                $locationQuery
                ->where("status", 3)
                ->pluck("Locataire");

            /**Factures */
            $factures = ($debut || $fin) ? Facture::whereIn("location", $locations->pluck("id"))
                ->latest()
                ->where("state_facture", false)
                ->whereBetween("created_at", [$debut, $fin])
                ->get() : Facture::whereIn("location", $locations->pluck("id"))
                ->latest()
                ->where("state_facture", false)
                ->get();

            /**Total des factures */
            $factures_total_amount += $factures->sum('amount');

            if ($debut || $fin) {
                alert()->success("Opération réussie", "Filtre de bilan éffectué avec succès!");
            }

            return view("admin.filtrage", compact([
                "agency",
                "locations",
                "locators",
                "moved_locators",
                "factures",
                "rooms",
                "factures_total_amount",

                "proprietors",
                "houses",
                "locators",

                "frees_rooms",
                "unpaid_locators"
            ]));
        } catch (\Exception $e) {
            alert()->error("Erreure", $e->getMessage());
            return back();
        }
    }

    #####____RECOUVREMENT A LA DATE 05
    function AgencyRecovery05(Request $request, $agencyId)
    {
        try {
            Log::info("Debut du chargement des locators du 05");

            $agency = Agency::where("visible", 1)
                ->find(deCrypId($agencyId));

            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!", 1);
            };
            ####____

            $houses = collect($agency->_Houses)
                ->filter(fn($house) => $house->States->isNotEmpty());

            $locators = recovery05Locators($houses);

            /**Stockage des locators en session pour réutiliser
             * au cours du filtre
             */
            session()->put("recovery05Locators", $locators);

            Log::info("Fin du chargement des locators du 05");

            return view("admin.recovery05", compact("agency", "houses", "locators"));
        } catch (\Exception $e) {
            Log::error("Erreure lors du chargement des locataires " . $e->getMessage());

            alert()->error("Erreure lors du chargement des locataires " . $e->getMessage());
            return back();
        }
    }

    #####____RECOUVREMENT A LA DATE 10
    function AgencyRecovery10(Request $request, $agencyId)
    {
        try {
            Log::info("Debut du chargement des locators du 10");

            $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!", 1);
            };
            ####____

            $houses = collect($agency->_Houses)
                ->filter(fn($house) => $house->States->isNotEmpty());
            $locators = recovery10Locators($houses);

            /**Stockage des locators en session pour réutiliser
             * au cours du filtre
             */
            session()->put("recovery10Locators", $locators);

            Log::info("Fin du chargement des locators du 10");

            return view("admin.recovery10", compact("agency", "houses", "locators"));
        } catch (\Exception $e) {
            Log::error("Erreure lors du chargement des locataires 10" . $e->getMessage());

            alert()->error("Erreure lors du chargement des locataires 10" . $e->getMessage());
            return back();
        }
    }

    function AgencyRecoveryQualitatif(Request $request, $agencyId)
    {
        try {
            Log::info("Debut du chargement des locators qualitatif");

            $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!", 1);
            };
            ####____

            $houses = collect($agency->_Houses)
                ->filter(fn($house) => $house->States->isNotEmpty());

            if ($request->debut || $request->fin) {
                $locators = recoveryQualitatifLocators($houses)->whereBetween("payment_date", [$request->debut || $request->fin]);
            } else {
                $locators = recoveryQualitatifLocators($houses);
            }

            /**Stockage des locators en session pour réutiliser
             * au cours du filtre
             */

            session()->put("recoveryQualitatifLocators", $locators);
            Log::info("Fin du chargement des locators qualitatif");

            if ($request->debut || $request->fin) {
                alert()->info("Opération réussie", "Filtrage effectué pour la période du $request->debut au $request->fin");
            }

            return view("admin.recovery_qualitatif", compact("agency", "houses", "locators"));
        } catch (\Exception $e) {
            Log::error("Erreure lors du chargement des locataires qualitatif" . $e->getMessage());

            alert()->error("Erreure lors du chargement des locataires qualitatif" . $e->getMessage());
            return back();
        }
    }

    function AgencyPerformance(Request $request, $agencyId)
    {
        $FIRST_MONTH_PERIOD = '+1 month';
        try {
            Log::info("Debut du chargement des locators qualitatif");

            $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!", 1);
            };
            ####____

            $houses = $agency->_Houses;

            $debut = $request->debut;
            $fin = $request->fin;

            $processedHouses = $houses->map(function (House $house) use ($FIRST_MONTH_PERIOD, $debut, $fin): House {
                $creationDate = Carbon::parse($house->created_at);
                $firstMonthPeriod = $creationDate->copy()->add($FIRST_MONTH_PERIOD);

                $rooms = $house->Rooms;
                $locations = $house->Locations;

                $roomStatus = $rooms->map(function (Room $room) use ($locations, $firstMonthPeriod) {
                    $location = $locations->firstWhere('Room.id', $room->id);

                    if ($location) {
                        $locationCreateDate = Carbon::parse($location->created_at);
                        $isFirstMonth = $locationCreateDate->lt($firstMonthPeriod);

                        $room->is_busy = true;
                        $room->is_first_month = $isFirstMonth;
                        return $room;
                    }

                    $room->is_busy = false;
                    $room->is_first_month = false;
                    return $room;
                });

                if ($debut || $fin) {
                    // $busy_rooms = $roomStatus->where('is_busy', true)
                    //     ->whereBetween("created_at", [$debut, $fin]);
                    // $frees_rooms = $roomStatus->where('is_busy', false)
                    //     ->whereBetween("created_at", [$debut, $fin]);
                    $busy_rooms_at_first_month = $roomStatus->where('is_busy', true)
                        ->where('is_first_month', true)
                        ->whereBetween("created_at", [$debut, $fin]);
                    $frees_rooms_at_first_month = $roomStatus->where('is_busy', true)
                        ->where('is_first_month', false)
                        ->whereBetween("created_at", [$debut, $fin]);
                } else {
                    $busy_rooms = $roomStatus->where('is_busy', true);
                    $frees_rooms = $roomStatus->where('is_busy', false);
                    $busy_rooms_at_first_month = $roomStatus->where('is_busy', true)
                        ->where('is_first_month', true);
                    $frees_rooms_at_first_month = $roomStatus->where('is_busy', true)
                        ->where('is_first_month', false);
                }

                $house->busy_rooms = $busy_rooms;
                $house->frees_rooms = $frees_rooms;
                $house->busy_rooms_at_first_month = $busy_rooms_at_first_month;
                $house->frees_rooms_at_first_month = $frees_rooms_at_first_month;

                return $house;
            });

            // $all_busy_rooms = $processedHouses->pluck('busy_rooms')->toArray();
            // $all_frees_rooms = $processedHouses->pluck('frees_rooms')->toArray();
            $all_frees_rooms_at_first_month = $processedHouses->pluck('busy_rooms_at_first_month')->toArray();

            /**Chambres Occupées */
            $all_busy_rooms = ($debut || $fin) ? $agency->_Locations
                ->where('status', '!=', 3)
                ->pluck("Room")
                ->whereBetween("created_at", [$debut, $fin]) :
                $agency->_Locations
                ->where('status', '!=', 3)
                ->pluck("Room")
                ->filter(fn($room) => $room && $room->buzzy());

            /**Chambres libres */
            $all_frees_rooms = ($debut || $fin) ? $houses->flatMap
                ->Rooms
                ->whereBetween("created_at", [$debut, $fin])
                ->filter(fn($room) => !$room->buzzy()) :
                $houses->flatMap
                ->Rooms
                ->filter(fn($room) => !$room->buzzy());

            if ($debut || $fin) {
                alert()->info("Opération éffectué", "Filtre de performance éffectué de la période du $debut au $fin");
            }

            return view("admin.performance", compact(
                "agency",
                "houses",
                "all_frees_rooms",
                "all_busy_rooms",
                "all_frees_rooms_at_first_month"
            ));
        } catch (\Exception $e) {
            Log::error("Erreure lors du chargement des performances" . $e->getMessage());

            alert()->error("Erreure lors du chargement des performances" . $e->getMessage());
            return back();
        }
    }

    function RecoveryAtAnyDate(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };

        ####____
        return view("admin.recovery_at_any_date", compact("agency"));
    }

    function FiltreByDateInAgency(Request $request, $agencyId)
    {
        try {
            $user = request()->user();

            // Validation des données
            $validated = $request->validate([
                "date" => ["required", "date"],
            ], [
                "date.required" => "Veuillez préciser la date",
                "date.date" => "Le champ doit être de format date",
            ]);

            // Récupération de l'agence
            $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
            if (!$agency) {
                throw new \Exception("Cette agence n'existe pas!");
            }

            // Filtrage des factures avec une requête plus efficace
            $factures = Facture::whereDate('created_at', $validated['date'])->get();

            // if ($factures->isEmpty()) {
            //     alert()->info("Information", "Aucune facture trouvée pour la date du $request->date ");
            //     return back()->withInput();
            // }

            // Récupération des locations avec eager loading
            $locations = Location::where("agency", deCrypId($agencyId))
                ->whereIn("id", $factures->pluck("location"))
                ->with('Locataire') // Eager loading pour éviter le N+1 problem
                ->get();

            // Récupération des locataires de manière plus efficace
            $locators = $locations->pluck('Locataire')->filter()->values();

            if ($locators->isEmpty()) {
                alert()->info("Information", "Aucun locataire trouvé pour la date du $request->date");
                return back()->withInput();
            }

            session()->flash("any_date", $validated['date']);
            alert()->success("Succès", "Filtre effectué avec succès!");
            return back()->withInput()->with(["locators" => $locators]);
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            alert()->error("Echec", $e->getMessage());
            return back()->withInput();
        }
    }

    function PaiementAll(Request $request)
    {
        $agency = [];
        return view("admin.paiements_all", compact("agency"));
    }

    function Setting(Request $request)
    {
        return view("admin.settings");
    }

    function Supervisors(Request $request)
    {
        return view("admin.supervisors");
    }

    function Statistique(Request $request)
    {
        return view("admin.statistiques");
    }

    function Caisses(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };

        return view("admin.caisses", compact("agency"));
    }

    function CaisseMouvements(Request $request, $agencyId, $agency_account)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ##___

        return view("admin.caisse-mouvements", compact(["agency", "agency_account"]));
    }

    function Encaisser(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ##___

        return view("admin.encaisser", compact("agency"));
    }

    function Decaisser(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ##___

        return view("admin.decaisser", compact("agency"));
    }

    function LocationFactures(Request $request, $agencyId)
    {
        try {
            $agency = Agency::where("visible", 1)
                ->findOrFail(deCrypId($agencyId));

            $user = auth()->user();

            if ($user->hasRole("Gestionnaire de compte")) {
                /**Ses superviseurs */
                $supervisorsIds = $user->supervisors->pluck("id")
                    ->toArray();

                // Locations
                $locations = $agency
                    ->_Locations()
                    ->where("status", "!=", 3)
                    ->whereHas("House", function ($house) use ($supervisorsIds) {
                        $house->whereIn("supervisor", $supervisorsIds);
                    });

                // $query = $locations
                //     ->get()
                //     ->flatMap
                //     ->Factures
                //     ->latest()
                //     ->where("state_facture", false); //on tient pas comptes des factures generée pour clotuer un état

                $query = Facture::with(["Location"])
                    ->whereIn("location", $locations
                        ->where("status", "!=", 3) //on tient pas compte des locations demenagées
                        ->pluck("id"))
                    ->latest()
                    ->where("state_facture", false); //on tient pas comptes des factures generée pour clotuer un étt
            } else {
                $query = Facture::with(["Location"])
                    ->whereIn("location", $agency->_Locations
                        ->where("status", "!=", 3) //on tient pas compte des locations demenagées
                        ->pluck("id"))
                    ->latest()
                    ->where("state_facture", false); //on tient pas comptes des factures generée pour clotuer un étt
            }

            if ($request->isMethod('POST')) {
                $validated = $request->validate([
                    'user' => 'required|exists:users,id',
                    'debut' => 'required|date',
                    'fin' => 'required|date|after_or_equal:debut'
                ], [
                    'user.required' => 'Veuillez sélectionner un utilisateur',
                    'user.exists' => 'L\'utilisateur sélectionné n\'existe pas',
                    'debut.required' => 'La date de début est requise',
                    'fin.required' => 'La date de fin est requise',
                    'fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début'
                ]);

                $debut = $validated['debut'];
                $fin = $validated['fin'];

                $factures = $query
                    ->where("owner", $validated['user'])
                    ->whereDate("created_at", ">=", $validated['debut'])
                    ->whereDate("created_at", "<=", $validated['fin']);

                // return response()->json($factures->pluck("Owner"));

                alert()->success("Succès", "Filtre effectué avec succès");
            } else {

                if ($request->status) {
                    switch ($request->status) {
                        case "valide":
                            $factures = $query
                                ->where("status", 2);
                            break;
                        case "en_attente":
                            $factures = $query->where("status", 1);
                            break;
                        case "rejetee":
                            $factures = $query->where("status", 3);

                            break;
                        default:
                            $factures = $query;
                    }
                } else {
                    $factures = $query;
                }
            }

            $factures = $factures->get();

            if ($factures->isEmpty()) {
                alert()->info("Echec", "Aucun résultat trouvé pour les critères sélectionnés!");
                return back()
                    ->withInput();
            }

            $montantTotal = $factures->sum("amount");
            $users = User::select('id', 'name')->get();

            // Factures status
            $factureStatus = FactureStatus::get();

            return view("admin.factures", compact("agency", "factures", "montantTotal", "users", "factureStatus"));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            alert()->error("Echec", "Cette agence n'existe pas!");
            return back()->withInput();
        } catch (ValidationException $e) {
            return back()
                ->withInput()
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::info("Error" . $e->getMessage(), ["ligne" => $e->getLine()]);
            alert()->error("Echec", "Une erreur est survenue lors du traitement de votre demande");
            return back()->withInput();
        }
    }
}
