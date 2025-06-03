<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Prestation statistique</title>

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

        td {
            border: 2px solid #000;
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 shadow-lg bg-light">
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
                                    <img src="{{asset('edou_logo.png')}}" alt="" style="width: 70px;" class="rounded img-fluid">
                                </td>
                                <td class="text"></td>
                                <td class="text"></td>
                                <td class="text"></td>
                                <td class="text">
                                    <h3 class="rapport-title text-uppercase">états des prestations</h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>

                <div class="text-center">
                    <h4 class="">Agence: <em class=""> {{$agency["name"]}} </em> </h4>
                </div>

                @if(count($locations))
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">Maison</th>
                            <th scope="col" class="text-center">Chambre</th>
                            <th scope="col" class="text-center">Locataire</th>
                            <th scope="col" class="text-center">Prestation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $location)
                        <tr>
                            <td class="text-center">{{$location->House->name}}</td>
                            <td class="text-center">{{$location->Room?$location->Room->number:"demenagé"}}</td>
                            <td class="text-center">{{$location->Locataire->name}} {{$location->Locataire->prenom}}</td>
                            <td class="text-center bg-secondary">{{$location->prestation}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="bg-secondary shadow-lg">Totaux: </td>
                            <td></td>
                            <td></td>
                            <td class="bg-secondary text-center">= &nbsp; <strong>{{$locations->sum('prestation')}} fcfa</strong> </td>
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