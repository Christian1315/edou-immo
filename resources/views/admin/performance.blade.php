<x-templates.agency :title="'Taux de performance'" :active="'recovery'" :agency="$agency">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Taux de <em class="text-red"> performance </em> </h1>
    </div>
    <br>

    <!-- Filtre par Superviseur & Maison -->
    <div class="row d-flex justify-content-center">
        <div class="col-md-6 text-center">
            <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                <input type="checkbox" hidden onclick="generate_taux_btn_fun()" name="discounter" class="btn-check" id="generate_taux_btn" autocomplete="off">
                <label class="btn btn-sm bg-red text-uppercase" for="generate_taux_btn"><i class="bi bi-file-earmark-pdf-fill"></i>Filtrer par superviseur ou maison</label>
            </div>

            <div class="text-center" id="show_action_buttons" hidden>
                <div class="d-flex">
                    <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#showTauxBySupervisor"><i class="bi bi-people"></i> Par Sperviseur </button>
                    <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#showTauxByHouse"><i class="bi bi-house-check-fill"></i>Par maison </button>
                </div>
            </div>
            <br><br>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <!-- SHOW TAUX BY SUPERVISOR -->
            <div class="modal fade" id="showTauxBySupervisor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p class="">Taux par superviseur:</p>
                            <form action="{{route('performance',crypId($agency->id))}}" method="get">
                                <select name="supervisor" class="form-control agency-modal-select2" required>
                                    @foreach(supervisors() as $supervisor)
                                    <option value="{{$supervisor->id}}">{{$supervisor->name}}</option>
                                    @endforeach
                                </select>
                                <br>
                                <button type="submit" class="btn btn-sm w-100 bg-red text-white"><i class="bi bi-funnel"></i> Filtrer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SHOW TAUX BY HOUSE -->
            <div class="modal fade" id="showTauxByHouse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p class="">Taux par maison:</p>
                            <form action="{{route('performance',crypId($agency->id))}}" method="get">
                                <select name="house" class="form-control agency-modal-select2" required>
                                    @foreach($houses as $house)
                                    <option value="{{$house->id}}">{{$house->name}}</option>
                                    @endforeach
                                </select>
                                <br>
                                <button type="submit" class="btn btn-sm w-100 bg-red text-white"><i class="bi bi-funnel"></i> Filtrer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtre par période -->
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <form action="{{route('performance',crypId($agency->id))}}" method="get" class="shadow border p-3 rounded">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="debut">Début</label>
                        <input type="date" name="debut" required class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="debut">Fin</label>
                        <input type="date" name="fin" required class="form-control">
                    </div>
                </div><br>
                <button class="btn btn-sm bg-red text-white w-100">Filtrer</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <h6 class="">Total: <strong class="text-red">{{count($houses)}}</strong> </h6>
                <br>
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Nbre total de chambre</th>
                            <th class="text-center">Superviseur</th>
                            <th class="text-center">Nbre de chambre vide en debut de mois</th>
                            <th class="text-center">Nbre de chambre vide actuel</th>
                            <th class="text-center">Nbre de chambre occupées</th>
                            <th class="text-center">Nbre de chambre restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($houses as $house)
                        <tr class="align-items-center">
                            <td class="text-center"><span class=" bg-light text-dark">{{$house->name}} </span> </td>
                            <td class="text-center"><strong class=" bg-light text-dark">{{$house->Rooms->count()}} </strong> </td>
                            <td class="text-center"><span class=" bg-light text-red">{{$house->Supervisor->name}} </span> </td>
                            <td class="text-center"><span class=" bg-light text-red"> {{count($house["frees_rooms_at_first_month"])}} </span></td>
                            <td class="text-center"><strong class=" bg-light text-success"> {{count($house["frees_rooms"])}} </strong> </td>
                            <td class="text-center"> <strong class=" bg-light text-red"> {{count($house["busy_rooms"])}}</strong></td>
                            <td class="text-center"> <strong class=" bg-light text-success">{{count($house["frees_rooms"])}} </strong> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <table>
                <tbody>
                    <tr class="text-center" style="margin-top: 20px!important;">
                        <td></td>
                        <td></td>
                        <br>
                        <td colspan="3" class="bg-warning py-5">
                            Performance =<em class="">(Nombre de chambres occupées <strong class="text-red"> ({{count($all_busy_rooms)}} )</strong> / Nombre de chambre Vide <strong class="text-red"> ({{count($all_frees_rooms)}} )</strong> )*100 </em>= <strong>{{Calcul_Perfomance(count($all_busy_rooms),count($all_frees_rooms_at_first_month))}}</strong>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @push("scripts")
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
    @endpush
</x-templates.agency>