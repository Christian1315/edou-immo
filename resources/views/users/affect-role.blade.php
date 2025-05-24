<x-templates.base :title="'Agences'" :active="'setting'">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Affecter un rôle</h1>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <form action="{{route('user.AttachRoleToUser',crypId($user->id))}}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" wire:submit.prevent="addUser">
                    @csrf
                    <h4>Affectation de rôle à: <span class="text-red">
                            << {{$user["name"]}}>>
                        </span> </h4>
                        <input type="hidden" value="{{$user->id}}" name="user_id">
                    <div class="row">
                        <div class="col-md-12">
                            <select required name="role_id" name="role" class="form-select select2 mb-3 form-control">
                                <option>Choisir un rôle</option>
                                @foreach($roles as $role)
                                <option value="{{$role['id']}}">{{$role['label']}} -- (<span class="text-red">{{$role['description']}}</span>) </option>
                                @endforeach
                            </select>

                            @error("role")
                            <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-center">
                        <button type="submit" class="btn bg-red">Affecter</button>
                    </div>
                </form>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
</x-templates.base>