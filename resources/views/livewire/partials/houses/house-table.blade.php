<table id="myTable" class="table table-striped table-sm">
    <thead class="bg_dark">
        <tr>
            <th class="text-center">N°</th>
            <th class="text-center">Nom</th>
            <th class="text-center">Latitude</th>
            <th class="text-center">Longitude</th>
            <th class="text-center">Type</th>
            <th class="text-center">Superviseur</th>
            <th class="text-center">Propriétaire</th>
            <th class="text-center">Chambres</th>
            <th class="text-center"><i class="bi bi-geo-fill"></i></th>
            <th class="text-center">Date paiement</th>
            <th class="text-center">Date création</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach (session('filteredHouses') ? session('filteredHouses') : $houses as $house)
        <tr class="align-items-center">
            <td class="text-center">{{ $loop->index + 1 }}</td>
            <td class="text-center">
                <span class="bg-light text-dark">
                    {{ $house['name'] }}
                    <span class="text-red">
                        {{ $house->pre_paid == 1 ? 'prépayé' : '' }}
                        {{ $house->post_paid == 1 ? 'postpayé' : '' }}
                    </span>
                </span>
            </td>
            <td class="text-center">{{ $house['latitude'] ?: '---' }}</td>
            <td class="text-center">{{ $house['longitude'] ?: '---' }}</td>
            <td class="text-center">{{ $house['Type']['name'] }}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-light">{{ $house['Supervisor']['name'] }}</button>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-light">
                    {{ $house['Proprietor']['lastname'] }} {{ $house['Proprietor']['firstname'] }}
                </button>
            </td>
            <td class="text-center">
                <button type="button" data-bs-toggle="modal" data-bs-target="#showRooms"
                    onclick="show_rooms_fun({{ $house['id'] }})" class="btn btn-sm bg-warning">
                    <i class="bi bi-eye-fill"></i> &nbsp; Voir
                </button>
            </td>
            <td class="text-center">
                @if ($house['geolocalisation'])
                <a title="Voir la localisation" target="_blank" href="{{ $house['geolocalisation'] }}"
                    class="btn btn-sm shadow-lg rounded" rel="noopener noreferrer">
                    <i class="bi bi-eye-fill"></i> <i class="bi bi-geo-fill"></i>
                </a>
                @else
                ---
                @endif
            </td>
            <td class="text-center text-red">
                <button class="btn btn-sm btn-light">
                    <i class="bi bi-calendar2-check-fill"></i>
                    {{ \Carbon\Carbon::parse($house->proprio_payement_echeance_date)->locale('fr')->isoFormat('D MMMM YYYY') }}
                </button>
            </td>
            <td class="text-center text-red">
                <button class="btn btn-sm btn-light">
                    <i class="bi bi-calendar2-check-fill"></i>
                    {{ \Carbon\Carbon::parse($house->created_at)->locale('fr')->isoFormat('D MMMM YYYY') }}
                </button>
            </td>
            <td class="text-center">
                <div class="btn-group dropstart">
                    <button class="btn btn-sm bg-red dropdown-toggle text-uppercase" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-kanban-fill"></i> &nbsp; Gérer
                    </button>
                    <ul class="dropdown-menu p-2">
                        @can("house.delete")
                        <li>
                            <form action="{{ route('house.DeleteHouse', crypId($house['id'])) }}" method="post">
                                @csrf
                                @method("DELETE")
                                <button onclick="return confirm('Voulez-vous vraiment supprimer cette maison?')"
                                    type="submit" class="w-100 btn btn-sm bg-red">
                                    <i class="bi bi-archive-fill"></i> Supprimer
                                </button>
                            </form>
                        </li>
                        @endcan

                        @can("house.edit")
                        <li>
                            <button class="w-100 btn btn-sm bg-warning" data-bs-toggle="modal"
                                data-bs-target="#updateModal" onclick="updateModal_fun({{ $house['id'] }})">
                                <i class="bi bi-person-lines-fill"></i> Modifier
                            </button>
                        </li>
                        @endcan

                        @can("house.stop.state")
                        <li>
                            <a href="/house/{{ crypId($house['id']) }}/{{ crypId($current_agency['id']) }}/stopHouseState"
                                class="w-100 btn btn-sm bg-warning text-dark">
                                <i class="bi bi-sign-stop-fill"></i>&nbsp; Arrêter les états
                            </a>
                        </li>
                        @endcan

                        @can("house.generate.caution")
                        <li>
                            <button class="w-100 btn btn-sm bg-light" data-bs-toggle="modal"
                                data-bs-target="#cautionModal" onclick="cautionModal_fun({{ $house['id'] }})">
                                <i class="bi bi-file-earmark-pdf-fill"></i> Gestion des cautions
                            </button>
                        </li>
                        @endcan

                        <li>
                            <a title="Voir l'image" href="{{ $house['image'] }}" target="_blank"
                                class="btn btn-sm shadow-lg rounded w-100" rel="noopener noreferrer">
                                Image <i class="bi bi-eye-fill"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table> 