<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Gestion de prorata</title>

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
                                <h3 class="rapport-title text-uppercase">états des prorata</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-left">
                    <img src="{{asset('edou_logo.png')}}" alt="" style="width: 100px;" class="img-fluid">
                    <h5 class="mt-5">
                        ETAT DE PRORATA <em class="text-red">DEPOSEE </em> <br>
                        PRORATA DU MOIS DE: <em class="text-red">{{ \Carbon\Carbon::parse($location->Locataire->prorata_date)->locale('fr')->isoFormat('MMMM YYYY') }} </em> <br>
                        MAISON: <em class="text-red"> {{$location->House->name}} </em>
                    </h5>
                </div>

                <br>
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center text-uppercase">N °</th>
                            <th scope="col" class="text-center text-uppercase">Nom</th>
                            <th scope="col" class="text-center text-uppercase">Prénom</th>
                            <th scope="col" class="text-center text-uppercase">Nature de la location</th>
                            <th scope="col" class="text-center text-uppercase">Loyer mensuel</th>
                            <th scope="col" class="text-center text-uppercase">Montant du prorata</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-center">{{$location->Locataire->name}}</td>
                            <td class="text-center bg-warning">{{$location->Locataire->prenom}}</td>
                            <td class="text-center">{{$location->Type->name}}</td>
                            <td class="text-center"><strong>{{number_format($location->loyer,0," "," ")}} </strong> </td>
                            <td class="text-center"><strong>{{number_format($location->prorata_amount,0," "," ") }} </strong> </td>
                            <!-- <td class="text-center"> <strong class="d-block"></td>
                            <td class="text-center bg-warning"> <strong>  </strong> </td> -->
                        </tr>


                        <tr>
                            <td colspan="5" class="text-center">TOTAL</td>
                            <td class="bg-warning"> <strong>{{number_format($location->loyer,0," "," ")}} </strong> </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center">COMMISION</td>
                            <td class="bg-warning"> <strong>{{number_format(($location->loyer*$location->commission_percent)/100,0," "," ") }} </strong> </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-center">NET A PAYER</td>
                            <td class="bg-warning"> <strong>{{number_format($location->loyer + ($location->loyer*$location->commission_percent)/100,0," "," ")}} </strong> </td>
                        </tr>

                    </tbody>
                </table>

                <br>
                <p class="text-center">
                    LE DIRECTEUR <br><br><br>

                    CHRISTIAN VIGAN 
                </p>
            </div>
        </div>
    </div>
</body>

</html>