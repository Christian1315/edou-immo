<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\StopHouseElectricityStateController;
use App\Http\Controllers\StopHouseWaterStateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\LocationElectrictyFactureController;
use App\Http\Controllers\LocationWaterFactureController;
use App\Http\Controllers\PaiementInitiationController;
use App\Http\Controllers\ProprietorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoleController;
use App\Models\Facture;
use App\Models\House;
use App\Models\Locataire;
use App\Models\Location;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix("debug")->group(function () {
    // Route::get("/factures", function () {
    //     return Locataire::firstWhere(["prorata" => true]);
    //     Facture::query()->where("status", 3)->update(["facture_code" => null]);
    //     return "code des factures rejeté rendu null désormais";
    // });

    Route::get("/{id}/supervisor-data", function ($id) {
        $suservisor = User::findOrFail($id);

        $_locationsIds = $suservisor->_Agency->_Locations
            ->filter(function ($location) use ($suservisor) {
                return $location->House->supervisor == $suservisor->id;
            })->pluck("id")->toArray();

        // suppression des locations
        // Location::whereIn("id", $_locationsIds)->delete();

        // suppression des maisons
        // House::where("supervisor", $suservisor->id)->delete();
        return "Opération de supression des datas du superviseur effectué avec succès!";
    });
    // Superviseur William's

    Route::get("/{id}/roles", function ($id) {
        $user = User::findOrFail($id);
        return response()->json($user->roles->pluck("name"));
        return "Opération éffectuée avec succès....";
    });

    Route::get("/{id}/supervisors", function ($id) {
        $current_user = User::findOrFail($id);

        return User::with(["account_agents"])->get()
            ->filter(function ($user) use ($current_user) {
                if ($current_user->hasRole("Gestionnaire de compte")) {
                    // dd(in_array($user->id, $current_user->supervisors->pluck("id")->toArray()));
                    /** Pour un Gestionnaire de compte, on recupère juste ses superviseurs
                     */
                    return in_array($user->id, $current_user->supervisors->pluck("id")->toArray());
                } else {
                    return $user->hasRole('Superviseur');
                }
            })->pluck(["id"]);
    });
});

######============ HOME ROUTE ============#########################
Route::controller(HomeController::class)->group(function () {
    Route::get('/', "Home")->name("home");
});

// ROLES
Route::controller(RoleController::class)->group(function () {
    Route::get("/roles/index", "index")->name("roles.index");
    Route::post("/roles/store", "store")->name("roles.store");
    Route::get("/roles/{id}/retrieve", "retrieve")->name("roles.retrieve");
    Route::patch("/roles/{id}/update", "update")->name("roles.update");
    Route::any("/roles/{id}/destroy", "_destroy")->name("roles._destroy");
    Route::post("/roles/{id}/affect", "affectToUser")->name("roles.affectToUser");
    Route::post("/roles/{id}/remove", "removeFromUser")->name("roles.removeFromUser");
});

