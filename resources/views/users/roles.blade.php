<x-templates.base :title="'Agences'" :active="'setting'">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Rôles des utilisateurs</h1>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12">
            <div class="modal-header">
                <h5 class="text-center">Utilisateur : <span class="text-red">
                        << {{$user->name}}>>
                    </span> </h5>
            </div>

            <div class="table-responsive">
                <table id="myTable" class="table table-striped table-sm shadow-lg">
                    @if($user->_roles)
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Label</th>
                            <th class="text-center">Description</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->_roles as $role)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center">{{$role["label"]}}</td>
                            <td class="text-center">
                                <textarea name="" rows="1" class="form-control" id="">{{$role["description"]}}</textarea>
                            </td>
                            <td class="text-center">
                                <form action="{{route('user.DesAttachRoleToUser')}}" method="post">
                                    @csrf
                                    <input type="hidden" value="{{$user->id}}" name="user_id">
                                    <input type="hidden" value="{{$role->id}}" name="role_id">
                                    <button type="submit" class="btn btn-sm btn-warning">- Retirer </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <p class="text-center text-red">Ce utilisateur ne dispose d'aucun rôle!</p>
                    @endif
                </table>
            </div>
        </div>
    </div>
</x-templates.base>