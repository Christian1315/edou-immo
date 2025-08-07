<div>
    <!-- AJOUT D'UN TYPE DE CHAMBRE -->
    <div class="d-flex gap-2 mb-3">
        @can("room.add.type")
        <button type="button" class="btn btn-sm bg-light shadow rounded" data-bs-toggle="modal" data-bs-target="#room_type">
            <i class="bi bi-cloud-plus-fill"></i> Ajouter un type de chambre
        </button>
        @endcan
        @can("room.add.nature")
        <button type="button" class="btn btn-sm bg-light shadow rounded" data-bs-toggle="modal" data-bs-target="#room_nature">
            <i class="bi bi-cloud-plus-fill"></i> Ajouter une nature de chambre
        </button>
        @endcan
    </div>

    <!-- FORM HEADER -->
    @can("room.create")
    <div class="d-flex header-bar">
        <h2 class="accordion-header">
            <button type="button" class="btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#addRoom">
                <i class="bi bi-cloud-plus-fill"></i> Ajouter une chambre
            </button>
        </h2>
    </div>
    @endcan

    <!-- Modals -->
    @include('livewire.partials.rooms.room-type-modal')
    @include('livewire.partials.rooms.room-nature-modal')
    @include('livewire.partials.rooms.filter-modals')
    @include('livewire.partials.rooms.add-room-modal')
    @include('livewire.partials.rooms.show-locators-modal')
    @include('livewire.partials.rooms.update-room-modal')

    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            @php
            $rooms = session('filteredRooms') ? session('filteredRooms') : $rooms;
            $buzy_rooms_count = count(collect($rooms)->filter(fn($room) => $room->buzzy()));
            $free_rooms_count = count($rooms) - $buzy_rooms_count;
            @endphp

            <div class="d-flex align-items-center gap-3 mb-3">
                <h4 class="mb-0">Total: <strong class="text-red">{{count($rooms)}}</strong></h4>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm bg-light border border-dark">
                        Libre <i class="bi text-primary bi-geo-alt-fill"></i>;
                        Occupée: <i class="bi text-red bi-geo-alt-fill"></i>
                    </button>
                    <button class="btn btn-sm btn-light">
                        Chambres Occupées: <strong class="text-red">{{$buzy_rooms_count}}</strong>
                    </button>
                    <button class="btn btn-sm btn-light">
                        Chambres Libres: <strong class="text-primary">{{$free_rooms_count}}</strong>
                    </button>
                </div>
            </div>

            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Superviseur</th>
                            <th class="text-center">Loyer brut</th>
                            <th class="text-center">Charge locatives</th>
                            <th class="text-center">Loyer Total</th>
                            <th class="text-center">Type de Chambre</th>
                            <th class="text-center">Locataires</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rooms as $room)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}} </td>
                            <td class="text-center">
                                {{$room["number"]}}
                                <i class="bi {{$room->buzzy() ? 'text-red' : 'text-primary'}} bi-geo-alt-fill"></i>
                            </td>
                            <td class="text-center">
                                <span class=" bg-light text-dark">{{$room["House"]["name"]}}</span>
                            </td>
                            <td class="text-center">
                                <span class=" bg-light text-dark">{{$room["House"]["Supervisor"]["name"]}}</span>
                            </td>
                            <td class="text-center">
                                <span class=" bg-dark">{{number_format((int) $room["loyer"],0,","," ")}}</span>
                            </td>
                            <td class="text-center">
                                <span class=" bg-warning">{{number_format($room->LocativeCharge(),0,","," ")}}</span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light text-red">
                                    {{number_format($room["total_amount"],0,","," ")}} fcfa
                                </button>
                            </td>
                            <td class="text-center">{{$room["Type"]['name']}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-light"
                                    data-bs-toggle="modal"
                                    data-bs-target="#showLocators"
                                    onclick="showLocators({{$room['id']}})">
                                    <i class="bi bi-eye-fill"></i> Voir
                                </button>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm bg-red dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-text-paragraph"></i> Action
                                    </button>
                                    <ul class="dropdown-menu">
                                        @can("room.edit")
                                        <li>
                                            <a href="#" class="dropdown-item btn btn-sm bg-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#updateModal"
                                                onclick="updateRoom({{$room['id']}})">
                                                <i class="bi bi-person-lines-fill"></i> Modifier
                                            </a>
                                        </li>
                                        @endcan
                                        @can("room.delete")
                                        <li>
                                            <a href="{{ route('room.DeleteRoom', crypId($room['id']))}}"
                                                class="dropdown-item btn btn-sm bg-red"
                                                data-confirm-delete="true">
                                                <i class="bi bi-archive-fill"></i> Supprimer
                                            </a>
                                        </li>
                                        @endcan
                                        <li>
                                            <a href="{{$room['photo']}}"
                                                class="dropdown-item btn btn-sm btn-light"
                                                rel="noopener noreferrer">
                                                Image <i class="bi bi-eye"></i>
                                            </a>
                                        </li>
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
<script type="text/javascript" src="{{ asset('js/rooms.js') }}"></script>
@endpush