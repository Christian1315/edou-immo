<?php

use App\Models\Facture;
use App\Models\Right;
use App\Models\User;
use App\Models\UserRole;
use App\Notifications\SendNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


function users()
{
    return User::all(["id", "name"]);
}

/**Loactaires du 05 */
function recovery05Locators($houses)
{
    $DATE_FORMAT = 'Y/m/d';

    $locators = collect();

    $houses->each(function ($house) use ($locators, $DATE_FORMAT) {
        $lastState = $house->States->last();
        $lastStateDate = Carbon::parse($lastState->stats_stoped_day)->format($DATE_FORMAT);

        $filterredFactures = $lastState->Factures
            ->filter(function ($facture) use ($lastStateDate, $DATE_FORMAT) {
                $location = $facture->Location;
                $echeanceDate = Carbon::parse($facture->echeance_date)->format($DATE_FORMAT);
                $previousEcheanceDate = Carbon::parse($location->previous_echeance_date)->format($DATE_FORMAT);

                return isValidPaymentDate05Locators($lastStateDate, $echeanceDate, $previousEcheanceDate);
            });

        /** */
        $filterredFactures
            ->pluck("Location")
            ->each(function ($location) use ($locators) {
                $location->Locataire["locator_location"] = $location;
                $locators[] = $location->Locataire;
            });
    });

    return $locators;
}

/**Loactaires du 10 */
function recovery10Locators($houses)
{
    $DATE_FORMAT = 'Y/m/d';

    $locators = collect();

    foreach ($houses as $house) {
        $lastState = $house->States->last();

        if (!$lastState) {
            continue;
        }

        $stateStopDate = Carbon::parse($lastState->stats_stoped_day)->format($DATE_FORMAT);

        foreach ($lastState->Factures as $facture) {
            $location = $facture->Location;
            $paymentDate = Carbon::parse($facture->echeance_date)->format($DATE_FORMAT);
            $echeanceDate = Carbon::parse($location->previous_echeance_date)->format($DATE_FORMAT);

            if (isValidPayment10Locators($stateStopDate, $paymentDate, $echeanceDate)) {
                $location->Locataire["locator_location"] = $location;
                $location->Locataire["payment_date"] = $paymentDate;
                $locators[] = $location->Locataire;
            }
        }
    }
    return $locators;
}

/**Locataires Qualitatifs(reovery 05 ou 10) */
function recoveryQualitatifLocators($houses)
{
    $DATE_FORMAT = 'Y/m/d';
    $locators = collect();

    foreach ($houses as $house) {
        $lastState = $house->States->last();

        if (!$lastState) {
            continue;
        }

        $stateStopDate = Carbon::parse($lastState->stats_stoped_day)->format($DATE_FORMAT);

        foreach ($lastState->Factures as $facture) {
            $location = $facture->Location;
            $paymentDate = Carbon::parse($facture->echeance_date)->format($DATE_FORMAT);
            $echeanceDate = Carbon::parse($location->previous_echeance_date)->format($DATE_FORMAT);

            if (isValidPayment10Locators($stateStopDate, $paymentDate, $echeanceDate) || isValidPaymentDate05Locators($stateStopDate, $paymentDate, $echeanceDate)) {
                $location->Locataire["locator_location"] = $location;
                $location->Locataire["payment_date"] = $paymentDate;
                $locators[] = $location->Locataire;
            }
        }
    }
    return $locators;
}

/**
 * Vérifie si le paiement est valide selon les critères métier
 */
function isValidPayment10Locators(string $stateStopDate, string $paymentDate, string $echeanceDate): bool
{
    $TARGET_DAY = '10';

    $dueDay = Carbon::parse($echeanceDate)->format('d');

    return $stateStopDate > $paymentDate
        && $paymentDate <= $echeanceDate
        && $dueDay === $TARGET_DAY;
}

/**
 * Vérifie si le paiement est valide selon les critères métier
 */
