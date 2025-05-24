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
            <!-- <div class="col-1"></div> -->
            <div class="col-12 shadow-lg" style="background-color: #f6f6f6!important">
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
                        <h5> <strong class="title">Superviseur</strong> :&nbsp; <strong class="text-red">{{$superviseur->name}}</strong></h5>
                        <h6> <strong class="">Total</strong> :&nbsp; <strong class="text-red">{{$locations->count()}}</strong></h6>
                    </div>
                </div>
                <br><br>
                <!-- LOCATIONS -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive table-responsive-list shadow-lg">
                            <table id="myTable" class="table table-striped table-sm">
                                <thead class="bg_dark">
                                    <tr>
                                        <!-- <th class="text-center">N°</th> -->
                                        <th class="text-center">Maison / Propriétaire</th>
                                        <!-- <th class="text-center">Superviseur</th> -->
                                        <th class="text-center">Chambre</th>
                                        <th class="text-center">Locataire</th>
                                        <th class="text-center">Dernier mois Payé</th>
                                        <th class="text-center">Loyer</th>
                                        <!-- <th class="text-center">Echéance actuelle</th> -->
                                        <th class="text-center">Echeance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($locations as $location)
                                    @php
                                    $now = strtotime(date("Y/m/d", strtotime(now())));
                                    $location_echeance_date = strtotime(date("Y/m/d", strtotime($location->echeance_date)));
                                    @endphp
                                    <tr class="align-items-center @if ($location_echeance_date < $now) bg-warning @elseif($location->status==3) bg-secondary text-white @endif ">
                                        <!-- <td class="text-center">{{$loop->index+1}} </td> -->
                                        <td class="text-center"><span class="badge bg-dark"> {{$location["House"]["name"]}} / {{$location->House->Proprietor->firstname}} {{$location->House->Proprietor->lastname}} </span></td>
                                        <!-- <td class="text-center"> <span class="text-uppercase badge bg-light text-dark">{{ $location->House->Supervisor->name }} </span> </td> -->
                                        <td class="text-center"><span class="badge {{$location->Room? 'bg-red' : 'text-dark bg-light'}}">{{$location->Room?$location->Room->number:"demenagé"}} </span> </td>
                                        <td class="text-center"><span class="text-uppercase badge bg-light text-dark">{{$location["Locataire"]["name"]}} {{$location["Locataire"]["prenom"]}} ({{$location["Locataire"]['phone']}})</span></td>

                                        <td class="text-center text-red"><small class="@if($location->status==3) text-white @endif"> <span class="badge bg-red"> <i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location["latest_loyer_date"])->locale('fr')->isoFormat('MMMM YYYY') }} </span></small> </td>
                                        <td class="text-center">{{$location["loyer"]}}</td>
                                        <td class="text-center text-red"><span class="text-uppercase badge bg-warning text-dark"><i class="bi bi-calendar2-check-fill"></i> {{ \Carbon\Carbon::parse($location["echeance_date"])->locale('fr')->isoFormat('D MMMM YYYY') }}<small class="text-dark">({{ $location->pre_paid?"PRE_PAYE":"" }} {{ $location->post_paid ? "POST_PAYE":'' }})</small></span> </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
            <!-- <div class="col-1"></div> -->
        </div>
    </div>
</body>

</html>