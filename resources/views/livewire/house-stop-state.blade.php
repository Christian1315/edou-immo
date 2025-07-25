<div>
    <br>
    <div class="d-flex header-bar justify-content-between">
        <h4> <strong>Superviseur: <span class="text-red">{{$house->Supervisor?$house->Supervisor->name:"---"}}</span> </strong></h4>
        &nbsp;&nbsp;

        @can("house.stop.state")
        <button class="btn btn-md bg-red" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="bi bi-sign-stop"></i> Arrêter les états de cette maison
        </button>
        @endcan
    </div>
    <br>

    <!-- ### MODAL POUR RENSEIGNER LE RAPPORT DE RECOUVREMENT -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('house.PostStopHouseState',crypId($house->id))}}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">Veuillez d'abord rediger un rapport de récouvrement</h6>
                    </div>
                    <div class="modal-body">
                        <textarea required name="recovery_rapport" name="recovery_rapport" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-md bg-red"><i class="bi bi-check-circle"></i> Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row px-0 mx-0">
        <div class="col-6">
            <table class="shadow-lg table table-striped table-sm">
                <tbody>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Nbre de mois récouvré:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{number_format($house["nbr_month_paid"],0,","," ")}} </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Montant total récouvré:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{number_format($house["total_amount_paid"],0,","," ")}} fcfa </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Commission:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{$house["commission"]}} fcfa </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Dépense totale:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{number_format($house["actuel_depenses"],0,","," ") }} fcfa </strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="" style="border: solid 2px #000;">
                            Net à payer au propriétaire:
                        </td>
                        <td class="bg-warning" style="border: solid 2px #000;">
                            <strong>= {{number_format($house["net_to_paid"],0,","," ") }} fcfa </strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-6"></div>
    </div><br>
    <p class="">Liste des locations de la maison</p>

    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive shadow-lg p-3">
                <table id="myTable" class="table table-striped table-sm">
                    <h4 class="">Total: <strong class="text-red"> {{count($house['Locations'])}} </strong> </h4>

                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Locataire <span class="d-block d-sm-none"> | Dernier loyé | Mois payé(s) | Montant payé</span> </th>
                            <th class="text-center">Téléphone</th>
                            <th class="text-center">Chambre</th>
                            <th class="text-center">Loyer Mensuel</th>
                            <th class="text-center">Mois payé(s)</th>
                            <th class="text-center">Montant payé</th>
                            <th class="text-center">Dernier loyé</th>
                            <th class="text-center">Mois d'effet</th>
                            <th class="text-center">Date d'Intégration</th>
                            {{-- <th class="text-center text-red">Prorata</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($house->Locations->where("status", "!=", 3) as $location)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}}</td>
                            <td class="text-center">
                                <span class="bg-light text-dark"> <strong> {{$location->Locataire?->name}} {{$location->Locataire?->prenom}}</strong> </span>
                                <span class="bg-light text-dark d-block d-sm-none">
                                    | <i class="bi bi-calendar-check-fill"></i> <strong>{{ \Carbon\Carbon::parse($location["latest_loyer_date"])->locale('fr')->isoFormat('MMMM YYYY') }}</strong>
                                    | {{$location["_locataire"]?($location->prorata_amount>0?'Prorata':$location["_locataire"]["nbr_month_paid"]):0}}
                                    | <span class=" bg-light text-red"> {{number_format($location["_locataire"]?($location->prorata_amount>0?$location->prorata_amount:$location["_locataire"]["nbr_facture_amount_paid"]):0,0,","," ")}} </span>
                                </span>
                            </td>
                            <td class="text-center">{{$location->Locataire?->phone}}</td>
                            <td class="text-center">{{$location->Room?->number}}</td>
                            <td class="text-center"><span class=" bg-light text-red"> {{number_format($location->Room?->total_amount,0,","," ")}} </span></td>
                            <td class="text-center">{{$location["_locataire"]?($location->prorata_amount>0?'Prorata':$location["_locataire"]["nbr_month_paid"]):0}}</td>
                            <td class="text-center"><span class=" bg-light text-red"> {{number_format($location["_locataire"]?($location->prorata_amount>0?$location->prorata_amount:$location["_locataire"]["nbr_facture_amount_paid"]):0,0,","," ")}} </span></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light shadow-lg"> <i class="bi bi-calendar-check-fill"></i> <strong>{{ \Carbon\Carbon::parse($location["latest_loyer_date"])->locale('fr')->isoFormat('MMMM YYYY') }}</strong> </button>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light shadow-lg"> <i class="bi bi-calendar-check-fill"></i> <strong>{{ \Carbon\Carbon::parse($location["effet_date"])->locale('fr')->isoFormat('MMMM YYYY') }} </strong> </button>
                            </td>
                            <td class="text-center"> <button class="btn btn-sm btn-light"> <i class="bi bi-calendar-check-fill"></i> <strong> {{ \Carbon\Carbon::parse($location["effet_date"])->locale('fr')->isoFormat('MMMM YYYY') }} </strong> </button></td>
                            {{-- <td class="text-center"> <button class="btn btn-sm btn-light text-red"></i> <strong> {{$location->Locataire->prorata?$location->Locataire->prorata_date:"---"}} </strong> </button></td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br>

    <h4 class="text-red">Liste des états de la maison</h4>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <h4 class="">Total: <strong class="text-red"> {{count($house["states"])}} </strong> </h4>

                <table id="myTable" class="table table-striped table-sm">
                    @if(count($house["states"])!=0)

                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Le responsable de l'arrêt</th>
                            <th class="text-center">Date d'arrêt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($house["states"] as $state)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index+1}}</td>
                            <td class="text-center"> {{$state["Owner"]["name"]}} </td>
                            <td class="text-center"> <span class="btn btn-sm p-1 bg-red">{{ \Carbon\Carbon::parse($state["stats_stoped_day"])->locale('fr')->isoFormat('D MMMM YYYY') }}</span> </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @else
                    <p class="text-center text-red">Aucun arrêt d'état!</p>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>