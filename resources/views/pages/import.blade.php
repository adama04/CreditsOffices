@section('title', 'Bilan')
@php($pays = 201)
@if($dbs == 'sensyyg2_beninbd')
@php($pays = 24)
@elseif($dbs == 'sensyyg2_burkinabd')
@php($pays = 34)
@elseif($dbs == 'sensyyg2_coteivoirbd')
@php($pays = 48)
@elseif($dbs == 'sensyyg2_guinnebissaubd')
@php($pays = 81)
@elseif($dbs == 'sensyyg2_malibd')
@php($pays = 134)
@elseif($dbs == 'sensyyg2_nigerbd')
@php($pays = 154)
@elseif($dbs == 'sensyyg2_senegalbd')
@php($pays = 201)
@elseif($dbs == 'sensyyg2_togobd')
@php($pays = 223)
@endif
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Africa B.I.C - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{asset('images/Senegal.ico')}}" />
    <!-- Bootstrap -->
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{asset('css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/mdb.css')}}">
    <link rel="stylesheet" href="{{asset('css/mdb.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/mdb.lite.css')}}">
    <link rel="stylesheet" href="{{asset('css/mdb.lite.min.css')}}">
    <script type="text/javascript" src="{{ asset('js/jquery.min.js')}}"></script>
    <script src="{{url('http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js')}}"></script>
    <script src="{{url('https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js')}}"></script>
    <script src="{{url('https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')}}"></script>
    <script src="{{url('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js')}}"></script>