######============ USERS ROUTE ============#########################
Route::controller(UserController::class)->group(function () {
    Route::match(["GET", "POST"], '/login', "Login")->name("user.login");
    Route::get('/logout', "Logout")->name("logout");
    Route::match(["GET", "POST"], '/demande-reinitialisation', "DemandReinitializePassword")->name("demandeReinitialisation");
    Route::match(["GET", "POST"], '/reinitialisation', "ReinitializePassword")->name("Reinitialisation");
    Route::post('add', 'AddUser')->name("AddUser");

    Route::get('users/{id}/retrieve', 'RetrieveUser')->name("user.RetrieveUser");
    Route::any('users/{id}/roles', 'GetUserRoles')->name("user.GetUserRoles");
    Route::any('{id}/archive', 'ArchiveAccount')->name("user.ArchiveAccount");

    Route::any('password/demand_reinitialize', 'DemandReinitializePassword');
    Route::any('password/reinitialize', 'ReinitializePassword');

    Route::match(["POST", "GET"], 'attach-user/{user?}', 'AttachRoleToUser')->name("user.AttachRoleToUser"); #Attacher un droit au user 
    Route::post('desattach-user', 'DesAttachRoleToUser')->name("user.DesAttachRoleToUser"); #Attacher un droit au user 

    Route::get('{id}/duplicate', 'DuplicatAccount')->name("user.DuplicatAccount");
    Route::any('{id}/update', 'UpdateCompte')->name("user.UpdateCompte");
    Route::delete('{id}/delete', 'DeleteAccount')->name("user.DeleteAccount");
    Route::post('attach-supervisor-to-agent_account/{supervisor}', 'AffectSupervisorToAccountyAgent')->name("user.AffectSupervisorToAccountyAgent"); #Affecter un superviseur à un agent comptable

    ##___
    Route::any('users', 'Users');
    Route::any('users/{id}', 'RetrieveUser');
    Route::any('{id}/password/update', 'UpdatePassword')->name("user.updatePassword");

    Route::any('attach-user', 'AttachRightToUser'); #Attacher un droit au user 
});

###========== PROPRIETAIRE ========###
Route::prefix("proprietor")->group(function () {
    Route::controller(ProprietorController::class)->group(function () {
        Route::post('add', '_AddProprietor')->name("proprietor._AddProprietor"); #AJOUT D'UN PROPRIETAIRE
        Route::any('{id}/update', 'UpdateProprietor')->name("proprietor.UpdateProprietor"); # MODIFICATION D'UN PROPRIETAIRE 
    });
});
##___

###========== HOUSE ========###
Route::prefix("house")->group(function () {
    Route::controller(HouseController::class)->group(function () {
        Route::post('add-type', 'AddHouseType')->name("house.AddHouseType"); #AJOUT D'UN TYPE DE MAISON

        Route::post('add', '_AddHouse')->name("house._AddHouse"); #AJOUT D'UNE MAISON
        Route::patch('{id}/update', 'UpdateHouse')->name("house.UpdateHouse"); # MODIFICATION D'UNE MAISON  
        Route::delete('{id}/delete', 'DeleteHouse')->name("house.DeleteHouse"); # SUPPRESSION D'UNE MAISON  

        Route::get('/{id}/{agencyId}/stopHouseState', "StopHouseState")->name("stopHouseState");
        Route::post('/{houseId}/generate_cautions_for_house_by_period', "GenerateCautionByPeriod")->name("house.GenerateCautionByPeriod");

        ##=========__ ARRETER LES ETATS DES MAISON ======
        Route::post('stop/{houseId}', 'PostStopHouseState')->name("house.PostStopHouseState"); ####___ARRET DES ETATS D'UNE MAISON;
        Route::get('{houseId}/imprime_house_last_state', "ShowHouseStateImprimeHtml")->name("house.ShowHouseStateImprimeHtml"); # DERNIER ETAT D' une maison 

        // FILTRAGE
        Route::post('filtrebysupervisor/{houseId}', 'FiltreHouseBySupervisor')->name("house.FiltreHouseBySupervisor"); ####___FILTRE DES MAISONS PAR SUPERVISEUR;
        Route::post('filtrebyperiode/{houseId}', 'FiltreHouseByPeriod')->name("house.FiltreHouseByPeriode"); ####___FILTRE DES MAISONS PAR PERIODE;
    });
});

// CAUTION HTML
Route::controller(LocationController::class)->group(function () {
    Route::get("{agencyId}/caution_html", "_ShowCautionsByAgency");
    Route::get("{first_date}/{last_date}/caution_html_by_period", "_ShowCautionsByPeriod");
    Route::any("{houseId}/caution_html_by_house", "_ShowCautionsByHouse");
    Route::get("{agencyId}/show_prestation_statistique", "_ShowPrestationStatistique");
    Route::get("/caution", "_ManageCautions");
    Route::get("{houseId}/{first_date}/{last_date}/caution_html_for_house_by_period", "_ShowCautionsForHouseByPeriod");
});

