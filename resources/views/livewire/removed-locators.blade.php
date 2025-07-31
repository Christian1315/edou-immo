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
                    <form action="{{route('locator.RemovedFiltreBySupervisor',$current_agency->id)}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choisissez un superviseur</label>
                                <select required name="supervisor" class="form-control agency-modal-select2">
                                    @foreach(supervisors() as $supervisor)
                                    <option value="{{$supervisor['id']}}"> {{$supervisor["name"]}} </option>
                                    @endforeach
                                </select> <br>
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
                    <form action="{{route('locator.RemovedFiltreByHouse',$current_agency->id)}}" method="POST">
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
            <h4 class="">Total: <strong class="text-red"> {{session("filteredLocators")? count(session("filteredLocators")):count($locators)}} </strong> </h4>
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Superviseurs</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Prénom</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Numéro de pièce</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Adresse</th>
                            <th class="text-center">Dernier mois payé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session("filteredLocators")?session("filteredLocators"):$locators as $location)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}} </td>
                            <td class="text-center text-red"> <strong class=" bg-light text-dark p-2 btn btn-sm"> {{$location->House?->name}}</strong></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{ $location->House?->Supervisor?->name }} </span></td>
                            <td class="text-center"> <strong class=" bg-light text-dark p-2"> {{ $location->Room?$location->Room->number:"deménagé" }}</strong></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$location->Locataire?->name}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$location->Locataire?->prenom}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$location->Locataire?->email}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$location->Locataire?->card_id}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$location->Locataire?->phone}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$location->Locataire?->adresse}}</span></td>
                            <td class="text-center"> <button class=" btn bg-light text-red"> {{$location["latest_loyer_date"]}} </button> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <br><br>

            <!-- pagination -->
            <div class="justify-center my-2">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
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