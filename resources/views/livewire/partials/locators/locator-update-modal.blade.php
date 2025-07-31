@can("locator.edit")
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fs-5" id="exampleModalLabel">Modifier <strong><em class="text-red" id="update_locator_fullname"></em></strong></h6>
            </div>
            <div class="modal-body">
                <form id="update_form" class="shadow-lg p-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="d-block">Name</label>
                                <input type="text" id="update-name" name="name" placeholder="Nom ..." class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="prenom" class="d-block">Prénom</label>
                                <input type="text" id="update-prenom" name="prenom" placeholder="Prénom ..." class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="d-block">Email</label>
                                <input type="email" id="update-email" name="email" placeholder="Email..." class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="sexe" class="d-block">Sexe</label>
                                <select id="update-sexe" class="form-select form-control agency-modal-select2" name="sexe">
                                    <option value="Maxculin">Maxculin</option>
                                    <option value="Feminin">Feminin</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="d-block">Phone</label>
                                <input id="update-phone" type="phone" name="phone" placeholder="Téléphone ..." class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="card_id" class="d-block">Id Carte</label>
                                <input id="update-card_id" type="text" name="card_id" class="form-control" placeholder="ID de la carte ....">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="adresse" class="d-block">Adresse</label>
                                <input id="update-adresse" type="text" name="adresse" class="form-control" placeholder="Adresse ....">
                            </div>

                            <div class="mb-3">
                                <label for="card_type" class="d-block">Type</label>
                                <select id="update-card_type" class="form-select form-control agency-modal-select2" name="card_type">
                                    @foreach($card_types as $type)
                                        <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="country" class="d-block">Pays</label>
                                <select id="update-country" class="form-select form-control agency-modal-select2" name="country">
                                    @foreach($countries as $countrie)
                                        @if($countrie['id']==4)
                                            <option value="{{ $countrie['id'] }}">{{ $countrie['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="departement" class="d-block">Département</label>
                                <select id="update-departement" class="form-select form-control agency-modal-select2" name="departement">
                                    @foreach($departements as $departement)
                                        <option value="{{ $departement['id'] }}">{{ $departement['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="comments" class="d-block">Commentaire</label>
                                <textarea id="update-comments" rows="1" name="comments" class="form-control" placeholder="Laissez un commentaire ici"></textarea>
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