<div>
    @can("water.invoices.generate")
    <button class="btn btn-sm btn-light text-uppercase" data-bs-toggle="modal" data-bs-target="#generate_water_facture"><i class="bi bi-file-earmark-pdf-fill"> </i> Génerer une facture d'eau </button>
    <br>
    @endcan

    <small>
        @can("water.invoices.stop.state")
        <button class="btn btn-sm bg-red text-white text-uppercase" data-bs-toggle="modal" data-bs-target="#stop_house_water_state"><i class="bi bi-stop-circle"></i> Arrêter les états</button>
        @endcan
        <input type="checkbox" hidden class="btn-check" id="displayLocatorsOptions" onclick="displayFiltreOptions()">
        <label class="btn btn-light" for="displayLocatorsOptions"><i class="bi bi-file-earmark-pdf-fill"></i>Filtrer les locations</label>
    </small>
    <br>

    <div id="filtre_options" class="d-none">
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#serchBySupervisor"><i class="bi bi-people"></i> Par Sperviseur</button>
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#searchByHouse"><i class="bi bi-house-check-fill"></i> Par Maison</button>
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#searchByProprio"><i class="bi bi-house-check-fill"></i> Par Propriétaire</button>
    </div>

    <!--===== MODAL DE FILTRES ==== -->

    <!-- FILTRE BY SUPERVISOR -->
    <div class="modal fade" id="serchBySupervisor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="" id="exampleModalLabel">Filtre par superviseur</p>
                </div>
                <div class="modal-body">
                    <form class="serchBySupervisor" action="{{route('location.WaterFiltreBySupervisor',$current_agency->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choisissez un superviseur</label>
                                <select required name="supervisor" class="form-control agency-modal-select2">
                                    @foreach(supervisors() as $supervisor)
                                    <option value="{{$supervisor['id']}}"> {{$supervisor["name"]}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-100 btn btn-sm bg-red mt-2"><i class="bi bi-funnel"></i> Filtrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTRE BY HOUSE -->
    <div class="modal fade" id="searchByHouse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="" id="exampleModalLabel">Filtre par maison</p>
                </div>
                <div class="modal-body">
                    <form action="{{route('location.WaterFiltreByHouse',$current_agency->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choisissez une maison</label>
                                <select required name="house" class="form-control agency-modal-select2">
                                    @foreach($houses as $house)
                                    @if($house)
                                    <option value="{{$house['id']}}"> {{$house["name"]}} </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-100 btn btn-sm bg-red mt-2"><i class="bi bi-funnel"></i> Filtrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTRE BY PROPRIETOR -->
    <div class="modal fade" id="searchByProprio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="" id="exampleModalLabel">Filtrer par proprietaire</p>
                </div>
                <div class="modal-body">
                    <form action="{{route('location.WaterFiltreByProprio',$current_agency->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choisissez un propriétaire</label>
                                <select required name="proprio" class="form-control agency-modal-select2">
                                    @foreach($houses as $house)
                                    @if($house)
                                    <option value="{{$house->Proprietor->id}}"> {{$house->Proprietor->firstname}} {{$house->Proprietor->lastname}} </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-100 btn btn-sm bg-red mt-2"><i class="bi bi-funnel"></i> Filtrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- GENERATE WATER FACTURE  -->
    @can("water.invoices.generate")
    <div class="modal fade" id="generate_water_facture" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="">Génerer une facture d'eau</p>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('water_facture._GenerateFacture')}}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <span class="text-red">Choisir la location concernée</span>
                                    <span class="text-red">Choisir la location concernée</span>

                                    <div class="input-group">
                                        <span class="input-group-text border-end-0 bg-light">
                                            <i class="fas fa-search" style="color: #FFB800;"></i>
                                        </span>
                                        <input type="text" id="search-input" class="form-control border-start-0 ps-0"
                                            placeholder="Rechercher des locations...">
                                    </div>
                                    <br>
                                    <select required name="location" class="form-control agency-modal-select2" id="select-search">
                                        @foreach($locations as $location)
                                        <option value="{{$location['id']}}" class="item-search"> <strong>Maison: </strong> {{$location->House->name}} ; <strong>Index début: </strong> {{count($location->WaterFactures)!=0?$location->WaterFactures->first()->end_index: ($location->Room?$location->Room->water_counter_start_index:null)}} ; <strong>Locataire: </strong>{{$location->Locataire->name}} {{$location->Locataire->prenom}}</option>
                                        @endforeach
                                    </select>

                                    @error("location")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="">Index de fin</label>
                                    <input type="number" required name="end_index" class="form-control" placeholder="Tapez l'Index de fin ...">
                                    @error("end_index")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="w-100 btn btn-sm bg-red"><i class="bi bi-card-list"></i> Génerer</button>
                            </form>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- STOP  WATER STATE  -->
    @can("water.invoices.stop.state")
    <div class="modal fade" id="stop_house_water_state" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="">Arrêt d'état d'eau d'une maison</p>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('house_state._StopWaterStatsOfHouse')}}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <span class="text-red">Choisir la maison concernée</span>
                                    <select required name="house" class="form-control agency-modal-select2">
                                        @foreach($houses as $house)
                                        @if($house)
                                        <option value="{{$house->id}}"> {{$house->name}} </option>
                                        @endif
                                        @endforeach
                                    </select>

                                    @error("house")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="w-100 btn btn-sm bg-red"><i class="bi bi-stop-circle"></i> Arrêter l'état</button>
                            </form>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
    <br>

    <div class="row">
        <div class="col-12">
            @php
            $locations = session("locations_filtred")?session("locations_filtred"):$locations;
            @endphp
            <h5 class="text-center">Liste des <span class="text-red">locations ayant d'eau</span> dans cette agence</h5>
            <h4 class="">Total: <strong class="text-red"> {{$locations->count()}} </strong> </h4>

            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Locataire</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Télephone</th>
                            <th class="text-center">Index début</th>
                            <th class="text-center">Index fin</th>
                            <th class="text-center">P.U</th>
                            <th class="text-center">Total à payer</th>
                            <th class="text-center">Facture à payer</th>
                            <th class="text-center">Montant payé</th>
                            <th class="text-center">Nbr arrièrées </th>
                            <th class="text-center">Arriérés </th>
                            <th class="text-center">Montant dû</th>
                            <th class="text-center">Payer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $location)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->iteration}} </td>
                            <td class="text-center"> <span class=" bg-dark text-white">{{$location->Locataire?->name}} {{$location->Locataire?->prenom}} </span> </td>
                            <td class="text-center"><span class=" bg-light text-dark text-bold"> {{$location->House?->name}} ({{$location->House?->Supervisor?->name}})</span></td>
                            <td class="text-center"><span class=" bg-light text-dark text-bold"> {{$location->Locataire?->phone}}</span></td>
                            <td class="text-center"> <span class=" bg-warning text-white"> {{$location->Room?$location->Room->water_counter_start_index:null}}</span> </td>
                            <td class="text-center"> <strong class=" bg-dark text-zhite"> {{$location->end_index??0}}</strong> </td>
                            <td class="text-center"> <strong class=""> <span class=" bg-light text-dark">{{number_format($location->Room?->unit_price,0,',',' ')}}</span> </strong> </td>
                            <td class="text-center"> <strong class=" text-red bg-light"> {{$location["total_un_paid_facture_amount"]? number_format($location["total_un_paid_facture_amount"],0,","," ") :0}} fcfa </strong> </td>
                            <td class="text-center"> <strong class=" text-success bg-light"> {{$location["current_amount"]? number_format($location["current_amount"],0,","," ") :0}} fcfa </strong> </td>
                            <td class="text-center"> <strong class=" text-success bg-light"> {{$location["paid_facture_amount"]? number_format($location["paid_facture_amount"],0,","," ") :0}} fcfa </strong> </td>
                            <td class="text-center text-red"> <span class=" bg-light text-dark">{{$location["nbr_un_paid_facture_amount"]? number_format($location["nbr_un_paid_facture_amount"],0,","," ") :0}}</span> </td>
                            <td class="text-center"> <strong class=" bg-light text-red"> {{$location["un_paid_facture_amount"]? number_format($location["un_paid_facture_amount"],0,","," ") :0}} fcfa </strong> </td>
                            <td class="text-center"> <strong class=" bg-light text-success"> {{$location["rest_facture_amount"]? number_format($location["rest_facture_amount"],0,","," ") :0}} fcfa </strong> </td>

                            <td class="text-center">
                                <div class="dropdown">
                                    <a class="btn btn-sm bg-red dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i> Action
                                    </a>

                                    <ul class="dropdown-menu">
                                        @can("water.invoices.payement")
                                        <a href="#" class="dropdown-item btn btn-sm bg-red" data-bs-toggle="modal" data-bs-target="#factures" onclick="showFactures({{$location}})">
                                            &nbsp; Payer
                                        </a>
                                        @endcan

                                        @can("water.invoices.print")
                                        <a href="#" class="dropdown-item btn btn-sm btn-light text-uppercase" data-bs-toggle="modal" data-bs-target="#state_impression" onclick="stateImpression({{$location}})"><i class="bi bi-file-earmark-pdf-fill"> </i> Imprimer les états</a>
                                        @endcan

                                        @can("water.invoices.change.index")
                                        <a href="#" class="dropdown-item btn btn-sm bg-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#updateLastFactureEndIndex" onclick="updateLastFactureEndIndex({{$location}})"><i class="bi bi-pencil-square"></i> Changer l'index de fin</a>
                                        @endcan

                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODALS -->
    <!-- ###### FACTURES D'EAU -->
    <div class="modal fade" id="factures" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="">Location:
                        <strong>Maison: </strong> <span class="house_name"> </span> ;
                        <strong>Index début: </strong> <span class="debut_index"></span> ;
                        <strong>Index fin: </strong> <span class="end_index"> </span>;
                        <strong>Locataire: </strong> <span class="locataire"> </span>
                    </span>
                </div>
                <div class="modal-body factures-body">
                    <!-- gerer par du js -->
                </div>
            </div>
        </div>
    </div>

    <!-- ###### IMPRESSION DES ETATS -->
    <div class="modal fade" id="state_impression" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="">Location:
                        <strong>Maison: </strong> <span class="house_name"> </span> ;
                        <strong>Index début: </strong> <span class="debut_index"></span> ;
                        <strong>Index fin: </strong> <span class="end_index"> </span>;
                        <strong>Locataire: </strong> <span class="locataire"> </span>
                    </span>
                </div>
                <div class="modal-body states-body">
                    <!-- gerer par du js -->
                </div>
            </div>
        </div>
    </div>

    <!-- ###### MODIFIER L'INDEX DE LA DERNIERE FACTURE -->
    <div class="modal fade" id="updateLastFactureEndIndex" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="">Location:
                        <strong>Maison: </strong> <span class="house_name"> </span> ;
                        <strong>Index début: </strong> <span class="debut_index"></span> ;
                        <strong>Index fin: </strong> <span class="end_index"> </span>;
                        <strong>Locataire: </strong> <span class="locataire"> </span>
                    </span>
                </div>
                <div class="modal-body index-body">
                    <!-- gerer par du js -->
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche instantanée
            const searchLocation = document.getElementById('search-input');
            searchLocation.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                // alert(searchTerm)
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

    <!-- SCRIPT -->
    <script type="text/javascript">
        // changement d'index
        function updateLastFactureEndIndex(location) {
            $(".house_name").html(location.house_name)
            $(".debut_index").html(location.start_index)
            $(".end_index").html(location.end_index)
            $(".locataire").val(location.locataire)

            $(".index-body").empty()

            let content = '';

            if (location.lastFacture) {

                content += `
                            <form id="updateIndexForm" action="/location/water/${location.lastFacture.id}/update_end_index" method="post">
                                @csrf
                                @method("PATCH")
                                <input type="hidden" name="start_index" class="form-control" value="${location.lastFacture.start_index}" id="">
                                <input type="number" name="end_index" class="form-control" value="${location.lastFacture.end_index}" id="">
                                <hr>
                                <button type="submit" class="btn btn-sm bg-red w-100"><i class="bi bi-pencil-square"></i> Enregistrer</button>
                            </form>
                `
            } else {
                content += `<p class="text-center text-red">Aucune facture disponible</p>`
            }

            $(".index-body").append(content)

        }

        // les etats
        function stateImpression(location) {
            $(".house_name").html(location.house_name)
            $(".debut_index").html(location.start_index)
            $(".end_index").html(location.end_index)
            $(".locataire").val(location.locataire)

            $(".states-body").empty()

            let content = '';

            if (location.water_factures_states.length > 0) {
                let rows = ''

                location.water_factures_states.forEach(state => {
                    rows += `
                        <li class="list-group-item mb-3 ">
                            <strong>Date d'arrêt: </strong> <span class="stop_date"> ${state.state_stoped_day} </span>
                            <br>
                            <a href="/water_facture/${state.id}/show_water_state_html" class="w-100 btn btn-sm bg-red"><i class="bi bi-file-earmark-pdf-fill"> </i> Imprimer</a>
                        </li>
                    `
                });

                content += `
                            <ul class="list-group ">
                                ${rows}
                            </ul>
                `
            } else {
                content += `<p class="text-center text-red">Aucun état disponible</p>`
            }

            $(".states-body").append(content)

            // console.log(content)
        }

        // factures
        function showFactures(location) {
            $(".house_name").html(location.house_name)
            $(".debut_index").html(location.start_index)
            $(".end_index").html(location.end_index)
            $(".locataire").val(location.locataire)

            $(".factures-body").empty()

            let content = '';

            console.log(location.water_factures)

            if (location.water_factures.length > 0) {
                let rows = ''

                location.water_factures.forEach(facture => {
                    rows += `
                        <li class="list-group-item mb-3 ">
                            <strong>Maison: </strong> ${location.house_name} ;
                            <strong>Index début: </strong> <span class="text-red"> ${facture.start_index}</span> ;
                            <strong>Index fin: </strong> <span class="text-red"> ${facture.end_index}</span>;
                            <strong>Consommation :</strong> <span class="text-red">${facture.consomation}</span> ;
                            <strong>Montant: </strong> <span class="text-red"> ${facture.amount} </span>;
                            <strong>Description: </strong> <textarea class="form-control" name="" rows="1" placeholder="${facture.comments}" id=""></textarea> ;
                            <strong>Statut :</strong>
                            ${facture.paid?
                                '<span class=" bg-success">Payé </span>':
                                `<span class=" bg-red">Impayé </span> <br> <a href="/water_facture/${facture.id}/payement" class="btn btn-sm bg-red">  Payer maintenant</a>`
                            }
                        </li>
                    `
                });

                content += `
                            <ul class="list-group factures-list">
                                ${rows}
                            </ul>
                `
            } else {
                content += `<p class="text-center text-red">Aucune facture disponible</p>`
            }

            $(".factures-body").append(content)

            // console.log(content)
        }

        // filtre
        function displayFiltreOptions() {
            if ($("#filtre_options").hasClass('d-none')) {
                $("#filtre_options").removeClass("d-none")
            } else {
                $("#filtre_options").addClass("d-none")
            }
        }
    </script>
</div>