Route::controller(AdminController::class)->group(function () {
    ###___GENERALES AGENCIES ROUTES
    Route::get('/{agency}/manage-agency', "ManageAgency")->name("manage-agency");

    ###___GENERALES ROUTES
    Route::get('count', "AccountSold")->name("count");
    Route::get('setting', "Setting")->name("setting");

    Route::match(["GET", "POST"], '/{agency}/paiement', "Paiement")->name("paiement");
    Route::get('/{agency}/paiement/{house?}', "Paiement")->name("paiementForAHouse");

    Route::get('/{agency}/initiation', "AgencyInitiation")->name("agency-initiation");

    Route::match(["GET", "POST"], '/{agency}/factures', "LocationFactures")->name("locationFacture");

    Route::get('/{agency}/caisses', "Caisses")->name("caisses");
    Route::get('/{agency}/{agency_account}/caisse-mouvements', "CaisseMouvements")->name("caisse-mouvements");

    Route::get('/{agency}/encaisser', "Encaisser")->name("encaisser");
    Route::get('/{agency}/decaisser', "Decaisser")->name("decaisser");

    #######
    Route::get("/{agency}/proprietor", "Proprietor")->name("proprietor");
    Route::get('/{agency}/house', "House")->name("house");

    Route::get('/{agency}/room', "Room")->name("room");
    Route::get('/{agency}/locator', "Locator")->name("locator");
    Route::get('/{agency}/paid_locators', "PaidLocator")->name("paid-locator");
    Route::get('/{agency}/unpaid_locators', "UnPaidLocator")->name("unpaid-locator");
    Route::get('/{agency}/removed_locators', "RemovedLocators")->name("removed-locators");
    Route::get('/{agency}/location', "Location")->name("location");

    Route::get('/{agency}/electricity/locations', "Electricity")->name("electricity");
    Route::get('/{agency}/eau/locations', "Eau")->name("eau");

    /** AGENCY STATISTIQUES */
    Route::any('/{agency}/statistique-before-state', "AgencyStatistiqueBeforeState")->name("agencyStatistiqueBeforeState");
    Route::any('/{agency}/statistique-after-state', "AgencyStatistiqueAfterState")->name("agencyStatistiqueAfterState");

    Route::get('/{agency}/recovery_05_to_echeance_date', "AgencyRecovery05")->name("recovery_05_to_echeance_date");
    Route::get('/{agency}/recovery_10_to_echeance_date', "AgencyRecovery10")->name("recovery_10_to_echeance_date");
    Route::get('/{agency}/recovery_qualitatif', "AgencyRecoveryQualitatif")->name("recovery_qualitatif");
    Route::get('/{agency}/performance', "AgencyPerformance")->name("performance");

    Route::get('/{agency}/recovery_quelconque_date', "RecoveryAtAnyDate")->name("recovery_quelconque_date");
    Route::post('/{agency}/recovery_quelconque_date_filtrage', "FiltreByDateInAgency")->name("recovery_quelconque_date.FiltreByDateInAgency");

    Route::get('/{agency}/filtrage', "Filtrage")->name("filtrage");

    ###___GENERALES ROUTES
    Route::get('dashbord', "Admin")->name("dashbord");
    Route::get('agency', "Agencies")->name("agency");
    Route::get('/paiement', "PaiementAll")->name("paiementAll");
    Route::get('supervisor', "Supervisors")->name("supervisor");
    Route::get('statistique', "Statistique")->name("statistique");
});

