<!-- FILTRE PAR SUPERVISEUR -->
<div class="modal fade" id="filtreBySupervisor" tabindex="-1" aria-labelledby="supervisorFilterLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supervisorFilterLabel">Filtre par superviseur</h5>
                <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('room.FiltreRoomBySupervisor',$current_agency->id)}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label>Choisissez un superviseur</label>
                                <select required name="supervisor" class="form-control agency-modal-select2">
                                    @foreach(supervisors() as $supervisor)
                                    <option value="{{$supervisor['id']}}">{{$supervisor["name"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-100 btn btn-sm bg-red">
                                <i class="bi bi-funnel"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FILTRE PAR MAISON -->
<div class="modal fade" id="filtreByHouse" tabindex="-1" aria-labelledby="houseFilterLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="houseFilterLabel">Filtre par maison</h5>
                <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('room.FiltreRoomByHouse',$current_agency->id)}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label>Choisissez une maison</label>
                                <select required name="house" class="form-control agency-modal-select2">
                                    @foreach($houses as $house)
                                    <option value="{{$house['id']}}">{{$house["name"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-100 btn btn-sm bg-red">
                                <i class="bi bi-funnel"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 