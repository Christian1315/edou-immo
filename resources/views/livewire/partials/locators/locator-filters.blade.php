<!-- FILTRE BY SUPERVISOR -->
<div class="modal fade" id="ShowSearchLocatorsBySupervisorForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="" id="exampleModalLabel">Filter par superviseur</p>
            </div>
            <div class="modal-body">
                <form action="{{ route('locator.FiltreBySupervisor', $current_agency->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>Choisissez un superviseur</label>
                            <select required name="supervisor" class="form-control agency-modal-select2">
                                @foreach(supervisors() as $supervisor)
                                    <option value="{{ $supervisor['id'] }}">{{ $supervisor["name"] }}</option>
                                @endforeach
                            </select>
                            <br>
                            <button type="submit" class="w-100 btn btn-sm bg-red mt-2">
                                <i class="bi bi-funnel"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FILTRE BY HOUSE -->
<div class="modal fade" id="ShowSearchLocatorsByHouseForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="" id="exampleModalLabel">Filter par maison</p>
            </div>
            <div class="modal-body">
                <form action="{{ route('locator.FiltreByHouse', $current_agency->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label>Choisissez une maison</label>
                            <select required name="house" class="form-control agency-modal-select2">
                                @foreach($current_agency->_Houses as $house)
                                    <option value="{{ $house['id'] }}">{{ $house["name"] }}</option>
                                @endforeach
                            </select>
                            <br>
                            <button type="submit" class="w-100 btn btn-sm bg-red mt-2">
                                <i class="bi bi-funnel"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 