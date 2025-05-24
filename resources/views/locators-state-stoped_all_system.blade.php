<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Etats des locataires</title>

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
                        <h3 class="rapport-title text-uppercase">état de récouvrement</h3>
                    </div>
                </div>
                <br>

                <div class="col-12">
                    <h6 class="">Agence: <em class="text-red"> Toutes agences confondues </em> </h6>
                </div>
                <div class="p-3" style="border: 2px solid #000;">
                    <div class=""><strong class="">Ratio = </strong> [ Nbre de locataires ayant payés ( <em class="text-red"> {{$locators_count}} </em> )] / [ Nbre de locataires total ( <em class="text-red"> {{$total_locators_count}} </em> )] = <em class="bg-warning">{{NumersDivider($locators_count,$total_locators_count)}} % </em> </div>
                </div>

                @if(count($locataires))
                <table class="table">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Maison</th>
                            <th scope="col" class="text-center">Nom</th>
                            <th scope="col" class="text-center">Prénom</th>
                            <th scope="col" class="text-center">Email</th>
                            <th scope="col" class="text-center">Phone</th>
                            <th scope="col" class="text-center">Mois Payé</th>
                            <th scope="col" class="text-center">Date de payement</th>
                            <th scope="col" class="text-center">Montant payé </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locataires as $locataire)
                        <tr>
                            <td class="text-center bg-warning"> <strong> {{$house["name"]}}</strong> </td>
                            <td class="text-center">{{$locataire["name"]}}</td>
                            <td class="text-center">{{$locataire["prenom"]}}</td>
                            <td class="text-center">{{$locataire["email"]}}</td>
                            <td class="text-center">{{$locataire["phone"]}}</td>
                            <td class="text-center"><i class="bi bi-calendar2-check-fill"></i> <button class="btn-sm btn text-red"> {{$locataire["month"]}}</button> </td>
                            <td class="text-center"><i class="bi bi-calendar2-check-fill"></i> <button class="btn-sm btn text-red"> {{$locataire["payement_date"]}}</button> </td>
                            <td class="text-center bg-warning"> <strong> {{$locataire["amount_paid"]}} </strong> </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td class="bg-red shadow-lg">Totaux: </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="bg-warning text-center">= &nbsp;
                                <strong>
                                    @if($action=="before")
                                    {{array_sum($before_amont_total_array)}} fcfa
                                    @elseif($action=="after")
                                    {{array_sum($after_amont_total_array)}} fcfa
                                    @endif
                                </strong>
                            </td>
                        </tr>
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