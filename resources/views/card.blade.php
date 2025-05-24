<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('logo.jpeg')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{asset('bootstrap.css')}}">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <title>CCIB</title>

    <style>
        * {
            font-family: "Poppins";
        }

        .info_block {
            border: 1px solid #000;
            margin-block: 10px;
        }

        .logo {
            height: 60px;
            width: 100%;
            margin-top: 20px;
        }

        p.sub-title {
            font-size: 20px;
            margin-bottom: 10px !important;
            font-size: bold;
        }

        .title {
            font-size: 25px;
            background-color: #e4540c;
            color: #fff;
            padding: 6px;
        }

        #body {
            background-repeat: no-repeat;
            background-position: center;
        }


        p.mandate-title {
            margin-top: 10px;
            font-weight: bold;
            font-size: 20px;
        }

        p.mandate-title em {
            color: #e4540c !important;
        }

        #header {
            border-bottom: solid 1px #000;
            align-items: center;
            margin-block: 10px;
        }

        #card-body,
        #card-body2 {
            /* padding-top: 10px; */
            height: 350px !important;
        }

        #card-body2 {
            /* padding-block: 80px; */
            /* height: 350px!important; */
        }

        #card-body2 p {
            font-size: 25px;
            font-weight: 500;
        }

        #card-body .reference {
            background-color: rgb(24, 95, 44);
            text-align: center;
            color: #fff;
            width: 100%;
        }

        #card-body .avatar {
            width: 100%;
            height: 250px;
            border-radius: 10px;
            border: solid 5px rgb(24, 95, 44);
        }

        ul li {
            /* list-style-type: none; */
        }

        ul.second li {
            list-style-type: none;
            font-size: 16px;
            margin-block: 1px;
        }

        #footer {
            background-color: #e4540c !important;
            color: #fff !important;
            margin-top: 20px;
            border-radius: 0px 0px 10px 10px;
            /* position:absolute; */
            /* bottom: 0px!important; */
        }

        #card {
            /* border: #000 solid 2px; */
            border-radius: 10px;
            background-image: url("{{asset('bg-card.png')}}");
        }

        .block-title span {
            color: #fff;
            padding: 5px 15px;
        }

        .block-title .span2 {
            background-color: #e4540c;
        }

        .block-title .span1 {
            background-color: rgb(24, 95, 44);
        }

        .block-title {
            margin-bottom: -10px !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid my-5">
        <div class="row" id="body">
            <!-- <div class="col-2"></div> -->
            <div class="col-12" id="card">
                <div class="row px-0" id="header">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-2">
                                <img src="{{asset('logo-card.png')}}" class="logo" alt="">
                            </div>
                            <div class="col-8 text-center">
                                <p class="sub-title">République du Bénin</p>
                                <span class="title">CARTE D'IDENTITE CONSULAIRE</span> <br>
                                <p class="mandate-title">Mandature <em class=""> 2023 - 2024</p>
                            </div>
                            <div class="col-2">
                                <!-- <img src="{{asset('drapeau.png')}}" class="logo" alt=""> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row px-5" id="card-body">
                    <div class="col-4" style="align-items: center;">
                        <h4 class="reference mt-3">N° XXXXXXX </h4>
                        <img src="{{asset('drapeau.png')}}" class="avatar shadow-lg" alt="avatar" srcset="">
                    </div>
                    <div class="col-8">

                        <!-- INFO ELU CONSULAIRE -->
                        <div class="text-center block-title">
                            <span class="span1">Informations personnelles de l'Elu consulaire</span>
                        </div>
                        <div class="row info_block" id="consular_info">
                            <div class="col-6">
                                <ul class="first">
                                    <li>Nom /Name</li>
                                    <li>Prénom(s) /Surname</li>
                                    <li>Télephone /Phone</li>
                                    <li>Email /Email</li>
                                    <li>Poste Occupé /Position :</li>
                                    <li>Mandature /Mandate:</li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="second">
                                    <li> <strong>Firstname </strong></li>
                                    <li> <strong>Lastname </strong></li>
                                    <li> <strong>22961765590 </strong></li>
                                    <li> <strong>gogochristian009@gmail.com</strong></li>
                                    <li> <strong>DG </strong></li>
                                    <li> <strong>Mandature 1 </strong></li>
                                </ul>
                            </div>
                        </div>

                        <!-- INFO ENTREPRISE -->
                        <div class="text-center block-title">
                            <span class="span2">Informations de l'entreprise representée</span>
                        </div>
                        <div class="row info_block" id="company_info">
                            <div class="col-6">
                                <ul class="first">
                                    <li>Dénomination /Denomination</li>
                                    <li>Forme Juridique /Legal status</li>
                                    <li>Secteur d'activité /Area</li>
                                    <!-- <li>Activité Principale /Main</li> -->
                                    <li>Fonction /Function</li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="second">
                                    <li> <strong>Finanfa </strong></li>
                                    <li> <strong>Sociale </strong></li>
                                    <li> <strong>HSMC </strong></li>
                                    <!-- <li> <strong>Comptabilité</strong></li> -->
                                    <li> <strong>Développeur web</strong></li>
                                </ul>
                            </div>
                        </div>
                        <!--  -->
                    </div>
                </div>

                <div class="row" id="footer">
                    <div class="col-md-12">
                        <h4 class="text-center">Validité: <em class=""> 2023 </em> - <em class=""> 2024</em></h4>
                    </div>
                </div>
            </div>
            <!-- <div class="col-2"></div> -->
        </div>

        <br><br><br><br><br>
        <!-- LE DERRIERE DE LA CARTE -->
        <div class="row" id="body">
            <!-- <div class="col-2"></div> -->
            <div class="col-12" id="card">
                <div class="row px-0" id="header">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-4">
                            </div>
                            <div class="col-2 text-center">
                                <img src="{{asset('logo-card.png')}}" style="width: 100; height: 100px;" alt="">
                            </div>
                            <div class="col-4">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="card-body2">
                    <div class="col-12">
                        <p class="text-center">
                            <strong>NB:</strong>
                            Cette Carte est strictement personnelle. En cas de perte,
                            Veuillez vous rapprochez du bureau de la CCIB.
                        </p>
                        <p class="text-center">
                            <strong>Contact </strong> +22991434343/21312081 <br>
                            <strong>Email: </strong> info@ccib.bj
                        </p>
                        <p class="">
                        <h3 class="text-center">Le président de la CCIB</h3>
                        <h4 class="text-center reference"><strong>Arnauld AKAKPO</strong></h4>
                        </p>
                    </div>
                </div>
                <div class="row" id="footer">
                    <div class="col-md-12">
                        <h4 class="text-center">Validité: <em class=""> 2023 - 2024</h4>
                    </div>
                </div>
            </div>
            <!-- <div class="col-2"></div> -->
        </div>
    </div>
</body>

</html>