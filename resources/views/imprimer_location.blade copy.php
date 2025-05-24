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
    <div class="container">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10 shadow-lg" style="background-color: #f6f6f6!important">
                <!-- HEADER -->
                <div class="row header">
                    <div class="col-3">
                        <img src="{{asset('edou_logo.png')}}" alt="" style="width: 100px;" class="rounded img-fluid">
                    </div>
                    <div class="col-9 px-0 mx-0 d-flex align-items-center ">
                        <h3 class="rapport-title text-uppercase">état de récouvrement</h3>
                    </div>
                </div>
                <br>

                <!-- MAISON -->
                <div class="row">
                    <div class="col-md-12">
                        <h5> <strong class="title">Maison</strong> :&nbsp; <strong class="text-red">{{$location["House"]["name"]}}</strong></h5>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">N°</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Superviseur</th>
                                    <th scope="col">Propriétaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">{{$location["House"]["id"]}}</th>
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
                                    <th scope="col">N°</th>
                                    <th scope="col">Numéro</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Loyer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">{{$location["Room"]["id"]}}</th>
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
                                    <th scope="col">N°</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Prénom</th>
                                    <th scope="col">Téléphone</th>
                                    <th scope="col">Adresse mail</th>
                                    <th scope="col">Pièce d'identité</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">{{$location["Locataire"]["id"]}}</th>
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
                                    <th scope="col">ID</th>
                                    <th scope="col">Prestation</th>
                                    <th scope="col">Peiture</th>
                                    <th scope="col">Caution Eau/Electricité</th>
                                    <th scope="col">Loyer</th>
                                    <th scope="col">Nbre caution loyer</th>
                                    <th scope="col">Montant caution loyer</th>
                                    <th scope="col">Date d'intégration</th>
                                    <!-- <th scope="col">Dernier loyer payé</th> -->
                                    <th scope="col">Date d'échéance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <th scope="row">{{$location["id"]}}</th>
                                    <td> <strong class="text-red">{{$location["prestation"]}} fcfa</strong> </td>
                                    <td> <strong>{{$location["frais_peiture"]}} </strong> </td>
                                    <td> <button class="btn btn-sm bg-light"> <strong> {{$location["caution_water"]}} / {{$location["caution_electric"]}}</strong> </button> </td>
                                    <td> <strong class="text-red"> {{$location["loyer"]}} fcfa</strong> </td>
                                    <td>{{$location["caution_number"]}}</td>
                                    <td> <strong class="text-red">{{$location["caution_number"]*$location["loyer"]}} fcfa </strong> </td>
                                    <td>{{$location["integration_date"]}}</td>
                                    <!-- <td>{{$location["latest_loyer_date"]}}</td> -->
                                    <td> <button class="btn btn-sm bg-light text-red"> <strong>{{$location["echeance_date"]}}</strong> </button> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <br><br><br><br><br>
                <!-- SIGNATURE -->
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="signature">Signature des deux parties</h5>
                        <br><br>
                        <table class="table">
                            <thead>
                                <tr class="d-flex justify-content-between">
                                    <th scope="col" class="text-left">Agent juridique</th>
                                    <th scope="col" class="text-right">Responsable juridique</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="d-flex justify-content-between">
                                    <td class="w-50">
                                        <hr>
                                    </td>
                                    <td class="w-50">
                                        <hr>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br><br><br><br><br>
            </div>
            <div class="col-1"></div>
        </div>


    </div>

</body>

</html>