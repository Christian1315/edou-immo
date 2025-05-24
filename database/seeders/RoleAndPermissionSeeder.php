<?php

namespace Database\Seeders;

// use App\Models\Securite\User;
use App\Models\User as ModelsUser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    private function createCrudValidatePermissions($name, $permission, $group = null)
    {
        return [
            "Voir les $name" => "$permission.view",
            "Créer des $name" => "$permission.create",
            "Modifier les $name" => "$permission.edit",
            "Supprimer des $name" => "$permission.delete",
            // "Valider les $name" => "$permission.validate",
        ];
    }

    public function run()
    {
        $permissionGroups = [
            'Administration' => array_merge(
                $this->createCrudValidatePermissions('utilisateurs', 'users', 'utilisateurs'),
                $this->createCrudValidatePermissions('rôles', 'roles', 'roles')
            ),

            'Propriétaires' => array_merge(
                $this->createCrudValidatePermissions('propriétaires', 'proprio', 'propriétaires'),
            ),

            'Maisons' => array_merge(
                $this->createCrudValidatePermissions('maisons', 'house', 'maisons'),
                [
                    "Arrêter un état" => "house.stop.state",
                    "Gestion des cautions" => "house.generate.caution",
                    "Ajouter un type de maison" => "house.add.type",
                ]
            ),

            'Chambres' => array_merge(
                $this->createCrudValidatePermissions('chambres', 'room', 'chambres'),
                [
                    "Ajouter un type de chambre" => "room.add.type",
                    "Ajouter une nature de chambre" => "room.add.nature"
                ]
            ),

            'Locataires' => array_merge(
                $this->createCrudValidatePermissions('locataires', 'locator', 'Locataires'),
            ),

            'Locations' => array_merge(
                $this->createCrudValidatePermissions('Locations', 'location', 'Locations'),
                $this->createCrudValidatePermissions('locataires à jour', 'locator.paid', 'Locations'),
                $this->createCrudValidatePermissions('locataires impayés', 'locator.unpaid', 'Locations'),
                $this->createCrudValidatePermissions('locataires démenagés', 'locator.removed', 'Locations'),
                [
                    "Encaisser une location" => "location.collect",
                    "Deménager un locataire" => "location.removed",
                    "Imprimer les rapports" => "location.print.report",
                    "Génerer les états de cautions de l'agence" => "location.generate.cautions.state.agency",
                    "Génerer les états de cautions" => "location.generate.cautions.state",
                    "Génerer les états des proratas" => "location.generate.proratas.state",

                    "Génerer les factures" => "location.generate.invoices",

                    "Ajouter un type de location" => "location.add.type",
                ]
            ),

            'Paiement propriétaires' => array_merge(
                [
                    "Voir les paiement des propriétaires" => "proprio.payement.view",
                    "Payer un propriétaire" => "proprio.payement",
                    "Imprimer les états" => "proprio.print.state",
                    "Imprimer les rapports" => "location.print.report",
                    "Génerer les états de cautions" => "location.generate.cautions.state",
                    "Génerer les états des proratas" => "location.generate.proratas.state",
                ]
            ),

            'Validation des paiements propriétaires' => array_merge(
                [
                    "Voir la validation des paiement des propriétaires" => "proprio.payement.validation.view",
                    "Valider un paiement" => "proprio.payement.validate",
                    "Rejeter un paiement" => "proprio.payement.cancel",
                ]
            ),

            'Factures' => array_merge(
                $this->createCrudValidatePermissions('factures', 'invoices', 'Factures'),
            ),

            'Caisses' => array_merge(
                [
                    "Voir les Caisses" => "caisses.view",
                    "Créditer une caisse" => "caisses.credite",
                    "Decréditer une Caisse" => "caisses.decredite",
                ]
            ),

            'Factures electicité' => array_merge(
                [
                    "Voir les locations ayant d'électricité" => "electicity.invoices.view",
                    "Genérer une facture d\'électricité d'une location" => "electicity.invoices.generate",
                    "Arrêter les états de facture d'électricité d'une location" => "electicity.invoices.stop.state",
                    "Payer une facture d'électricité d'une location" => "electicity.invoices.payement",
                    "Imprimer les états de facture d'électricité d'une location" => "electicity.invoices.print",
                    "Modifier l'index de fin d'une location" => "electicity.invoices.change.index",
                ]
            ),

            'Factures d\'eau' => array_merge(
                [
                    "Voir les locations ayant d'eau" => "water.invoices.view",
                    "Genérer une facture d\'eau d'une location" => "water.invoices.generate",
                    "Arrêter les états de facture d'eau d'une location" => "water.invoices.stop.state",
                    "Payer une facture d'eau d'une location" => "water.invoices.payement",
                    "Imprimer les états de facture d'eau d'une location" => "water.invoices.print",
                    "Modifier l'index de fin d'une location" => "water.invoices.change.index",
                ]
            ),

            'Statistiques' => array_merge(
                [
                    "Voir les statistiques" => "statistiques.view",
                ]
            ),

            'Bilan' => array_merge(
                [
                    "Voir les bilans" => "bilan.view",
                ]
            ),

            'Taux de recouvrement' => array_merge(
                [
                    "Voir les taux de recouvrement" => "recovery.rates.view",
                    "Génerer les états des taux de recouvrement" => "generate.recovery.rates.states",
                ]
            ),
        ];

        // Création des permissions
        $allPermissions = [];
        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $description => $permission) {
                $createdPermission = Permission::firstOrCreate(
                    ['name' => $permission, 'guard_name' => 'web'],
                    ['name' => $permission, 'group_name' => $group, 'description' => $description]
                );
                $allPermissions[] = $createdPermission;
            }
        }

        // Création des rôles
        $roles = [
            'Super Administrateur',
            'Master',
            'Gestionnaire de compte',
            'Chef comptable',
            'Superviseur',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Attribution de toutes les permissions au super-admin
        $superAdmin = Role::findByName('Super Administrateur');
        $superAdmin->syncPermissions($allPermissions);

        // Assigner le rôle de super administrateur à l'utilisateur avec l'ID 1
        $user_admin = ModelsUser::find(1);
        if ($user_admin) {
            $user_admin->assignRole('Super Administrateur');
        }


        // Attribution de toutes les permissions au Master
        $master = Role::findByName('Master');
        $master->syncPermissions($allPermissions); ## ajout de toutes les permisions au Master

        // Assigner le rôle de Master à l'utilisateur avec l'ID 2
        $user_master = ModelsUser::find(2);
        if ($user_master) {
            $user_master->assignRole('Master');
        }
    }
}
