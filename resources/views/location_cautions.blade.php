<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Gestion de cautions</title>
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

        .text-dark {
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
                                <h3 class="rapport-title text-uppercase">etat de caution</h3>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br>
        </div>

        <div class="row mt-5 px-3">
            <table class="table table-striped table-sm">
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">Maison</th>
                            <th scope="col" class="text-center">Chambre</th>
                            <th scope="col" class="text-center">Locataire</th>
                            <th scope="col" class="text-center">Date d'integration</th>
                            <th scope="col" class="text-center">Caution Loyer</th>
                            <th scope="col" class="text-center">Caution Eau/Électricité</th>
                            <th scope="col" class="text-center">Frais de peinture</th>
                            <th scope="col" class="text-center">Totaux(fcfa) </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{$location->House->name}}</td>
                            <td class="text-center">{{$location->Room?$location->Room->number:'---'}}</td>
                            <td class="text-center">{{$location->Locataire->name}} {{$location->Locataire->prenom}} ({{$location->Locataire->phone}})</td>
                            <td class="text-center text-dark"><strong class="text-dark"> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location->integration_date)->locale('fr')->isoFormat('D MMMM YYYY') }}</strong> </td>
                            <td class="text-center"> <strong class="d-block">{{number_format($cautions['loyer'],0," "," ")}} </strong> ({{$location->caution_number}}X{{$location->loyer}})</td>
                            <td class="text-center">{{number_format($cautions['eau'] + $cautions['electricite'],0," "," ")}} ({{$cautions['eau']}}+{{$cautions['electricite']}})</td>
                            <td class="text-center bg-light text-dark">{{number_format($location->frais_peiture,0," "," ") }}</td>
                            <td class="text-center bg-light text-dark"> <strong> {{number_format($total_cautions + $location->frais_peiture,0,""," ") }} </strong> </td>
                        </tr>

                        <tr>
                            <td class="bg-secondary text-white shadow-lg">Totaux: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="bg-light text-dark text-center"> <strong>{{number_format($cautions['loyer'],0," "," ")}} </strong> </td>
                            <td class="bg-light text-dark text-center"> <strong>{{number_format($cautions['eau'] + $cautions['electricite'],0," "," ")}} </strong> </td>
                            <td class="bg-light text-dark text-center"> <strong>{{number_format($location->frais_peiture,0," "," ")}} </strong> </td>
                            <td class="bg-light text-dark text-center"> </td>
                        </tr>

                    </tbody>
                </table>
            </table>
        </div>
        <br><br><br>

        <!-- BORDEREAU DE CAUTION -->
        <div class="row" class="@if(!$location->caution_bordereau) d-none @endif">
            <div class="col-12 text-center">
                <img src="{{$location->caution_bordereau}}" title="Bordereau caution" class="shadow" style="border:none;border-radius:10px" width="500px" height="500px" alt="" srcset="">
            </div>
        </div>

        <br>
        <h3 class="text-center">
            Arrêté le présent état à la somme de <strong class="text-dark">({{nombre_en_lettres($total_cautions + $location->frais_peiture)==""?'zéro':nombre_en_lettres($total_cautions + $location->frais_peiture) }} Fcfa)</strong>
        </h3>

        <br>
        <!-- SIGNATURE SESSION -->
        <div class="text-right">
            <h5 class="" style="text-decoration: underline;">Signature du Gestionnaire de compte</h5>
            <br>
            <hr class="">
            <br>
        </div>
    </div>
</body>

</html>