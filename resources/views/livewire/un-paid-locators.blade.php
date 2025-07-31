<div>
    <input type="checkbox" hidden class="btn-check" id="displayLocatorsOptions" onclick="displayLocatorsOptions_fun()">
    <label class="btn btn-light" for="displayLocatorsOptions"><i class="bi bi-funnel"></i>FILTRER LES LOCATAIRES</label>

    <div id="display_filtre_options" hidden>
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#ShowSearchLocatorsBySupervisorForm"><i class="bi bi-people"></i> Par Sperviseur</button>
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#ShowSearchLocatorsByHouseForm"><i class="bi bi-house-check-fill"></i> Par Maison</button>
    </div>

    <!-- FILTRE BY SUPERVISOR -->

    <div class="modal fade" id="ShowSearchLocatorsBySupervisorForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="" id="exampleModalLabel">Filter par superviseur</p>
                </div>
                <div class="modal-body">
                    <form action="{{route('locator.UnPaidFiltreBySupervisor',$current_agency->id)}}" method="POST">
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
    <div class="modal fade" id="ShowSearchLocatorsByHouseForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="" id="exampleModalLabel">Filter par maison</p>
                </div>
                <div class="modal-body">
                    <form action="{{route('locator.UnPaidFiltreByHouse',$current_agency->id)}}" method="POST">
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
    <br><br>

    <br>

    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <h4 class="">Total: <strong class="text-red"> {{session('filteredLocators')? count(session('filteredLocators')):count($locators)}} </strong> </h4>
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Superviseur</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Locataire</th>
                            <th class="text-center">Dernier mois Payé</th>
                            <th class="text-center">Loyer</th>
                            <!-- <th class="text-center">Echéance actuelle</th> -->
                            <th class="text-center">Echeance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('filteredLocators')?session('filteredLocators'):$locators as $location)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center"> <span class=" bg-dark text-white">{{$location->House?->name}}</span> </td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{ $location->House?->Supervisor?->name }} </span> </td>
                            <td class="text-center">{{ $location->Room?$location->Room->number:"deménagé" }}</td>
                            <td class="text-center"> <span class=" bg-light text-dark"> {{$location->Locataire?->name}} {{$location->Locataire?->prenom}} ({{$location->Locataire?->phone}}) </span> </td>
                            <td class="text-center text-red"><small> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location["latest_loyer_date"])->locale('fr')->isoFormat('MMMM YYYY') }}</small> </td>
                            <td class="text-center"> <span class=" bg-dark text-white"> {{number_format($location->loyer,0," "," ")}}</span> </td>
                            <td class="text-center text-red"><small> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location["echeance_date"])->locale('fr')->isoFormat('D MMMM YYYY') }}</small> <small class="text-dark">({{ $location->pre_paid?"PRE_PAYE":"" }} {{ $location->post_paid ? "POST_PAYE":'' }})</small>  </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function displayLocatorsOptions_fun() {
            var value = $('#displayLocatorsOptions')[0].checked
            if (value) {
                $('#display_filtre_options').removeAttr('hidden');
            } else {
                $('#display_filtre_options').attr("hidden", "hidden");
            }
        }
    </script>
</div>