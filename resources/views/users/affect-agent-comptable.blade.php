<x-templates.base :title="'Agences'" :active="'setting'">

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h3 class="h2">Affecter à un agent comptable</h3>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <form action="{{route('user.AffectSupervisorToAccountyAgent',crypId($user->id))}}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" wire:submit.prevent="addUser">
                    @csrf
                    <p>Affectation d'agent comptable à: <span class="text-red">
                            << {{$user["name"]}}>>
                        </span> </p>
                    <input type="hidden" value="{{$user->id}}" name="user_id">
                    <div class="row">
                        <div class="col-md-12">
                            <select required wire:model="agent" name="agent" class="form-select mb-3 form-control">
                                <option>Choisir un agent comptable</option>
                                @foreach($agents as $agent)
                                <option value="{{$agent['id']}}">{{$agent['name']}} </option>
                                @endforeach
                            </select>
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