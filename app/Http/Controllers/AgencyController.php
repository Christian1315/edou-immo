<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\AgencyAccount;
use App\Models\AgencyAccountSold;
use App\Models\Country;
use App\Models\House;
use App\Models\ImmoAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgencyController extends Controller
{
    #VERIFIONS SI LE USER EST AUTHENTIFIE
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    #########========== VALIDATION  =========#########
    // ======== AGENCY VALIDATION =======//
    static function Agency_rules(): array
    {
        return [
            'name' => ['required'],
            'phone' => ['required', "numeric"],

            'country' => ['required', "integer"],
            'city' => ['required'],
        ];
    }

    static function Agency_messages(): array
    {
        return [
            'name.required' => "Le nom de l'agence est réquis!",

            'phone.required' => "Le phone de l'agence est réquis!",
            'phone.numeric' => "Le phone de l'agence doit être de type numérique!",

            'country.required' => "Le pays de l'agence est réquis!",
            'country.integer' => "Le champ *country* de l'agence doit être de type entier!",

            'city.required' => "La commune de l'agence est réquise!",
            'city.integer' => "Le champ *city* de l'agence doit être de type entier!",
        ];
    }


    ##########======= GESTION DES METHODES ==============#########
    function AddAgency(Request $request)
    {
        $rules = self::Agency_rules();
        $messages = self::Agency_messages();

        Validator::make($request->all(), $rules, $messages)->validate();

        #ENREGISTREMENT DANS LA DB VIA **_createAgency** DE LA CLASS BASE_HELPER HERITEE PAR AGENCY_HELPER
        $user = request()->user();
        $formData = $request->all();

        ###___VOYONS D'ABORD SI CETTE AGENCE EXISTE DEJA
        $agency = Agency::where(["name" => $formData["name"]])->first();

        if ($agency) {
            alert()->error('Echec', "Cette agence existe déjà!");
            return redirect()->back()->withInput();
        }

        ###___
        $country = Country::find($formData["country"]);
        if (!$country) {
            alert()->error('Echec', "Ce pays n'existe pas!");
            return redirect()->back()->withInput();
        }

        $type = "AGY";

        ##GESTION DES FICHIERS
        if ($request->file('ifu_file')) {
            $ifu_file = $request->file('ifu_file');
            $ifu_name = $ifu_file->getClientOriginalName();
            $ifu_file->move("pieces", $ifu_name);
            $formData["ifu_file"] = asset("pieces/" . $ifu_name);
        }

        if ($request->file('rccm_file')) {
            $rccm_file = $request->file('rccm_file');
            $rccm_name = $rccm_file->getClientOriginalName();
            $rccm_file->move("pieces", $rccm_name);
            $formData["rccm_file"] = asset("pieces/" . $rccm_name);
        }

        ####___

        $formData["number"] = Add_Number($user, $type); ##Add_Number est un helper qui genère le **number** 
        if (!$request->get("owner")) {
            $formData["owner"] = $user->id;
        } else {
            $formData["owner"] = $request->get("owner");
        }

        $formData["email"] = $request->email ? $request->email : "null";

        #ENREGISTREMENT DE L'AGENCE DANS LA DB
        $created_agency = Agency::create($formData);

        ###___GENERATION DES COMPTES DE CETTE AGENCE
        $all_accounts = ImmoAccount::all();
        foreach ($all_accounts as $account) {
            AgencyAccount::create(
                [
                    "agency" => $created_agency->id,
                    "account" => $account->id,
                ]
            );
        }
        ###___


        alert()->success('Succès', "Agence ajoutée avec succès!!");
        return redirect()->back()->withInput();
    }


    ######____CREDITATION D'UN COMPTE
    function _CreditateAccount(Request $request, $out_call = false)
    {

        $user = request()->user();

        ##__quand c'est un appel externe
        $formData = $request->all();

        ##__
        $agency_account = AgencyAccount::with(["_Account"])->where(["agency" => $formData["agency"]])->find($formData["agency_account"]);
        if (!$agency_account) {
            alert()->error("Echec", "Ce compte d'agence n'existe pas! Vous ne pouvez pas le créditer!");
            return back()->withInput();
        }

        $account = $agency_account->_Account;

        $formData["sold_added"] = $formData["sold"];

        ###___VERIFIONS LE SOLD ACTUEL DU COMPTE ET VOYONS SI ça DEPPASE OU PAS LE PLAFOND
        $agencyAccountSold = AgencyAccountSold::where(["agency_account" => $formData["agency_account"], "visible" => 1])->first();

        if ($agencyAccountSold) { ##__Si ce compte dispose déjà d'un sold
            $formData["old_sold"] = $agencyAccountSold->sold;

            ##__voyons si le sold atteint déjà le plafond de ce compte
            if ($agencyAccountSold->sold >= $account->plafond_max) {
                alert()->error("Echec", "Le sold de ce compte (" . $account->name . ") a déjà atteint son plafond! Vous ne pouvez plus le créditer");
                return back()->withInput();
            } else {
                # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
                # ça depasserait le plafond maximum du compte

                if (($agencyAccountSold->sold + $formData["sold"]) > $account->plafond_max) {
                    alert()->error("Echec", "L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant");
                    return back()->withInput();
                }
            }

            ###__creditation proprement dite du compte
            #__Deconsiderons l'ancien sold
            $agencyAccountSold->visible = 0;
            $agencyAccountSold->delete_at = now();
            $agencyAccountSold->save();

            #__Construisons un nouveau sold(en se basant sur les datas de l'ancien sold)
            $formData["agency_account"] = $agencyAccountSold->agency_account; ##__ça revient à l'ancien compte
            $formData["sold"] = $agencyAccountSold->sold + $formData["sold"];

            $agencyAccountSold = AgencyAccountSold::create($formData);
        } else {
            # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
            # ça depasserait le plafond maximum du compte
            $formData["old_sold"] = 0;

            if ($formData["sold"] > $account->plafond_max) {
                alert()->error("Echec", "L'ajout de ce montant au sold de ce compte (" . $account->name . ") dépasserait son plafond! Veuillez diminuer le montant");
                return back()->withInput();
            }

            # on le crée
            $agencyAccountSold = AgencyAccountSold::create($formData);
        }

        ####____
        alert()->success("Succès", "Le compte (" . $account->name . " (" . $account->description . ") " . ") a été crédité  avec succès!!");
        return back()->withInput();
    }

    ######____DECREDITATION DE COMPTE
    function _DeCreditateAccount(Request $request)
    {
        $user = request()->user();
        $formData = $request->all();

        ##__
        $agency_account = AgencyAccount::find($formData["agency_account"]);
        if (!$agency_account) {
            alert()->error('Echèc', "L'agence ne dispose pas de ce compte !");
            return redirect()->back()->withInput();
        }

        $formData["sold_retrieved"] = $formData["sold"];

        $account = $agency_account->_Account;

        ###___VERIFIONS LE SOLD ACTUEL DU COMPTE ET VOYONS SI ça DEPPASE OU PAS LE PLAFOND
        $agencyAccountSold = AgencyAccountSold::where(["agency_account" => $formData["agency_account"], "visible" => 1])->first();


        ###___
        if (!$agencyAccountSold) {
            alert()->error('Echèc', "Désolé! Ce compte ne dispose pas de solde!");
            return redirect()->back()->withInput();
        }

        $formData["old_sold"] = $agencyAccountSold->sold;

        # voyons si en ajoutant le montant actuel **$formData["sold"]** au sold du compte
        # ça descendrait en bas de 0
        if (($agencyAccountSold->sold - $formData["sold"]) < 0) {
            alert()->error('Echèc', "La décreditation de ce montant au sold de ce compte (" . $account->name . ") descendrait en dessous de 0!");
            return redirect()->back()->withInput();
        }

        ##__Quant il s'agit de la caisse CDR
        if ($account->id == 3) {
            if (!$request->get("house")) {
                alert()->error('Echèc', "Pour le compte CDR, la maison est réquise!");
                return redirect()->back()->withInput();
            }

            ###___
            $house = House::find($request->get("house"));
            if (!$house) {
                alert()->error('Echèc', "Désolé! Cette maison n'existe pas!");
                return redirect()->back()->withInput();
            }
        } else {
            $formData["house"] = null;
        }

        #__Construisons un nouveau sold(en se basant sur les datas de l'ancien sold)
        $formData["agency_account"] = $agencyAccountSold->agency_account; ##__ça revient à l'ancien compte
        $formData["sold_retrieved"] = $formData["sold"];
        $formData["sold"] = $agencyAccountSold->sold - $formData["sold"];

        ###__creditation proprement dite du compte
        #__Deconsiderons l'ancien sold
        $agencyAccountSold->visible = 0;
        $agencyAccountSold->delete_at = now();
        $agencyAccountSold->save();

        $agencyAccountSold = AgencyAccountSold::create($formData);

        ###___
        alert()->success('Succès', "Le compte (" . $account->name . " (" . $account->description . ") " . ") a été décrédité  avec succès!!");
        return redirect()->back()->withInput();
    }
}
