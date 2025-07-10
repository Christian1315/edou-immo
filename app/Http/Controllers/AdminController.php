<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Facture;
use App\Models\FactureStatus;
use App\Models\House;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
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

    function Electricity(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____
        return view("admin.electricity", compact("agency"));
    }

    function AgencyStatistique(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.agency-statistique", compact("agency"));
    }


    #####____BILAN
    function Filtrage(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.filtrage", compact("agency"));
    }

    #####____RECOUVREMENT A LA DATE 05
    function AgencyRecovery05(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.recovery05", compact("agency"));
    }

    #####____RECOUVREMENT A LA DATE 10
    function AgencyRecovery10(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.recovery10", compact("agency"));
    }

    function AgencyRecoveryQualitatif(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.recovery_qualitatif", compact("agency"));
    }

    function AgencyPerformance(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ####____

        return view("admin.performance", compact("agency"));
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

            if ($factures->isEmpty()) {
                alert()->info("Information", "Aucune facture trouvée pour cette date");
                return back()->withInput();
            }

            // Récupération des locations avec eager loading
            $locations = Location::where("agency", deCrypId($agencyId))
                ->whereIn("id", $factures->pluck("location"))
                ->with('Locataire') // Eager loading pour éviter le N+1 problem
                ->get();

            // Récupération des locataires de manière plus efficace
            $locators = $locations->pluck('Locataire')->filter()->values();

            if ($locators->isEmpty()) {
                alert()->info("Information", "Aucun locataire trouvé pour cette date");
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

    function Eau(Request $request, $agencyId)
    {
        $agency = Agency::where("visible", 1)->find(deCrypId($agencyId));
        if (!$agency) {
            alert()->error("Echec", "Cette agence n'existe pas!");
        };
        ##___

        return view("admin.eau_locations", compact("agency"));
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

            $query = Facture::with(["Location"])
                ->whereIn("location", $agency->_Locations
                    ->where("status", "!=", 3) //on tient pas compte des locations demenagées
                    ->pluck("id"))
                ->where("state_facture", false); //on tient pas comptes des factures generée pour clotuer un étt


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

                $factures = $query
                    ->where("owner", $validated['user'])
                    ->whereDate("created_at", ">=", $validated['debut'])
                    ->whereDate("created_at", "<=", $validated['fin'])
                    ->get();

                if ($factures->isEmpty()) {
                    alert()->error("Echec", "Aucun résultat trouvé pour les critères sélectionnés!");
                    return back()
                        ->withInput();
                }

                alert()->success("Succès", "Filtre effectué avec succès");
            } else {

                if ($request->status) {
                    // dd($request->status);
                    switch ($request->status) {
                        case "valide":
                            $query->where("status", 2);
                            // dd($query->where("status", 2)->get());
                            break;
                        case "en_attente":
                            $query->where("status", 1);
                            break;
                        case "rejetee":
                            $query->where("status", 3);
                            break;
                        default:
                            $query;
                    }
                }
                $factures = $query->get();
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
            alert()->error("Echec", "Une erreur est survenue lors du traitement de votre demande");
            return back()->withInput();
        }
    }
}
