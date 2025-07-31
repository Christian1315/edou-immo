<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Qualitatif</title>

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
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 shadow-lg bg-light">
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
                                    <h3 class="rapport-title text-uppercase">taux de recouvrement qualitatif</h3>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>

                <div class="d-flex" style="justify-content: space-between!important; align-items: center; ">
                    <div class="">
                        @if($agency)
                        <div class="">
                            <h6 class="">Agence: <em class="text-red"> {{$agency?->name}} </em> </h6>
                        </div>
                        @endif
                        <div class="">
                            @if($action=="supervisor")
                            <h6>Superviseur: <em class="text-red"> {{$supervisor?$supervisor["name"]:""}} </em></h6>
                            @elseif($action=="house")
                            <h6>Par maison: <em class="text-red"> {{$house?$house["name"]:""}} </em></h6>
                            @endif
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-10">
                        <div class="p-3" style="border: 2px solid #000;">
                            <h6 class=""><strong class="">Ratio = </strong> [ Nbre de locataires ayant payés ( <em class="text-red"> {{count($locations)}} </em> )] / ([ Nbre de locataires ayant payé ( <em class="text-red"> {{count($locations)}} </em> )] + [ Nbre de locataires n'ayant pas payé ( <em class="text-red"> {{count($locations_that_do_not_paid)}} </em> )]) = <em class="bg-light">{{NumersDivider(count($locations),$total_of_both_of_them)}} % </em> </h6>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>
                <br>

                @if(count($locations))
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="text-center">Maison</th>
                            <th scope="col" class="text-center">Nom</th>
                            <th scope="col" class="text-center">Prénom</th>
                            <th scope="col" class="text-center">Email</th>
                            <th scope="col" class="text-center">Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $location)
                        <tr>
                            <td class="text-center bg-light"> <strong>{{$location->House?->name}}</strong></td>
                            <td class="text-center">{{$location->Locataire->name}}</td>
                            <td class="text-center">{{$location->Locataire->prenom}}</td>
                            <td class="text-center">{{$location->Locataire->email}}</td>
                            <td class="text-center">{{$location->Locataire->phone}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-red text-center">Aucun locataire disponible!</p>
                @endif

                <br>
                <!-- SIGNATURE SESSION -->
                <div class="text-right px-5">
                    <h5 class="" style="text-decoration: underline;">Gestionnaire de compte</h5>
                    <br>
                    <hr class="">
                    <br>
                </div>
            </div>
        </div>
    </div>
</body>

</html>