</head>
<body>
<div class="header">

    <div id="nav">  <!-- début menu -->

        <li>
            <a href="#">Registre de commerce</a>
            <ul>
                <li>
                    <a href="#"><span>Immatriculation</span></a>
                    <ul>
                        <li>
                            <a href="#"><span>SA</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SARL</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SNC</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SCS</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SF</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SP</span></a>
                        </li>
                        <li>
                            <a href="#"><span>G.I.E</span></a>
                        </li>
                        <li>
                            <a href="#"><span>Personnes Physiques</span></a>
                        </li>
                    </ul>
                </li>
                <li><a href="#"><span> Modification</span></a>
                    <ul>
                        <li>
                            <a href="#"><span>SA</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SARL</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SNC</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SCS</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SF</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SP</span></a>
                        </li>
                        <li>
                            <a href="#"><span>G.I.E</span></a>
                        </li>
                        <li>
                            <a href="#"><span>Personnes Physiques</span></a>
                        </li>
                    </ul>
                </li>
                <li><a href="#"><span>  Radiation</span></a>
                    <ul>
                        <li>
                            <a href="#"><span>SA</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SARL</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SNC</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SCS</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SF</span></a>
                        </li>
                        <li>
                            <a href="#"><span>SP</span></a>
                        </li>
                        <li>
                            <a href="#"><span>G.I.E</span></a>
                        </li>
                        <li>
                            <a href="#">
                                <span>Personnes Physiques</span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#"><span>D&eacute;pot des comptes</span></a>
                </li>
                <li>
                    <a href="#"><span>Formulaire et models</span></a>
                </li>
                <li>
                    <a href="#"><span>RCCM Internationaux</span></a>
                </li>
            </ul>
        </li>
        <li >
            <a href="#">Entreprsises</a>
            <ul>
                <li><a href="#"><span>ANALYSE FINACIER</span></a>
                    <ul>
                        <li>
                            <a href="#">
                                <span>Etats Finaciers</span></a>
                        </li>
                        <li>
                            <a href="#">
                                <span>Agr&eacute;gats Finaciers</span></a>
                        </li>
                        <li>
                            <a href="#">
                                <span>Agr&eacute;gats Finaciers D&eacute;taill&eacute;</span></a>
                        </li>
                        <li>
                            <a href="#">
                                <span>Comptabilit&eacute; Nationale</span></a>
                        </li>
                        <li>
                            <a href="#">
                                <span>Ratios</span></a>
                        </li>
                    </ul>
                </li>
                <li><a href="#">RECHERCHE</a></li>
                <li><a href="#">STATISTIQUES</a></li>
                <li><a href="#">ESPACE ACTEURS</a></li>
            </ul>
        </li>
        <li><a href="#">Partenaires Economiques</a>
            <ul>
                <li><a href="#">CHAMBRES DE COMMERCES </a>
                    <ul>
                        <li><a href="#">Ch-Com de Dakar</a></li>
                        <li><a href="#">Ch-Com de Diourbel</a></li>
                        <li><a href="#">Ch-Com de Fatick</a></li>
                        <li><a href="#">Ch-Com de Kaolack</a></li>
                        <li><a href="#">Ch-Com de Kédougou</a></li>
                        <li><a href="#">Ch-Com de Kolda</a></li>
                        <li><a href="#">Ch-Com de Louga</a></li>
                        <li><a href="#">Ch-Com de Matam</a></li>
                        <li><a href="#">Ch-Com de Saint Louis</a></li>
                        <li><a href="#">Ch-Com de Tambacounda</a></li>
                        <li><a href="#">Ch-Com de Thiès</a></li>
                        <li><a href="#">Ch-Com de Vélinguara</a></li>
                        <li><a href="#">Ch-Com de Ziguinchor</a></li>
                    </ul>
                </li>
                <li><a href="#">APIX</a></li>
                <li><a href="#">NOTAIRES</a></li>
                <li><a href="#">IPRES</a></li>
                <li><a href="#">CAISSE DE SECURITE SOCIALE</a></li>
            </ul>
        </li>
        <li><a href="#">Partenaires Sociaux</a>
            <ul>
                <li><a href="#">ANDS</a></li>
                <li><a href="#">CHAMBRE DES METIERS</a></li>
                <li><a href="#">MINISTERES</a>
                    <ul>
                        <li><a href="#">de l'industrie et de l'artisanat</a></li>
                        <li><a href="#">de l'emploie</a></li>
                    </ul>
                </li>
                <li><a href="#">PATRONAT</a>
                    <ul>
                        <li><a href="#">MEDES</a></li>
                        <li><a href="#">CNP</a></li>
                        <li><a href="#">CNES</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li><a href="#">Privil&eacute;ges et Nantissements</a>
            <ul>
                <li><a href="#">
                        Inscription de privilèges et Nantissements </a></li>
                <li><a href="#">
                        Rediation de Privilèges et Nantissments</a></li>
                <li><a href="#">
                        Information sur les Privilèges et Nantissements </a></li>
                <li><a href="#">Tarif des Privilèges et Nantissements</a></li>
            </ul>
        </li>
        <li>
            <a href="{{url('/index_import?pays='.$pays)}}">Chargement de la base</a>
        </li>
    </div>   <!-- fin menu -->
</div>
    <div style="align-self: center" class="container">
        <br />
        @if(count($errors) > 0)
        <div class="alert alert-danger">
            Erreur de Validation du Fichier Importé<br><br>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        @if($message = Session::get('failled'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif
        <form class="md-form" action="{{route('import',['pays' => $pays])}}" method="POST" enctype="multipart/form-data">
<!--            <p>{{$dbs}}</p>-->
            @csrf
            <div class="card" >
               <br>
                        <h1 align="center" style="font-family: 'Times New Roman';font-size: xx-large;background-color: #1a237e;color: whitesmoke">Importation Excel Pour Charger la Base </h1>
                <br>
                <div class="form-group row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        <input type="file" name="select_file" size="100" style="font-family: 'Times New Roman';" >
                    </div>
                    <div class="col-lg-1">
                        <button class="btn btn-success" type="submit" style="font-family: 'Times New Roman'; font-size: larger"><span class='glyphicon glyphicon-export' >Importer</span></button>
                    </div>
                </div>
                <br>
                <div align="center"><span  class="text-muted" style="font-size: large; font-family: 'Times New Roman';color: red;">.xls, .xslx</span></div>
            </div>
        </form>
    </div>
</body>
</html>
