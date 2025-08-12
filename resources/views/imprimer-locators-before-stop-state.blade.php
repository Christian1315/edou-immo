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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 shadow-lg" style="background-color: #f6f6f6!important">
                <!-- HEADER -->
                <div class="row _header">
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
                                    <h3 class="rapport-title text-uppercase">Locataires <span class="text-red">ayant payés avant</span> arrêt des états</h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
                <br><br>

                <!-- MAISON -->
                <div class="row">
                    <div class="col-md-12">
                        @if($gestionnaire)
                        <h5> <strong class="title">Gestionnaire</strong> :&nbsp; <strong class="text-red">{{$gestionnaire->name}}</strong></h5>
                        @endif

                        @if($supervisor)
                        <h6> <strong class="title">Superviseur</strong> :&nbsp; <strong class="text-red">{{$supervisor?->name}}</strong></h6>
                        @endif
                    </div>
                </div>
                <br><br>

                <h4 class="">Total: <strong class="text-red"> {{$locators->count()}} </strong> </h4>
                <h4 class="">Montant total: <strong class="text-red"> {{number_format($locators->sum("amount_paid"),2,"."," ")}} </strong> FCFA </h4>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive shadow-lg p-3">
                            <table id="myTable" class="table table-striped table-sm p-3">
                                <thead class="bg_dark">
                                    <tr>
                                        <th class="text-center">N°</th>
                                        <th class="text-center">Nom/Prénom</th>
                                        <th class="text-center">Maison</th>
                                        <th class="text-center">Superviseur</th>
                                        <th class="text-center">Montant payé</th>
                                        <th class="text-center">Loyer</th>
                                        <th class="text-center">Date de payement</th>
                                        <th class="text-center">Date arrêt d'état</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locators as $locator)
                                    <tr class="align-items-center">
                                        <td class="text-center">{{$loop->iteration}}</td>
                                        <td class="text-center text-red"> <span class=" bg-light text-dark">{{$locator->name}} </span> </td>
                                        <td class="text-center text-red"> <span class=" bg-light text-dark">{{$locator->house_name}} </span> </td>
                                        <td class="text-center text-red"> <span class=" bg-light text-dark">{{$locator->supervisor}} </span> </td>
                                        <td class="text-center"> <span class=" bg-light text-dark">{{number_format($locator->amount_paid,2,"."," ")}} FCFA </span> </td>
                                        <td class="text-center"> <span class=" bg-light text-success">{{number_format($locator->loyer,2,"."," ")}} FCFA </span> </td>
                                        <td class="text-center"> <span class=" bg-light text-dark">{{\Carbon\Carbon::parse($locator->payement_date)->locale('fr')}} </span> </td>
                                        <td class="text-center"> <span class=" bg-light text-red">{{\Carbon\Carbon::parse($locator->last_state_date)->locale('fr')}} </span> </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>