<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Gestion des cautions</title>

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
            color: #fff;
            border: solid 2px #cc3301;
            text-align: center !important;
            padding: 20px;
            background-color: #000;
            --bs-bg-opacity: 0.5
        }

        .text-red {
            color: #cc3301;
        }

        td {
            border: 2px solid #000;
        }

        .bg-red {
            background-color: #cc3301;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10 shadow-lg bg-light">
                <!-- HEADER -->
                <div class="row">
                    <div class="col-12 px-0 mx-0">
                        <div>
                            <div class="col-12">
                                <h3 class="rapport-title text-uppercase">états des cautions</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <img src="{{asset('edou_logo.png')}}" alt="" style="width: 100px;" class="img-fluid">
                </div>

                @if(count($locations))
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">Maison</th>
                            <th scope="col" class="text-center">Chambre</th>
                            <th scope="col" class="text-center">Locataire</th>
                            <th scope="col" class="text-center">Date d'integration</th>
                            <th scope="col" class="text-center">Caution Loyer</th>
                            <th scope="col" class="text-center">Caution Eau / Electrique</th>
                            <th scope="col" class="text-center">Frais de peinture</th>
                            <th scope="col" class="text-center">Totaux(fcfa) </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $location)
                        <tr>
                            <td class="text-center">{{$location->House->name}}</td>
                            <td class="text-center">{{$location->Room->number}}</td>
                            <td class="text-center">{{$location->Locataire->name}} {{$location->Locataire->prenom}}</td>
                            <td class="text-center text-red"><small class="text-red"> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location->integration_date)->locale('fr')->isoFormat('D MMMM YYYY') }}</small> </td>
                            <td class="text-center"> <strong class="d-block">{{number_format($location->caution_number*$location->loyer,0," "," ")}} </strong> ({{$location->caution_number}}X{{$location->loyer}})</td>
                            <td class="text-center">{{number_format($location->caution_water+$location->caution_electric,0," "," ")}} ({{$location->caution_water}}+{{$location->caution_electric}})</td>
                            <td class="text-center">{{number_format($location->frais_peiture,0," "," ") }}</td>
                            <td class="text-center bg-warning"> <strong> {{number_format($location->caution_number*$location->loyer + $location->caution_water+$location->caution_electric + $location->frais_peiture,0,""," ") }} </strong> </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="bg-red shadow-lg">Totaux: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="bg-warning"> <strong>{{number_format(array_sum($cautions_loyer),0," "," ") }} </strong> </td>
                            <td class="bg-warning"> <strong>{{number_format(array_sum($cautions_electricity)+array_sum($cautions_eau),0," "," ") }} </strong> </td>
                            <td class="bg-warning"> <strong>{{number_format(array_sum($cautions_eau),0," "," ") }} </strong> </td>
                        </tr>
                    </tbody>
                </table>
                @else
                <p class="text-red text-center">Aucune location disponible!</p>
                @endif

                <br>
                <p class="text-center">
                    Arrêté le présent état à la somme de <em class="text-red">{{number_format(array_sum($cautions_electricity) + array_sum($cautions_eau) + array_sum($cautions_loyer),0," "," ")}} cfa</em>
                </p>

                <br>
                <!-- SIGNATURE SESSION -->
                <div class="text-right">
                    <h5 class="" style="text-decoration: underline;">Signature du Gestionnaire de compte</h5>
                    <br>
                    <hr class="">
                    <br>
                </div>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</body>

</html>