function isValidPaymentDate05Locators(string $stateDate, string $echeanceDate, string $previousEcheanceDate): bool
{
    $TARGET_DAY = '05';

    try {
        $dueDay = Carbon::parse($previousEcheanceDate)->format('d');
        return $stateDate > $echeanceDate
            && $echeanceDate <= $previousEcheanceDate
            && $dueDay === $TARGET_DAY;
    } catch (\Exception $e) {
        Log::error("Erreure lors du chargement de isValidPaymentDate() " . $e->getMessage());
    }
}

function supervisors()
{
    $users = User::with(["account_agents"])->get()->filter(function ($user) {
        if (Auth::user()->hasRole("Gestionnaire de compte")) {
            /** Pour un Gestionnaire de compte, on recupère juste ses superviseurs
             */
            return in_array($user->id, Auth::user()->supervisors
                ->pluck("id")
                ->toArray());
        }

        return $user->hasRole('Superviseur');
    });
    return $users;
}

function gestionnaires()
{
    return User::with(["account_agents"])->get()->filter(fn($user) => $user->hasRole('Gestionnaire de compte'));
}

function IS_USER_HAS_ACCOUNT_CHIEF_ROLE($user)
{
    if (in_array(env("ACCOUNT_CHIEF_ROLE_ID"), $user->_roles->pluck("id")->toArray())) {
        return true;
    }

    return false;
}

function IS_USER_HAS_MASTER_ROLE($user)
{
    if (in_array(env("MASTER_ROLE_ID"), $user->_roles->pluck("id")->toArray())) {
        return true;
    }

    return false;
}

function crypId($id)
{
    return Crypt::encrypt($id);
}

function deCrypId($id)
{
    return Crypt::decrypt($id);
}

function Is_dates_equals($date1, $date2)
{
    $date1_str = strtotime($date1);
    $date2_str = strtotime($date2);

    $date1_transformed = date("Y/m/d", $date1_str);
    $date2_transformed = date("Y/m/d", $date2_str);

    if ($date1_transformed == $date2_transformed) {
        return true;
    } else {
        return false;
    }
}

function Calcul_Perfomance(int $nbr_buzy_rooms, int $nbr_free_rooms)
{
    if ($nbr_free_rooms == 0) {
        $total = 0;
    } else {
        $total = ($nbr_buzy_rooms / $nbr_free_rooms) * 100;
    }
    return $total;
}

function Change_date_to_text($date)
{
    return  \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('MMMM YYYY');
}

function userCount()
{
    return count(User::all()) + 1;
}

function NumersDivider($a, $b)
{
    return $b != 0 ? ($a / $b) * 100 : 0;
}

function Custom_Timestamp()
{
    $date = new DateTimeImmutable();
    $micro = (int)$date->format('Uu'); // Timestamp in microseconds
    return $micro;
}

function Get_Username($user, $type)
{
    $created_date = $user->created_at;

    $year = explode("-", $created_date)[0];
    $an = substr($year, -2);
    $tierce = substr(Custom_Timestamp(), -3);

    $username =  $type . $an . userCount() . $tierce;
    return $username;
}

function Add_Number($user, $type)
{
    $created_date = $user->created_at;

    $year = explode("-", $created_date)[0]; ##RECUPERATION DES TROIS PREMIERS LETTRES DU USERNAME
    $an = substr($year, -2);

    $number = "DGT" . $type . $an . userCount();
    return $number;
}

function Send_Notification($receiver, $subject, $message)
{
    $data = [
        "subject" => $subject,
        "message" => $message,
    ];

    Notification::send($receiver, new SendNotification($data));
}

function Send_Notification_Via_Mail($email, $subject, $message)
{
    $data = [
        "subject" => $subject,
        "message" => $message,
    ];
    Notification::route("mail", $email)->notify(new SendNotification($data));
}

