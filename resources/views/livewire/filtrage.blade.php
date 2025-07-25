<div>
    <!-- TABLEAU DE LISTE -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <h4 class="">Bilan de cette agence </h4>
                    <thead class="bg_dark">
                        <tr>
                            <th class="text-center">Nbr de propriétaires</th>
                            <th class="text-center">Nbr de maisons</th>
                            <th class="text-center">Nbr de locataires</th>
                            <th class="text-center">Nbr de locataires demenagé</th>
                            <th class="text-center">Nbr de locations</th>
                            <th class="text-center">Nbr de chambres</th>
                            <th class="text-center">Facture</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="align-items-center">
                            <td class="text-center">{{$proprietors->count()}}</td>
                            <td class="text-center">{{$houses->count()}}</td>
                            <td class="text-center">{{$locators->count()}}</td>
                            <td class="text-center">
                                <button data-bs-toggle="modal" data-bs-target="#ShowMovedLocators" class="btn btn-sm  shadow-lg bg-red"> {{$moved_locators->count()}} <i class="bi bi-eye-fill"></i> Voir </button>
                            </td>
                            <td class="text-center">{{$locations->count()}}</td>
                            <td class="text-center">{{$rooms->count()}}</td>
                            <td class="text-center">
                                <button data-bs-toggle="modal" data-bs-target="#ShowFactures" class="btn btn-sm  shadow-lg bg-red"> {{$factures->count()}} <i class="bi bi-eye-fill"></i> Voir</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bg-warning p-3"> <strong>Montant total en facture: </strong> </td>
                            <td class="p-3 bg-red">= {{number_format($factures_total_amount,0,","," ")}} fcfa </td>
                        </tr>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- MODAL DES FACTURES -->
    <div class="modal fade" id="ShowFactures" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <h4 class="">Total des factures: <strong class="text-red"> {{count($factures)}} </strong> </h4>
                    <div class="table-responsive">
                        <table id="myTable" class="table table-striped table-sm">
                            <thead class="bg_dark">
                                <tr>
                                    <th class="text-center">N°</th>
                                    <th class="text-center">Faturier</th>
                                    <th class="text-center">Maison</th>
                                    <th class="text-center">Chambre</th>
                                    <th class="text-center">Locataire</th>
                                    <th class="text-center">Montant</th>
                                    <th class="text-center">Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($factures as $facture)
                                <tr class="align-items-center">
                                    <td class="text-center">{{$loop->index+1}}</td>
                                    <td class="text-center"><span class=" bg-light text-dark"> {{$facture->Owner->name}} </span></td>
                                    <td class="text-center"><span class=" bg-light text-dark"> {{$facture->Location->House->name}} </span></td>
                                    <td class="text-center"><span class=" bg-light text-dark"> {{$facture->Location->Room?->number}}  </span></td>
                                    <td class="text-center"><span class=" bg-light text-dark"> {{$facture->Location->Locataire->name}} {{$facture->Location->Locataire->prenom}} </span></td>
                                
                                    <td class="text-center"><span class="bg-light  text-red"> {{number_format($facture->amount,0,","," ")}}</span> </td>
                                    <td class="text-center">
                                        <textarea name="" rows="1" class="form-control" placeholder="{{$facture->comments}}"></textarea>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DES LOCATAIRES DEMENAGES -->
    <div class="modal fade" id="ShowMovedLocators" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <table class="table table-striped table-sm">
                        <h4 class="">Locataires demenagés: <strong class="text-red"> {{$moved_locators->count()}} </strong> </h4>

                        @if($moved_locators->count()>0)
                        <thead class="bg_dark">
                            <tr>
                                <th class="text-center">N°</th>
                                <th class="text-center">Nom</th>
                                <th class="text-center">Prénom</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Numéro de pièce</th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Adresse</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($moved_locators as $locator)
                            <tr class="align-items-center">
                                <td class="text-center">{{$loop->index + 1}}</td>
                                <td class="text-center">{{$locator["name"]}}</td>
                                <td class="text-center">{{$locator["prenom"]}}</td>
                                <td class="text-center">{{$locator["email"]}}</td>
                                <td class="text-center">{{$locator["piece_number"]}}</td>
                                <td class="text-center">{{$locator["phone"]}}</td>
                                <td class="text-center">{{$locator["adresse"]}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <p class="text-center text-red">Aucun locataire n'a été demenagé!</p>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>