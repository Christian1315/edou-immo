<div>
    <!-- Filter Section -->
    <div class="filter-section mb-3">
        <input type="checkbox" class="btn-check" id="displayLocatorsOptions">
        <label class="btn btn-light" for="displayLocatorsOptions">
            <i class="bi bi-file-earmark-pdf-fill"></i> Filtrer les locataires
        </label>

        <div id="display_locators_options_block" class="mt-2">
            <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#searchByGestionnaire">
                <i class="bi bi-house-check-fill"></i> Par Gestionnaire
            </button>
            <button class="btn btn-sm bg-light d-block mb-2" data-bs-toggle="modal" data-bs-target="#searchBySupervisor">
                <i class="bi bi-people"></i> Par Superviseur
            </button>
        </div>
    </div>

    <!-- FILTRE BY SUPERVISOR -->
    <div class="modal fade" id="searchBySupervisor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="" id="exampleModalLabel">Filter par superviseur</p>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choisissez un superviseur</label>
                                <select required name="house" class="form-control agency-modal-select2">
                                    @foreach(supervisors() as $supervisor)
                                    <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                    @endforeach
                                </select>
                                <br>
                                <button type="submit" class="w-100 btn btn-sm bg-red mt-2">
                                    <i class="bi bi-funnel"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTRE BY GESTIONNAIRE -->
    <div class="modal fade" id="searchByGestionnaire" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="" id="exampleModalLabel">Filter par gestionnaire de compte</p>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label>Choisissez un gestionnaire de compte</label>
                                <select required name="house" class="form-control agency-modal-select2">
                                    @foreach(gestionnaires() as $gestionnaire)
                                    <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                    @endforeach
                                </select>
                                <br>
                                <button type="submit" class="w-100 btn btn-sm bg-red mt-2">
                                    <i class="bi bi-funnel"></i> Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLEAU DE LISTE -->
    <h4 class="">Total: <strong class="text-red"> {{session()->get("houses_filtered")?->count()??$houses->count()}} </strong> </h4>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg p-3">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Latitude</th>
                            <th class="text-center">Longitude</th>
                            <th class="text-center">Type de maison</th>
                            <th class="text-center">Superviseur</th>
                            <th class="text-center">Propriétaire</th>
                            <th class="text-center">Mouvements</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session()->get("houses_filtered")??$houses as $house)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center text-red"> <span class=" bg-light text-dark">{{$house->name}} </span> </td>
                            <td class="text-center"> <span class=" bg-light text-dark">@if($house["latitude"]) {{$house["latitude"]}} @else --- @endif </span> </td>
                            <td class="text-center">@if($house["longitude"]) {{$house["longitude"]}} @else --- @endif</td>
                            <td class="text-center">{{$house->Type?->name}}</td>
                            <td class="text-center text-red"> <span class=" bg-light text-dark"> {{$house->Supervisor?->name}}</span> </td>
                            <td class="text-center"> <span class=" bg-light text-dark"> {{$house->Proprietor?->lastname}} {{$house->Proprietor?->firstname}}</span> </td>

                            <td class="text-center">
                                <a href="{{route('location.FiltreBeforeStateDateStoped', crypId($house['id']))}}" class="btn btn-sm btn-dark" title="Avant arrêt des états"><i class="bi bi-arrow-left-circle-fill"></i></a> &nbsp;
                                <a href="{{route('location.FiltreAfterStateDateStoped', crypId($house['id']))}}" class="btn btn-sm bg-red" title="Après arrêt des états"><i class="bi bi-arrow-right-circle-fill"></i></a>
                                &nbsp;
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push("scripts")
    <script type="text/javascript">
        alert("agency-statistique")
        // Display locators options toggle
        const optionsDiv = document.getElementById('display_locators_options_block');
        optionsDiv.style.display = 'none'

        const displayLocatorsOptions = document.getElementById('displayLocatorsOptions');
        if (displayLocatorsOptions) {
            displayLocatorsOptions.addEventListener('change', function() {
                optionsDiv.style.display = this.checked ? 'block' : 'none';
            });
        }
    </script>
    @endpush
</div>