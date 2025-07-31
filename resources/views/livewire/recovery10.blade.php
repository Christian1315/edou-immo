<div>
    <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
        <input type="checkbox" hidden onclick="generate_taux_btn_fun()" name="discounter" class="btn-check" id="generate_taux_btn" autocomplete="off">
        <label class="btn btn-sm bg-red text-uppercase" for="generate_taux_btn"><i class="bi bi-file-earmark-pdf-fill"></i>Génerer les états des taux</label>
    </div>

    <div id="show_action_buttons" hidden>
        <a class="btn btn-sm btn-light" href="{{route('taux._ShowAgencyTaux10_Simple',crypId($agency->id))}}"><i class="bi bi-house-dash"></i> Pour cette agence</a>
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#showTauxBySupervisor"><i class="bi bi-people"></i> Par Sperviseur </button>
        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#showTauxByHouse"><i class="bi bi-house-check-fill"></i>Par maison </button>
    </div>

    <br><br>
    <!-- SHOW TAUX BY SUPERVISOR -->
    <div class="modal fade" id="showTauxBySupervisor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="">Taux par superviseur:</p>
                    <ul class="list-group">
                        @foreach(supervisors() as $supervisor)
                        <li class="list-group-item" style="justify-content: space-between!important">{{$supervisor->name}} &nbsp; <a href="{{route('taux._ShowAgencyTaux10_By_Supervisor',['agencyId'=>crypId($agency->id),'supervisor'=>crypId($supervisor->id)])}}" class="btn btn-sm btn-light text-red"><i class="bi bi-eye"></i></a> </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="showTauxByHouse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="">Taux par maison:</p>
                    <ul class="list-group">
                        @foreach($houses as $house)
                        <li class="list-group-item" style="justify-content: space-between!important">{{$house->name}} &nbsp; <a href="{{route('taux._ShowAgencyTaux10_By_House',['agencyId'=>crypId($agency->id),'house'=>crypId($house->id)])}}" class="btn btn-sm btn-light text-red"><i class="bi bi-eye"></i></a> </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p class="text">Locataires <strong class="text-red"> ayant payé</strong> après l’arrêt des derniers états jusqu’à leur date d’échéance du 10 à 00h au plus tard</p>
            <h4 class="">Total: <strong class="text-red"> {{count($locators)}} </strong> </h4>
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Prénom</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Adresse</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Superviseur</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locators as $locator)
                        <tr class="align-items-center">
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["name"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["prenom"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["phone"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["adresse"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["email"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["locator_location"]["House"]["name"]}}</span></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$locator["locator_location"]["House"]["Supervisor"]["name"]}}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>