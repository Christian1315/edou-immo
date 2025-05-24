<div>
    <style>
        .border-red {
            border-color: #cc3301;
            font-weight: bold;
            font-size: 20px;
        }
    </style>
    <div class="row">
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"> <i class="bi bi-people-fill"></i> Propriétaires</h5>
                    <p class="card-text">Liste des Propriétaires</p>
                    <a disabled class="border-red btn bg-light shadow text-red w-100">{{$proprietors_count}}</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-house-fill"></i> Maisons</h5>
                    <p class="card-text">Liste des Maisons</p>
                    <a disabled class="border-red btn bg-light shadow text-red w-100">{{$houses_count}}</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-people-fill"></i> Locataires</h5>
                    <p class="card-text">Liste des Locataires</p>
                    <a disabled class="border-red btn bg-light shadow text-red w-100">{{$locators_count}}</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-house-fill"></i> Locations</h5>
                    <p class="card-text">Liste des Locations</p>
                    <a disabled class="border-red btn bg-light shadow text-red w-100">{{$locations_count}}</a>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-hospital-fill"></i> Chambres</h5>
                    <p class="card-text">Liste des Chambres</p>
                    <a disabled class="border-red btn bg-light shadow text-red w-100">{{$rooms_count}}</a>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-cash-coin"></i> Paiements</h5>
                    <p class="card-text">Liste des Paiements</p>
                    <a disabled class="border-red btn bg-light shadow text-red w-100">{{$paiement_count}}</a>

                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-receipt"></i> Factures</h5>
                    <p class="card-text">Liste des Factures</p>
                    <a disabled class="border-red btn bg-light shadow text-red w-100">{{$factures_count}}</a>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h5 class="card-title text-red"><i class="bi bi-bank2"></i> Comptes</h5>
                    <p class="card-text">Liste comptes & soldes</p>
                    <a disabled class="border-red btn bg-light shadow text-red w-100">{{$accountSold_count}}</a>
                </div>
            </div>
        </div>
    </div>
</div>