##Ce Helper permet de creér le passCode de réinitialisation de mot de passe
function Get_passCode($user, $type)
{
    $created_date = $user->created_at;

    $year = explode("-", $created_date)[0]; ##RECUPERATION DES TROIS PREMIERS LETTRES DU USERNAME
    $an = substr($year, -2);
    $timestamp = substr(Custom_Timestamp(), -3);

    $passcode =  $timestamp . $type . $an . userCount();
    return $passcode;
}

##======== CE HELPER PERMET DE VERIFIER SI LE USER EST UN SIMPLE ADMIN OU PAS ==========## 
function Is_User_An_Admin($userId)
{ #
    $user = User::where(['id' => $userId, 'is_admin' => 1])->get();
    if (count($user) == 0) {
        return false;
    }
    return true; #Sil est un Simple Admin
}

##======== CE HELPER PERMET DE VERIFIER SI LE USER EST UN SUPER ADMIN OU PAS ==========## 
function Is_User_A_Super_Admin($userId)
{ #
    $user = User::where(['id' => $userId, 'is_super_admin' => 1])->get();
    if (count($user) == 0) {
        return false;
    }
    return true; #Sil est un Super Admin
}

function Is_User_A_SimpleAdmin_Or_SuperAdmin($userId)
{
    if (Is_User_An_Admin($userId) || Is_User_A_Super_Admin($userId)) {
        return true; #S'il s'agit d'un Simple Admin ou d'un Super Admin
    }
    return false; #S'il n'est ni l'un nil'autre
}

function GET_USER_ROLES($userId)
{
    $roles = UserRole::with(["role", "user"])->where(["user_id" => $userId])->get();
    return $roles;
}


##======== CE HELPER PERMET DE VERIFIER SI LE USER EST A LE ROLR D'UN MASTER OU PAS ==========## 
function Is_User_Has_A_Master_Role($userId)
{
    $user_roles = GET_USER_ROLES($userId);
    $result = false;
    foreach ($user_roles as $user_role) {
        if ($user_role->role_id == 4) {
            $result = true;
        }
    }
    ##____
    return $result;
}

##======== CE HELPER PERMET DE VERIFIER SI LE USER A LE ROLE D'UN CHEF COMPTABLE OU PAS ==========## 
function Is_User_Has_A_Chief_Accountant_Role($userId)
{ #
    $user_roles = GET_USER_ROLES($userId);
    $result = false;
    foreach ($user_roles as $user_role) {
        if ($user_role->role_id == 3) {
            $result = true;
        }
    }
    ##____
    return $result;
}

##======== CE HELPER PERMET DE VERIFIER SI LE USER A LE ROLE D'UN AGENT COMPTABLE OU PAS ==========## 
function Is_User_Has_An_Agent_Accountant_Role($userId)
{
    $user_roles = GET_USER_ROLES($userId);
    $result = false;
    foreach ($user_roles as $user_role) {
        if ($user_role->role_id == 2) {
            $result = true;
        }
    }
    ##____
    return $result;
}

##======== CE HELPER PERMET DE VERIFIER SI LE USER A LE ROLE D'UN SUPERVISEUR OU PAS ==========## 
function Is_User_Has_A_Supervisor_Role($userId)
{
    $user_roles = GET_USER_ROLES($userId);
    $result = false;
    foreach ($user_roles as $user_role) {
        if ($user_role->role_id == 1) {
            $result = true;
        }
    }
    ##____
    return $result;
}


##======== CE HELPER PERMET DE RECUPERER LES DROITS D'UN UTILISATEUR ==========## 
function User_Rights($rangId, $profilId)
{ #
    $rights = Right::with(["action", "profil", "rang"])->where(["rang" => $rangId, "profil" => $profilId])->get();
    return $rights;
}


