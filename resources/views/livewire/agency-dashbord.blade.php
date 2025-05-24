<div>
    <div class="row">
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-person-lines-fill"></i> Propriétaires</h5>
                    <p class="card-text">Liste des Propriétaires ({{$proprietors_count}}) </p>
                    <a  href="/{{crypId($current_agency['id'])}}/proprietor" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-house-gear-fill"></i> Maisons</h5>
                    <p class="card-text">Liste des Maisons ({{$houses_count}} ) </p>
                    <a  href="/{{crypId($current_agency['id'])}}/house" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-person-fill-gear"></i> Locataires</h5>
                    <p class="card-text">Liste des Locataires ({{$locators_count}})</p>
                    <a  href="/{{crypId($current_agency['id'])}}/locator" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-pin-map-fill"></i> Locations</h5>
                    <p class="card-text">Liste des Locations ({{$locations_count}}) </p>
                    <a  href="/{{crypId($current_agency['id'])}}/location" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-hospital"></i> Chambres</h5>
                    <p class="card-text">Liste des Chambres ({{$rooms_count}})</p>
                    <a  href="/{{crypId($current_agency['id'])}}/room" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-cash-coin"></i> Paiements</h5>
                    <p class="card-text">Liste des Paiements ({{$paiement_count}})</p>
                    <a  href="/{{crypId($current_agency['id'])}}/paiement" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-receipt"></i> Factures</h5>
                    <p class="card-text">Liste des Factures ({{$factures_count}}) </p>
                    <a  href="/{{crypId($current_agency['id'])}}/factures" class="btn bg-dark">Voir détail &nbsp; <i class="bi bi-arrow-right-circle"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>