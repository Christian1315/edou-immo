<div>
    <style>
        input {
            font-weight: bold !important;
            font-size: 30px;
        }
    </style>
    <h4 class="">Total: {{count($allAgenciesAccounts)}} </h4>
    <div class="row">
        @foreach($allAgenciesAccounts as $account)
        <div class="col-sm-3">
            <div class="card shadow-lg">
                <div class="card-body"><h5 class="card-title text-red">{{$account['name']}}</h5>
                    <p class="card-text">{{substr($account['description'],0,20)}} ...</p>
                    <h5 class="">Plafond: <strong class="text-red"> {{$account["plafond_max"]}} </strong> </h5>
                    <em>
                        <input disabled type="text" class="form-control" value="Solde : {{$principalAccounts?0:$account['agency_current_sold']}}">
                    </em>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>