#######____GET HOUSE DETAIL ======######
function GET_HOUSE_DETAIL($house)
{
    $house->load(["PayementInitiations", "Supervisor"]);

    // Get the last state once
    $house_last_state = $house->States->last();

    // Get active locations
    $locations = $house->Locations->where("status", "!=", 3);

    // Initialize collections for calculations
    $house_factures = collect();
    $house_amounts = collect();
    $last_state_depenses = collect();
    $current_state_depenses = collect();

    // Process locations and their invoices
    $locations->each(function ($location) use ($house_last_state, &$house_factures, &$house_amounts) {
        if ($house_last_state) {
            $location_factures = Facture::where([
                'location' => $location->id,
                'state_facture' => 0
            ])->whereBetween("created_at", [$house_last_state->created_at, now()])->get();
        } else {
            $location_factures = $location->Factures;
        }

        $house_factures = $house_factures->concat($location_factures);
        $house_amounts = $house_amounts->concat($location_factures->map(function ($facture) use ($location) {
            return $location->prorata_amount ?
                $location->prorata_amount : $facture->amount;
        }));

        // Add tenant information
        $location->_locataire = [
            'nbr_month_paid' => $location->Factures->count(),
            'nbr_facture_amount_paid' => $location->Factures->sum('amount'),
            'houses' => $location->House,
            'rooms' => $location->Room
        ];
    });

    // Calculate expenses
    if ($house_last_state) {
        $last_state_depenses = collect($house_last_state->CdrAccountSolds)->pluck('sold_retrieved');
    }
    $current_state_depenses = collect($house->CurrentDepenses)->pluck('sold_retrieved');

    // Calculate room statistics
    $roomStats = calculateRoomStatistics($house);

    // Calculate financial metrics
    $total_amount_paid = $house_amounts->sum();
    $commission = ($total_amount_paid * $house->commission_percent) / 100;
    $net_to_paid = $total_amount_paid - ($current_state_depenses->sum() + $commission);

    $house['last_depenses'] = $last_state_depenses->sum();
    $house['actuel_depenses'] = $current_state_depenses->sum();
    $house['total_amount_paid'] = $total_amount_paid;
    $house['house_last_state'] = $house_last_state;
    $house['nbr_month_paid'] = $house_factures->count();
    $house['commission'] = $commission;
    $house['net_to_paid'] = $net_to_paid;
    $house['last_payement_initiation'] = $house_last_state ? ($house_last_state->PaiementInitiations ? $house_last_state->PaiementInitiations->last() : []) : [];
    $house['busy_rooms'] = $roomStats['busy_rooms'];
    $house['frees_rooms'] = $roomStats['frees_rooms'];
    $house['busy_rooms_at_first_month'] = $roomStats['busy_rooms_at_first_month'];
    $house['frees_rooms_at_first_month'] = $roomStats['frees_rooms_at_first_month'];

    return $house;
}

function calculateRoomStatistics($house)
{
    $creation_date = date("Y/m/d", strtotime($house->created_at));
    $creation_time = strtotime($creation_date);
    $first_month_period = strtotime("+1 month", strtotime($creation_date));

    $activeLocations = $house->Locations->where("status", "!=", 3);
    $occupiedRoomIds = $activeLocations->pluck('Room.id')->filter();

    $rooms = collect($house->Rooms);
    $busy_rooms = $rooms->filter(fn($room) => $occupiedRoomIds->contains($room->id));
    $frees_rooms = $rooms->filter(fn($room) => !$occupiedRoomIds->contains($room->id));

    $firstMonthRooms = $activeLocations->filter(function ($location) use ($creation_time, $first_month_period) {
        if (!$location->Room) return false;
        $location_create_date = strtotime(date("Y/m/d", strtotime($location->created_at)));
        return $creation_time < $location_create_date && $location_create_date < $first_month_period;
    });

    return [
        'busy_rooms' => $busy_rooms->values(),
        'frees_rooms' => $frees_rooms->values(),
        'busy_rooms_at_first_month' => $firstMonthRooms->pluck('Room')->values(),
        'frees_rooms_at_first_month' => $rooms->filter(fn($room) => !$firstMonthRooms->pluck('Room.id')->contains($room->id))->values()
    ];
}

