<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Etats Electricité</title>

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
                                <h3 class="rapport-title text-uppercase">etat de consommation en électricité</h3>
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
                                    <h6 class="">Maison : <em class=""> {{$state->House->name}} </em> </h6>
                                    <h6 class="">Superviseur : <strong> <em class=""> {{$state->House->Supervisor->name}} </em> </h6>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>
            <h5 class="text-center">Date d'arrêt: <strong class=""> {{Change_date_to_text($state->state_stoped_day) }} </strong> </h5>
            <br>

            @if(count($state->StatesFactures)>0)
            <table class="table" style="margin-left: -30px!important;">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">N°</th>
                        <th class="text-center">Chambre</th>
                        <th class="text-center" colspan="3">Locataire</th>
                        <th class="text-center">Index début</th>
                        <th class="text-center">Index fin</th>
                        <th class="text-center">Consommation</th>
                        <th class="text-center">P.U</th>
                        <th class="text-center">Montant facturé</th>
                        <th class="text-center">Montant Payé</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($state->StatesFactures as $facture)
                    @if(!$facture->state_facture)
                    <tr class="align-items-center">
                        <td class="text-center">{{$loop->index+1}}</td>
                        <td class="text-center">{{$facture->Location->Room->number}}</td>

                        <td class="text-center ">{{$facture->Location->Locataire->name}}</td>
                        <td class="text-center ">{{$facture->Location->Locataire->prenom}}</td>
                        <td class="text-center ">{{$facture->Location->Locataire->phone}}</td>

                        <td class="text-center"> {{$facture["start_index"]}} </td>
                        <td class="text-center"> {{$facture["end_index"]}} </td>
                        <td class="text-center"> {{$facture["consomation"]}} </td>
                        <td class="text-center"> <strong class="shadow ">{{$facture->Location->Room->electricity_unit_price}} </strong> </td>
                        <td class="text-center">{{$facture['amount']}}</td>
                        <td class="text-center">
                            @if($facture['paid'])
                            {{$facture['amount']}}
                            @else
                            ---
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    <tr>
                        <td class="bg-secondary text-white" colspan="2">Totaux: </td>
                        <td colspan="7"></td>
                        <td class=""> <strong class="text-center">= {{$factures_sum}} fcfa</strong></td>
                        <td class=""> <strong class="text-center">= {{$paid_factures_sum}} fcfa</strong></td>
                    </tr>

                    <tr>
                        <td colspan="9"></td>
                        <td class="bg-dark text-white">Arriérés: </td>
                        <td class="bg-secondary text-white"> <strong class="text-center">= {{$umpaid_factures_sum}} fcfa</strong></td>
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