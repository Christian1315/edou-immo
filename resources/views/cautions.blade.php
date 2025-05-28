
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
    <div class="container-fluid p-5">
        <div class="row">
            <div class="col-12 shadow-lg bg-light">
              
                <div class="row _header pt-5">
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
                                    <h3 class="rapport-title text-uppercase">états des cautions</h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>

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
                            <td class="text-center">{{$location->Room?->number}}</td>
                            <td class="text-center">{{$location->Locataire->name}} {{$location->Locataire->prenom}}</td>
                            <td class="text-center "><small class=""> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location->integration_date)->locale('fr')->isoFormat('D MMMM YYYY') }}</small> </td>
                            <td class="text-center"> <strong class="d-block">{{number_format($location->caution_number*$location->loyer,0," "," ")}} </strong> ({{$location->caution_number}}X{{$location->loyer}})</td>
                            <td class="text-center">{{number_format($location->caution_water+$location->caution_electric,0," "," ")}} ({{$location->caution_water}}+{{$location->caution_electric}})</td>
                            <td class="text-center">{{number_format($location->frais_peiture,0," "," ") }}</td>
                            <td class="text-center bg-secondary"> <strong> {{number_format($location->caution_number*$location->loyer + $location->caution_water+$location->caution_electric + $location->frais_peiture,0,""," ") }} </strong> </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class=" shadow-lg">Totaux: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="bg-secondary"> <strong>{{number_format($totals["cautionLoyer"],0," "," ") }} </strong> </td>
                            <td class="bg-secondary"> <strong>{{number_format($totals["electricity"]+$totals["water"],0," "," ") }} </strong> </td>
                            <td class="bg-secondary"> <strong>{{number_format($totals["water"],0," "," ") }} </strong> </td>
                        </tr>
                    </tbody>
                </table>
                @else
                <p class=" text-center">Aucune location disponible!</p>
                @endif

                <br>
                <!-- SIGNATURE SESSION -->
                <div class="text-right">
                    <h5 class="" style="text-decoration: underline;">Signature du Gestionnaire de compte</h5>
                    <br>
                    <hr class="">
                    <br>
                </div>
            </div>
        </div>
    </div>
</body>

</html>