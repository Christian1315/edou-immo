<div>
    <!doctype html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="api-base-url" content="{{ env('API_BASE_URL') }}">

        <link rel="shortcut icon" href="{{ asset('images/edou_logo.png') }}" type="image/x-icon">
        <link rel="stylesheet" href="{{ asset('fichiers/icon-font.min.css') }}">
        <link rel="stylesheet" href="{{ asset('fichiers/animate.min.css') }}" />
        <title>{{ $title }}</title>

        <link rel="canonical" href="https://getbootstrap.com/docs/4.1/examples/dashboard/">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- Bootstrap core CSS -->

        <link href="{{ asset('fichiers/bootstrap.css') }}" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="{{ asset('fichiers/dashbord.css') }}" rel="stylesheet">
        <link href="{{ asset('fichiers/base.css') }}" rel="stylesheet">

        <!-- overlayScrollbars -->
        <!-- <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> -->

        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"> -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

        <!-- select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        @livewireStyles
        @stack("styles")
    </head>

    <body>
        <nav class="navbar navbar-dark fixed-top bg-red flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0 justify-content-between" href="#">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasSideBar" aria-controls="offcanvasWithBothOptions">
                    <span class="navbar-toggler-icon"></span>
                </button>
                &nbsp;
                <span>{{str_replace('_','-',env('APP_NAME'))}}</span>
            </a>

            <marquee class="text-uppercase" behavior="alternate" style="font-size: 15px;font-weight: bold;">
                {{ $agency?->name }}
            </marquee>

            <li style="list-style-type: none;"><a class="btn btn-sm btn-light" onclick="return confirm('Voulez-vous vraiment vous déconnecter!?')" href="{{route('logout')}}"><i class="bi bi-power"></i> Se Déconnecter</a></li>
            <li style="list-style-type: none;"> <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal"
                    data-bs-target="#updatePassword"><i class="bi bi-key"></i> Mot de passe</a>
            </li>
            <li style="list-style-type: none;">
                <span class="border-white text-uppercase rounded-circle btn bnt-sm btn-dark">
                    {{ auth()->user()->username }}
                </span>
            </li>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="offcanvas bg-dark offcanvas-start" style="width: 250px!important;" data-bs-scroll="true"
                    tabindex="-1" id="offcanvasSideBar" aria-labelledby="offcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <p class="offcanvas-title w-100" id="offcanvasWithBothOptionsLabel">
                            <a class="nav-link bg-white text-danger"
                                style="font-weight: bold;font-style:oblique;font-size:15px"
                                href="/{{ crypId($agency['id']) }}/manage-agency">
                                <i class="bi bi-house-add-fill"></i>
                                {{ $agency?->name }}
                            </a>
                        </p>
                        <!-- <button type="button" class="btn-close text-red btn btn-sm btn-light" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x"></i></button> -->
                    </div>
                    <div class="offcanvas-body">
                        <div class="">
                            @if ($active == 'stop_state')
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link active" href="/{{ crypId($agency['id']) }}/house">
                                        <i class="bi bi-house-add-fill"></i>
                                        Arrêt des Etats
                                    </a>
                                    <a class="nav-link text-red bg-light"
                                        href="/{{ crypId($agency['id']) }}/house">
                                        <i class="bi bi-arrow-left-circle-fill"></i> &nbsp; Retour
                                    </a>
                                </li>
                            </ul>
                            @else

                            <ul class="nav flex-column">
                                <!-- Proprietaires -->
                                @can("proprio.view")
                                <li class="nav-item">
                                    <a class="nav-link text-white @if($active == 'proprietor') active @endif" href="/{{crypId($agency['id'])}}/proprietor">
                                        <i class="bi bi-person-fill-gear"></i>
                                        Propriétaires
                                    </a>
                                </li>
                                @endcan

                                <!-- Maisons -->
                                @can("house.view")
                                <li class="nav-item">
                                    <a class="nav-link text-white @if($active == 'house') active @endif" href="/{{ crypId($agency['id']) }}/house">
                                        <i class="bi bi-house-add-fill"></i>
                                        Maisons
                                    </a>
                                </li>
                                @endcan

                                <!-- Chambres -->
                                @can("room.view")
                                <li class="nav-item">
                                    <a class="nav-link text-white @if($active == 'room') active @endif"
                                        href="/{{ crypId($agency['id']) }}/room">
                                        <i class="bi bi-hospital-fill"></i>
                                        Chambres
                                    </a>
                                </li>
                                @endcan

                                <!-- Locataires -->
                                @can("locator.view")
                                <li class="nav-item">
                                    <a class="nav-link text-white @if($active == 'locator') active @endif"
                                        href="/{{ crypId($agency['id']) }}/locator">
                                        <i class="bi bi-person-fill-gear"></i>
                                        Locataires
                                    </a>
                                </li>
                                @endcan

                                <!-- LOcations -->
                                @can("location.view")
                                <li class="nav-item">
                                    <div class="btn-group dropdown-center">
                                        <a class="w-100 nav-link text-white dropdown-toggle @if($active == 'location') active @endif" href="#"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-person-fill-gear"></i>
                                            Locations
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li><a class="dropdown-item"
                                                    href="/{{ crypId($agency['id']) }}/location">Toutes les
                                                    locations</a></li>
                                            <!-- Loacataires à jour -->
                                            @can("locator.paid.view")
                                            <li><a class="dropdown-item"
                                                    href="/{{ crypId($agency['id']) }}/paid_locators">Locataires
                                                    à jour</a></li>
                                            @endcan

                                            <!-- LOcataires en impayés -->
                                            @can("locator.unpaid.view")
                                            <li><a class="dropdown-item"
                                                    href="/{{ crypId($agency['id']) }}/unpaid_locators">Locataires
                                                    en impayé</a></li>
                                            @endcan

                                            <!-- Locataires déménagés -->
                                            @can("locator.removed.view")
                                            <li><a class="dropdown-item"
                                                    href="/{{ crypId($agency['id']) }}/removed_locators">Locataires
                                                    démenagés</a></li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                                @endcan

                                <!-- PAIEMENT -->
                                @can("proprio.payement.view")
                                <li class="nav-item">
                                    <a class="nav-link @if($active == 'paiement') active @endif text-white"
                                        href="/{{ crypId($agency['id']) }}/paiement">
                                        <i class="bi bi-currency-exchange"></i>
                                        Payer Propriétaire
                                    </a>
                                </li>
                                @endcan

                                <!-- Valider paiement -->
                                @can("proprio.payement.validation.view")
                                <li class="nav-item">
                                    <a class="nav-link text-white @if($active == 'initiation') active @endif"
                                        href="/{{ crypId($agency['id']) }}/initiation">
                                        <i class="bi bi-cash-coin"></i>
                                        Valider paiement
                                    </a>
                                </li>
                                @endcan

                                <!-- les factures -->
                                @can ("invoices.view")
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'facture') active @endif text-white"
                                        href="/{{ crypId($agency['id']) }}/factures">
                                        <i class="bi bi-file-pdf"></i>
                                        Factures
                                    </a>
                                </li>
                                @endcan


                                <!-- CAISSES -->
                                @can("caisses.view")
                                <li class="nav-item"><a class="nav-link text-white @if($active=='caisse') active @endif"
                                        href="/{{ crypId($agency['id']) }}/caisses"><i class="bi bi-currency-dollar"></i> Toutes les
                                        caisses</a>
                                </li>
                                @endcan

                                <!-- Electricté -->
                                <li class="nav-item">
                                    <div class="btn-group dropdown-center">
                                        <a class="nav-link @if($active == 'electricity') active @endif text-white dropdown-toggle" href="#"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-ev-station"></i> &nbsp; Electricité / Eau
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <!-- electricite -->
                                            @can("electicity.invoices.view")
                                            <li><a class="dropdown-item @if($active == 'electricity') active @endif"
                                                    href="/{{ crypId($agency['id']) }}/electricity/locations">Electricité</a>
                                            </li>
                                            @endcan

                                            <!-- eau -->
                                            @can("water.invoices.view")
                                            <li><a class="dropdown-item @if($active == 'water') active @endif"
                                                    href="/{{ crypId($agency['id']) }}/eau/locations">Eau</a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>
                            </ul>

                            <h6
                                class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                                <span>Paramètres & Statistiques</span>
                                <a class="d-flex align-items-center text-muted" href="#">
                                    <span data-feather="plus-circle"></span>
                                </a>
                            </h6>

                            <ul class="nav flex-column">
                                <!-- statistique -->
                                <li class="nav-item">
                                    <div class="btn-group dropdown-center">
                                        <a class="nav-link @if($active == 'statistique') active @endif text-white dropdown-toggle" href="#"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-ev-station"></i> &nbsp; Statistiques
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <!-- electricite -->
                                            @can("statistiques.view")
                                            <li><a class="dropdown-item @if($active == 'electricity') active @endif"
                                                    href="/{{ crypId($agency['id']) }}/statistique-before-state">Statistique avant arrêt d'état</a>
                                            </li>
                                            <li><a class="dropdown-item @if($active == 'electricity') active @endif"
                                                    href="/{{ crypId($agency['id']) }}/statistique-after-state">Statistique après arrêt d'état</a>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </li>

                                <!-- bilan agence -->
                                @can("bilan.view")
                                <li class="nav-item">
                                    <a class="nav-link @if ($active == 'filtrage') active @endif text-white"
                                        href="/{{ crypId($agency['id']) }}/filtrage">
                                        <i class="bi bi-filter-circle"></i> &nbsp; Bilan
                                    </a>
                                </li>
                                @endcan

                                <!-- Recouvrement -->
                                @can("recovery.rates.view")
                                <li class="nav-item">
                                    <div class="btn-group dropdown-center">
                                        <a class="nav-link @if ($active == 'recovery') active @endif text-white dropdown-toggle"
                                            href="#" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi bi-reception-4"></i> &nbsp; Taux de recouvrement
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li><a class="dropdown-item active"
                                                    href="/{{ crypId($agency['id']) }}/recovery_05_to_echeance_date">Recouvrement
                                                    au 05</a></li>
                                            <li><a class="dropdown-item"
                                                    href="/{{ crypId($agency['id']) }}/recovery_10_to_echeance_date">Recouvrement
                                                    au 10</a></li>
                                            <li><a class="dropdown-item"
                                                    href="/{{ crypId($agency['id']) }}/recovery_qualitatif">Recouvrement
                                                    qualitatif</a></li>
                                            <li><a class="dropdown-item"
                                                    href="/{{ crypId($agency['id']) }}/performance">Taux
                                                    d'occupation des maisons</a></li>
                                            <li><a class="dropdown-item"
                                                    href="/{{ crypId($agency['id']) }}/recovery_quelconque_date">Quelconque
                                                    date</a></li>
                                        </ul>
                                    </div>
                                </li>
                                @endcan
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- =============== LE BODY DU DASHBORD ========= -->

                <main role="main" class="col-md-12 ml-sm-auto px-4">
                    <x-alert />

                    <!-- ALERT -->
                    {{ $slot }}

                    {{-- MODAL DE CHANGEMENT DE MOT DE PASE --}}
                    <!-- Modal -->
                    <div class="modal fade" id="updatePassword" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fs-5" id="exampleModalLabel">Modification de mot de passe
                                    </h5>
                                </div>
                                <form action="{{ route('user.UpdateCompte', auth()->user()->id) }}"
                                    method="post">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <input type="password" placeholder="**********" required name="password" class="form-control" id="">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="w-100 btn btn-sm bg-red"><i class="bi bi-check-circle"></i> Enregistrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div class="row">
                <div class="col-md-12 bg-white shadow-lg py-2 bg-white mt-5">
                    <p class="text-center">© Copyright - <strong class="text-red">{{date("Y")}}</strong> - Réalisé par <strong class="text-red">Code4Christ </strong> </p>
                </div>
            </div>
        </div>
        @livewireScripts
    </body>

    <script src="{{ asset('fichiers/jquery.min.js') }}"></script>

    <script src="{{asset('fichiers/popper.min.js')}}"></script>
    <script src="{{asset('fichiers/bootstrap.min.js')}}"></script>

    <script src="{{ asset('fichiers/axios.min.js') }}"></script>

    <!-- Select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- overlayScrollbars -->
    <!-- <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script> -->

    <!-- API DE GESTION DES SUM DES COLUMS DES DATATABLES -->
    <script src="https://cdn.datatables.net/plug-ins/2.1.8/api/sum().js"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <!-- <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script> -->

    <!-- #### DATA TABLES -->
    <script type="text/javascript">
        // Alert2
        $('.agency-select2').select2();

        $(document).ready(function() {
            $(document).on('shown.bs.modal', '.modal', function() {
                $(this).find('.agency-modal-select2').each(function() {
                    $(this).select2({
                        tags: true,
                        width: '100%',
                        placeholder: $(this).data('placeholder'),
                        dropdownParent: $(this).closest('.modal'),
                        allowClear: true
                    });
                });
            });
        })

        $(function() {
            $("#myTable").DataTable({
                    "paging": true,
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": true,
                    "buttons": ["excel", "pdf", "print"],
                    "order": [
                        [0, 'desc']
                    ],
                    "pageLength": 15,

                    language: {
                        "emptyTable": "Aucune donnée disponible dans le tableau",
                        "lengthMenu": "Afficher _MENU_ éléments",
                        "loadingRecords": "Chargement...",
                        "processing": "Traitement...",
                        "zeroRecords": "Aucun élément correspondant trouvé",
                        "paginate": {
                            "first": "Premier",
                            "last": "Dernier",
                            "previous": "Précédent",
                            "next": "Suiv"
                        },
                        "aria": {
                            "sortAscending": ": activer pour trier la colonne par ordre croissant",
                            "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                        },
                        "select": {
                            "rows": {
                                "_": "%d lignes sélectionnées",
                                "1": "1 ligne sélectionnée"
                            },
                            "cells": {
                                "1": "1 cellule sélectionnée",
                                "_": "%d cellules sélectionnées"
                            },
                            "columns": {
                                "1": "1 colonne sélectionnée",
                                "_": "%d colonnes sélectionnées"
                            }
                        },
                        "autoFill": {
                            "cancel": "Annuler",
                            "fill": "Remplir toutes les cellules avec <i>%d<\/i>",
                            "fillHorizontal": "Remplir les cellules horizontalement",
                            "fillVertical": "Remplir les cellules verticalement"
                        },
                        "searchBuilder": {
                            "conditions": {
                                "date": {
                                    "after": "Après le",
                                    "before": "Avant le",
                                    "between": "Entre",
                                    "empty": "Vide",
                                    "equals": "Egal à",
                                    "not": "Différent de",
                                    "notBetween": "Pas entre",
                                    "notEmpty": "Non vide"
                                },
                                "number": {
                                    "between": "Entre",
                                    "empty": "Vide",
                                    "equals": "Egal à",
                                    "gt": "Supérieur à",
                                    "gte": "Supérieur ou égal à",
                                    "lt": "Inférieur à",
                                    "lte": "Inférieur ou égal à",
                                    "not": "Différent de",
                                    "notBetween": "Pas entre",
                                    "notEmpty": "Non vide"
                                },
                                "string": {
                                    "contains": "Contient",
                                    "empty": "Vide",
                                    "endsWith": "Se termine par",
                                    "equals": "Egal à",
                                    "not": "Différent de",
                                    "notEmpty": "Non vide",
                                    "startsWith": "Commence par"
                                },
                                "array": {
                                    "equals": "Egal à",
                                    "empty": "Vide",
                                    "contains": "Contient",
                                    "not": "Différent de",
                                    "notEmpty": "Non vide",
                                    "without": "Sans"
                                }
                            },
                            "add": "Ajouter une condition",
                            "button": {
                                "0": "Recherche avancée",
                                "_": "Recherche avancée (%d)"
                            },
                            "clearAll": "Effacer tout",
                            "condition": "Condition",
                            "data": "Donnée",
                            "deleteTitle": "Supprimer la règle de filtrage",
                            "logicAnd": "Et",
                            "logicOr": "Ou",
                            "title": {
                                "0": "Recherche avancée",
                                "_": "Recherche avancée (%d)"
                            },
                            "value": "Valeur"
                        },
                        "searchPanes": {
                            "clearMessage": "Effacer tout",
                            "count": "{total}",
                            "title": "Filtres actifs - %d",
                            "collapse": {
                                "0": "Volet de recherche",
                                "_": "Volet de recherche (%d)"
                            },
                            "countFiltered": "{shown} ({total})",
                            "emptyPanes": "Pas de volet de recherche",
                            "loadMessage": "Chargement du volet de recherche..."
                        },
                        "buttons": {
                            "copyKeys": "Appuyer sur ctrl ou u2318 + C pour copier les données du tableau dans votre presse-papier.",
                            "collection": "Collection",
                            "colvis": "Visibilité colonnes",
                            "colvisRestore": "Rétablir visibilité",
                            "copy": "Copier",
                            "copySuccess": {
                                "1": "1 ligne copiée dans le presse-papier",
                                "_": "%ds lignes copiées dans le presse-papier"
                            },
                            "copyTitle": "Copier dans le presse-papier",
                            "csv": "CSV",
                            "excel": "Excel",
                            "pageLength": {
                                "-1": "Afficher toutes les lignes",
                                "_": "Afficher %d lignes"
                            },
                            "pdf": "PDF",
                            "print": "Imprimer"
                        },
                        "decimal": ",",
                        "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                        "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                        "infoThousands": ".",
                        "search": "Rechercher:",
                        "thousands": ".",
                        "infoFiltered": "(filtrés depuis un total de _MAX_ éléments)",
                        "datetime": {
                            "previous": "Précédent",
                            "next": "Suivant",
                            "hours": "Heures",
                            "minutes": "Minutes",
                            "seconds": "Secondes",
                            "unknown": "-",
                            "amPm": [
                                "am",
                                "pm"
                            ],
                            "months": [
                                "Janvier",
                                "Fevrier",
                                "Mars",
                                "Avril",
                                "Mai",
                                "Juin",
                                "Juillet",
                                "Aout",
                                "Septembre",
                                "Octobre",
                                "Novembre",
                                "Decembre"
                            ],
                            "weekdays": [
                                "Dim",
                                "Lun",
                                "Mar",
                                "Mer",
                                "Jeu",
                                "Ven",
                                "Sam"
                            ]
                        },
                        "editor": {
                            "close": "Fermer",
                            "create": {
                                "button": "Nouveaux",
                                "title": "Créer une nouvelle entrée",
                                "submit": "Envoyer"
                            },
                            "edit": {
                                "button": "Editer",
                                "title": "Editer Entrée",
                                "submit": "Modifier"
                            },
                            "remove": {
                                "button": "Supprimer",
                                "title": "Supprimer",
                                "submit": "Supprimer",
                                "confirm": {
                                    "1": "etes-vous sure de vouloir supprimer 1 ligne?",
                                    "_": "etes-vous sure de vouloir supprimer %d lignes?"
                                }
                            },
                            "error": {
                                "system": "Une erreur système s'est produite"
                            },
                            "multi": {
                                "title": "Valeurs Multiples",
                                "restore": "Rétablir Modification",
                                "noMulti": "Ce champ peut être édité individuellement, mais ne fait pas partie d'un groupe. ",
                                "info": "Les éléments sélectionnés contiennent différentes valeurs pour ce champ. Pour  modifier et "
                            }
                        }
                    },
                })
                .buttons().container().appendTo('#myTable_wrapper .col-md-6:eq(0)');
        });
    </script>
    @stack('scripts')
    </html>
</div>