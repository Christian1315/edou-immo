<div>
    @can("house.add.type")
    <!-- AJOUT D'UN TYPE DE CHAMBRE -->
    <div class="text-left mb-3">
        <button type="button" class="btn btn-sm bg-light shadow rounded" data-bs-toggle="modal" data-bs-target="#room_type">
            <i class="bi bi-cloud-plus-fill"></i> Ajouter un type de maison
        </button>
    </div>

    <!-- Modal room type-->
    <div class="modal fade" id="room_type"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5">Type de maison</h5>
                    <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <form action="{{ route('house.AddHouseType') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <input type="text" required value="{{ old('name') }}" name="name"
                                        placeholder="Le label ...." class="form-control">
                                    @error('house_type_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <textarea required value="{{ old('description') }}" name="description" class="form-control"
                                        placeholder="Description ...."></textarea>
                                    @error('house_type_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="w-100 btn-sm btn bg-red">
                            <i class="bi bi-building-check"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan

    @can("house.create")
    <div class="mb-3">
        <div class="d-flex header-bar">
            <h2 class="accordion-header">
                <button type="button" class="btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#addHouse">
                    <i class="bi bi-cloud-plus-fill"></i> Ajouter
                </button>
            </h2>
        </div>
    </div>

    <!-- ADD HOUSE -->
    <div class="modal fade" id="addHouse"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="mb-0">Ajout d'une maison</p>
                    <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('house._AddHouse') }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="agency" value="{{ $current_agency->id }}">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="d-block">Nom</label>
                                    <input type="text" value="{{ old('name') }}" name="name"
                                        placeholder="Nom de la maison" class="form-control">
                                    @error('name')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Latitude</label>
                                    <input type="text" value="{{ old('latitude') }}" name="latitude"
                                        placeholder="Latitude de la maison" class="form-control">
                                    @error('latitude')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Longitude</label>
                                    <input type="text" value="{{ old('longitude') }}" name="longitude"
                                        placeholder="Longitude de la maison" class="form-control">
                                    @error('longitude')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Type</label>
                                    <select class="select2 form-control agency-modal-select2" name="type"
                                        aria-label="Default select example" style="width: 100% !important">
                                        @foreach ($house_types as $type)
                                        <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Pays</label>
                                    <select class=" form-control agency-modal-select2" value="{{ old('country') }}"
                                        name="country" aria-label="Default select example" style="width: 100% !important">
                                        @foreach ($countries as $countrie)
                                        @if ($countrie['id'] == 4)
                                        <option value="{{ $countrie['id'] }}">{{ $countrie['name'] }}
                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Département</label>
                                    <select class=" form-control agency-modal-select2" value="{{ old('departement') }}"
                                        name="departement" aria-label="Default select example" style="width: 100% !important">
                                        @foreach ($departements as $departement)
                                        <option value="{{ $departement['id'] }}">{{ $departement['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('departement')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>

                                <div class="mb-3">
                                    <span class="text-red"><i class="bi bi-geo-fill"></i> Géolocalisation de la
                                        maison</span>
                                    <input type="text" value="{{ old('geolocalisation') }}"
                                        name="geolocalisation" class="form-control"
                                        placeholder="Entrez le lien de géolocalisation de la maison">
                                    @error('geolocalisation')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Une image de la maison</label>
                                    <input type="file" value="{{ old('image') }}" name="image"
                                        class="form-control">
                                    @error('image')
                                    <span class="text-red">{{ $message }}</span>
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
                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="d-block">Ville/Commune</label>
                                    <select class=" form-control agency-modal-select2" value="{{ old('city') }}"
                                        name="city" aria-label="Default select example" style="width: 100% !important">
                                        @foreach ($cities as $citie)
                                        @if ($citie['_country']['id'] == 4)
                                        <option value="{{ $citie['id'] }}">{{ $citie['name'] }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('city')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Quartier</label>
                                    <select class=" form-control agency-modal-select2" value="{{ old('quartier') }}"
                                        name="quartier" aria-label="Default select example" style="width: 100% !important">
                                        @foreach ($quartiers as $quartier)
                                        <option value="{{ $quartier['id'] }}">{{ $quartier['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('quartier')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Zone</label>
                                    <select class=" form-control agency-modal-select2" value="{{ old('zone') }}"
                                        name="zone" aria-label="Default select example" style="width: 100% !important">
                                        @foreach ($zones as $zone)
                                        <option value="{{ $zone['id'] }}">{{ $zone['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('zone')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Superviseur</label>
                                    <select class=" form-control agency-modal-select2" value="{{ old('supervisor') }}"
                                        name="supervisor" aria-label="Default select example" style="width: 100% !important">
                                        @foreach (supervisors() as $supervisor)
                                        <option value="{{ $supervisor['id'] }}">{{ $supervisor['name'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('supervisor')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Propriétaire</label>
                                    <select class=" form-control agency-modal-select2" value="{{ old('proprietor') }}"
                                        name="proprietor" aria-label="Default select example" style="width: 100% !important">
                                        @foreach ($proprietors as $proprietor)
                                        <option value="{{ $proprietor['id'] }}">{{ $proprietor['lastname'] }}
                                            {{ $proprietor['firstname'] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('proprietor')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <label for="" class="d-block">Commentaire</label>
                                    <textarea name="comments" value="{{ old('comments') }}" class="form-control"
                                        placeholder="Laissez un commentaire ici" class="form-control" id=""></textarea>
                                    @error('comments')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <span>Date d'échéance de paiement du propriétaire</span>
                                    <input value="{{ old('proprio_payement_echeance_date') }}" type="date"
                                        name="proprio_payement_echeance_date" class="form-control" id="">
                                    @error('proprio_payement_echeance_date')
                                    <span class="text-red"> {{ $message }} </span>
                                    @enderror
                                </div>
                                <div class="">
                                    <label for="locative_commission">Commision charge locatives en (%) </label>
                                    <input type="number" name="locative_commission" class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="w-100 btn-sm btn bg-red">
                                <i class="bi bi-check-circle-fill"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- Filters -->
    <div class="mb-3">
        <small class="d-block">
            <button data-bs-toggle="modal" data-bs-target="#filtreBySupervisor" class="btn btn-sm bg-light text-dark text-uppercase">
                <i class="bi bi-funnel"></i> Filtrer par superviseur
            </button>
            <button data-bs-toggle="modal" data-bs-target="#filtreByPeriod" class="btn mx-2 btn-sm bg-light text-dark text-uppercase">
                <i class="bi bi-funnel"></i> Filtrer par période
            </button>
        </small>
    </div>

    <!-- Filter Modals -->
    @include('livewire.partials.houses.filter-modals')

    <!-- House List Table -->
    <div class="row">
        <div class="col-12">
            <h4 class="mb-3">Total: <strong class="text-red">{{ session('filteredHouses') ? count(session('filteredHouses')) : $houses_count }}</strong></h4>
            <div class="table-responsive table-responsive-list shadow-lg">
                @include('livewire.partials.houses.house-table')
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('livewire.partials.houses.house-modals')
</div>


@push('scripts')
<script src="{{ asset('js/house.js') }}"></script>
@endpush