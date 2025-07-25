<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Etats de maison</title>
    <style>
        * {
            font-family: "Poppins";
        }

        .title {
            text-decoration: underline;
            font-size: 25px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .rapport-title {
            color: #000;
            /* border: solid 2px #cc3301; */
            text-align: center !important;
            padding: 10px;
            background-color: rgb(159, 160, 161) !important;
            /* --bs-bg-opacity: 0.5 */
        }

        .text-red {
            color: #cc3301;
        }

        tr th {
            font-size: 15px !important;
        }

        td {
            border: 2px solid #000;
        }

        td.text {
            border: none !important;
        }

        .bg-red {
            background-color: #cc3301;
            color: #fff;
        }

        tr,
        td {
            align-items: center !important;
        }

        .header {
            margin-top: 100px;
        }

        .head-info-left {
            float: left;
            width: 60%;
        }

        .head-info-right {
            float: left;
            width: 60%;
        }
    </style>
</head>

<body>
    <div class="container-fluid bg-light px-3">
        <div class="shadow-lg">
            <!-- HEADER -->
            <div class="row _header px-5">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text">
                                <img src="{{public_path('edou_logo.png')}}" alt="" style="width: 100px;" class="rounded img-fluid">
                            </td>

                            <td class="text" style="padding-left: 100px!important;">
                                <h3 class="rapport-title text-uppercase">etat de recouvrement</h3>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>

            @php
            $recovery_date = $house->post_paid?
            date("Y/m/d", strtotime("-1 month", strtotime($state->created_at))):
            $state->created_at;
            @endphp

            <!-- infos liés à la maison -->
            <div class="row px-2">
                <div class="head-info-left">
                    <div class="mt-3">
                        <h6 class="">Mois de recouvrement: <strong> <em class=""> {{ \Carbon\Carbon::parse($state->created_at)->locale('fr')->isoFormat('MMMM YYYY') }} </em> </strong> </h6>
                        <h6 class="">Mois récouvré: <strong> <em class=""> {{ \Carbon\Carbon::parse($state->recovery_date)->locale('fr')->isoFormat('MMMM YYYY') }} </em> </strong> </h6>
                        <div>
                            <strong class="">Trm = <em class="">{{number_format(NumersDivider($unPaidLocatairesPlusLocataireAjour,count($locations)),2,"."," ")}} % </em>
                        </div>
                    </div>
                </div>
                <div class="head-info-right">
                    <div class="">
                        <h6 class="">Maison : <strong> <em class=""> {{$house["name"]}} </em> </strong> </h6>
                        <h6 class="">Superviseur : <strong> <em class=""> {{$house->Supervisor->name}} </em> </strong> </h6>
                        <h6 class="">Propriétaire : <strong> <em class=""> {{$house->Proprietor->lastname}} {{$house->Proprietor->firstname}} ({{$house->Proprietor->phone}})</em> </strong> </h6>
                        <h6 class="">Date d'arrêt: <strong> <em class=""> {{ \Carbon\Carbon::parse($house->PayementInitiations->last()?->state_stoped_day)->locale('fr')->isoFormat('D MMMM YYYY') }} </em> </strong> </h6>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <br>
        <br>
        <br>
        <br>

        <!-- les totaux -->
        <div class="row mt-5 px-3">
            <table class="table table-striped table-sm">
                <thead class="bg_dark">
                    <tr>
                        <th class="text-center">Maison</th>
                        <th class="text-center">Montant total récouvré</th>
                        <th class="text-center">Commission</th>
                        <th class="text-center">Dépense totale</th>
                        <th class="text-center">Charge locatives</th>
                        <th class="text-center">Charge Commission</th>
                        <th class="text-center">Net à payer</th>
                        {{-- <th class="text-center">Date d'arrêt d'état</th> --}}
                    </tr>
                </thead>
                <tbody>
                    <tr class="align-items-center">
                        <td class="text-center"> {{$house["name"]}}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-light shadow-lg "><i class="bi bi-currency-exchange"></i> <strong> {{ number_format($total_revenue,0,","," ") }} fcfa </strong> </button>
                        </td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-light shadow-lg "><i class="bi bi-currency-exchange"></i> <strong> {{number_format($total_commission,0,","," ")}} fcfa </strong> </button>
                        </td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-light shadow-lg "><i class="bi bi-currency-exchange"></i> <strong> {{number_format($total_expenses,0,","," ")}} fcfa </strong> </button>
                        </td>

                        <td class="text-center">
                            <strong class="">{{number_format($locativeCharge,0,","," ")}} fcfa</strong>
                        </td>

                        <td class="text-center">
                            <strong class="">{{number_format($locative_commission,0,","," ")}} fcfa</strong>
                        </td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-light shadow-lg "><i class="bi bi-currency-exchange"></i> <strong> {{number_format($net_amount,0,","," ")}} fcfa </strong> </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br><br><br>

        <!-- les locataires -->
        <div class="row mt-3 px-3">
            <table class="table table-striped table-sm" style="margin-inline-end: 50px!important;">
                @if($locations->count()>0)
                <thead>
                    <tr>
                        <!-- <th class="text-center">N°</th> -->
                        <th class="text-center">Locataire</th>
                        <th class="text-center">Téléphone</th>
                        <th class="text-center">Chambre</th>
                        <th class="text-center">Loyer Mensuel</th>
                        <th class="text-center">Prorata</th>
                        <th class="text-center">Nbre de mois payé(s)</th>
                        <th class="text-center">Montant payé</th>
                        <th class="text-center">Dernier mois payé</th>
                        <th class="text-center">Début du contrat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <td colspan="9">Les Chambres Occupées ({{$locations->count()}})</td>
                    </tr>
                    <!-- locations -->
                    @foreach($locations as $location)
                    <tr class="align-items-center">
                        <td class="text-center"> <small class="btn-light"> <strong> {{$location->Locataire->name}} {{$location->Locataire?->prenom}}</strong> </small> </td>
                        <td class="text-center">{{$location->Locataire?->phone}}</td>
                        <td class="text-center">{{$location->Room?$location->Room->number:'---'}}</td>
                        <td class="text-center"><span class="badge bg-light "> {{number_format($location->Room?$location->Room->total_amount:0,0,","," ")}} </span></td>
                        <td class="text-center"><span class="badge bg-light ">{{$location->prorata_amount>0?number_format($location->prorata_amount,0,","," "):'--'}} </span></td>
                        <td class="text-center">{{$location["_locataire"]?($location->prorata_amount>0?'--':$location["nbr_facture_amount_paid"]):00}}</td>
                        <td class="text-center"><span class="badge bg-light ">{{number_format($location["_locataire"]?
                                                ($location->prorata_amount>0?
                                                    $location->prorata_amount:
                                                    $location["facture_amount_paid"]
                                                ):00,0,","," ")}}</span></td>
                        <td class="text-left">
                            <small class="btn-light shadow-lg"> <i class="bi bi-calendar-check-fill"></i> <strong class="">{{ \Carbon\Carbon::parse($location["latest_loyer_date"])->locale('fr')->isoFormat('MMMM YYYY') }} </strong> </small>
                        </td>
                        <td class="text-left">
                            <small class="btn-light shadow-lg"> <i class="bi bi-calendar-check-fill"></i> <strong class="">{{ \Carbon\Carbon::parse($location["effet_date"])->locale('fr')->isoFormat('D MMMM YYYY') }} </strong> </small>
                        </td>
                    </tr>
                    @endforeach

                    <tr class="text-center">
                        <td colspan="9">Les Chambres libres ({{$free_rooms->count()}})</td>
                    </tr>

                    <!-- chambres libres -->
                    @foreach($free_rooms as $room)
                    <tr class="align-items-center">
                        <td class="text-center"> <small class="btn-light"> <strong> Vide </strong> </small> </td>
                        <td class="text-center">--</td>
                        <td class="text-center">{{$room?$room->number:'---'}}</td>
                        <td class="text-center"><span class="badge bg-light "> {{number_format($room?$room->total_amount:0,0,","," ")}} </span></td>
                        <td class="text-center"><span class="badge bg-light ">-- </span></td>
                        <td class="text-center">--</td>
                        <td class="text-center"><span class="badge bg-light ">--</span></td>
                        <td class="text-left">
                            <small class="btn-light shadow-lg"> <i class="bi bi-calendar-check-fill"></i> <strong class="">--</strong> </small>
                        </td>
                        <td class="text-left">
                            <small class="btn-light shadow-lg"> <i class="bi bi-calendar-check-fill"></i> <strong class="">--</strong> </small>
                        </td>
                    </tr>
                    @endforeach

                    <tr>
                        <td colspan="3" class=" text-center"><strong> Détails des dépenses: </strong></td>
                        <td colspan="6" class="text-left">
                            <ul class="">
                                @forelse($state->CdrAccountSolds as $depense)
                                <li class=""><strong class="">{{number_format($depense->sold_retrieved,0,","," ")}} fcfa</strong> - {{$depense->description}}</li>
                                @empty
                                <li>Aucune dépense éffectuée dans la maison!</li>
                                @endforelse
                            </ul>
                        </td>
                    </tr>
                </tbody>
                @else
                <p class="text-center ">Aucune location!</p>
                @endif
            </table>
        </div>

        <br>
        <!--  RAPPORT DE RECOUVREMENT -->
        <div class="row px-3">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text"></td>
                        <td class="text"></td>
                        <td class="text">
                            <h4 class="text-center" style="text-decoration: underline;">Rapport de récouvrement</h4>
                            <div class="p-3 shadow text-justify" style="border: #000 2px solid;border-radius:5px ">
                                {{$state?$state->recovery_rapport:($house->PayementInitiations->last()?$house->PayementInitiations->last()->recovery_rapport:"---")}}
                            </div>
                        </td>
                        <td class="text"></td>
                        <td class="text"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>
        <!-- SIGNATURE SESSION -->
        <div class="text-right">
            <h5 class="" style="text-decoration: underline;">Le Chef d'Agence</h5>
            <br>
            <hr class="" style="width: 100px!important;position:absolute;right:0">
            <br>
        </div>
    </div>
</body>

</html>