#######____GET HOUSE DETAIL ======######
function GET_HOUSE_DETAIL_FOR_THE_LAST_STATE($house)
{
    $cacheKey = 'house_detail_last_state_' . $house->id;
    return Cache::remember($cacheKey, 60, function () use ($house) {
        // Pour profiler, active Laravel Debugbar et regarde les requêtes SQL générées
        $house->load([
            'PayementInitiations',
            'States.CdrAccountSolds',
            'CurrentDepenses',
            'Locations.Room',
            'Locations.House',
            'Locations.Locataire',
        ]);

        $house_last_state = $house->States->last();
        $locations = $house->Locations->where('status', '!=', 3);
        $locationIds = $locations->pluck('id');

        $house_factures = collect();
        $house_amounts = collect();
        if ($house_last_state && $locationIds->count() > 0) {
            $factures = \App\Models\Facture::select(['id', 'location', 'amount'])
                ->forHouseLastState($locationIds, $house_last_state->id)
                ->get();
            $house_factures = $factures;
            $house_amounts = $factures->pluck('amount');
        }

        $facturesByLocation = $house_factures->groupBy('location');
        foreach ($locations as $location) {
            $locFactures = $facturesByLocation->get($location->id, collect());
            $location->_locataire = [
                'nbr_month_paid' => $locFactures->count(),
                'nbr_facture_amount_paid' => $locFactures->sum('amount'),
                'houses' => $location->House,
                'rooms' => $location->Room
            ];
        }

        $last_state_depenses = collect();
        if ($house_last_state) {
            $last_state_depenses = collect($house_last_state->CdrAccountSolds)->pluck('sold_retrieved');
        }
        $current_state_depenses = collect($house->CurrentDepenses)->pluck('sold_retrieved');

        $roomStats = calculateRoomStatistics($house);

        $total_amount_paid = $house_amounts->sum();
        $commission = ($total_amount_paid * $house->commission_percent) / 100;
        $net_to_paid = $total_amount_paid - ($last_state_depenses->sum() + $commission);

        return array_merge($house->toArray(), [
            'last_depenses' => $last_state_depenses->sum(),
            'actuel_depenses' => $current_state_depenses->sum(),
            'total_amount_paid' => $total_amount_paid,
            'house_last_state' => $house_last_state,
            'nbr_month_paid' => $house_factures->count(),
            'commission' => $commission,
            'net_to_paid' => $net_to_paid,
            'payement_initiations_last' => $house->PayementInitiations->last(),
            'busy_rooms' => $roomStats['busy_rooms'],
            'frees_rooms' => $roomStats['frees_rooms'],
            'busy_rooms_at_first_month' => $roomStats['busy_rooms_at_first_month'],
            'frees_rooms_at_first_month' => $roomStats['frees_rooms_at_first_month'],
            '_amount' => $net_to_paid != 0 ? $net_to_paid : ($house->PayementInitiations->last()?->amount ?? 0)
        ]);
    });
}

