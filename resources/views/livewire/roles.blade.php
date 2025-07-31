<div>
    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>Liste des Rôles
                </h5>
                <button class="btn text-white bg-red px-4" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                    <i class="fas fa-plus-circle me-2"></i>Ajouter un rôle
                </button>
            </div>
        </div>
        <div class="card-body p-#">
            <div class="table-responsive">
                <table id="myTable" class="table table-hover align-middle" id="rolesTable">
                    <thead>
                        <tr class="bg-light">
                            <th class="border-0 px-4 py-3 text-secondary">#</th>
                            <th class="border-0 px-4 py-3 text-secondary">Rôle</th>
                            <th class="border-0 px-4 py-3 text-secondary">Permissions</th>
                            <th class="border-0 px-4 py-3 text-secondary">Utilisateurs</th>
                            <th class="border-0 px-4 py-3 text-secondary">Date création</th>
                            <th class="border-0 px-4 py-3 text-end text-secondary">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td class="px-4">{{ $role->id }}</td>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3 bg-red">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ $role->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4">
                                <div class="form-control w-100" style="height: auto;overflow-y: scroll;height:100px " name="" rows="1" class="form-control" id="">
                                    @php
                                    $groupedPermissions = $role->permissions->groupBy(function($permission) {
                                    return $permission->name;
                                    }); 
                                    @endphp
                                    @foreach($groupedPermissions as $group => $permissions)
                                    <ul class="list-group">
                                        @foreach($permissions as $permission)
                                        <li class="">{{$permission->description}}</li>
                                        @endforeach
                                    </ul>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-muted me-2"></i>
                                    <div class="form-control w-100" style="height: auto;overflow-y: scroll;height:100px " name="" rows="1" class="form-control" id="">@foreach($role->users as $user) {{$user->name}}
                                        <hr> @endforeach
                                    </div>
                                </div>
                            </td>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <span class=" bg-light text-dark">
                                        <i class="fas fa-calendar text-muted me-2"></i>
                                        {{ \Carbon\Carbon::parse($role->created_at)->locale('fr')->isoFormat('D MMMM YYYY') }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex text-center">
                                    @if($role->id !== 1 && $role->name !== 'super-admin')
                                    <button type="button"
                                        class="btn mx-2 btn-sm btn-light-warning edit-role"
                                        data-id="{{ $role->id }}"
                                        data-bs-toggle="tooltip"
                                        title="Modifier"
                                        onclick="editRole({{$role->id}})">
                                        <i class="bi bi-pencil-square" style="color: #FFB800;"></i>
                                    </button>
                                    <a href="{{route('roles._destroy',crypId($role->id))}}"
                                        class="btn btn-sm btn-light-danger delete-role"
                                        data-id="{{ $role->id }}"
                                        data-name="{{ $role->name }}"
                                        data-bs-toggle="tooltip"
                                        title="Supprimer"
                                        data-confirm-delete="true">
                                        <i class="bi bi-trash text-red"></i>
                                    </a>
                                    <button type="button"
                                        class="btn border mx-2 btn-sm btn-light text-warning affect-role"
                                        data-id="{{ $role->id }}"
                                        data-bs-toggle="tooltip"
                                        title="Affecter à un utilisateur"
                                        onclick="attachRole({{$role->id}})">
                                        <i class="bi bi-link"></i>
                                    </button>
                                    <button type="button"
                                        class="btn border mx-2 btn-sm btn-light text-red affect-role"
                                        data-id="{{ $role->id }}"
                                        data-bs-toggle="tooltip"
                                        title="Affecter à un utilisateur"
                                        onclick="removeRole({{$role->id}})">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    @else
                                    <span class=" rounded-pill" style="background-color: #cc3301; color: #FFB800;">
                                        <i class="fas fa-lock me-1"></i>Rôle système protégé
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon mb-3">
                                        <i class="fas fa-shield-alt fa-3x" style="color: #FFB800;"></i>
                                    </div>
                                    <h5 class="empty-state-title fw-medium">Aucun rôle trouvé</h5>
                                    <p class="empty-state-description text-muted">
                                        Commencez par ajouter un nouveau rôle.
                                    </p>
                                    <button class="btn mt-3 text-white" style="background-color: #FFB800;" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                        <i class="fas fa-plus-circle me-2"></i>Ajouter un rôle
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Styles identiques à la première liste -->
    <style>
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-light-warning {
            background-color: #cc3301;
            border: none;
        }

        .btn-light-warning:hover {
            background-color: rgba(255, 184, 0, 0.2);
        }

        .btn-light-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border: none;
        }

        .btn-light-danger:hover {
            background-color: rgba(220, 53, 69, 0.2);
        }

        .badge {
            padding: 0.5rem 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .badge:hover {
            transform: scale(1.05);
        }

        .empty-state {
            padding: 3rem 0;
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Styles pour le popover des permissions */
        .permissions-popover {
            max-height: 300px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .permissions-popover::-webkit-scrollbar {
            width: 4px;
        }

        .permissions-popover::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .permissions-popover::-webkit-scrollbar-thumb {
            background: #FFB800;
            border-radius: 4px;
        }

        .permission-group:not(:last-child) {
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation des tooltips existants
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Initialisation des popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl, {
                    sanitize: false
                });
            });
        });
    </script>

    <!-- ADD ROLES -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header text-white border-0 py-3 bg-light">
                    <div class="d-flex align-items-center">
                        <div class="modal-title-icon me-3">
                            <i class="fas fa-shield-alt fa-lg"></i>
                        </div>
                        <h5 class="modal-title textè-dark fw-semibold mb-0">Nouveau Rôle</h5>
                    </div>
                    <button type="button" class="btn-close btn btn-sm bg-light text-red" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
                </div>

                <form action="{{route('roles.store')}}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Information du Rôle -->
                            <div class="col-12 mb-2">
                                <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #FFB800;">
                                    <i class="fas fa-info-circle me-2"></i>Information du Rôle
                                </h6>
                            </div>

                            <div class="col-12">
                                <label for="name" class="form-label fw-medium required">Nom du rôle</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-light">
                                        <i class="fas fa-shield-alt" style="color: #FFB800;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0"
                                        id="name" name="name" placeholder="Entrez le nom du rôle" required>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Permissions -->
                            <div class="col-12 mt-4 mb-2">
                                <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #FFB800;">
                                    <i class="fas fa-key me-2"></i>Permissions
                                </h6>
                            </div>

                            <!-- Barre de recherche -->
                            <div class="col-12 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-light">
                                        <i class="fas fa-search" style="color: #FFB800;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0"
                                        id="searchPermissions"
                                        placeholder="Rechercher des permissions...">
                                </div>
                            </div>

                            <!-- Groupes de permissions -->
                            <div class="col-12">
                                <div class="row g-4">
                                    @foreach($allPermissions->groupBy('group_name') as $groupName => $groupPermissions)
                                    <div class="col-md-6 permission-group">
                                        <div class="card border shadow-sm h-100">
                                            <div class="card-header bg-light border-bottom py-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-folder me-2" style="color: #FFB800;"></i>
                                                        <h6 class="card-title mb-0 fw-bold">{{ $groupName }}</h6>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-light select-all-group" data-group="{{ $groupName }}">
                                                            <i class="fas fa-check-square me-1"></i>Tout
                                                        </button>
                                                        <button type="button" class="btn btn-light deselect-all-group" data-group="{{ $groupName }}">
                                                            <i class="fas fa-square me-1"></i>Aucun
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="border-0" style="width: 70%">Permission</th>
                                                                <th class="border-0 text-end">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($groupPermissions as $permission)
                                                            <tr class="permission-row">
                                                                <td>
                                                                    <div class="form-check permission-item">
                                                                        <input type="checkbox"
                                                                            class="form-check-input permission-checkbox"
                                                                            name="permissions[]"
                                                                            value="{{ $permission->name }}"
                                                                            id="perm_{{ $permission->id }}"
                                                                            data-group="{{ $groupName }}">
                                                                        <label class="form-check-label permission-label"
                                                                            for="perm_{{ $permission->id }}">
                                                                            {{ $permission->description }}
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="text-end text-muted small">
                                                                    <code>{{ $permission->name }}</code>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="submit" class="w-100 btn btn-sm bg-red">
                            <i class="bi bi-check-circle"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .bg-warning-soft {
            background-color: #cc3301;
        }

        .modal-content {
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .form-control,
        .form-select {
            padding: 0.6rem 1rem;
            border-radius: 0.375rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #FFB800;
            box-shadow: 0 0 0 0.25rem rgba(255, 184, 0, 0.25);
        }

        .input-group-text {
            padding: 0.6rem 1rem;
            background-color: #f8f9fa;
        }

        .required:after {
            content: ' *';
            color: #dc3545;
            font-weight: bold;
        }

        .table-sm td,
        .table-sm th {
            padding: 0.5rem;
        }

        .permission-item {
            margin: 0;
        }

        .permission-checkbox:checked+.permission-label {
            color: #FFB800;
            font-weight: 500;
        }

        code {
            font-size: 0.75rem;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 0.2rem;
        }

        .table-light {
            background-color: rgba(255, 184, 0, 0.05);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        .permission-row:hover {
            background-color: rgba(255, 184, 0, 0.05);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche instantanée
            const searchInput = document.getElementById('searchPermissions');
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.permission-row').forEach(row => {
                    const permissionText = row.textContent.toLowerCase();
                    if (permissionText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Masquer/afficher les groupes vides
                document.querySelectorAll('.permission-group').forEach(group => {
                    const visibleRows = group.querySelectorAll('.permission-row[style=""]').length;
                    if (visibleRows === 0) {
                        group.style.display = 'none';
                    } else {
                        group.style.display = '';
                    }
                });
            });

            // Sélection/Désélection par groupe
            document.querySelectorAll('.select-all-group').forEach(btn => {
                btn.addEventListener('click', function() {
                    const group = this.dataset.group;
                    document.querySelectorAll(`input[type="checkbox"][data-group="${group}"]`)
                        .forEach(cb => cb.checked = true);
                });
            });

            document.querySelectorAll('.deselect-all-group').forEach(btn => {
                btn.addEventListener('click', function() {
                    const group = this.dataset.group;
                    document.querySelectorAll(`input[type="checkbox"][data-group="${group}"]`)
                        .forEach(cb => cb.checked = false);
                });
            });
        });
    </script>

    <!-- UDDATE ROLES -->
    <!-- Début du modal d'édition harmonisé -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header text-white border-0 py-3 bg-light text-dark">
                    <div class="d-flex align-items-center">
                        <div class="modal-title-icon me-3">
                            <i class="fas fa-edit fa-lg"></i>
                        </div>
                        <h5 class="modal-title fw-semibold mb-0">Modifier le Rôle</h5>
                    </div>
                    <button type="button" class="btn-close btn btn-sm text-red bg-light" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
                </div>

                <form method="POST" action="javascript:void(0)" id="roleUpdateForm">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" id="edit_role_id" name="role_id">

                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Information du Rôle -->
                            <div class="col-12 mb-2">
                                <h6 class="fw-bold mb-3 border-bottom pb-2 bg-light text-dark">
                                    <i class="fas fa-info-circle me-2"></i>Information du Rôle
                                </h6>
                            </div>

                            <div class="col-12">
                                <label for="edit_name" class="form-label fw-medium required">Nom du rôle</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-light">
                                        <i class="fas fa-shield-alt" style="color: #FFB800;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0"
                                        id="edit_name" name="name" placeholder="Entrez le nom du rôle" required>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <!-- Permissions -->
                            <div class="col-12 mt-4 mb-2">
                                <h6 class="fw-bold mb-3 border-bottom pb-2" style="color: #FFB800;">
                                    <i class="fas fa-key me-2"></i>Permissions
                                </h6>
                            </div>

                            <!-- Barre de recherche -->
                            <div class="col-12 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-light">
                                        <i class="fas fa-search" style="color: #FFB800;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0"
                                        id="editSearchPermissions"
                                        placeholder="Rechercher des permissions...">
                                </div>
                            </div>

                            <!-- Groupes de permissions -->
                            <div class="col-12">
                                <div class="row g-4">
                                    @foreach($allPermissions->groupBy('group_name') as $groupName => $groupPermissions)
                                    <div class="col-md-6 edit-permission-group">
                                        <div class="card border shadow-sm h-100">
                                            <div class="card-header bg-light border-bottom py-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-folder me-2" style="color: #FFB800;"></i>
                                                        <h6 class="card-title mb-0 fw-bold">{{ $groupName }}</h6>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-light edit-select-all-group" data-group="{{ $groupName }}">
                                                            <i class="fas fa-check-square me-1"></i>Tout
                                                        </button>
                                                        <button type="button" class="btn btn-light edit-deselect-all-group" data-group="{{ $groupName }}">
                                                            <i class="fas fa-square me-1"></i>Aucun
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="border-0" style="width: 70%">Permission</th>
                                                                <th class="border-0 text-end">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($groupPermissions as $permission)
                                                            <tr class="_permission-row">
                                                                <td>
                                                                    <div class="form-check permission-item">
                                                                        <input type="checkbox"
                                                                            class="form-check-input edit-permission-checkbox"
                                                                            name="permissions[]"
                                                                            value="{{ $permission->name }}"
                                                                            id="edit_perm_{{ $permission->id }}"
                                                                            data-group="{{ $groupName }}">
                                                                        <label class="form-check-label permission-label"
                                                                            for="edit_perm_{{ $permission->id }}">
                                                                            {{ $permission->description }}
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                                <td class="text-end text-muted small">
                                                                    <code>{{ $permission->name }}</code>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="submit" class="w-100 btn btn-sm bg-red">
                            <i class="bi bi-check-circle"></i> Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Edit User
        function editRole(roleId) {
            axios.get(`/roles/${roleId}/retrieve`).then((response) => {
                let data = response.data;

                $('#edit_role_id').val(data.role.id);
                $('#edit_name').val(data.role.name);

                // Reset checkboxes
                $('.permission-checkbox').prop('checked', false);

                // Check permissions
                data.role.permissions.forEach(permission => {
                    console.log(permission)

                    let permissionInput = $(`#edit_perm_${permission.id}`)
                    permissionInput[0].checked = true
                    // console.log(permissionInput[0].checked)
                    // permissionInput.prop('checked', true);
                });

                $('#editRoleModal').modal('show');

                // Reset radio buttons
                $('.role-radio').prop('checked', false);

                // // Check user role
                // if (data.roles && data.roles.length > 0) {
                //     // On prend le premier rôle car c'est un radio button
                //     const userRole = data.roles[0];
                //     // console.log(userRole);
                // }
                $(`input[name="roles"][value="${data.role.name}"]`).prop('checked', true);

                $('#roleUpdateForm').attr("action", `/roles/${roleId}/update`)

            }).catch((error) => {
                alert("une erreure s'est produite")
                console.log(error)
            })

        };

        // 
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche instantanée
            const editSearchInput = document.getElementById('editSearchPermissions');
            editSearchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                // alert(searchTerm)
                document.querySelectorAll('._permission-row').forEach(row => {
                    const permissionText = row.textContent.toLowerCase();
                    if (permissionText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Masquer/afficher les groupes vides
                document.querySelectorAll('.edit-permission-group').forEach(group => {
                    const visibleRows = group.querySelectorAll('._permission-row[style=""]').length;
                    if (visibleRows === 0) {
                        group.style.display = 'none';
                    } else {
                        group.style.display = '';
                    }
                });
            });

            // Sélection/Désélection par groupe
            document.querySelectorAll('.edit-select-all-group').forEach(btn => {
                btn.addEventListener('click', function() {
                    const group = this.dataset.group;
                    document.querySelectorAll(`input[type="checkbox"][data-group="${group}"]`)
                        .forEach(cb => cb.checked = true);
                });
            });

            document.querySelectorAll('.edit-deselect-all-group').forEach(btn => {
                btn.addEventListener('click', function() {
                    const group = this.dataset.group;
                    document.querySelectorAll(`input[type="checkbox"][data-group="${group}"]`)
                        .forEach(cb => cb.checked = false);
                });
            });
        });
    </script>

    <!-- AFFECT TO USER  -->
    <div class="modal fade" id="affectRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header text-white border-0 py-3 bg-light text-dark">
                    <div class="d-flex align-items-center">
                        <div class="modal-title-icon me-3">
                            <i class="fas fa-edit fa-lg"></i>
                        </div>
                        <h5 class="modal-title fw-semibold mb-0">Affecter à un utilisateur</h5>
                    </div>
                    <button type="button" class="btn-close btn btn-sm text-red bg-light" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
                </div>

                <form method="POST" action="javascript:void(0)" id="roleAffectForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Information du Rôle -->
                            <div class="col-12 mb-2">
                                <h6 class="fw-bold mb-3 border-bottom pb-2 bg-light text-dark">
                                    <i class="fas fa-info-circle me-2"></i>Information du Rôle
                                </h6>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="edit_name" class="form-label fw-medium required">Nom du rôle</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-light">
                                        <i class="fas fa-shield-alt" style="color: #FFB800;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0"
                                        id="affect_name" disabled>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <!-- <br> <br> -->
                            <!-- Barre de recherche -->
                            <div class="col-12 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-light">
                                        <i class="fas fa-search" style="color: #FFB800;"></i>
                                    </span>
                                    <input type="text" id="userSearch" class="form-control border-start-0 ps-0"
                                        placeholder="Rechercher des uilisateur...">
                                </div>
                                <br>
                                <!-- utlisateurs -->
                                <p class="text-red">Les utilisateurs ayant déjà un rôle, sont désactivés</p>
                                <select id="userResult" name="user" class="form-select form-select-sm form-control agency-modal-select2" aria-label="Small select example">
                                    <option selected>Selectionnez un utilisateur</option>
                                    @foreach($users as $user)
                                    <option @disabled($user->roles->count()>0) class="user-row" value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="submit" class="w-100 btn btn-sm bg-red">
                            <i class="bi bi-check-circle"></i> Affecter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Affect role User
        function attachRole(roleId) {
            axios.get(`/roles/${roleId}/retrieve`).then((response) => {
                let data = response.data;

                $('#affect_name').val(data.role.name);
                $('#affectRoleModal').modal('show');
                $('#roleAffectForm').attr("action", `/roles/${roleId}/affect`)
            }).catch((error) => {
                alert("une erreure s'est produite")
                console.log(error)
            })
        };

        // 
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche instantanée
            const editSearchInput = document.getElementById('userSearch');
            editSearchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                // alert(searchTerm)
                document.querySelectorAll('.user-row').forEach(row => {
                    const permissionText = row.textContent.toLowerCase();
                    if (permissionText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <!-- REMOVE ROLE FROM USER  -->
    <div class="modal fade" id="removeRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header text-white border-0 py-3 bg-light text-dark">
                    <div class="d-flex align-items-center">
                        <div class="modal-title-icon me-3">
                            <i class="fas fa-edit fa-lg"></i>
                        </div>
                        <h5 class="modal-title fw-semibold mb-0">Retirer un rôle à un utilisateur</h5>
                    </div>
                    <button type="button" class="btn-close btn btn-sm text-red bg-light" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
                </div>

                <form method="POST" action="javascript:void(0)" id="roleRemoveForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-4">
                            <!-- Information du Rôle -->
                            <div class="col-12 mb-2">
                                <h6 class="fw-bold mb-3 border-bottom pb-2 bg-light text-dark">
                                    <i class="fas fa-info-circle me-2"></i>Information du Rôle
                                </h6>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="edit_name" class="form-label fw-medium required">Nom du rôle</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-light">
                                        <i class="fas fa-shield-alt" style="color: #FFB800;"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0"
                                        id="remove_name" disabled>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <!-- <br> <br> -->
                            <!-- Barre de recherche -->
                            <div class="col-12 mb-3">
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-light">
                                        <i class="fas fa-search" style="color: #FFB800;"></i>
                                    </span>
                                    <input type="text" id="userSearch" class="form-control border-start-0 ps-0"
                                        placeholder="Rechercher des uilisateur...">
                                </div>
                                <br>
                                <!-- utlisateurs -->
                                <select name="user" class="form-select form-select-sm form-control agency-modal-select2" aria-label="Small select example">
                                    <option selected>Selectionnez un utilisateur</option>
                                    @foreach($users as $user)
                                    <!-- on affiche pas le compte damin -->
                                    @continue($user->id==1)
                                    <option class="user-row" value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 bg-light p-3">
                        <button type="submit" class="w-100 btn btn-sm bg-red">
                            <i class="bi bi-check-circle"></i> Retirer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Retirer role User
        function removeRole(roleId) {
            axios.get(`/roles/${roleId}/retrieve`).then((response) => {
                let data = response.data;

                $('#remove_name').val(data.role.name);
                $('#removeRoleModal').modal('show');
                $('#roleRemoveForm').attr("action", `/roles/${roleId}/remove`)
            }).catch((error) => {
                alert("une erreure s'est produite")
                console.log(error)
            })
        };

        // 
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche instantanée
            const editSearchInput = document.getElementById('userSearch');
            editSearchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                // alert(searchTerm)
                document.querySelectorAll('.user-row').forEach(row => {
                    const permissionText = row.textContent.toLowerCase();
                    if (permissionText.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</div>