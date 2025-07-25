<div>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <h4 class="">Total: <strong class="text-red"> {{count($initiations)}} </strong> | Légende: <button class="btn btn-sm bg-light border">En cours(s) <i class="text-warning bi bi-geo-alt"></i></button> <button class="btn btn-sm bg-light border">Traité(s) <i class="text-success bi bi-geo-alt"></i></button> </h4>

            <div class="table-responsive table-responsive-list shadow-lg">
                <table id="myTable" class="table table-striped table-sm shadow-lg">
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center">Arrêt</th>
                            <th class="text-center">Maison</th>
                            <th class="text-center">Propriétaire</th>
                            <th class="text-center">Montant</th>
                            <th class="text-center">Commentaire</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($initiations as $initiation)
                        <tr class="align-items-center">
                            <td class="text-center">{{$loop->index + 1}} @if($initiation->Status->id==1) <i class="text-warning bi bi-geo-alt"></i> @else <i class="text-success bi bi-geo-alt"></i>@endif </td>
                            <td class="text-center"> <strong class="text-dark  bg-light text-dark">
                                    {{$initiation->House?->States->last()?
                                    \Carbon\Carbon::parse($initiation->House?->States->last()->stats_stoped_day)->locale('fr')->isoFormat('D MMMM YYYY'):
                                    $initiation->stats_stoped_day
                                }} </strong></td>
                            <td class="text-center"> <strong class="text-dark  bg-light text-red">{{$initiation->House?->name}} </strong></td>
                            <td class="text-center"><span class=" bg-light text-dark"> {{$initiation->House?->Proprietor?->lastname}} {{$initiation->House?->Proprietor?->firstname}}</span> </td>
                            <td class="text-center">
                                <span class=" bg-light text-red"><i class="bi bi-currency-exchange"></i> {{number_format($initiation['amount'],0,","," ") }}</span>
                            </td>
                            <td class="text-center">
                                <textarea name="" rows="1" class="form-control" id="">{{$initiation['comments']}}</textarea>
                            </td>
                            <td class="text-center">
                                <span class=" @if($initiation['Status']['id']==2) bg-success @else bg-red  @endif" @if($initiation['Status']['id']==3) title="{{$initiation->rejet_comments}}" @elseif($initiation['Status']['id']==2) disabled @endif> @if($initiation->Status->id==3) <i class="bi bi-eye"></i> @else <i class="bi bi-check-circle"></i>@endif {{$initiation->Status->name}}</span>
                            </td>
                            <td class="text-center d-flex">
                                @if($initiation['Status']["id"]==2)
                                <span class="text-dark bg-light ">Déjà validé</span>
                                @elseif($initiation['Status']["id"]==3)
                                <span class="text-success ">Déjà rejetée</span>
                                @elseif($initiation['Status']["id"]==1)

                                @can("proprio.payement.validate")
                                <a href="{{route('payement_initiation.ValidePaiementInitiation',crypId($initiation->id))}}" class="btn btn-sm btn-success" title="Valider"><i class="bi bi-check-circle"></i> </a>
                                @endcan

                                @can("proprio.payement.cancel")
                                @if($initiation->state!=null)
                                <a href="#" data-bs-toggle="modal" data-bs-target="#payement_rejet" data-initiation-id="{{crypId($initiation->id)}}" class="btn btn-sm btn-danger" title="Rejeter"><i class="bi bi-x-circle"></i> </a>
                                @endcan
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- REJET D'UN PAIEMENT -->
    @can("proprio.payement.cancel")
    <div class="modal fade" id="payement_rejet" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="">Rejet de paiement</p>
                    <form id="rejetForm"  action="#" method="post">
                        @csrf
                        <textarea required name="rejet_comments" value="{{old('rejet_comments')}}" class="form-control" placeholder="Pourquoi voulez-vous effectuer ce rejet"></textarea>
                        <br>
                        <button type="submit" class="w-100 btn btn-sm bg-red"><i class="bi bi-x-circle"></i> Rejeter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @push("scripts")
    <script type="text/javascript">
        document.querySelectorAll('[data-bs-target="#payement_rejet"]').forEach(button => {
            button.addEventListener('click', function() {
                const initiationId = this.dataset.initiationId;
                validation(initiationId);
            });
        });
        
        async function validation(initiationId) {
            $("#rejetForm").attr("action",`/payement_initiation/${initiationId}/rejet`)
        }
    </script>
    @endpush
</div>