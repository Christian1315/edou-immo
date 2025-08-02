<div>
    <!doctype html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="{{asset('images/edou_logo.png')}}" type="image/x-icon">
        <link href="{{asset('fichiers/bootstrap.css')}}" rel="stylesheet">

        <!-- overlayScrollbars -->
        <!-- <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}"> -->

        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <!-- <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"> -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">


        <link rel="stylesheet" href="{{asset('fichiers/icon-font.min.css')}}">
        <link rel="stylesheet" href="{{asset('fichiers/animate.min.css')}}" />


        <title>{{$title}}</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- Bootstrap core CSS -->

        <!-- Custom styles for this template -->
        <link href="{{asset('fichiers/dashbord.css')}}" rel="stylesheet">
        <link href="{{asset('fichiers/base.css')}}" rel="stylesheet">

        <!-- <script src="https://cdn.datatables.net/1.13.10/css/jquery.dataTables.css"></script> -->

        <!-- select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        @livewireStyles
    </head>

    <body>
        <nav class="navbar navbar-dark fixed-top bg-red flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0 justify-content-between" href="#">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSideBar" aria-controls="offcanvasWithBothOptions">
                    <span class="navbar-toggler-icon"></span>
                </button>
                &nbsp;
                <span>{{str_replace('_','-',env('APP_NAME'))}} </span>
            </a>

            <input class="mx-2 rounded form-control form-control-dark w-100 bg-light search--bar" type="text" placeholder="Recherche" aria-label="searh">

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
                <div class="offcanvas bg-dark offcanvas-start" style="width: 250px!important;" data-bs-scroll="true" tabindex="-1" id="offcanvasSideBar" aria-labelledby="offcanvasWithBothOptionsLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">
                            {{str_replace('_','-',env('APP_NAME'))}}
                        </h5>
                        <button type="button" class="btn-close text-red btn btn-sm btn-light" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    @if($active=="dashbord")
                                    <a class="nav-link active" href="/dashbord">
                                        <i class="bi bi-house-add-fill"></i>
                                        Tableau de board <span class="sr-only">(current)</span>
                                    </a>
                                    @else
                                    <a class="nav-link text-white" href="/dashbord">
                                        <i class="bi bi-house-add-fill"></i>
                                        Tableau de board <span class="sr-only">(current)</span>
                                    </a>
                                    @endif
                                </li>

                                <li class="nav-item">
                                    @if($active=="agency")
                                    <a class="nav-link active" href="/agency">
                                        <i class="bi bi-house-add-fill"></i>
                                        Agences <span class="sr-only">(current)</span>
                                    </a>
                                    @else
                                    <a class="nav-link text-white" href="/agency">
                                        <i class="bi bi-house-add-fill"></i>
                                        Agences <span class="sr-only">(current)</span>
                                    </a>
                                    @endif
                                </li>

                                @if($active=="count")
                                <li class="nav-item">
                                    <a class="nav-link active" href="/count">
                                        <i class="bi bi-person-fill-add"></i>
                                        Comptes & Soldes
                                    </a>
                                </li>
                                @else
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="/count">
                                        <i class="bi bi-person-fill-add"></i>
                                        Comptes & Soldes
                                    </a>
                                </li>
                                @endif

                            </ul>
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                                <span>Paramètres & Statistiques</span>
                                <a class="d-flex align-items-center text-muted" href="#">
                                    <span data-feather="plus-circle"></span>
                                </a>
                            </h6>
                            @if(auth()->user()->hasRole('Super Administrateur') || auth()->user()->hasRole('Master'))
                            <ul class="nav flex-column mb-2">

                                <li class="nav-item">
                                    <a class="text-white nav-link @if($active=='setting') active @endif" href="/setting">
                                        <i class="bi bi-gear-fill"></i>
                                        Utilisateurs
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="text-white nav-link @if($active=='supervisor') active @endif" href="/supervisor">
                                        <i class="bi bi-people-fill"></i>
                                        Superviseurs
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="text-white nav-link @if($active=='role') active @endif" href="{{route('roles.index')}}">
                                        <i class="bi bi-person-wheelchair"></i>
                                        Les Rôles
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="text-white nav-link @if($active=='statistique') active @endif" href="/statistique">
                                        <i class="bi bi-flag-fill"></i>
                                        Statistiques
                                    </a>
                                </li>
                            </ul>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- =============== LE BODY DU DASHBORD ========= -->
                <main role="main" class="col-md-12 ml-sm-auto col-lg-12 px-4">
                    <!-- MESSAGE FLASH -->
                    <x-alert />

                    {{$slot}}
                </main>
            </div>

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

            <!--  -->
            <div class="row">
                <div class="col-md-12 bg-white shadow-lg py-2 bg-white mt-5">
                    <p class="text-center">© Copyright - <strong class="text-red">{{date("Y")}}</strong> - Réalisé par <strong class="text-red">Code4Christ </strong> </p>
                </div>
            </div>
        </div>

        @livewireScripts

        @stack("scripts")
    </body>

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{asset('fichiers/jquery.min.js')}}"></script>
    <script src="{{asset('fichiers/popper.min.js')}}"></script>
    <script src="{{asset('fichiers/bootstrap.min.js')}}"></script>

    <!-- API DE GESTION DES SUM DES COLUMS DES DATATABLES -->
    <script src="https://cdn.datatables.net/plug-ins/2.1.8/api/sum().js"></script>

    <script src="{{ asset('fichiers/axios.min.js') }}"></script>

    <!-- Select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <!-- <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script> -->

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche instantanée
            const searchLocation = document.getElementById('select-search');
            searchLocation.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                document.querySelectorAll('.item-search').forEach(row => {
                    const permissionText = row.textContent.toLowerCase();
                    if (permissionText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <!--  DATA TABLES -->
    <script>
        // In your Javascript (external .js resource or <script> tag)
        $('.agency-select2').select2();

        $(document).ready(function() {
            $(document).on('shown.bs.modal', '.modal', function() {
                $(this).find('.agency-modal-select2').each(function() {
                    $(this).select2({
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
                "responsive": true,
                "lengthChange": true,

                "autoWidth": true,
                "buttons": ["excel", "pdf", "print"],
                "order": [
                    [0, 'desc']
                ],
                "pageLength": 10,

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
            });
        });
    </script>

    </html>
</div>