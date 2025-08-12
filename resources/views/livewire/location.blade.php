<div>
    @can("location.add.type")
    <!-- AJOUT D'UN TYPE DE CHAMBRE -->
    <div class="text-left">
        <button type="button" class="btn btn btn-sm bg-light shadow roundered" data-bs-toggle="modal" data-bs-target="#location_type">
            <i class="bi bi-cloud-plus-fill"></i>Ajouter un type de location
        </button>
    </div>
    <br>

    <!-- Modal room type-->
    <div class="modal fade" id="location_type" aria-labelledby="location_type" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5">Type de location</h5>
                </div>
                <form action="{{route('location.AddType')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <input type="text" required name="name" placeholder="Le label ...." class="form-control">
                                </div><br>
                                <div class="mb-3">
                                    <textarea required name="description" class="form-control" placeholder="Description ...."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn bg-red btntsm"><i class="bi bi-building-check"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    <small>
        <input type="checkbox" hidden class="btn-check" id="displayLocatorsOptions" onclick="displayFiltreOptions()">
        <label class="btn btn-light" for="displayLocatorsOptions"><i class="bi bi-file-earmark-pdf-fill"></i>Filtrer les locations</label>
        <!--  -->
        @can("location.generate.cautions.state.agency")
        <a href="{{route('location._ManageCautions',crypId($current_agency->id))}}" class="btn btn-sm bg-light text-dark text-uppercase"><i class="bi bi-file-earmark-pdf-fill"></i> états des cautions de l'agence</a> &nbsp;
        @endcan
        <button data-bs-toggle="modal" data-bs-target="#ShowSearchLocatorsByHouseForm" class="btn btn-sm bg-light text-dark text-uppercase"><i class="bi bi-file-pdf-fill"></i> Prestation par période</button>
    </small>

    <!-- FILTRE CAUTIONS BY PERIOD -->
    <div class="modal fade" id="ShowSearchLocatorsByHouseForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="" id="exampleModalLabel">Générer par période</p>
                </div>
                <div class="modal-body">
                    <form action="{{route('location._ManagePrestationStatistiqueForAgencyByPeriod', crypId($current_agency->id))}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <span>Date de début</span>
                                <input type="date" required name="first_date" class="form-control" id="">
                            </div>
                            <div class="col-md-6">
                                <span class="">Date de fin</span>
                                <input type="date" required name="last_date" class="form-control" id="">
                            </div>
                        </div>
                        <br>
                        <div class="text-center">
                            <button type="submit" class="w-100 text-center bg-red btn btn-sm"><i class="bi bi-funnel"></i> Génerer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="filtre_options" class="d-none">
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#serchBySupervisor"><i class="bi bi-people"></i> Par Sperviseur</button>
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#searchByHouse"><i class="bi bi-house-check-fill"></i> Par Maison</button>
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#searchByProprio"><i class="bi bi-house-check-fill"></i> Par Propriétaire</button>
    </div>

    <!-- FILTRE BY SUPERVISOR -->
    <div class="modal fade" id="serchBySupervisor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="" id="exampleModalLabel">Filtre par superviseur</p>
                </div>
                <div class="modal-body">
                    <form class="serchBySupervisor" action="{{route('location.FiltreBySupervisor',$current_agency->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choisissez un superviseur</label>
                                <select required name="supervisor" class="form-control agency-modal-select2">
                                    @foreach(supervisors() as $supervisor)
                                    <option value="{{$supervisor['id']}}"> {{$supervisor["name"]}} </option>
                                    @endforeach
                                </select>
                                <br>
                                <button type="submit" class="w-100 btn btn-sm bg-red mt-2"><i class="bi bi-funnel"></i> Filtrer</button>
                            </div>
                        </div>
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
                    <form action="{{route('location.FiltreByHouse',$current_agency->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choisissez une maison</label>
                                <select required name="house" class="form-control agency-modal-select2">
                                    @foreach($current_agency->_Houses as $house)
                                    <option value="{{$house['id']}}"> {{$house["name"]}} </option>
                                    @endforeach
                                </select>
                                <br>
                                <button type="submit" class="w-100 btn btn-sm bg-red mt-2"><i class="bi bi-funnel"></i> Filtrer</button>
                            </div>
                        </div>
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
                    <form action="{{route('location.FiltreByProprio',$current_agency->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choisissez un propriétaire</label>
                                <select required name="proprio" class="form-control agency-modal-select2">
                                    @foreach($current_agency->_Houses as $house)
                                    <option value="{{$house->Proprietor->id}}"> {{$house->Proprietor->firstname}} {{$house->Proprietor->lastname}} </option>
                                    @endforeach
                                </select>
                                <br>
                                <button type="submit" class="w-100 btn btn-sm bg-red mt-2"><i class="bi bi-funnel"></i> Filtrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @can("location.create")
    <div class="d-flex header-bar">
        <small>
            <button type="button" class="btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#addLocation">
                <i class="bi bi-cloud-plus-fill"></i> Ajouter
            </button>
        </small>
    </div>
    <br><br>

    <!-- ADD LOCATION -->
    <div class="modal fade" id="addLocation" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="">Ajout d'une location</p>
                    <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('location._AddLocation')}}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="d-block" for="">Agence</label>
                                    <input type="hidden" name="agency" value="{{$current_agency->id}}">
                                    <input type="text" disabled class="form-control" placeholder="Agence :{{$current_agency['name']}}">
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Maison</label>
                                    <select class="form-select form-control agency-modal-select2" onchange="houseSelect()" id="houseSelection" name="house" aria-label="Default select example">
                                        @foreach($houses as $house)
                                        <option value="{{$house['id']}}" @if(old('house')==$house['id']) selected @endif>{{$house['name']}}</option>
                                        @endforeach
                                    </select>
                                    @error("house")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <br>

                                <div class="mb-3" id="roomsShow" hidden>
                                    <label class="d-block" for="">Chambre</label>
                                    <select class="form-select form-control agency-modal-select2" name="room" id="rooms" aria-label="Default select example">

                                    </select>
                                    @error("room")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <br>

                                <div class="mb-3">
                                    <label class="d-block" for="">Locataire</label>
                                    <select class="form-select form-control agency-modal-select2" name="locataire" aria-label="Default select example">
                                        @foreach($locators as $locator)
                                        <option value="{{$locator['id']}}" @if(old('locator')==$locator['id']) selected @endif>{{$locator['name']}} {{$locator['prenom']}}</option>
                                        @endforeach
                                    </select>
                                    @error("locataire")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Type</label>
                                    <select class="form-select form-control agency-modal-select2" name="type">
                                        @foreach($location_types as $type)
                                        <option value="{{$type['id']}}" @if(old('type')==$type['id']) selected @endif>{{$type['name']}}</option>
                                        @endforeach
                                    </select>
                                    @error("type")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <br>
                                <div class="mb-3">
                                    <span>Uploader le bordereau du caution</span><br>
                                    <input type="file" name="caution_bordereau" class="form-control">
                                    @error("caution_bordereau")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Caution d'électricité</label>
                                    <input value="{{old('caution_electric')}}" type="number" name="caution_electric" class="form-control" placeholder="Caution d'électricité...">
                                    @error("caution_electric")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Numéro du compteur eau ...</label>
                                    <input value="{{old('water_counter')}}" type="text" name="water_counter" placeholder="Compteur eau..." class="form-control">
                                    @error("water_counter")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Prestation</label>
                                    <input value="{{old('prestation')}}" type="number" name="prestation" placeholder="La prestation..." class="form-control">
                                    @error("prestation")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block">Numéro contrat</label>
                                    <input value="{{old('numero_contrat')}}" type="text" name="numero_contrat" placeholder="Numéro du contrat..." class="form-control">
                                    @error("numero_contrat")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                            </div>
                            <!--  -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <span>Uploader le contrat</span><br>
                                    <input type="file" name="img_contrat" class="form-control">
                                    @error("img_contrat")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Caution eau</label>
                                    <input value="{{old('caution_water')}}" type="number" name="caution_water" class="form-control" placeholder="Caution eau ....">
                                    @error("caution_water")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Numéro du compteur électrique</label>
                                    <input value="{{old('electric_counter')}}" type="text" name="electric_counter" class="form-control" placeholder="Compteur électricité ....">
                                    @error("electric_counter")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>

                                <div class="mb-3">
                                    <span>Uploader l'image de la prestation</span><br>
                                    <input type="file" name="img_prestation" class="form-control">
                                    @error("img_prestation")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Nbr de caution loyer</label>
                                    <input value="{{old('caution_number')}}" type="number" name="caution_number" class="form-control" placeholder="Nombre de caution loyer ....">
                                    @error("caution_number")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <span>Date d'effet</span><br>
                                    <input value="{{old('effet_date')}}" type="date" name="effet_date" class="form-control" placeholder="Date de prise d'effet ....">
                                    @error("effet_date")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <span>Frais de reprise de peinture</span><br>
                                    <input value="{{old('frais_peiture')}}" type="number" name="frais_peiture" class="form-control" placeholder="Frais de reprise de peinture ....">
                                    @error("frais_peiture")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Commentaire</label>
                                    <textarea value="{{old('comments')}}" name="comments" class="form-control" placeholder="Laissez un commentaire ici" class="form-control" id=""></textarea>
                                    @error("comments")
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div><br>

                                <div class="mb-3 d-flex">
                                    <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                                        <input type="checkbox" name="pre_paid" class="btn-check" id="pre_paid" autocomplete="off">
                                        <label class="btn bg-dark text-white" for="pre_paid">Prépayé</label>
                                    </div>
                                    <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                                        <input type="checkbox" name="post_paid" class="btn-check" id="post_paid" autocomplete="off">
                                        <label class="btn bg-dark text-white" for="post_paid">Post-Payé</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="w-100 btn-sm btn bg-red"><i class="bi bi-check-circle-fill"></i> Enregistrer</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <?php
            $now = strtotime(date("Y/m/d", strtotime(now())));

            $locations = session("locations_filtred") ? session("locations_filtred") : $locations;

            $paid_locators = $locations->where("status", '!=', 3)->filter(function ($location) use ($now) {
                $location_echeance_date = strtotime(date("Y/m/d", strtotime($location->echeance_date)));

                if ($location_echeance_date > $now) {
                    return $location->Locataire;
                }
            });

            $unpaid_locators = $locations->where("status", '!=', 3)->filter(function ($location) use ($now) {
                $location_echeance_date = strtotime(date("Y/m/d", strtotime($location->echeance_date)));
                if ($location_echeance_date < $now) {
                    return $location->Locataire;
                }
            });

            $removed_locators = $locations->where("status", 3);; ?>

            <h4 class="">Total: <strong class="text-red"> {{session("locations_filtred")?count(session("locations_filtred")): $locations_count}} </strong> | Légende: <button class="btn btn-sm btn-warning text-uppercase" data-bs-toggle="modal" data-bs-target="#serchBySupervisor" onclick="searchPaidBySuperviseur()">{{count($unpaid_locators)}} Impayé(s) <span class="bagde bg-light shadow rounded p-1"><i class="bi bi-file-earmark-pdf-fill"></i> Imprimer</span> </button> <button class="btn btn-sm btn-secondary text-uppercase">{{count($removed_locators)}} Démenagé(s)</button> <button class="btn btn-sm btn-light text-uppercase">{{count($paid_locators)}} à jour</button> | <button class="btn btn-sm btn-light text-uppercase" data-bs-toggle="modal" data-bs-target="#serchBySupervisor" onclick="searchAllBySupervisor()"> {{count($locations)}} Tous <span class="bagde bg-dark shadow shadow rounded p-1"><i class="bi bi-file-earmark-pdf-fill"></i> Imprimer</span></button> </h4>
            <div class="p-3 table table-responsive table-responsive-list shadow">
                <table id="myTable" class="table-striped">
                    <div id="buttons"></div>
                    <thead class="bg_dark">
                        <tr>
                            <!-- <th class="text-center">N°</th> -->
                            <th class="text-center">Maison <span class="d-block d-sm-none"> | Locataire | Loyer | Dernier mois Payé</span></th>
                            <th class="text-center">Propriétaire</th>
                            <th class="text-center">Superviseur</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Locataire</th>
                            <th class="text-center">Dernier mois Payé</th>
                            <th class="text-center">Loyer</th>
                            <!-- <th class="text-center">Echéance actuelle</th> -->
                            <th class="text-center">Echeance</th>
                            {{-- <th class="text-center">Commentaire</th> --}}
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session("locations_filtred")?session("locations_filtred"): $locations as $location)
                        @php
                        $now = strtotime(date("Y/m/d", strtotime(now())));
                        $location_echeance_date = strtotime(date("Y/m/d", strtotime($location->echeance_date)));
                        @endphp
                        <tr class="align-items-center @if($location->status==3) bg-secondary text-white @elseif($location_echeance_date < $now) bg-warning @endif ">
                            <!-- <td class="text-center">{{$loop->index+1}} </td> -->
                            <td class="text-left">
                                <span class=" bg-light text-dark">{{$location->House?->name}}</span>
                                <span class="bg-light text-dark d-block d-sm-none">
                                    | {{$location->Locataire?->name}} {{$location->Locataire?->prenom}} ({{$location->Locataire?->phone}}) | {{number_format($location["loyer"],0,","," ") }} | <small class="@if($location->status==3) text-white @endif"> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location["latest_loyer_date"])->locale('fr')->isoFormat('MMMM YYYY') }}</small>
                                </span>
                            </td>
                            <td class="text-left"><span class=" bg-light text-dark">{{$location->House?->Proprietor?->firstname}} {{$location->House?->Proprietor?->lastname}} </span></td>
                            <td class="text-center"> <span class="text-uppercase ">{{ $location->House?->Supervisor?->name }} </span> </td>
                            <td class="text-center">{{$location->Room?$location->Room->number:"---"}}</td>
                            <td class="text-center"><span class="text-uppercase   bg-light text-dark">{{$location->Locataire?->name}} {{$location->Locataire?->prenom}} ({{$location->Locataire?->phone}})</span></td>

                            <td class="text-center text-red"><small class="@if($location->status==3) text-white @endif"> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location["latest_loyer_date"])->locale('fr')->isoFormat('MMMM YYYY') }}</small> </td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{number_format($location["loyer"],0,","," ") }}</span></td>
                            <td class="text-center">
                                @if($location->status!=3)
                                <span class="text-red text-uppercase  bg-light text-dark">
                                    <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location["echeance_date"])->locale('fr')->isoFormat('D MMMM YYYY') }}<small class="text-dark">({{ $location->pre_paid?"PRE_PAYE":"" }} {{ $location->post_paid ? "POST_PAYE":'' }})</small>
                                </span>
                                @else
                                <textarea name="" rows="1" class="form-control" placeholder="Démenagé le {{ \Carbon\Carbon::parse($location['move_date'])->locale('fr')->isoFormat('D MMMM YYYY') }} par {{$location->MovedBy?->name}} ; Commentaire: ({{$location->move_comments}})"></textarea>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group dropstart">
                                    <button class="btn bg-red btn-sm dropdown-toggle" style="z-index: 0;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-kanban-fill"></i> &nbsp; Gérer
                                    </button>
                                    <ul class="dropdown-menu">
                                        <!-- quand le locataire n'est pas demenagé -->
                                        @if ($location->status!=3)
                                        @can("location.collect")
                                        <li>
                                            <button data-bs-toggle="modal" data-bs-target="#encaisse" onclick="encaisser({{$location}})" class="w-100 btn btn-sm bg-dark">
                                                <i class="bi bi-currency-exchange"></i> Encaisser
                                            </button>
                                        </li>
                                        @endcan

                                        @can("location.removed")
                                        <li>
                                            <button data-bs-toggle="modal" data-bs-target="#demenage" onclick="demenage({{$location}})" class="w-100 btn btn-sm bg-red">
                                                <i class="bi bi-folder-x"></i> Démenager
                                            </button>
                                        </li>
                                        @endcan

                                        @can("location.edit")
                                        <li>
                                            <button class="w-100 btn btn-sm bg-warning" onclick="editLocation({{$location}})" data-bs-toggle="modal" data-bs-target="#updateModal"><i class="bi bi-person-lines-fill"></i> Modifier</button>
                                        </li>
                                        @endcan
                                        @endif

                                        <li>
                                            <button class="w-100 btn btn-sm btn-light text-dark" data-bs-toggle="modal" data-bs-target="#shoFactures_{{$location['id']}}"><i class="bi bi-printer"></i> Gérer les factures</button>
                                        </li>

                                        @can("location.print.report")
                                        <li>
                                            <a target="_blank" href="{{route('location.imprimer',crypId($location['id']))}}" class="btn btn-sm bg-light text-dark w-100"><i class="bi bi-file-earmark-pdf-fill"></i> Imprimer rapport</a>
                                        </li>
                                        @endcan

                                        @can("location.generate.cautions.state")
                                        <li>
                                            <a target="_blank" href="{{route('location._ManageLocationCautions',$location->id)}}" class="btn btn-sm text-dark btn-light w-100" rel="noopener noreferrer"><i class="bi bi-file-earmark-pdf"></i> Etats des cautions </a>
                                        </li>
                                        @endcan

                                        <!-- @can("location.generate.proratas.state")
                                        <li>
                                            <a target="_blank" @style(["pointer-events:none"=>!$location->Locataire->prorata,"cursor:default"=>!$location->Locataire->prorata]) data-role="disabled" href="{{route('location._ManageLocationProrata',$location->id)}}" class="btn btn-sm text-dark btn-light w-100" rel="noopener noreferrer"><i class="bi bi-file-earmark-pdf"></i> Etats des proratas </a>
                                        </li>
                                        @endcan -->
                                    </ul>
                                </div>
                            </td>
                        </tr>

                        <!-- ###### MODEL DE SHOW DES FACTURES ###### -->
                        <div class="modal fade" id="shoFactures_{{$location['id']}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title fs-5" id="exampleModalLabel">Factures </h6>
                                    </div>
                                    <div class="modal-body">
                                        <div class="">
                                            <strong>Maison: <em class="text-red"> {{$location['House']["name"]}}</em> </strong> <br>
                                            <strong>Chambre: <em class="text-red"> {{$location['Room']?$location['Room']['number']:"---"}} </em> </strong> <br>
                                            <strong>Locataire: <em class="text-red"> {{$location['Locataire']['name']}} {{$location['Locataire']['prenom']}}</em> </strong>
                                        </div>
                                        <div>
                                            <ul class="list-group">
                                                @foreach($location->Factures as $facture)
                                                <li class="list-group-item mb-3 ">
                                                    <strong>Code :</strong> {{$facture->facture_code}} <br>
                                                    <strong>Montant: </strong> {{$facture->amount}} <br>
                                                    <strong>Fichier: </strong> <a href="{{$facture->facture}}" class="" rel="noopener noreferrer"><i class="bi bi-eye"></i></a><br>
                                                    <strong>Date d'écheance: </strong> {{Change_date_to_text($facture->echeance_date)}} <br>
                                                    <strong>Status: </strong> <span class=" @if($facture->status==1) bg-warning @elseif($facture->status==2) bg-success @else bg-danger @endif "> {{$facture->Status->description}}</span> <br>
                                                    <strong>Description: </strong> <textarea class="form-control" name="" rows="1" placeholder="{{$facture->comments}}" id=""></textarea>
                                                </li>
                                                @endforeach
                                            </ul>
                                            @if(count($location->Factures)==0)
                                            <p class="text-center text-red">Aucune facture disponible</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ###### MODEL D'ENCAISSEMENT ###### -->
    <div class="modal fade" id="encaisse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fs-5" id="exampleModalLabel">Encaissement </h6>
                </div>
                <div class="modal-body">
                    <div class="">
                        <strong>Maison: <em class="text-red location_name"> </em> </strong> <br>
                        <strong>Chambre: <em class="text-red location_room"> </em> </strong> <br>
                        <strong>Locataire: <em class="text-red location_locataire"></em> </strong>
                    </div>
                    <form method="POST" id="encaisserForm" class="shadow-lg p-3 animate__animated animate__bounce p-3" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="location" class="location">

                        <div class="row p-3">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Type de paiement </label>
                                    <select name="type" class="form-select form-control agency-modal-select2" aria-label="Default select example">
                                        @foreach($paiements_types as $type)
                                        <option value="{{$type['id']}}" name="type">{{$type["name"]}}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <span>Date ou mois pour lequel vous voulez encaisser pour cette location</span>
                                    <input disabled class="form-control next_loyer_date">
                                </div>

                                <div class="d-none prorata">
                                    <span class="text-primary">Ce locataire est un prorata(veuillez renseigner ses infos)</span>
                                    <br>
                                    <div class="mb-3">
                                        <label for="" class="d-block">Nbre de jour du prorata</label>
                                        <input type="number" name="prorata_days" placeholder="Nbre de jour du prorata ..." class="form-control prorata_days">
                                        @error('prorata_days')
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="d-block">Montant du prorata</label>
                                        <input type="number" name="prorata_amount" placeholder="Montant du prorata ..." class="form-control prorata_amount">
                                        @error('prorata_amount')
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="d-block">Date du prorata</label>
                                        <input name="prorata_date" type="date" class="form-control prorata_date" hidden>
                                        <input disabled type="date" class="form-control prorata_date">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <span>Uploader la facture ici</span> <br>
                                    <input type="file" name="facture" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="d-block">Code de facture</label>
                                    <input value="{{old('facture_code')}}" name="facture_code" required placeholder="Code facture ...." class="form-control facture_code">
                                    @error('facture_code')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="w-100 btn btn-sm bg-red"><i class="bi bi-check-all"></i> Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ###### MODEL DE DEMENAGEMENT ###### -->
    <div class="modal fade" id="demenage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fs-5" id="exampleModalLabel">Démenagement </h6>
                </div>
                <div class="modal-body">
                    <div class="">
                        <strong>Maison: <em class="text-red location_name"> </em> </strong> <br>
                        <strong>Chambre: <em class="text-red location_room"> </em> </strong> <br>
                        <strong>Locataire: <em class="text-red location_locataire"></em> </strong>
                    </div>
                </div>
                <form action="{{route('location.DemenageLocation')}}" id="demenageForm" method="POST" class="shadow-lg p-3 animate__animated animate__bounce p-3">
                    @csrf
                    @method("POST")
                    <input type="hidden" name="locationId" id="locationId">
                    <div class="p-2">
                        <textarea name="move_comments" id="move_comments" required class="form-control" placeholder="Donner une raison justifiant ce déménagement"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="w-100 btn btn-sm bg-red"><i class="bi bi-check-all"></i> Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ###### MODEL DE MODIFICATION ###### -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fs-5" id="exampleModalLabel">Modification </h6>
                </div>
                <div class="modal-body">
                    <div class="">
                        <strong>Maison: <em class="text-red location_name"> </em> </strong> <br>
                        <strong>Chambre: <em class="text-red location_room"> </em> </strong> <br>
                        <strong>Locataire: <em class="text-red location_locataire"></em> </strong>
                    </div>
                    <form id="updateForm" method="POST" class="shadow-lg p-2">
                        @csrf
                        @method("PATCH")
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="d-block">Maison</label>
                                    <select class="form-select form-control house agency-modal-select2" name="house" aria-label="Default select example">
                                        @foreach($houses as $house)
                                        <option value="{{$house['id']}}">{{$house['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>

                                <div class="mb-3">
                                    <label class="d-block" for="">Chambre</label>
                                    <select class="form-select form-control room agency-modal-select2" name="room" aria-label="Default select example">
                                        @foreach($rooms as $room)
                                        <option value="{{$room['id']}}">{{$room['number']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>

                                <div class="mb-3">
                                    <label class="d-block" for="">Locataire</label>
                                    <select class="form-select form-control locataire agency-modal-select2" name="locataire" aria-label="Default select example">
                                        @foreach($locators as $locator)
                                        <option value="{{$locator['id']}}">{{$locator->name}} {{$locator->prenom}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Type</label>
                                    <select class="form-select form-control type agency-modal-select2" name="type">
                                        @foreach($location_types as $type)
                                        <option value="{{$type['id']}}">{{$type['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <div class="mb-3">
                                    <span>Uploader le bordereau du caution</span><br>
                                    <input type="file" name="caution_bordereau" class="form-control">
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Caution d'électricité</label>
                                    <input type="text" name="caution_electric" class="form-control caution_electric" placeholder="Caution d'électricité...">

                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Numéro du compteur eau ...</label>
                                    <input type="text" name="water_counter" placeholder="Compteur eau..." class="form-control water_counter">
                                </div><br>

                            </div>
                            <!--  -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <span>Uploader le contrat</span><br>
                                    <input type="file" name="img_contrat" class="form-control">
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Caution eau</label>
                                    <input type="text" required name="caution_water" class="form-control caution_water" placeholder="Caution eau ....">

                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Numéro du compteur électrique</label>
                                    <input type="text" name="electric_counter" class="form-control electric_counter" placeholder="Compteur électricité ....">
                                </div><br>

                                <div class="mb-3">
                                    <span>Uploader l'image de la prestation</span><br>
                                    <input type="file" name="img_prestation" class="form-control">
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Nbr de caution loyer</label>
                                    <input type="number" name="caution_number" class="form-control caution_number" placeholder="Nombre de caution loyer ....">
                                </div><br>
                                <div class="mb-3">
                                    <span>Frais de reprise de peinture</span><br>
                                    <input type="number" name="frais_peiture" class="form-control frais_peiture" placeholder="Frais de reprise de peinture ....">
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block" for="">Prestation</label>
                                    <input type="number" name="prestation" placeholder="La prestation..." class="form-control prestation">
                                </div><br>
                                <div class="mb-3">
                                    <label class="d-block">Numéro contrat</label>
                                    <input type="text" name="numero_contrat" placeholder="Numéro du contrat..." class="form-control numero_contrat">
                                </div><br>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="w-100 btn btn-sm bg-red">Modifier</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            houseSelect($('#houseSelection').val())
        })

        function demenage(location) {
            console.log("Location object:", location);

            $(".location_name").html(location.house.name)
            $(".location_room").html(location.room ? location.room.number : "---")
            $(".location_locataire").html(location.locataire.name + " " + location.locataire.prenom)

            // Vérifier si l'élément existe
            const locationIdInput = document.getElementById("locationId");
            if (locationIdInput) {
                locationIdInput.value = location.id;
                console.log("Input value set to:", locationIdInput.value);
            } else {
                console.error("Element with id 'locationId' not found!");
            }
        }

        function encaisser(location) {
            $(".location_name").html(location.house.name)
            $(".location_room").html(location.room.number)
            $(".location_locataire").html(location.locataire.name + " " + location.locataire.prenom)
            $("#locationsId").val(location.id)

            $("#encaisserForm").attr("action", `/location/add-paiement`)
            $(".location").val(location.id)

            // const date = new Date(location.echeance_date);
            const date = new Date(location.latest_loyer_date);
            date.setMonth(date.getMonth() + 1);

            const options = {
                year: "numeric",
                month: "long",
                day: "numeric"
            };
            const formattedDate = date.toLocaleDateString("fr", options);

            // alert(formattedDate)
            $(".next_loyer_date").val(formattedDate)
            // alert(location.locataire.prorata)
            if (location.locataire.prorata) {
                $(".prorata").removeClass("d-none")
                $(".prorata_days").val(location.prorata_days ?? 0)
                $(".prorata_amount").val(location.prorata_amount)
                $(".prorata_date").val(location.locataire.prorata_date)
            }
        }

        function editLocation(location) {
            $(".location_name").html(location.house.name)
            $(".location_room").html(location.room.number)
            $(".location_locataire").html(location.locataire.name + " " + location.locataire.prenom)
            $("#updateForm").attr("action", `/location/${location.id}/update`)
            $(".caution_electric").val(location.caution_electric)
            $(".water_counter").val(location.water_counter)
            $(".caution_water").val(location.caution_water)
            $(".electric_counter").val(location.electric_counter)
            $(".caution_number").val(location.caution_number)
            $(".frais_peiture").val(location.frais_peiture)
            $(".prestation").val(location.prestation)
            $(".numero_contrat").val(location.numero_contrat)
        }

        // IMPRESSION
        function searchPaidBySuperviseur() {
            $(".serchBySupervisor").attr("action", "{{route('location.PrintUnPaidLocationBySupervisor',crypId($current_agency->id))}}")
        }

        function searchAllBySupervisor() {
            $(".serchBySupervisor").attr("action", "{{route('location.PrintAllLocationBySupervisor',crypId($current_agency->id))}}")
        }

        // filtre
        function displayFiltreOptions() {
            if ($("#filtre_options").hasClass('d-none')) {
                $("#filtre_options").removeClass("d-none")
            } else {
                $("#filtre_options").addClass("d-none")
            }
        }

        function locationSelection(val = null) {
            var locationSelected = val ? val : $('#location_selected').val()

            $('#loading').removeAttr('hidden');

            axios.get("{{env('API_BASE_URL')}}location/" + locationSelected + "/retrieve").then((response) => {
                var location = response.data
                var location_locataire = location["locataire"]

                $("#encaisse_date_info").removeAttr("hidden")
                $("#encaisse_date").val(location.next_loyer_date)

                // alert(location_locataire)
                if (location_locataire.prorata) {
                    $("#prorata_infos").removeAttr("hidden")
                    $("#prorata_date").removeAttr("hidden")
                    $("#prorata_date").val(location_locataire.prorata_date)
                }
            }).catch(() => {
                //alert("une erreure s'est produite")
            })
        }

        function houseSelect(_val = null) {
            var houseSelected = _val ? _val : $('#houseSelection').val()
            $('#rooms').empty();

            $('#loading').removeAttr('hidden');

            axios.get("{{env('API_BASE_URL')}}house/" + houseSelected + "/retrieve").then((response) => {
                // alert("gogo "+houseSelected)
                var house_rooms = response.data["rooms"];
                for (var i = 0; i < house_rooms.length; i++) {
                    var val = house_rooms[i].id;
                    var text = house_rooms[i].number;
                    $('#rooms').append("<option value=" + val + ">" + text + "</option>");
                }

                $('#roomsShow').removeAttr("hidden");
                $('#loading').attr("hidden", "hidden");

            }).catch(() => {
                //alert("une erreure s'est produite")
            })
        }

        function discounterClick_fun() {
            var value = $('#discounter')[0].checked
            if (value) {
                $('#show_discounter_info').removeAttr('hidden');
            } else {
                $('#show_discounter_info').attr("hidden", "hidden");
            }
        }
    </script>
</div>