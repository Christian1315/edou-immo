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
            <div class="col-10 shadow-lg bg-light">
                <!-- HEADER -->
                <div class="row header">
                    <div class="col-3">
                        <img src="{{asset('edou_logo.png')}}" alt="" style="width: 100px;" class="rounded img-fluid">
                    </div>
                    <div class="col-9 px-0 mx-0 d-flex align-items-center ">
                        <h3 class="rapport-title text-uppercase">taux de recouvrement qualitatif</h3>
                    </div>
                </div>
                <br>

                <div class="d-flex" style="justify-content: space-between!important; align-items: center; ">
                    <div class="text-center">
                        <!-- <img src="{{asset('edou_logo.png')}}" alt="" style="width: 100px;" class="img-fluid"> -->
                    </div>
                    <div class="">
                        <div class="">
                            <h6 class="">Agence: <em class="text-red"> {{$agency["name"]}} </em> </h6>
                        </div>
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
                            <h6 class=""><strong class="">Ratio = </strong> [ Nbre de locataires ayant payés ( <em class="text-red"> {{count($locations)}} </em> )] / ([ Nbre de locataires ayant payé ( <em class="text-red"> {{count($locations)}} </em> )] + [ Nbre de locataires n'ayant pas payé ( <em class="text-red"> {{count($locations_that_do_not_paid)}} </em> )]) = <em class="bg-warning">{{NumersDivider(count($locations),$total_of_both_of_them)}} % </em> </h6>
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
                            <td class="text-center bg-warning"> <strong>{{$location->House->name}}</strong></td>
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