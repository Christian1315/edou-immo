<div>
    @can("proprio.create")
    <div class="d-flex header-bar">
        <h2 class="accordion-header">
            <button type="button" class="btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#addProprietorModal">
                <i class="bi bi-cloud-plus-fill"></i> Ajouter un propriétaire
            </button>
        </h2>
    </div>

    <!-- Add Proprietor Modal -->
    <div class="modal fade" id="addProprietorModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addProprietorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajout d'un propriétaire</h5>
                    <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('proprietor._AddProprietor') }}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="agency" value="{{ $current_agency['id'] }}">

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                @include('livewire.partials.proprietor-form-fields-left')
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                @include('livewire.partials.proprietor-form-fields-right')
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="w-100 btn bg-red">
                                <i class="bi bi-check-circle-fill"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan

    <!-- Proprietors List -->
    <div class="row mt-4">
        <div class="col-md-12">
            <h4>Total: <strong class="text-red">{{ $proprietors_count }}</strong></h4>
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom</th>
                            <th class="text-center">Prénom</th>
                            <th class="text-center">Téléphone</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Pièce</th>
                            <th class="text-center">Contrat</th>
                            <th class="text-center">Maisons</th>
                            @can("proprio.edit")
                            <th class="text-center">Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proprietors as $proprietor)
                        <tr class="align-items-center">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $proprietor["lastname"] }}</td>
                            <td class="text-center">{{ $proprietor["firstname"] }}</td>
                            <td class="text-center">{{ $proprietor["phone"] }}</td>
                            <td class="text-center">{{ $proprietor["email"] }}</td>
                            <td class="text-center">
                                <a title="Voir pièce d'identité" href="{{ $proprietor['piece_file'] }}" class="btn btn-sm btn-light">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <a title="Voir Contrat" href="{{ $proprietor['mandate_contrat'] }}" class="btn btn-sm btn-light">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm bg-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#showHousesModal"
                                    onclick="showHouses({{ $proprietor['id'] }})">
                                    <i class="bi bi-eye-fill"></i> Voir
                                </button>
                            </td>
                            @can("proprio.edit")
                            <td class="text-center">
                                <button class="btn btn-sm bg-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#updateProprietorModal"
                                    onclick="loadProprietorData({{ $proprietor['id'] }})">
                                    <i class="bi bi-person-lines-fill"></i> Modifier
                                </button>
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Show Houses Modal -->
    <div class="modal fade" id="showHousesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        Propriétaire : <strong><em class="text-red" id="proprietorFullName"></em></strong>
                    </h6>
                </div>
                <div class="modal-body">
                    <h6>Total de maison: <em class="text-red" id="proprietorHousesCount"></em></h6>
                    <ul class="list-group" id="housesList"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Proprietor Modal -->
    @can("proprio.edit")
    <div class="modal fade" id="updateProprietorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">
                        Modifier <strong><em class="text-red" id="updateProprietorFullName"></em></strong>
                    </h6>
                </div>
                <div class="modal-body">
                    <form id="updateProprietorForm" method="post" class="shadow-lg p-3 animate__animated animate__bounce">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nom</label>
                                    <input id="updateLastname" name="lastname" class="form-control" required>
                                    @error("lastname")
                                    <span class="text-red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Prénom</label>
                                    <input id="updateFirstname" type="text" name="firstname" class="form-control" required>
                                    @error("firstname")
                                    <span class="text-red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Téléphone</label>
                                    <input id="updatePhone" type="tel" name="phone" class="form-control" required>
                                    @error("phone")
                                    <span class="text-red">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input id="updateEmail" type="email" name="email" class="form-control" required>
                                    @error("email")
                                    <span class="text-red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Adresse</label>
                                    <input id="updateAddress" type="text" name="adresse" class="form-control">
                                    @error("adresse")
                                    <span class="text-red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="w-100 btn btn-sm bg-red">
                                <i class="bi bi-check-circle"></i> Modifier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @push('scripts')
    <script type="text/javascript">
       
        const API_BASE_URL = "{{ env('API_BASE_URL') }}";

        async function showHouses(id) {
            try {
                const response = await axios.get(`${API_BASE_URL}proprietor/${id}/retrieve`);
                const proprietor = response.data;
                const fullName = `${proprietor.firstname} ${proprietor.lastname}`;
                const houses = proprietor.houses;

                document.getElementById('proprietorFullName').textContent = fullName;
                document.getElementById('proprietorHousesCount').textContent = houses.length;

                const housesList = document.getElementById('housesList');
                housesList.innerHTML = houses.map(house =>
                    `<li class='list-group-item'><strong>Nom: </strong>${house.name}</li>`
                ).join('');
            } catch (error) {
                console.error('Error loading houses:', error);
                alert("Une erreur s'est produite lors du chargement des maisons");
            }
        }

        async function loadProprietorData(id) {
            try {
                const response = await axios.get(`${API_BASE_URL}proprietor/${id}/retrieve`);
                const proprietor = response.data;
                const fullName = `${proprietor.firstname} ${proprietor.lastname}`;

                document.getElementById('updateProprietorFullName').textContent = fullName;
                document.getElementById('updateFirstname').value = proprietor.firstname;
                document.getElementById('updateLastname').value = proprietor.lastname;
                document.getElementById('updatePhone').value = proprietor.phone;
                document.getElementById('updateEmail').value = proprietor.email;
                document.getElementById('updateAddress').value = proprietor.adresse;

                document.getElementById('updateProprietorForm').action = `/proprietor/${proprietor.id}/update`;
            } catch (error) {
                console.error('Error loading proprietor data:', error);
                alert("Une erreur s'est produite lors du chargement des données");
            }
        }
    </script>
    @endpush
</div>