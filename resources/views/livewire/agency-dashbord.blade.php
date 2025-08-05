<div>
    @if(Auth::user()->hasRole("Superviseur"))
    <div class="row d-flex justify-content-center">
        <div class="col-6">
            <small>
                <button type="button" class="w-100 btn btn-sm bg-dark" data-bs-toggle="modal" data-bs-target="#encaisse">
                    <i class="bi bi-cloud-plus-fill"></i> Encaisser pour une location
                </button>
            </small>
        </div>
    </div>

    <!-- ###### MODEL D'ENCAISSEMENT ###### -->
    <div class="modal fade" id="encaisse" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fs-5" id="exampleModalLabel">Encaissement </h6>
                </div>
                <div class="modal-body">
                    <form action="{{route('location._AddPaiement')}}" method="POST" id="encaisserForm" class="shadow-lg p-3 animate__animated animate__bounce p-3" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="location" id="location_input">

                        <div class="row p-3">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Location </label>
                                    <div class="input-group">
                                        <span class="input-group-text border-end-0 bg-light">
                                            <i class="fas fa-search" style="color: #FFB800;"></i>
                                        </span>
                                        <input type="text" id="search-input" class="form-control border-start-0 ps-0"
                                            placeholder="Rechercher des locations...">
                                    </div>
                                    <br>
                                    <select name="location" class="form-select form-control agency-modal-select2" onchange="encaisser(this)" id="select-search">
                                        @foreach($locations as $location)
                                        <option
                                            class="item-search"
                                            @if($location->status==3) disabled class='bg-secondary text-white' @endif
                                            value="{{$location->id}}"
                                            data-house-name="{{$location->House?->name}}"
                                            data-room-number="{{$location->Room?->number}}"
                                            data-locataire-nom="{{$location->Locataire?->nom}}"
                                            data-locataire-prenom="{{$location->Locataire?->prenom}}"
                                            data-locataire-prorata="{{$location->Locataire?->prorata}}"
                                            data-prorata-days="{{$location->prorata_days}}"
                                            data-prorata-amount="{{$location->prorata_amount}}"
                                            data-prorata-date="{{$location->Locataire?->prorata_date}}"
                                            data-latest-loyer-date="{{$location->latest_loyer_date}}"
                                            >
                                            Maison: {{$location->House?->name}} | Chambre : {{$location->Room?->number}} | Locataire: {{$location->Locataire?->name}} {{$location->Locataire?->prenom}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('location')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label>Type </label>
                                    <select name="type" class="form-select form-control agency-modal-select2">
                                        @foreach($types as $type)
                                        <option value="{{$type->id}}">
                                            {{$type->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <span>Date ou mois pour lequel vous voulez encaisser pour cette location</span>
                                    <input disabled class="form-control next_loyer_date">
                                </div>

                                <div class="d-none prorata">
                                    <span class="text-primary">Ce locataire est un prorata(veuillez renseigner ses infos)</span>
                                    <br>
                                    <div class="mb-3">
                                        <label for="" class="d-block">Nbre de jour du prorata</label>
                                        <input type="number" name="prorata_days" placeholder="Nbre de jour du prorata ..." class="form-control prorata_days">
                                        @error('prorata_days')
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="d-block">Montant du prorata</label>
                                        <input type="number" name="prorata_amount" placeholder="Montant du prorata ..." class="form-control prorata_amount">
                                        @error('prorata_amount')
                                        <span class="text-red">{{$message}}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="d-block">Date du prorata</label>
                                        <input name="prorata_date" type="date" class="form-control prorata_date" hidden>
                                        <input disabled type="date" class="form-control prorata_date">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <span>Uploader la facture ici</span> <br>
                                    <input type="file" name="facture" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="" class="d-block">Code de facture</label>
                                    <input value="{{old('facture_code')}}" name="facture_code" placeholder="Code facture ...." class="form-control facture_code">
                                    @error('facture_code')
                                    <span class="text-red">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="w-100 btn btn-sm bg-red"><i class="bi bi-check-all"></i> Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Recherche instantanée
            const searchLocation = document.getElementById('search-input');
            searchLocation.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                document.querySelectorAll('.item-search').forEach(row => {
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

    <script type="text/javascript">
        function encaisser(select) {
            var option = select.selectedOptions[0];

            // Récupération des données
            var houseName = option.getAttribute('data-house-name');
            var roomNumber = option.getAttribute('data-room-number');
            var locataireNom = option.getAttribute('data-locataire-nom');
            var locatairePrenom = option.getAttribute('data-locataire-prenom');
            var prorata = option.getAttribute('data-locataire-prorata');
            var prorataDays = option.getAttribute('data-prorata-days');
            var prorataAmount = option.getAttribute('data-prorata-amount');
            var prorataDate = option.getAttribute('data-prorata-date');
            var latestLoyerDate = option.getAttribute('data-latest-loyer-date');

            // Exemple d'affichage ou d'affectation dans le formulaire
            $(".location_name").html(houseName);
            $(".location_room").html(roomNumber);
            $(".location_locataire").html(locataireNom + " " + locatairePrenom);

            $("#location_input").val(option.value)

            // Date du prochain loyer
            if (latestLoyerDate) {
                var date = new Date(latestLoyerDate);
                date.setMonth(date.getMonth() + 1);
                var options = {
                    year: "numeric",
                    month: "long",
                    day: "numeric"
                };
                var formattedDate = date.toLocaleDateString("fr", options);
                $(".next_loyer_date").val(formattedDate);
            }

            // Prorata
            if (prorata == "1" || prorata == 1) {
                $(".prorata").removeClass("d-none");
                $(".prorata_days").val(prorataDays);
                $(".prorata_amount").val(prorataAmount);
                $(".prorata_date").val(prorataDate);
            } else {
                $(".prorata").addClass("d-none");
            }
        }
    </script>
    <br>
    @endif

    <div class="row">
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-person-lines-fill"></i> Propriétaires</h5>
                    <p class="card-text">Liste des Propriétaires ({{$proprietors_count}}) </p>
                    <a href="/{{crypId($current_agency['id'])}}/proprietor" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-house-gear-fill"></i> Maisons</h5>
                    <p class="card-text">Liste des Maisons ({{$houses_count}} ) </p>
                    <a href="/{{crypId($current_agency['id'])}}/house" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-person-fill-gear"></i> Locataires</h5>
                    <p class="card-text">Liste des Locataires ({{$locators_count}})</p>
                    <a href="/{{crypId($current_agency['id'])}}/locator" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-pin-map-fill"></i> Locations</h5>
                    <p class="card-text">Liste des Locations ({{$locations_count}}) </p>
                    <a href="/{{crypId($current_agency['id'])}}/location" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-hospital"></i> Chambres</h5>
                    <p class="card-text">Liste des Chambres ({{$rooms_count}})</p>
                    <a href="/{{crypId($current_agency['id'])}}/room" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>

        <!-- <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-cash-coin"></i> Paiements</h5>
                    <p class="card-text">Liste des Paiements ({{$paiement_count}})</p>
                    <a href="/{{crypId($current_agency['id'])}}/paiement" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div> -->

        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-receipt"></i> Factures</h5>
                    <p class="card-text">Liste des Factures ({{$factures_count}}) </p>
                    <a href="/{{crypId($current_agency['id'])}}/factures" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>