###========== ROOM ========###
Route::prefix("room")->group(function () {
    Route::controller(RoomController::class)->group(function () {
        Route::prefix("type")->group(function () {
            Route::any('add', 'AddRoomType')->name("room.AddType"); ##__AJOUT D'UN TYPE DE CHAMBRE
        });

        Route::prefix("nature")->group(function () {
            Route::any('add', 'AddRoomNature')->name("room.AddRoomNature"); ##__AJOUT D'UNE NATURE
        });

        Route::post('add', 'AddRoom')->name("room._AddRoom"); #AJOUT D'UNE CHAMBRE
        Route::patch('{id}/update', 'UpdateRoom')->name("room.UpdateRoom"); #MODIFICATION D'UNE CHAMBRE 
        Route::delete('{id}/delete', 'DeleteRoom')->name("room.DeleteRoom"); #SUPPRESSION D'UNE CHAMBRE 

        // FILTRAGE
        Route::post('filtrebysupervisor/{agencyId}', 'FiltreRoomBySupervisor')->name("room.FiltreRoomBySupervisor"); ####___FILTRE DES CHAMBRES PAR SUPERVISEUR;
        Route::post('filtrebyhouse/{agencyId}', 'FiltreRoomByHouse')->name("room.FiltreRoomByHouse"); ####___FILTRE DES CHAMBRES PAR MAISON;
    });
});
##___

###========== LOCATAIRE ========###
Route::prefix("locataire")->group(function () {
    Route::controller(LocataireController::class)->group(function () {
        Route::any('add', '_AddLocataire')->name("locator._AddLocataire"); #AJOUT D'UN LOCATAIRE
        Route::any('{id}/update', 'UpdateLocataire')->name("locator.UpdateLocataire"); #RECUPERATION D'UN LOCATAIRE 
        Route::delete('{id}/delete', 'DeleteLocataire')->name("locator.DeleteLocataire"); #SUPPRESSION D'UN LOCATAIRE
        Route::post('{agency}/filtre_by_supervisor', 'FiltreBySupervisor')->name("locator.FiltreBySupervisor"); #FILTRER PAR SUPERVISEUR
        Route::post('{agency}/filtre_by_house', 'FiltreByHouse')->name("locator.FiltreByHouse"); #FILTRER PAR MAISON

        ####___A JOUR 
        Route::post('{agency}/paid/filtre_by_supervisor', 'PaidFiltreBySupervisor')->name("locator.PaidFiltreBySupervisor"); #LOCATAIRE A JOUR FILTRER PAR SUPERVISEUR
        Route::post('{agency}/paid/filtre_by_house', 'PaidFiltreByHouse')->name("locator.PaidFiltreByHouse"); #LOCATAIRE A JOUR FILTRER PAR MAISON

        ####___NON A JOUR (impayés)
        Route::post('{agency}/unpaid/filtre_by_supervisor', 'UnPaidFiltreBySupervisor')->name("locator.UnPaidFiltreBySupervisor"); #LOCATAIRE NON A JOUR FILTRER PAR SUPERVISEUR
        Route::post('{agency}/unpaid/filtre_by_house', 'UnPaidFiltreByHouse')->name("locator.UnPaidFiltreByHouse"); #LOCATAIRE NON A JOUR FILTRER PAR MAISON

        ####___DEMENAGES
        Route::post('{agency}/removed/filtre_by_supervisor', 'RemovedFiltreBySupervisor')->name("locator.RemovedFiltreBySupervisor"); #LOCATAIRE DEMENAGES FILTRER PAR SUPERVISEUR
        Route::post('{agency}/removed/filtre_by_house', 'RemovedFiltreByHouse')->name("locator.RemovedFiltreByHouse"); #LOCATAIRE DEMENAGES FILTRER PAR MAISON

        ###___
        Route::any('{agencyId}/recovery_05_to_echeance_date', 'Recovery05ToEcheanceDate'); ###_______
        Route::any('{agencyId}/recovery_10_to_echeance_date', 'Recovery10ToEcheanceDate'); ###_______
        Route::any('{agencyId}/recovery_qualitatif', 'RecoveryQualitatif'); ###_______
    });
});
##___

###========== LOCATION ========###
Route::prefix("location")->group(function () {
    Route::controller(LocationController::class)->group(function () {
        Route::prefix("type")->group(function () {
            Route::post('add', '_AddType')->name("location.AddType"); ##__AJOUT DE TYPE DE LOCATION
        });
        Route::get("{agencyId}/generate_cautions", "_ManageCautions")->name("location._ManageCautions"); #GENERATE CAUTION POUR TOUTE L4AGENCE
        Route::get("location/{locationId}/generate_cautions", "_ManageLocationCautions")->name("location._ManageLocationCautions"); #GENERATE CAUTION UNE LOCATION
        Route::get("location/{locationId}/generate_prorata", "_ManageLocationProrata")->name("location._ManageLocationProrata"); #GENERATE ETAT DES PRORATAS
        Route::post("{agencyId}/prestation_statistique_for_agency_by_period", "_ManagePrestationStatistiqueForAgencyByPeriod")->name("location._ManagePrestationStatistiqueForAgencyByPeriod"); #GENERATE PRESATION BY PERIODE
        Route::post('add', '_AddLocation')->name("location._AddLocation"); #AJOUT D'UNE LOCATION
        Route::patch('{id}/update', 'UpdateLocation')->name("location.UpdateLocation"); #MODIFICATION D'UNE LOCATION
        Route::get('/location/{location}/imprimer', "Imprimer")->name("location.imprimer");
        Route::post('/demenage', 'DemenageLocation')->name("location.DemenageLocation"); #DEMENAGEMENT D'UNE LOCATION 

        Route::any('add-paiement', '_AddPaiement')->name("location._AddPaiement"); #AJOUT D'UN PAIEMENT
        Route::post('{id}/facture-traitement', 'FactureTraitement')->name("location.FactureTraitement"); #TRAITEMENT DE LA FACTURE

        // FILTRE LOCATION
        Route::post('{agency}/location_filtre_by_supervisor', 'FiltreBySupervisor')->name("location.FiltreBySupervisor"); #FILTRER PAR SUPERVISEUR
        Route::post('{agency}/location_filtre_by_house', 'FiltreByHouse')->name("location.FiltreByHouse"); #FILTRER PAR MAISON
        Route::post('{agency}/location_filtre_by_proprio', 'FiltreByProprio')->name("location.FiltreByProprio"); #FILTRER PAR PROPRIETAIRE

        // IMPRESSION
        Route::post('{agency}/print_all_location_by_supervisor', 'PrintAllLocationBySupervisor')->name("location.PrintAllLocationBySupervisor"); #IMPRRIMER TOUTES LES LOCATIONS PAR SUPERVISEUR
        Route::post('{agency}/print_unpaid_location_by_supervisor', 'PrintUnPaidLocationBySupervisor')->name("location.PrintUnPaidLocationBySupervisor"); #IMPRRIMER TOUTES LES LOCATIONS IMPAYES PAR SUPERVISEUR


        ###___GESTION DES FACTURES D'ELECTRICITE DANS UNE LOCATION

        #UPDATE END_INDEX 
        Route::patch('electricity/{factureId}/update_end_index', 'ElectricityUpdateEndIndex')->name("location.ElectricityUpdateEndIndex");
        Route::patch('water/{factureId}/update_end_index', 'WaterUpdateEndIndex')->name("location.WaterUpdateEndIndex");

        ##=========__ ARRETER LES ETATS D'ELECTRICITE DES MAISON ======
        Route::any('stop', '_StopStatsOfHouse')->name("location._StopStatsOfHouse");

        ####__STATISTIQUE DES AGENCES (payement avant & apres arret des etats)
        Route::any('{houseId}/filtre_before_stateDate_stoped', 'FiltreBeforeStateDateStoped')->name("location.FiltreBeforeStateDateStoped"); #FILTRER LES PAIEMENTS AVANT ARRET DES ETATS
        Route::any('{houseId}/filtre_after_stateDate_stoped', 'FiltreAfterStateDateStoped')->name("location.FiltreAfterStateDateStoped"); #FILTRER LES PAIEMENTS APRES ARRET DES ETATS

        Route::post('{agency}/filtre_by_supervisor', 'FiltreBySupervisor')->name("locator.FiltreBySupervisor"); #FILTRER PAR SUPERVISEUR
    });
});
##___

