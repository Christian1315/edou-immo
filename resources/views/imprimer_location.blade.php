<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="bootstrap.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Imprimer</title>

    <style>
        * {
            font-family: "Poppins";
        }
        td{
            border: solid 1px #000!important;
        }

        td.none{
            border: none!important;
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

        /* td {
            border: 2px solid #000;
        } */

        /* td {
            border: none !important;
        } */

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
            <div class="col-12">
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
                            <tr class="">
                                <td class="text none">
                                    <img src="{{public_path('edou_logo.png')}}" alt="" style="width: 70px;" class="rounded img-fluid">
                                </td>
                                <td class="text none"></td>
                                <td class="text none">
                                    <h3 class="rapport-title text-uppercase">état de récouvrement</h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <br>

                <!-- MAISON -->
                <div class="row">
                    <div class="col-md-12">
                        <h5> <strong class="title">Maison</strong> :&nbsp; <strong class="text-red">{{$location["House"]["name"]}}</strong></h5>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Superviseur</th>
                                    <th scope="col">Propriétaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$location["House"]["name"]}}</td>
                                    <td>{{$location["House"]["Type"]["name"]}}</td>
                                    <td>{{$location["House"]["Supervisor"]["name"]}}</td>
                                    <td>{{$location["House"]["Proprietor"]["lastname"]}} {{$location["House"]["Proprietor"]["firstname"]}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br><br>


                <!-- CHAMBRE -->
                <div class="row">
                    <div class="col-md-12">
                        <h5> <strong class="title">Chambre</strong> :&nbsp; <strong class="text-red">{{$location["Room"]["number"]}}</strong></h5>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Numéro</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Loyer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$location["Room"]["number"]}}</td>
                                    <td>{{$location["Room"]["Type"]["name"]}}</td>
                                    <td>{{$location["Room"]["loyer"]}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <br><br>
                <!-- LOCATAIRE -->
                <div class="row">
                    <div class="col-md-12">
                        <h5> <strong class="title">Locataire</strong> :&nbsp; <strong class="text-red">{{$location["Locataire"]["name"]}}</strong></h5>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Prénom</th>
                                    <th scope="col">Téléphone</th>
                                    <th scope="col">Adresse mail</th>
                                    <th scope="col">Pièce d'identité</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$location["Locataire"]["name"]}}</td>
                                    <td>{{$location["Locataire"]["prenom"]}}</td>
                                    <td>{{$location["Locataire"]["phone"]}}</td>
                                    <td>{{$location["Locataire"]["email"]}}</td>
                                    <td>{{$location["Locataire"]["piece_number"]}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <br><br>
                <!-- LOCATION -->
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="title">Location :</h5>

                        <table class="table">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">Prestation</th>
                                    <th scope="col">Peiture</th>
                                    <th scope="col">Caution Eau/Electricité</th>
                                    <th scope="col">Loyer</th>
                                    <th scope="col">Nbre caution loyer</th>
                                    <th scope="col">Montant caution loyer</th>
                                    <th scope="col">Date d'intégration</th>
                                    <th scope="col">Date d'échéance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td> <strong class="text-red">{{$location["prestation"]}} fcfa</strong> </td>
                                    <td> <strong>{{$location["frais_peiture"]}} </strong> </td>
                                    <td> <button class="btn btn-sm bg-light"> <strong> {{$location["caution_water"]}} / {{$location["caution_electric"]}}</strong> </button> </td>
                                    <td> <strong class="text-red"> {{$location["loyer"]}} fcfa</strong> </td>
                                    <td>{{$location["caution_number"]}}</td>
                                    <td> <strong class="text-red">{{$location["caution_number"]*$location["loyer"]}} fcfa </strong> </td>
                                    <td>{{$location["integration_date"]}}</td>
                                    <td> <button class="btn btn-sm bg-light text-red"> <strong>{{$location["echeance_date"]}}</strong> </button> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br>
                <!-- SIGNATURE -->
                <div class="row">
                    <h5 class="signature">Signature des deux parties</h5>
                    <br><br>
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-left">Agent juridique</th>
                                <th class="text-right">Responsable juridique</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <hr>
                                </td>
                                <td>
                                    <hr>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>