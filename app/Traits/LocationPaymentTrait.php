<?php

namespace App\Traits;

use App\Models\Location;
use App\Models\Facture;
use App\Models\AgencyAccount;
use App\Models\AgencyAccountSold;
use App\Models\Locataire;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

trait LocationPaymentTrait
{

    public function validatePaymentData(array $formData)
    {
        $rules = [
            'location' => ['required', "integer", "exists:locationsnew,id"],
            'type' => ['required', "integer", "exists:paiement_types,id"],
            'facture_code' => ['required', "unique:factures,facture_code"],
        ];

        $messages = [
            'location.required' => 'La location est réquise!',
            'type.required' => "Le type de paiement est réquis!",
            'location.integer' => "Ce champ doit être de type entier!",
            'type.integer' => "Ce champ doit être de type entier!",
            'facture_code.required' => "Veillez préciser le code de la facture!",
            'facture_code.unique' => "Ce code de facture existe déjà!",
        ];

        Validator::make($formData, $rules, $messages)->validate();
    }

    public function getLocationWithRelations($locationId)
    {
        $location = Location::with(["House", "Locataire", "Room"])->find($locationId);

        if (!$location) {
            throw new \Exception("Cette location n'existe pas!");
        }

        return $location;
    }

    public function preparePaymentData(array $formData, Location $location, $user)
    {
        $data = [
            "module" => 2,
            "status" => 1,
            "amount" => $location->loyer,
            "comments" => $this->generatePaymentComment($location, $user),
            "owner" => $user->id,
            "echeance_date" => $location->next_loyer_date,
            "location" => $formData["location"],
            "type" => 1,
            "facture" => $this->handleFactureFile($formData, $location),
            "begin_date" => null,
            "end_date" => null,
            "facture_code" => $formData["facture_code"],
            "is_penality" => $formData["is_penality"] ?? false,

            "prorata_days" => $formData["prorata_days"] ?? 0,
            "prorata_amount" => $formData["prorata_amount"] ?? 0,
        ];

        if ($location->Locataire->prorata) {
            $this->validateProrataData($formData);
            $data["amount"] = $formData["prorata_amount"];
        }

        return $data;
    }

    protected function validateProrataData(array $formData)
    {
        Validator::make(
            $formData,
            [
                "prorata_amount" => ["required", "numeric"],
                "prorata_days" => ["required", "numeric"],
                "prorata_date" => ["required", "date"],
            ],
            [
                "prorata_amount.required" => "Veuillez préciser le montant du prorata",
                "prorata_amount.numeric" => "Ce champ doit être de format numérique",
                "prorata_days.required" => "Veuillez préciser le nombre de jour du prorata",
                "prorata_days.numeric" => "Ce champ doit être de format numérique",
                "prorata_date.required" => "Veuillez préciser la date de jour du prorata",
                "prorata_date.date" => "Ce champ doit être de format date",
            ]
        )->validate();
    }

    protected function handleFactureFile(array $formData, Location $location)
    {
        if (isset($formData["facture"]) && $formData["facture"]->isValid()) {
            $fileName = $formData["facture"]->getClientOriginalName();
            $formData["facture"]->move("factures", $fileName);
            return asset("factures/" . $fileName);
        }

        return $location->facture;
    }

    protected function generatePaymentComment(Location $location, $user)
    {
        return sprintf(
            "Encaissement de loyer à la date %s pour le locataire (%s %s) habitant la chambre (%s) de la maison (%s) par <<%s>>",
            now(),
            $location->Locataire->name,
            $location->Locataire->prenom,
            $location->Room->number,
            $location->House->name,
            $user->name
        );
    }

    public function createFacture(array $paymentData)
    {
        return Facture::create($paymentData);
    }

    public function updateAgencyAccount(array $paymentData, Location $location)
    {
        $agency_rent_account = AgencyAccount::where([
            "agency" => $location->agency,
            "account" => env("LOYER_ACCOUNT_ID")
        ])->first();

        if (!$agency_rent_account) {
            throw new \Exception("Ce compte n'existe pas! Vous ne pouvez pas le créditer!");
        }

        $accountSold = AgencyAccountSold::where([
            "agency_account" => $agency_rent_account->id,
            "visible" => 1
        ])->first();

        $this->validateAndUpdateAccountSold($accountSold, $agency_rent_account, $paymentData);
    }

    protected function validateAndUpdateAccountSold($accountSold, $agencyAccount, array $paymentData)
    {
        $account = $agencyAccount->_Account;
        $newSold = $accountSold ? $accountSold->sold + $paymentData["amount"] : $paymentData["amount"];

        if ($newSold > $account->plafond_max) {
            throw new \Exception("L'ajout de ce montant au sold de ce compte ({$account->name}) dépasserait son plafond!");
        }

        if ($accountSold) {
            $accountSold->visible = 0;
            $accountSold->delete_at = now();
            $accountSold->save();
        }

        AgencyAccountSold::create([
            "agency_account" => $agencyAccount->id,
            "sold" => $newSold,
            "sold_added" => $paymentData["amount"],
            "old_sold" => $accountSold ? $accountSold->sold : 0,
            "description" => $paymentData["comments"]
        ]);
    }

    public function updateLocationAfterPayment(Location $location, array $formData)
    {
        try {
            DB::beginTransaction();

            // Mise à jour des dates avec Carbon
            $nextLoyerDate = Carbon::parse($location->next_loyer_date)->addMonth();
            $echeanceDate = Carbon::parse($location->echeance_date)->addMonth();

            $location->update([
                'latest_loyer_date' => $location->next_loyer_date,
                'next_loyer_date' => $nextLoyerDate->format('Y-m-d'),
                'echeance_date' => $echeanceDate->format('Y-m-d')
            ]);

            // Gestion du prorata si nécessaire
            if ($location->Locataire->prorata) {
                $this->handleProrataUpdate($location, $formData);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Erreur lors de la mise à jour de la location après paiement: " . $e->getMessage());
        }
    }

    /**
     * Gère la mise à jour des informations de prorata
     * 
     * @param Location $location
     * @param array $formData
     * @return void
     */
    private function handleProrataUpdate(Location $location, array $formData)
    {
        if (!isset($formData["prorata_amount"]) || !isset($formData["prorata_days"])) {
            throw new \Exception("Les informations de prorata sont incomplètes");
        }

        $location->update([
            'prorata_amount' => $formData["prorata_amount"],
            'prorata_days' => $formData["prorata_days"]
        ]);

        // Désactivation du prorata pour le locataire
        $locataire = Locataire::find($location->locataire);
        if ($locataire) {
            $locataire->update(['prorata' => false]);
        }
    }
}
