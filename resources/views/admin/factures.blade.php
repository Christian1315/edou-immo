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
                <small class="d-flex">
                    <!-- <button data-bs-toggle="modal" data-bs-target="#ShowSearchLocatorsByHouseForm" class="btn btn-sm bg-light text-dark text-uppercase"><i class="bi bi-file-pdf-fill"></i> Prestation par période</button> -->
                    <button data-bs-toggle="modal" data-bs-target="#filtreByUserAndPeriod" class="btn btn-sm bg-light text-dark text-uppercase"><i class="bi bi-file-pdf-fill"></i> Filtrer par période et utilisateur</button>
                    <a href="{{route('locationFacture',crypId($agency->id))}}" class="btn btn-sm bg-light text-dark text-uppercase"><i class="bi bi-file-pdf-fill"></i> Actualiser</a>
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
                                            <select name="user" class="form-select form-control agency-modal-select2" required aria-label="Default select example">
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

                <!-- FILTRER PAR ETAT DES FACTURES -->
                <div class="modal fade" id="filtreByFactureStatus" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <p class="" id="exampleModalLabel">Filter par statut</p>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('locationFacture',crypId($agency->id))}}" method="GET">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select name="status" required class="form-control agency-modal-select2">
                                                <option value="valide">Validée</option>
                                                <option value="en_attente">En attente</option>
                                                <option value="rejetee">Rejetée</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="text-center">
                                        <button type="submit" class="w-100 text-center bg-red btn btn-sm"><i class="bi bi-funnel"></i> Filtrer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h4 class="">Total: <strong class="text-red"> {{count($factures)}} </strong> | Légende: <span class="border btn btn-sm btn-light text-success" data-bs-toggle="modal" data-bs-target="#filtreByFactureStatus"><i class="bi bi-check-circle"></i> Facture validées ({{$factures->where("status",2)->count()}}) </span> <span class="border btn btn-sm btn-light text-warning" data-bs-toggle="modal" data-bs-target="#filtreByFactureStatus"><i class="bi bi-check-circle"></i> Factures en attente ({{$factures->where("status",1)->count()}}) </span> <span class="border btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#filtreByFactureStatus"><i class="bi bi-x-circle"></i> Facture rejetées ({{$factures->where("status",3)->count()}}) </span></h4>
                <h5 class="">Totale encaissé : <b id='montantTotal' class="badge bg-red">{{ number_format($montantTotal ?? 0,0,","," ")  }} FCFA</b> </h5>
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
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($factures->count()>0)
                            @foreach($factures as $facture)
                            <tr class="align-items-center @if($facture->status==3) bg-secondary @elseif($facture->status==1) bg-warning @endif">
                                <td class="text-center"><span class="badge text-red bg-light"> {{$facture->facture_code?$facture->facture_code:"---"}} </span></td>
                                <td class="text-center text-red"><span class="badge bg-light text-dark"> {{$facture->Location?->House?->Supervisor?->name}}</span></td>
                                <td class="text-center"> <span class="badge bg-dark">{{$facture->Owner?->name}} </span> </td>
                                <td class="text-center"> <span class="badge bg-light text-dark">{{$facture->Location?->House?->name}} </span> </td>
                                <td class="text-center"> <span class="badge bg-light text-dark">{{$facture->Location->Room?$facture->Location->Room->number:"deménagé"}} </span> </td>
                                <td class="text-center"><button class="btn btn-sm btn-light">{{$facture->Location->Locataire?->name}} {{$facture->Location?->Locataire?->prenom}} {{$facture->Location->id}}</button> </td>
                                <td class="text-center"> <a href="{{$facture['facture']}}" class="btn btn-sm btn-light shadow-sm"><i class="bi bi-eye"></i></a></td>
                                <td class="text-center">{{number_format($facture['amount'],0,","," ")}}</td>
                                <td class="text-center text-red"><span class="badge bg-light text-red"> <b>{{ \Carbon\Carbon::parse($facture['echeance_date'])->locale('fr')->isoFormat('D MMMM YYYY') }} </b></span> </td>
                                <td class="text-center text-red"><span class="badge bg-light text-red"> <b>{{ \Carbon\Carbon::parse($facture->created_at)->locale('fr')->isoFormat('D MMMM YYYY') }} </b></span> </td>
                                <td class="text-center">
                                    <textarea name="" rows="1" class="form-control" id="" placeholder="{{$facture->comments}}"></textarea>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group dropstart">
                                        <button class="btn bg-red btn-sm dropdown-toggle" style="z-index: 0;" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-kanban-fill"></i> &nbsp; Gérer
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                @if($facture->status==2)
                                                <span class="text-success">Facture déjà validée</span>
                                                @else
                                                <button onclick="manageFacture({{$facture}})" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#updateFacture" class="w-100 btn btn-sm bg-warning">
                                                    <i class="bi bi-folder-x"></i> Traiter la facture
                                                </button>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE GESTION DE FACTURE -->
    <div class="modal fade" id="updateFacture" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" id="updateStatusForm">
                    @csrf
                    <div class="modal-header">
                        <p class="">
                            Code : <strong class="text-red" id="facture_code"></strong><br>
                            Maison : <strong class="text-red" id="facture_house"></strong><br>
                            Chambre : <strong class="text-red" id="facture_room"></strong><br>
                            Locataire : <strong class="text-red" id="facture_locataire"></strong><br>
                        </p>
                        <button type="button" class="btn btn-sm bg-light text-red" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-circle"></i></button>
                    </div>
                    <div class="modal-body">
                        <select class="form-control agency-modal-select2" name="status" id="" required>
                            @foreach($factureStatus as $statut)
                            @continue($statut->id==1 || $statut->id==4)
                            <option value="{{$statut->id}}">{{$statut->description}} </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="w-100 btn btn-sm bg-red text-white"><i class="bi bi-floppy"></i> Enregistrer</button>
                    </div>
                </form>
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

        // TRAITEMENT DE FACTURE
        async function manageFacture(facture) {
            // console.log(facture)
            $("#facture_code").html(facture.location.facture_code)
            $("#facture_house").html(facture.location.house.name)
            $("#facture_room").html(facture.location.room.number)
            $("#facture_locataire").html(`${facture.location.locataire.name} ${facture.location.locataire.prenom}`)

            $("#updateStatusForm").attr("action", `/location/${facture.id}/facture-traitement`)
        }
    </script>
</x-templates.agency>