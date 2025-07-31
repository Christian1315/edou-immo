<div>
    <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
        <input type="checkbox" hidden onclick="generate_taux_btn_fun()" name="discounter" class="btn-check" id="generate_taux_btn" autocomplete="off">
        <label class="btn btn-sm bg-red text-uppercase" for="generate_taux_btn"><i class="bi bi-file-earmark-pdf-fill"></i>Génerer les états des taux</label>
    </div>

    <div id="show_action_buttons" hidden>
        <a class="btn btn-sm btn-light" href="{{route('taux._ShowAgencyTaux05_Simple',crypId($agency->id))}}"><i class="bi bi-house-dash"></i> Pour cette agence</a>
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
                    <form action="{{route('taux._ShowAgencyTaux05_By_Supervisor',crypId($agency->id))}}" method="get">
                        <select name="supervisor" class="form-control agency-modal-select2" required>
                            @foreach(supervisors() as $supervisor)
                            <option value="{{$supervisor->id}}">{{$supervisor->name}}</option>
                            @endforeach
                        </select>
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="imprimer" name="imprimer" checked>
                            <label class="form-check-label" for="imprimer">
                                Je veux imprimer aussi!
                            </label>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-sm w-100 bg-red text-white"><i class="bi bi-funnel"></i> Filtrer</button>
                    </form>
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
                        <li class="list-group-item" style="justify-content: space-between!important">{{$house->name}} &nbsp; <a href="{{route('taux._ShowAgencyTaux05_By_House',['agencyId'=>crypId($agency->id),'house'=>crypId($house->id)])}}" class="btn btn-sm btn-light text-red"><i class="bi bi-eye"></i></a> </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p class="text">Locataires <strong class="text-red"> ayant payé</strong> après l’arrêt des derniers états jusqu’à leur date d’échéance du 05 à 00h au plus tard</p>
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

    <script type="text/javascript">
        function generate_taux_btn_fun() {
            var value = $('#generate_taux_btn')[0].checked
            if (value) {
                $('#show_action_buttons').removeAttr('hidden');
            } else {
                $('#show_action_buttons').attr("hidden", "hidden");
            }
        }
    </script>
</div>