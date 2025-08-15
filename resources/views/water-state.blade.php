<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Etats Eau</title>

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
            font-size: 10px !important;
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
    </style>
</head>

<body>
    <div class="container-fluid bg-light">
        <div class="row shadow-lg" style="padding-inline: 20px;">
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
                                <img src="{{public_path('edou_logo.png')}}" alt="" style="width: 70px;" class="rounded img-fluid">
                            </td>
                            <td class="text"></td>
                            <td class="text"></td>
                            <td class="text"></td>
                            <td class="text">
                                <h3 class="rapport-title text-uppercase">etat de consommation en eau</h3>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>

            <!-- infos liés  -->
            <div class="row">
                <table>
                    <thead>
                        <tr>
                            <!-- <th></th> -->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>

                            <td class="text">
                                <div class="">
                                    <h6 class="">Maison : <em class=""> {{$state->House?->name}} </em> </h6>
                                    <h6 class="">Superviseur : <strong> <em class=""> {{$state->House?->Supervisor?->name}} </em> </h6>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>
            <h5 class="text-center">Date d'arrêt: <strong class=""> {{\Carbon\carbon::parse($state->state_stoped_day)->locale('fr')->isoFormat("D MMMM YYYY")}} </strong> </h5>
            <br>

            @if(count($state->StatesFactures)>0)
            <table class="table" style="margin-left: -30px!important;">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">N°</th>
                        <th class="text-center">Locataire</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center">Chambre</th>
                        <th class="text-center">Index début</th>
                        <th class="text-center">Index fin</th>
                        <th class="text-center">Consommation</th>
                        <th class="text-center">P.U</th>
                        <th class="text-center">Montant à payer</th>
                        <th class="text-center">Impayés</th>
                        <th class="text-center">Montant total à payé</th>
                        <th class="text-center">Montant payé</th>
                        <th class="text-center">Montant dû</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($factures as $facture)
                    <tr class="align-items-center">
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td class="text-center ">{{$facture->Location?->Locataire?->name}}</td>
                        <td class="text-center ">{{$facture->Location?->Locataire?->phone}}</td>
                        <td class="text-center">{{$facture->Location?->Room?->number}}</td>

                        <td class="text-center"> {{$facture["start_index"]}} </td>
                        <td class="text-center"> {{$facture["end_index"]}} </td>
                        <td class="text-center"> {{$facture["consomation"]}} </td>
                        <td class="text-center"> <strong class="shadow ">{{$facture->Location?->Room?->electricity_unit_price}} </strong> </td>
                        <!-- montant à payer-->
                        <td class="text-center">{{$facture['amount']}}</td>
                        <!-- montant impayé -->
                        <td class="text-center">
                            {{$facture->paid?'---':$facture->amount}}
                        </td>
                        <!-- total à payer-->
                        <td class="text-center">{{$facture['amount']}}</td>
                        <!-- montant payé -->
                        <td class="text-center">
                            {{$facture->paid?$facture->amount:'---'}}
                        </td>
                        <!-- montant dû -->
                        <td class="text-center">{{$facture->paid?'---':$facture->amount}}</td>
                    </tr>
                    @endforeach
                    <tr class="text-center" style="font-weight: bold!important;">
                        <td class="bg-light text-dark" colspan="7">MONTANT A PAYER: </td>
                        <td colspan="6"> {{number_format($umpaid_factures_sum,2,"."," ")}} fcfa</td>
                    </tr class="text-center">

                    <tr class="text-center" style="font-weight: bold!important;">
                        <td class="bg-light text-dark" colspan="7">COMMISSION DE L'AGENCE: </td>
                        <td colspan="6"> {{number_format($umpaid_factures_sum*10/100,2,"."," ")}} fcfa</td>
                    </tr>

                    <tr class="text-center" style="font-weight: bold!important;">
                        <td class="bg-light text-dark" colspan="7">NET A PAYER: </td>
                        <td colspan="6"> {{number_format(($umpaid_factures_sum - $umpaid_factures_sum*10/100),2,"."," ")}} fcfa</td>
                    </tr>
                </tbody>
            </table>
            @else
            <p class="text-center ">Aucune facture disponible!</p>
            @endif

            <br>
            <!-- SIGNATURE SESSION -->
            <div class="text-right">
                <h5 class="" style="text-decoration: underline;">Signature du Gestionnaire de compte</h5>
                <br>
                <hr class="">
                <br>
            </div>
            <div class="col-1"></div>
        </div>
</body>

</html>