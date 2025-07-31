@can("locator.create")
<div class="modal fade" id="addLocator" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p class="">Ajout d'un Locataire</p>
                <button type="button" class="btn btn-sm text-red" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('locator._AddLocataire') }}" method="POST" class="shadow-lg p-3 animate__animated animate__bounce" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="d-block">Name</label>
                                <input type="text" value="{{ old('name') }}" name="name" id="name" placeholder="Nom ..." class="form-control">
                                @error("name")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="prenom" class="d-block">Prénom</label>
                                <input type="text" value="{{ old('prenom') }}" name="prenom" id="prenom" placeholder="Prénom ..." class="form-control">
                                @error("prenom")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="d-block">Email</label>
                                <input type="email" value="{{ old('email') }}" name="email" id="email" placeholder="Email..." class="form-control">
                                @error("email")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sexe" class="d-block">Sexe</label>
                                <select value="{{ old('sexe') }}" class="form-select form-control agency-modal-select2" name="sexe" id="sexe">
                                    <option value="Maxculin">Maxculin</option>
                                    <option value="Feminin">Feminin</option>
                                </select>
                                @error("sexe")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="d-block">Phone</label>
                                <input value="{{ old('phone') }}" type="phone" name="phone" id="phone" placeholder="Téléphone ..." class="form-control">
                                @error("phone")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="card_id" class="d-block">Id Carte</label>
                                <input value="{{ old('card_id') }}" type="text" name="card_id" id="card_id" class="form-control" placeholder="ID de la carte ....">
                                @error("card_id")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="mandate_contrat" class="d-block">Télécharger le contrat de location</label>
                                <input value="{{ old('mandate_contrat') }}" type="file" name="mandate_contrat" id="mandate_contrat" class="form-control">
                                @error("mandate_contrat")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="btn-group">
                                    <input type="checkbox" name="prorata" class="btn-check" id="prorata">
                                    <label class="btn bg-dark text-white" for="prorata">Prorata?</label>
                                </div>
                            </div>

                            <div class="water shadow-lg roundered p-2" id="show_prorata_info" style="display: none;">
                                <div class="form-check">
                                    <label for="prorata_date" class="d-block">Date du Prorata</label>
                                    <input value="{{ old('prorata_date') }}" name="prorata_date" id="prorata_date" class="form-control" type="date">
                                    @error("prorata_date")
                                        <span class="text-red">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <input type="text" name="agency" value="{{ $current_agency->id }}" hidden class="form-control">
                                <input type="text" disabled class="form-control" placeholder="Agence :{{ $current_agency['name'] }}">
                            </div>

                            <div class="mb-3">
                                <label for="adresse" class="d-block">Adresse</label>
                                <input value="{{ old('adresse') }}" type="text" name="adresse" id="adresse" class="form-control" placeholder="Adresse ....">
                                @error("adresse")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="card_type" class="d-block">Type</label>
                                <select value="{{ old('card_type') }}" class="form-select form-control agency-modal-select2" name="card_type" id="card_type">
                                    @foreach($card_types as $type)
                                        <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                    @endforeach
                                </select>
                                @error("card_type")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="country" class="d-block">Pays</label>
                                <select value="{{ old('country') }}" class="form-select form-control agency-modal-select2" name="country" id="country">
                                    @foreach($countries as $countrie)
                                        @if($countrie['id']==4)
                                            <option value="{{ $countrie['id'] }}">{{ $countrie['name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error("country")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="departement" class="d-block">Département</label>
                                <select value="{{ old('departement') }}" class="form-select form-control agency-modal-select2" name="departement" id="departement">
                                    @foreach($departements as $departement)
                                        <option value="{{ $departement['id'] }}">{{ $departement['name'] }}</option>
                                    @endforeach
                                </select>
                                @error("departement")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="comments" class="d-block">Commentaire</label>
                                <textarea value="{{ old('comments') }}" rows="1" name="comments" id="comments" class="form-control" placeholder="Laissez un commentaire ici"></textarea>
                                @error("comments")
                                    <span class="text-red">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="btn-group">
                                    <input type="checkbox" name="avalisor" class="btn-check" id="avalisor">
                                    <label class="btn bg-dark text-white" for="avalisor">Avaliseur?</label>
                                </div>
                            </div>

                            <div class="d-none p-2" id="show_avalisor_info">
                                <div class="mb-3">
                                    <label for="ava_name" class="d-block">Name</label>
                                    <input type="text" name="ava_name" id="ava_name" placeholder="Nom ..." class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="ava_prenom" class="d-block">Prénom</label>
                                    <input type="text" name="ava_prenom" id="ava_prenom" placeholder="Prénom ..." class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="ava_phone" class="d-block">Phone</label>
                                    <input type="phone" name="ava_phone" id="ava_phone" placeholder="Phone ..." class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="ava_parent_link" class="d-block">Lien parenté</label>
                                    <input type="text" name="ava_parent_link" id="ava_parent_link" placeholder="Lien parenté ..." class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn bg-red w-100">
                            <i class="bi bi-check-circle"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan 