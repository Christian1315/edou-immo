<div>
    <div class="row d-flex justify-content-center">
        <div class="col-6">
            <form action="{{route('paiement',['agency'=>crypId($current_agency->id)])}}" method="POST" class="border shadow shadow-sm p-3">
                @csrf
                <select name="house" id="" class="form-control agency-modal-select2">
                    @foreach($houses as $house)
                    <option value="{{$house->id}}" class="">{{$house->name}}</option>
                    @endforeach
                </select>

                <button class="btn btn-sm bg-red mt-3 w-100"><i class="bi bi-search"></i> Afficher</button>
            </form>
        </div>
    </div>

    @if($house)
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm">
                    <h4 class="">Total: <strong class="text-red"> 1 </strong> </h4>
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Montant total récouvré</th>
                            <th class="text-center">Commission</th>
                            <th class="text-center">Dépense totale</th>
                            <th class="text-center">Net à payer</th>
                            <th class="text-center">Date d'arrêt d'état</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Payer</th>
                            <th class="text-center">Impression</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-items-center">
                            <td class="text-center">1 </td>
                            <td class="text-center"> <span class=" bg-light text-dark"> {{$house["name"]}}</span> </td>
                            <td class="text-center">
                                <strong class=" bg-light text-success"><i class="bi bi-currency-exchange"></i> {{number_format($house["total_amount_paid"],2,","," ")}} fcfa </strong>
                            </td>

                            <td class="text-center">
                                <strong class=" bg-light text-success"><i class="bi bi-currency-exchange"></i> {{number_format($house["commission"],2,","," ")}} fcfa </strong>
                            </td>

                            <td class="text-center">
                                <strong class=" bg-light text-red"><i class="bi bi-currency-exchange"></i> {{number_format($house["last_depenses"],2,","," ")}} fcfa </strong>
                            </td>
                            <td class="text-center">
                                <strong class=" bg-light text-success"><i class="bi bi-currency-exchange"></i> {{number_format($house["_amount"],2,","," ")}} fcfa </strong>
                            </td>

                            <td class="text-center">
                                <strong class=" bg-light text-dark"> <i class="bi bi-calendar-check"></i>
                                    {{$house["house_last_state"]?
                                        \Carbon\Carbon::parse(date($house["house_last_state"]["stats_stoped_day"]))->locale('fr')->isoFormat('D MMMM YYYY') : 
                                        ($house["payement_initiations_last"]? 
                                            \Carbon\Carbon::parse(date($house["payement_initiations_last"]->stats_stoped_day))->locale('fr')->isoFormat('D MMMM YYYY'):
                                            "---"
                                            )
                                    }}
                                </strong>
                            </td>
                            <td class="text-center d-flex">
                                @if($house['house_last_state'])
                                @if($house["house_last_state"]->proprietor_paid)
                                <span aria-disabled="true" class=" bg-light text-success">Payé</span>
                                @else
                                <span class=" bg-light text-red"> Non payé</span>
                                @endif
                                @endif

                                @if($house["payement_initiations_last"])
                                @if($house["payement_initiations_last"]->status==3)
                                <span aria-disabled="true" class=" bg-light text-red">mais Rejeté</span>
                                @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @if($house['house_last_state'])
                                @if(!$house['house_last_state']["proprietor_paid"])
                                @can("proprio.payement")
                                <button class="btn btn-sm bg-red" data-bs-toggle="modal" data-bs-target="#paid"
                                    data-house-id="{{$house['id']}}"
                                    data-house-name="{{$house['name']}}"
                                    data-house-amount="{{$house['_amount']}}"><i class="bi bi-currency-exchange"></i>Payer</button>
                                @endcan
                                @endif
                                @endif
                            </td>
                            <td class="text-center">
                                @can("proprio.print.state")
                                <a href="{{route('house.ShowHouseStateImprimeHtml',crypId($house['id']))}}" class="btn text-dark btn-sm bg-light"><i class="bi bi-file-earmark-pdf-fill"></i> Imprimer les états</a>
                                @endcan
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <p class="text-center border">Aucune maison affichée!</p>
    @endif

    <!-- ###### MODEL DE PAIEMENT AU PROPRIETAIRE ###### -->
    <div class="modal fade" id="paid" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="">
                        <strong>Maison: <em class="text-red house_name"></em> </strong> <br>
                    </div>
                </div>
                <form id="paimentForm" method="POST" class="shadow-lg p-3 animate__animated animate__bounce p-3">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <input type="hidden" name="house" class="house">
                            <div class="mb-3 p-3">
                                <label for="" class="d-block">Montant à payer <span class="text-red"> (fcfa)</span> </label>
                                <input type="hidden" name="amount" class="form-control amount">
                                <input disabled type="number" class="form-control amount">
                                @error("amount")
                                <span class="text-red">{{$message}}</span>
                                @enderror
                                <div class="text-right mt-2">
                                    <button class="w-100 btn btn-sm bg-red"><i class="bi bi-check-circle"></i> Valider</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        document.querySelectorAll('[data-bs-target="#paid"]').forEach(button => {
            button.addEventListener('click', function() {
                const houseId = this.dataset.houseId;
                const houseName = this.dataset.houseName;
                const houseAmount = this.dataset.houseAmount;
                paiement(houseId, houseName, houseAmount);
            });
        });

        async function paiement(houseId, houseName, houseAmount) {
            $(".house_name").html(houseName)
            $(".house").val(houseId)

            $(".amount").val(houseAmount)
            $("#paimentForm").attr("action", `/payement_initiation/initiateToProprio`)
        }
    </script>
</div>