##========__PAIEMENT INITIATION MANAGEMENT======
Route::prefix("payement_initiation")->group(function () {
    Route::controller(PaiementInitiationController::class)->group(function () {
        Route::post('initiateToProprio', 'InitiatePaiementToProprietor')->name("payement_initiation.InitiatePaiementToProprietor"); ## INITIER UN PAIEMENT A UN PROPRIETAIRE
        Route::any('{id}/valide', 'ValidePaiementInitiation')->name("payement_initiation.ValidePaiementInitiation"); ## VALIDATION D'UNE INITIATION DE PAIUEMENT
        Route::post('{id}/rejet', 'RejetPayementInitiation')->name("payement_initiation.RejetPayementInitiation"); ## REJET D'UNE INITIATION DE PAIUEMENT
    });
});

###========== AGENCY ROUTINGS ========###
Route::controller(AgencyController::class)->group(function () {
    Route::prefix('agency')->group(function () {
        Route::any('add', 'AddAgency')->name("AddAgency"); #AJOUT D'UN AGENCY
        Route::any('all', 'Agencys'); #GET ALL AGENCYS
        Route::any('{id}/delete', 'DeleteAgency'); #SUPPRESSION D'UN AGENCY
        Route::any('{id}/update', 'UpdateAgency'); #MODIFICATION D'UN AGENCY
    });
});

##__ACCOUNT_SOLD MANAGEMENT
Route::prefix("sold")->group(function () {
    Route::controller(AgencyController::class)->group(function () {
        Route::post('creditate', '_CreditateAccount')->name("sold._CreditateAccount"); ## CREDITATION DU SOLDE DU COMPTE D'UNE AGENCE
        Route::post('decreditate', '_DeCreditateAccount')->name("sold._DeCreditateAccount"); ## DECREDITATION DU SOLDE DU COMPTE D'UNE AGENCE
    });
});

###___GESTION DES FACTURES D'ELECTRICITE DANS UNE LOCATION
Route::prefix("electricity_facture")->group(function () {
    Route::controller(LocationElectrictyFactureController::class)->group(function () {
        Route::post("generate", "_GenerateFacture")->name("electricity_facture._GenerateFacture"); ##__generer la facture
        Route::any("{id}/payement", "_FacturePayement")->name("electricity_facture._FacturePayement"); ##__Payement d'une facture d'electricité

        ##=========__ ARRETER LES ETATS D'ELECTRICITE DES MAISON ======
        Route::prefix("house_state")->group(function () {
            Route::post('stop', '_StopStatsOfHouse')->name("house_state._StopStatsOfHouse"); ###___ARRETER LES ETATS EN ELECTRICITE D'UNE MAISON
        });

        ###____impression des etats de factures eau-electricité
        Route::get("{state}/show_electricity_state_html", [StopHouseElectricityStateController::class, "ShowStateImprimeHtml"])->name("house_state.ImprimeElectricityHouseState");

        // FILTRE LOCATION
        Route::post('{agency}/electricity_location_filtre_by_supervisor', 'ElectricityFiltreBySupervisor')->name("location.ElectricityFiltreBySupervisor"); #FILTRER PAR SUPERVISEUR
        Route::post('{agency}/electricity_location_filtre_by_house', 'ElectricityFiltreByHouse')->name("location.ElectricityFiltreByHouse"); #FILTRER PAR MAISON
        Route::post('{agency}/electricity_location_filtre_by_proprio', 'ElectricityFiltreByProprio')->name("location.ElectricityFiltreByProprio"); #FILTRER PAR PROPRIETAIRE
    });
});