function GET_HOUSE_DETAIL_FOR_THE_LAST_STATE_OLD($house)
{
    $house->load("PayementInitiations");

    // Get the last state once
    $house_last_state = $house->States->last();

    // Get active locations
    $locations = $house->Locations->where("status", "!=", 3);

    // Initialize arrays for calculations
    $house_factures = collect();
    $house_amounts = collect();
    $last_state_depenses = collect();
    $current_state_depenses = collect();

    // Process locations and their invoices
    $locations->each(function ($location) use ($house_last_state, &$house_factures, &$house_amounts) {
        if ($house_last_state) {
            $location_factures = Facture::where([
                'location' => $location->id,
                'state' => $house_last_state->id,
                'state_facture' => 0
            ])->get();

            $house_factures = $house_factures->concat($location_factures);
            $house_amounts = $house_amounts->concat($location_factures->pluck('amount'));
        }

        // Add tenant information
        $location->_locataire = [
            'nbr_month_paid' => $house_factures->count(),
            'nbr_facture_amount_paid' => $house_factures->sum('amount'),
            'houses' => $location->House,
            'rooms' => $location->Room
        ];
    });

    // Calculate expenses
    if ($house_last_state) {
        $last_state_depenses = collect($house_last_state->CdrAccountSolds)->pluck('sold_retrieved');
    }
    $current_state_depenses = collect($house->CurrentDepenses)->pluck('sold_retrieved');

    // Calculate room statistics
    $roomStats = calculateRoomStatistics($house);

    // Calculate financial metrics
    $total_amount_paid = $house_amounts->sum();
    $commission = ($total_amount_paid * $house->commission_percent) / 100;
    $net_to_paid = $total_amount_paid - ($last_state_depenses->sum() + $commission);

    // Merge all data into house object
    return array_merge($house->toArray(), [
        'last_depenses' => $last_state_depenses->sum(),
        'actuel_depenses' => $current_state_depenses->sum(),
        'total_amount_paid' => $total_amount_paid,
        'house_last_state' => $house_last_state,
        'nbr_month_paid' => $house_factures->count(),
        'commission' => $commission,
        'net_to_paid' => $net_to_paid,
        'payement_initiations_last' => $house->PayementInitiations->last(),
        'busy_rooms' => $roomStats['busy_rooms'],
        'frees_rooms' => $roomStats['frees_rooms'],
        'busy_rooms_at_first_month' => $roomStats['busy_rooms_at_first_month'],
        'frees_rooms_at_first_month' => $roomStats['frees_rooms_at_first_month'],
        '_amount' => $net_to_paid != 0 ? $net_to_paid : ($house->PayementInitiations->last()?->amount ?? 0)
    ]);
}

function nombre_en_lettres($nombre)
{
    $unites = [
        '',
        'un',
        'deux',
        'trois',
        'quatre',
        'cinq',
        'six',
        'sept',
        'huit',
        'neuf',
        'dix',
        'onze',
        'douze',
        'treize',
        'quatorze',
        'quinze',
        'seize',
        'dix-sept',
        'dix-huit',
        'dix-neuf'
    ];
    $dizaines = [
        '',
        '',
        'vingt',
        'trente',
        'quarante',
        'cinquante',
        'soixante',
        'soixante',
        'quatre-vingt',
        'quatre-vingt'
    ];

    if (!is_numeric($nombre)) {
        return false;
    }

    if ($nombre < 0) {
        return 'moins ' . nombre_en_lettres(-$nombre);
    }

    if ($nombre < 20) {
        return $unites[$nombre];
    }

    if ($nombre < 100) {
        $dizaine = intval($nombre / 10);
        $reste = $nombre % 10;
        if ($dizaine == 7 || $dizaine == 9) {
            $dizaine--;
            $reste += 10;
        }
        $lettre = $dizaines[$dizaine];
        if ($reste == 1 && $dizaine != 8) {
            $lettre .= '-et-un';
        } elseif ($reste > 0) {
            $lettre .= ' ' . $unites[$reste];
        }
        return $lettre;
    }

    if ($nombre < 1000) {
        $centaine = intval($nombre / 100);
        $reste = $nombre % 100;
        $lettre = ($centaine > 1 ? $unites[$centaine] . '-' : '') . 'cent';
        if ($reste > 0) {
            $lettre .= ' ' . nombre_en_lettres($reste);
        }
        return $lettre;
    }

    if ($nombre < 1000000) {
        $mille = intval($nombre / 1000);
        $reste = $nombre % 1000;
        $lettre = ($mille > 1 ? nombre_en_lettres($mille) . ' ' : '') . 'mille';
        if ($reste > 0) {
            $lettre .= ' ' . nombre_en_lettres($reste);
        }
        return $lettre;
    }

    if ($nombre < 1000000000) {
        $million = intval($nombre / 1000000);
        $reste = $nombre % 1000000;
        $lettre = nombre_en_lettres($million) . ' million' . ($million > 1 ? 's' : '');
        if ($reste > 0) {
            $lettre .= ' ' . nombre_en_lettres($reste);
        }
        return $lettre;
    }

    return 'nombre trop grand';
}
