<div>
    <div class="d-flex header-bar">
        <small>
            <button type="button" class="btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                <i class="bi bi-cloud-plus-fill"></i> Ajouter un utilisateur
            </button>
        </small>
    </div>
    <br>

    <!-- AJOUT D'UN USER -->
    <div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="">Ajout d'un utilisateur</p>
                    <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{route('AddUser')}}" class="shadow-lg p-3 animate__animated animate__bounce">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <select value="{{old('agency')}}" name="agency" class="select2 form-control mb-1 agency-modal-select2">
                                    <option value="">Choisir une agence</option>
                                    @foreach($agencies as $agency)
                                    <option value="{{$agency['id']}}">{{$agency['name']}} </option>
                                    @endforeach
                                </select>
                                @error("agency")
                                <span class="text-red"> {{$message}} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="text" name="name" value="{{old('name')}}" placeholder="Nom/Prénom ...." class="form-control">
                                    @error("name")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <input type="text" name="username" value="{{old('username')}}" placeholder="Identifiant(username)" class="form-control">
                                    @error("username")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div>
                            </div>
                            <!--  -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="phone" value="{{old('phone')}}" name="phone" placeholder="Téléphone ..." class="form-control">
                                    @error("phone")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div><br>
                                <div class="mb-3">
                                    <input type="text" value="{{old('email')}}" placeholder="Votre Adresse mail ..." name="email" class="form-control">
                                    @error("email")
                                    <span class="text-red"> {{$message}} </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="role">Choisissez un rôle</label>
                                <select name="role" id="role" class="select2 form-control mb-1 agency-modal-select2">
                                    @foreach($allRoles as $role)
                                    @continue($role->id==1)
                                    <option value="{{ $role->name }}">{{$role->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-center">
                            <button type="submit" class="w-100 bg-red btn"><i class="bi bi-check-circle"></i> Enregistrer</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <h4 class="">Total: <strong class="text-red"> {{count($users)}} </strong> </h4>
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm table-bordered">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Nom/Prénom</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Phone</th>
                            <th class="text-center">Agence</th>
                            <th class="text-center">Date de création</th>
                            <!-- <th class="text-center">Rôles</th> -->
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr @if($user->is_archive) disabled @endif class="align-items-center my-2 @if($user->is_archive) shadow bg-secondary @endif" @if($user->is_archive) style="background-color:#F6F6F6;border: solid 1px #000" @endif>
                            <td class="text-center">{{$loop->index + 1}} </td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$user["name"]}} ({{$user?->username}})</span> </td>
                            <td class="text-center"><span class=" bg-dark text-white">{{$user["email"]}} </span> </td>
                            <td class="text-center"> <span class=" bg-light text-dark">{{$user["phone"]}} </span> </td>
                            <td class="text-center">
                                {{$user->_Agency?$user->_Agency->name:'---'}}
                            </td>
                            <td class="text-center text-red"> <span class=" bg-light text-red"> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($user["created_at"])->locale('fr')->isoFormat('D MMMM YYYY') }}</span></small></th>

                            <td class="text-center">
                                <div class="btn-group dropstart">
                                    <button type="button" class="w-100 btn btn-sm bg-dark dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden"> <i class="bi bi-kanban"></i> Gérer </span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <!-- if($user->id != 1) -->
                                        <button type="button"
                                            class="btn btn-sm btn-warning edit-user"
                                            data-id="{{ $user->id }}"
                                            title="Modifier"
                                            onclick="editUser({{$user->id}})">
                                            <i class="bi bi-pencil-square"></i> Modifier
                                        </button>
                                        <!-- endif -->
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

    <!--MODAL UPDATE USER  -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom-0 py-3">
                    <div class="modal-header">
                        <h6 class="modal-title fs-5">Modifier <strong> <em class="text-red" id="userName"></em> </strong> </h6>
                    </div>
                    <button type="button" class="btn btn-sm bg-light text-red" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i></button>
                </div>

                <form id="userEditForm" method="post" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <span class="">Choisir une agence </span>
                                <select required name="agency" class="form-select mb-3 form-control agency-modal-select2">
                                    @foreach($agencies as $agency)
                                    <option value="{{$agency['id']}}">{{$agency['name']}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="user_edit" class="form-label fw-semibold required">Nom complet</label>
                                <input type="text" class="form-control" id="user_edit" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="edit_email" class="form-label fw-semibold required">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <br>
                        <div class="col-12">
                            <label class="form-label fw-semibold required">Gestion des rôles</label>
                            <div class="row g-3">
                                @foreach($allRoles as $role)
                                <!-- role Super Admin -->
                                <div class="col-md-6">
                                    <div class="form-check my-2 p-2 rounded shadow shadow-sm">
                                        <input type="radio" class="form-check-input role-radio"
                                            name="roles" value="{{ $role->name }}"
                                            id="edit_role_{{ $role->id }}"
                                            required
                                            @if($role->name=="Super Administrateur") disabled @endif
                                        ><!-- Ajout de required -->
                                        <label class="form-check-label" for="edit_role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-top-0 py-3">
                        <button type="submit" class="w-100 bg-red  btn btn-primary px-4">
                            <i class="bi bi-check-circle"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Edit User
        function editUser(userId) {
            axios.get(`/users/${userId}/retrieve`).then((response) => {
                let data = response.data;
    
                $('#userName').html(data.name);
                $('#user_edit').val(data.name);
                $('#edit_email').val(data.email);

                // Reset radio buttons
                $('.role-radio').prop('checked', false);

                // Check user role
                if (data.roles && data.roles.length > 0) {
                    // On prend le premier rôle car c'est un radio button
                    const userRole = data.roles[0];
                    // console.log(userRole);
                    $(`input[name="roles"][value="${userRole.name}"]`).prop('checked', true);
                }

                $('#userEditForm').attr("action", `/${userId}/update`)
                $('#editUserModal').modal('show');

            }).catch((error) => {
                alert("une erreure s'est produite")
                console.log(error)
            })
        }
    </script>
</div>