###___GESTION DES FACTURES D'ELECTRICITE DANS UNE LOCATION
Route::prefix("water_facture")->group(function () {
    Route::controller(LocationWaterFactureController::class)->group(function () {
        Route::post("generate", "_GenerateFacture")->name("water_facture._GenerateFacture"); ##__generer la facture
        Route::any("{id}/payement", "_FacturePayement")->name("water_facture._FactureWaterPayement"); ##__Payement d'une facture d'electricité

        ##=========__ ARRETER LES ETATS D'ELECTRICITE DES MAISON ======
        Route::prefix("house_state")->group(function () {
            Route::post('stop', '_StopStatsOfHouse')->name("house_state._StopWaterStatsOfHouse"); ###___ARRETER LES ETATS EN ELECTRICITE D'UNE MAISON
        });

        ###____impression des etats de factures eau-electricité
        Route::get("{state}/show_water_state_html", [StopHouseWaterStateController::class, "ShowWaterStateImprimeHtml"])->name("house_state.ShowWaterStateImprimeHtml");

        // FILTRE LOCATION
        Route::post('{agency}/water_location_filtre_by_supervisor', 'WaterFiltreBySupervisor')->name("location.WaterFiltreBySupervisor"); #FILTRER PAR SUPERVISEUR
        Route::post('{agency}/water_location_filtre_by_house', 'WaterFiltreByHouse')->name("location.WaterFiltreByHouse"); #FILTRER PAR MAISON
        Route::post('{agency}/water_location_filtre_by_proprio', 'WaterFiltreByProprio')->name("location.WaterFiltreByProprio"); #FILTRER PAR PROPRIETAIRE
    });
});


####____impression des taux  locataires
##__05
Route::get("{agencyId}/show_taux_05_agency_simple", [LocataireController::class, "_ShowAgencyTaux05_Simple"])->name("taux._ShowAgencyTaux05_Simple");
Route::get("{agencyId}/show_taux_05_agency_by_supervisor", [LocataireController::class, "_ShowAgencyTaux05_By_Supervisor"])->name("taux._ShowAgencyTaux05_By_Supervisor");
Route::get("{agencyId}/show_taux_05_agency_by_house", [LocataireController::class, "_ShowAgencyTaux05_By_House"])->name("taux._ShowAgencyTaux05_By_House");

##__10
Route::get("{agencyId}/show_taux_10_agency_simple", [LocataireController::class, "_ShowAgencyTaux10_Simple"])->name("taux._ShowAgencyTaux10_Simple");
Route::get("{agencyId}/show_taux_10_agency_by_supervisor", [LocataireController::class, "_ShowAgencyTaux10_By_Supervisor"])->name("taux._ShowAgencyTaux10_By_Supervisor");
Route::get("{agencyId}/show_taux_10_agency_by_house", [LocataireController::class, "_ShowAgencyTaux10_By_House"])->name("taux._ShowAgencyTaux10_By_House");

##__qualitatif
Route::get("{agencyId}/show_taux_qualitatif_simple", [LocataireController::class, "_ShowAgencyTauxQualitatif_Simple"])->name("taux._ShowAgencyTauxQualitatif_Simple");
Route::get("{agencyId}/show_taux_qualitatif_by_supervisor", [LocataireController::class, "_ShowAgencyTauxQualitatif_By_Supervisor"])->name("taux._ShowAgencyTauxQualitatif_By_Supervisor");
Route::get("{agencyId}/show_taux_qualitatif_by_house", [LocataireController::class, "_ShowAgencyTauxQualitatif_By_House"])->name("taux._ShowAgencyTauxQualitatif_By_House");


Route::get("{agencyId}/{houseId}/{action}/locators_state_stoped", [LocationController::class, "_ShowLocatorStateStoped"]);
