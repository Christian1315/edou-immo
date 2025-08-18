<x-templates.agency :title="'Statistiques'" :active="'statistique'" :agency="$agency">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Les locations ayant payés <strong class="text-red"> après le dernier arrêt </strong> des états</h1>
    </div>
    <br>

    <div class="row">
        <div class="col-12">
            <div>
                <!-- Filter Section -->
                <div class="filter-section mb-3">
                    <input type="checkbox" class="btn-check" id="displayLocatorsOptions">
                    <label class="btn btn-light" for="displayLocatorsOptions">
                        <i class="bi bi-file-earmark-pdf-fill"></i> Filtrer les maisons
                    </label>

                    <div id="display_locators_options_block" class="mt-2">
                        <button class="btn btn-sm bg-light d-block mb-2" data-bs-toggle="modal" data-bs-target="#searchBySupervisor">
                            <i class="bi bi-people"></i> Par Superviseur
                        </button>
                        <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#searchByGestionnaire">
                            <i class="bi bi-house-check-fill"></i> Par Gestionnaire
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
                                <form action="{{route('agencyStatistiqueBeforeState',crypId($agency->id))}}" method="GET">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Choisissez un superviseur</label>
                                            <select required name="supervisor" value="{{old('supervisor')}}" class="form-control agency-modal-select2">
                                                <option value="">******</option>
                                                @foreach(supervisors() as $supervisor)
                                                <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                                @endforeach
                                            </select>
                                            <br>
                                            <div class="">
                                                <label for="month">Filtrer par <span class="test-red">mois</span></label>
                                                <input type="month" name="month" class="form-control">
                                            </div>
                                            <div class="d-flex aligns-item-center">
                                                <input type="checkbox" id="imprimer" name="imprimer"> &nbsp;
                                                <label for="imprimer">Je veux imprimer aussi</label>
                                            </div>
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
                                <form action="{{route('agencyStatistiqueBeforeState',crypId($agency->id))}}" method="GET">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Choisissez un gestionnaire de compte</label>
                                            <select required name="gestionnaire" value="{{old('gestionnaire')}}" class="form-control agency-modal-select2">
                                                <option value="">******</option>
                                                @foreach(gestionnaires() as $gestionnaire)
                                                <option value="{{ $gestionnaire->id }}">{{ $gestionnaire->name }}</option>
                                                @endforeach
                                            </select>
                                            <br>
                                            <div class="">
                                                <label for="month">Filtrer par <span class="test-red">mois</span></label>
                                                <input type="month" name="month" class="form-control">
                                            </div>
                                            <div class="d-flex aligns-item-center">
                                                <input type="checkbox" id="_imprimer" name="imprimer"> &nbsp;
                                                <label for="_imprimer">Je veux imprimer aussi</label>
                                            </div>

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
                <h4 class="">Total: <strong class="text-red"> {{$locators->count()}} </strong> </h4>
                <h6 class="">Montant total: <strong class="text-red"> {{number_format($locators->sum("amount_paid"),2,"."," ")}} </strong> FCFA </h6>

                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive table-responsive-list shadow-lg p-3">
                            <table id="myTable" class="table table-striped table-sm">
                                <thead class="bg_dark">
                                    <tr>
                                        <th class="text-center">N°</th>
                                        <th class="text-center">Nom/Prénom</th>
                                        <th class="text-center">Maison</th>
                                        <th class="text-center">Superviseur</th>
                                        <th class="text-center">Montant payé</th>
                                        <th class="text-center">Loyer</th>
                                        <th class="text-center">Date de payement</th>
                                        <th class="text-center">Date arrêt d'état</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locators as $locator)
                                    <tr class="align-items-center">
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center text-red"> <span class=" bg-light text-dark">{{$locator->name}} </span> </td>
                                        <td class="text-center text-red"> <span class=" bg-light text-dark">{{$locator->house_name}} </span> </td>
                                        <td class="text-center text-red"> <span class=" bg-light text-dark">{{$locator->supervisor}} </span> </td>
                                        <td class="text-center"> <span class=" bg-light text-dark">{{number_format($locator->amount_paid,2,"."," ")}} FCFA </span> </td>
                                        <td class="text-center"> <span class=" bg-light text-success">{{number_format($locator->loyer,2,"."," ")}} FCFA </span> </td>
                                        <td class="text-center"> <span class=" bg-light text-dark">{{\Carbon\Carbon::parse($locator->payement_date)->locale('fr')}} </span> </td>
                                        <td class="text-center"> <span class=" bg-light text-red">{{\Carbon\Carbon::parse($locator->last_state_date)->locale('fr')}} </span> </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push("scripts")
    <script type="text/javascript">
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

</x-templates.agency>