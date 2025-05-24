<x-templates.agency :title="'Factures'" :active="'facture'" :agency=$agency>

    <!-- HEADER -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Panel des Factures</h1>
    </div>
    <br>

    <div id="facturBody">
        <!-- TABLEAU DE LISTE -->
        <div class="row">
            <div class="col-12">
                <small>
                    <!-- <button data-bs-toggle="modal" data-bs-target="#ShowSearchLocatorsByHouseForm" class="btn btn-sm bg-light text-dark text-uppercase"><i class="bi bi-file-pdf-fill"></i> Prestation par période</button> -->
                    <button data-bs-toggle="modal" data-bs-target="#filtreByUserAndPeriod" class="btn btn-sm bg-light text-dark text-uppercase"><i class="bi bi-file-pdf-fill"></i> Filtrer par période et utilisateur</button>
                </small>

                <!-- FILTRE BY PERIOD -->
                <div class="modal fade" id="filtreByUserAndPeriod" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="" id="exampleModalLabel">Filter par période</p>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST">
                                    @csrf
                                    @method("POST")
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="">Selectionnez un utilisateur</label>
                                            <select name="user" class="form-select form-control" required aria-label="Default select example">
                                                @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span>Date de début</span>
                                            <input type="date" required name="debut" class="form-control" id="">
                                        </div>
                                        <div class="col-md-6">
                                            <span class="">Date de fin</span>
                                            <input type="date" required name="fin" class="form-control" id="">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="text-center">
                                        <button type="submit" class="w-100 text-center bg-red btn btn-sm"><i class="bi bi-funnel"></i> Génerer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h4 class="">Total: <strong class="text-red"> {{count($factures)}} </strong> </h4>
                <div class="table-responsive table-responsive-list shadow-lg">
                    <table id="myTable" class="table table-striped table-sm">
                        <thead class="bg_dark">
                            <tr>
                                <th class="text-center">Code</th>
                                <th class="text-center">Superviseur</th>
                                <th class="text-center">Faturier</th>
                                <th class="text-center">Maison</th>
                                <th class="text-center">Chambre</th>
                                <th class="text-center">Locataire</th>
                                <th class="text-center">Facture</th>
                                <th class="text-center">Montant</th>
                                <th class="text-center">Echéance</th>
                                <th class="text-center">Fait le:</th>
                                <th class="text-center">Commentaire</th>
                                <!-- <th class="text-center">Status</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($factures)>0)
                            @foreach($factures as $facture)
                            <tr class="align-items-center">
                                <td class="text-center "><span class="badge text-red bg-light"> {{$facture["facture_code"]?$facture["facture_code"]:"---"}}</span></td>
                                <td class="text-center text-red"><span class="badge bg-light text-dark"> {{$facture["Location"]["House"]["Supervisor"]["name"]}}</span></td>
                                <td class="text-center"> <span class="badge bg-dark">{{$facture["Owner"]["name"]}} </span> </td>
                                <td class="text-center"> <span class="badge bg-light text-dark">{{$facture["Location"]["House"]["name"]}} </span> </td>
                                <td class="text-center"> <span class="badge bg-light text-dark">{{$facture->Location->Room?$facture->Location->Room->number:"deménagé"}} </span> </td>
                                <td class="text-center"><button class="btn btn-sm btn-light">{{$facture["Location"]["Locataire"]["name"]}} {{$facture["Location"]["Locataire"]["prenom"]}} </button> </td>
                                <td class="text-center"> <a href="{{$facture['facture']}}" class="btn btn-sm btn-light shadow-sm"><i class="bi bi-eye"></i></a>
                                </td>
                                <td class="text-center"><span class="badge bg-success text-white"><i class="bi bi-currency-dollar"></i> {{number_format($facture['amount'],2,","," ")}}</span></td>
                                <td class="text-center text-red"><span class="badge bg-light text-red"> <b>{{ \Carbon\Carbon::parse($facture['echeance_date'])->locale('fr')->isoFormat('D MMMM YYYY') }} </b></span> </td>
                                <td class="text-center text-red"><span class="badge bg-light text-red"> <b>{{ \Carbon\Carbon::parse($facture->created_at)->locale('fr')->isoFormat('D MMMM YYYY') }} </b></span> </td>
                                <td class="text-center">
                                    <textarea name="" rows="1" class="form-control" id="" placeholder="{{$facture->comments}}"></textarea>
                                </td>
                                <!-- <td class="text-center"><span class="badge @if($facture->status==2) bg-success @elseif($facture->status==3 || $facture->status==4)  bg-danger @else bg-warning @endif">{{$facture->Status->name}} </span></td> -->
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-sm">
                        <tr>
                            <br />
                            <td class="" colspan="2"><b>Montant Total Encaissé :</b></td>
                            <td colspan="6" class="text-right"><b id='montantTotal' class="badge bg-red">{{ number_format($montantTotal ?? 0,2,","," ")  }} FCFA</b></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $("#facturBody").on('change', function() {

            const montantTotal = new DataTable('#myTable').column(7, {
                page: 'all',
                search: 'applied'
            }).data().sum()
            __montantTotal = montantTotal < 0 ? -montantTotal : montantTotal

            $("#montantTotal").html(__montantTotal.toLocaleString() + " FCFA")
        })
    </script>

</x-templates.agency>