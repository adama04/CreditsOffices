@extends('templates.master')

<<<<<<< HEAD
@section('title', 'Bilan')

@section('content')
    <iframe src="" frameborder="0" name="index" class="bgiframe container" style="width: 98%; height: 900px;"></iframe>
@stop
=======
@section('nature', 'Bilan')

@section('content')
<br/>
<br/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <div class="container " id="container">
        <ul class="nav nav-pills">
            <li class="active" style=" color: white; font-size: medium; font-family: 'Times New Roman'">
                <a  href="#1" data-toggle="tab">Entreprise et son Secteur d'Activité</a>
            </li>
            <li style=" color: white; font-size: medium; font-family: 'Times New Roman'"><a href="#2" data-toggle="tab">Secteur d'Activité </a>
            </li>
        </ul>
        <div class="card">
            <div class="card-header">
                  <span class="title_icon">
                    <img src="images/bullet.jpg" alt="" title="" />
                  </span>
                <span class="title_icon">
                <h3>L'INFORMATION L'EGAL SUR LES ENTREPRISES</h3>
                  </span>
                <span class="title_icon" style="background-color: powderblue">
                <h3> Analyse Financ&egrave;re :&nbsp;&nbsp;&nbsp; </h3>
                  </span>
                <span class="title_icon">
                                  <img src="images/Benin.jpg" alt="" title=""  />
                  </span>
                <span class="title_icon">
                                  <img src="images/Burkina.jpg" alt="" title="" border="0" />
                  </span>
                <span class="title_icon">
                                  <img src="images/Cotedivoire.jpg" alt="" title="" border="0" />
                  </span>
                <span class="title_icon">
                                  <img src="images/Guinneabissao.jpg" alt="" title="" border="0" />
                  </span>
                <span class="title_icon">
                                  <img src="images/Mali.jpg" alt="" title="" border="0" />
                  </span>
                <span class="title_icon">
                                  <img src="images/Niger.jpg" alt="" title="" border="0" />
                  </span>
                <span class="title_icon">
                                  <img src="images/Senegal.jpg" alt="" title="" border="0" />
                  </span>
                <span class="title_icon">
                                  <img src="images/Togo.jpg" alt="" title="" border="0" />
                  </span>
            </div>
            <div class="card-body">
                <div class="form-group row ">
                    <div class="col-md-4">
                        <h3>Renseigne la Raison Sociale l'Entreprise :</h3>
                        <input  style="font-family: 'Times New Roman';font-size: medium;" type="text" class="typeahead form-control" placeholder="Selectionner une entreprise">
                    <script type="text/javascript">
                        var path = "{{ route('autocomplete') }}";
                        $('input.typeahead').typeahead({
                            source:  function (query, process) {
                                return $.get(path, { query: query }, function (data) {
                                    return process(data);
                                });
                            }
                        });
                    </script>
                    </div>
                    <div class="col-md-4" style="font-size: medium">
                        <h3>Renseigne le document de l'Analyse :</h3>
                        <form>
                            <label class="radio-inline">
                                <input type="radio" name="optradio" checked>Bilan
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio">Compte résultat
                            </label>
                        </form>
                    </div>
                    <div class="col-md-4" style="font-size: medium">
                        <h3>Renseigne l'espace communautaire de l'analyse:</h3>
                        <form>
                            <label class="radio-inline">
                                <input type="radio" name="optradio" checked>SENEGAL
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio">GROUPE
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio">UEMOA
                            </label>
                        </form>
                    </div>
                </div>
                <div class="form-group row ">
                    <div class="col-md-4" style="font-size: medium">
                        <h3>Renseigne la nature de la prériodicité :</h3>
                        <form>
                            <label class="radio-inline">
                                <input type="radio" name="optradio" checked>Par année
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio">Variation
                            </label>
                        </form>
                    </div>
                    <div class="col-md-4" style="font-size: medium">
                        <h3>Renseigne la Période de l'Analyse:</h3>
                        EXERCICE1:
                        <select name="exercice1">
                          @if($lignebilans->count() > 0)
                                @foreach($lignebilans as $lignebilan)
                                    <option value="{{$lignebilan->exercice}}">{{$lignebilan->exercice}}</option>
                                @endForeach
                            @else
                                No Record Found
                            @endif
                        </select>
                        EXERCICE2:
                        <select name="exercice2">
                           @if($lignebilans->count() > 0)
                                @foreach($lignebilans as $lignebilan)
                                    <option value="{{$lignebilan->exercice}}">{{$lignebilan->exercice}}</option>
                                @endForeach
                            @else
                                No Record Found
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <h3>Analyser le bilan:</h3>
                        <a href="{{--{{action('BilanController@index','bonjour')}}--}}" class="btn btn-success btn-md" style="font-size: large" title="Add New Permission">
                            <i class="fa fa-plus" aria-hidden="true"></i>Trouver
                        </a>
                    </div>
                </div>
                <div class="col">
                    Numero Registre:
                    <label type="text"></label>
                    Raison Sociale:
                    <label type="text"></label>
                    Adresse:
                    <label type="text"></label>
                </div>
                <div class="col">
                    Secteur :
                    <label type="text"></label>
                    Activité :
                    <label type="text"></label>
                    Services :
                    <label type="text"></label>
                </div>
              <div class="table-striped">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Nature</th>
                            <th>Code</th>
                            <th colspan="3">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($classes as $classe)
                            <tr>
                                <td>{{$classe->idClasse}}</td>
                            <td>{{$classe->nomClasse}}</td>
                            <td>{{$classe->nature}}</td>
                            <td>{{$classe->code}}</td>

                            <td><a href="#" class="btn btn-warning">Edit</a></td>
                            <td><a href="#" class="btn btn-danger">Delete</a></td>
                            <td><a href="#" class="btn btn-info">View</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                  {{ $classes->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
>>>>>>> 5aff8f104a6701d39d626f784f7f9aa16baea9eb
