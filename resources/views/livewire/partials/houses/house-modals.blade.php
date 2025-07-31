<!-- Show Rooms Modal -->
<div class="modal fade" id="showRooms" tabindex="-1" aria-labelledby="showRoomsLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="showRoomsLabel">
                    Maison: <strong><em class="text-red" id="house_fullname"></em></strong>
                </h6>
                <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body">
                <h6>Total de chambre: <em class="text-red" id="house_rooms_count"></em></h6>
                <ul class="list-group" id="house_rooms"></ul>
            </div>
        </div>
    </div>
</div>

<!-- Update House Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="updateModalLabel">
                    Modifier <strong><em class="text-red" id="update_house_fullname"></em></strong>
                </h6>
                <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body">
                <form id="update-update_form" method="post" class="needs-validation" novalidate>
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" id="update-name" name="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" id="update-longitude" name="longitude" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" id="update-latitude" name="latitude" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="geolocalisation" class="form-label">Géolocalisation</label>
                                <input type="text" id="update-geolocalisation" name="geolocalisation" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="proprio_payement_echeance_date" class="form-label">Date d'échéance du propriétaire</label>
                                <input type="date" id="update-proprio_payement_echeance_date" name="proprio_payement_echeance_date" class="form-control">
                            </div>

                            <div class="mb-3 d-flex paid_blocked d-none">
                                <div class="form-check form-switch me-3">
                                    <input type="checkbox" class="form-check-input" id="update_pre_paid" name="pre_paid">
                                    <label class="form-check-label" for="update_pre_paid">Prépayé</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="update_post_paid" name="post_paid">
                                    <label class="form-check-label" for="update_post_paid">Post-Payé</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label for="commission_percent" class="form-label">Commission (en %)</label>
                                <input type="number" id="update-commission_percent" name="commission_percent" class="form-control" min="0" max="100" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label for="locative_commission" class="form-label">Commission charge locatives (en %)</label>
                                <input type="number" id="update-locative_commission" name="locative_commission" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="" class="d-block">Superviseur</label>
                                <select class="form-select form-control agency-modal-select2" value="{{ old('supervisor') }}"
                                    name="supervisor" aria-label="Default select example">
                                    @foreach (supervisors() as $supervisor)
                                    <option value="{{ $supervisor['id'] }}">{{ $supervisor['name'] }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('supervisor')
                                <span class="text-red"> {{ $message }} </span>
                                @enderror
                            </div><br>
                            <div class="mb-3">
                                <label for="" class="d-block">Propriétaire</label>
                                <select class="form-select form-control agency-modal-select2"
                                    name="proprietor" aria-label="Default select example">
                                    @foreach ($proprietors as $proprietor)
                                    <option value="{{ $proprietor['id'] }}">{{ $proprietor['lastname'] }}
                                        {{ $proprietor['firstname'] }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('proprietor')
                                <span class="text-red"> {{ $message }} </span>
                                @enderror
                            </div><br>
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

<!-- Caution Modal -->
<div class="modal fade" id="cautionModal" tabindex="-1" aria-labelledby="cautionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="cautionModalLabel">
                    Maison : <em class="text-red" id="caution_house_fullname"></em>
                </h6>
                <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i></button>
            </div>
            <div class="modal-body">
                <form id="caution_form" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="first_date" class="form-label">Date de début</label>
                                <input type="date" id="first_date" name="first_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="last_date" class="form-label">Date de fin</label>
                                <input type="date" id="last_date" name="last_date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="w-100 btn btn-sm bg-red">Générer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>