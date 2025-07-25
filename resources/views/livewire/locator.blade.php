<!-- Main Container -->
<div>
    <!-- Add Locator Button -->
    @can("locator.create")
    <div class="d-flex header-bar">
        <h2 class="accordion-header">
            <button type="button" class="btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#addLocator">
                <i class="bi bi-cloud-plus-fill"></i> Ajouter
            </button>
        </h2>
    </div>
    @endcan

    <!-- Filter Section -->
    <div class="filter-section mb-3">
        <input type="checkbox" class="btn-check" id="displayLocatorsOptions">
        <label class="btn btn-light" for="displayLocatorsOptions">
            <i class="bi bi-file-earmark-pdf-fill"></i> Filtrer les locataires
        </label>

        <div id="display_locators_options_block" class="mt-2">
            <button class="btn btn-sm bg-light d-block mb-2" data-bs-toggle="modal" data-bs-target="#ShowSearchLocatorsBySupervisorForm">
                <i class="bi bi-people"></i> Par Superviseur
            </button>
            <button class="btn btn-sm bg-light d-block" data-bs-toggle="modal" data-bs-target="#ShowSearchLocatorsByHouseForm">
                <i class="bi bi-house-check-fill"></i> Par Maison
            </button>
        </div>
    </div>

    <!-- Include Partial Views -->
    @include('livewire.partials.locators.locator-filters')
    @include('livewire.partials.locators.locator-add-modal')
    @include('livewire.partials.locators.locator-update-modal')
    @include('livewire.partials.locators.locator-show-modals')

    <!-- Locators Table Section -->
    <div class="row">
        <div class="col-12">
            <h4>Total: <strong class="text-red">{{ session()->get("locators_filtred") ? count(session()->get("locators_filtred")) : $locators_count }}</strong></h4>
            <div class="table-responsive table-responsive-list shadow-lg px-3">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Prénom</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Avaliseur</th>
                            <th class="text-center">Contrat</th>
                            <th class="text-center">Maisons</th>
                            <th class="text-center">Superviseurs</th>
                            <th class="text-center">Chambres</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach((session()->get("locators_filtred") ? session()->get("locators_filtred") : $locators) as $locator)
                        <tr class="align-items-center">
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td class="text-center"><span class=" bg-dark">{{ $locator["name"] }}</span></td>
                            <td class="text-center">{{ $locator["prenom"] }}</td>
                            <td class="text-center"><span class=" text-dark bg-light">{{ $locator["phone"] }}</span></td>
                            
                            <!-- Avaliseur Column -->
                            <td class="text-center">
                                @if($locator->avaliseur)
                                    <button class='btn btn-sm btn-light shadow' data-bs-toggle='modal' data-bs-target='#showAvalisor' data-locator-id="{{ $locator['id'] }}">
                                        <i class='bi bi-eye-fill'></i>
                                    </button>
                                @else
                                    ---
                                @endif
                            </td>

                            <!-- Contract Column -->
                            <td class="text-center">
                                <a href="{{ $locator['mandate_contrat'] }}" class="btn btn-sm btn-light" target="_blank" rel="noopener noreferrer">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            </td>

                            <!-- Houses Column -->
                            <td class="text-center">
                                <button class="btn btn-sm bg-light" data-bs-toggle="modal" data-bs-target="#showHouses" data-locator-id="{{ $locator['id'] }}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </td>

                            <!-- Supervisors Column -->
                            <td class="text-center">
                                <div class="form-control w-100" style="height: auto; overflow-y: scroll; height: 50px">
                                    @if(count($locator->Locations) > 0)
                                        <ul class="list-group">
                                            @foreach ($locator->Locations as $location)
                                                <li class="list-group-item">
                                                    <span class=" bg-dark text-white">{{ $location->House?->Supervisor?->name }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        ---
                                    @endif
                                </div>
                            </td>

                            <!-- Rooms Column -->
                            <td class="text-center">
                                <button type="button" class="btn btn-sm bg-light" data-bs-toggle="modal" data-bs-target="#showRooms" data-locator-id="{{ $locator['id'] }}">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                            </td>

                            <!-- Actions Column -->
                            <td class="d-flex">
                                <div class="dropdown">
                                    <button class="btn btn-sm bg-red dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        @can("locator.edit")
                                            <li>
                                                <button class="dropdown-item btn btn-sm bg-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-locator-id="{{ $locator->id }}">
                                                    <i class="bi bi-person-lines-fill"></i> Modifier
                                                </button>
                                            </li>
                                        @endcan
                                        @can("locator.delete")
                                            <li>
                                                <form action="{{ route('locator.DeleteLocataire', crypId($locator->id)) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item btn btn-sm bg-red" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce locataire ?')">
                                                        <i class="bi bi-archive-fill"></i> Supprimer
                                                    </button>
                                                </form>
                                            </li>
                                        @endcan
                                        <li><span class="dropdown-item">Adresse: {{ $locator["adresse"] }}</span></li>
                                        <li><span class="dropdown-item">Card ID: {{ $locator["card_id"] }}</span></li>
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
</div>

@push('scripts')
<script src="{{ asset('js/locator.js') }}"></script>
@endpush