@extends('templates.master')
@section('title', '(+/-)')
@section("header")
    @php
        if (!$pays)
            $pays = 201;
    @endphp
    <div class="card-header">
        <div class="form-group row ">
                      <span class="title_icon">
                        <img src={{asset("images/bullet.jpg")}} alt="" title="" />
                    </span>
            <span class="title_icon" style="font-family: 'Times New Roman, Times, serif';
                                    font-size: 15px">
                        <label>L'INFORMATION L'EGAL SUR LES ENTREPRISES</label>
                    </span>
            <div class=" title_icon" style="background-color: powderblue;height: 5%;font-family: 'Times New Roman, Times, serif';
                                    font-size:large">
                <label for="">Analyse Financ&egrave;re</label> :&nbsp;&nbsp;
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.df.bilan.create', ['pays' => 24]) }}">
                    <img src={{asset("images/Benin.jpg")}} title="Benin" alt="Benin" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.df.bilan.create', ['pays' => 34]) }}">
                    <img src={{asset("images/Burkina.jpg")}} title="Burkina" alt="Burkina" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.df.bilan.create', ['pays' => 48]) }}">
                    <img src={{asset("images/Cotedivoire.jpg")}} title="Cotedivoire" alt="Cote d'ivoire"  style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{url('/bilandf?pays=81')}}">
                    <img src={{asset("images/Guinneabissao.jpg")}} title="GuineeBissau" alt="Guinnee Bissau" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.df.bilan.create', ['pays' => 134]) }}">
                    <img src={{asset("images/Mali.jpg")}} title="Mali" alt="Mali" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.df.bilan.create', ['pays' => 154]) }}">
                    <img src={{asset("images/Niger.jpg")}} title="Niger" alt="Niger" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.df.bilan.create', ['pays' => 201]) }}">
                    <img src={{asset("images/Senegal.jpg")}} title="Senegal" alt="Senegal" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.df.bilan.create', ['pays' => 223]) }}">
                    <img src={{asset("images/Togo.jpg")}} title="Togo" alt="Togo" style=""/></a>
            </div>
        </div>
    </div>
@stop
@section("forms")

    <div class="card-body">

        <form action="{{ route('entreprise.df.bilan.store', ['pays' => $pays]) }}" method="post" target="index">
            @csrf
            <div class="form-group row">
                <div class="col">

                    <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif'">
                        <strong>
                            Raison Sociale Entreprise De R&eacute;f&eacute;rence:
                        </strong>
                    </label>
                </div>

                <div class="col">

                    <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif'">
                        <strong>
                            Raison Sociale Entreprise &Aacute; Analyser:
                        </strong>
                    </label>
                </div>

                <div class="col">
                    <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times'">
                        <strong>
                            Renseigne la nature de la pr&eacute;riodicit&eacute; :
                        </strong>
                    </label>
                </div>

            </div>
            <div class="form-group row">
                <div class="col">
                    <input  style="font-family: 'Times New Roman';font-size: medium;" type="text" class="typeahead form-control" placeholder="Selectionner l'entreprise de Référence" name="idEntrepriser" required>
                    <script type="text/javascript">
                        var path = "{{ route('autocompleteEntreprise', ['pays' => $pays]) }}";
                        $('input.typeahead').typeahead({
                            source:  function (query, process) {
                                return $.get(path, { query: query }, function (data) {
                                    return process(data);
                                });
                            }
                        });
                    </script>
                </div>
                <div class="col">
                    <input  style="font-family: 'Times New Roman';font-size: medium;" type="text" class="typeahead form-control" placeholder="Selectionner une Entreprise" name="idEntreprise" required>
                    <script type="text/javascript">
                        var path = "{{ route('autocompleteEntreprise', ['pays' => $pays]) }}";
                        $('input.typeahead').typeahead({
                            source:  function (query, process) {
                                return $.get(path, { query: query }, function (data) {
                                    return process(data);
                                });
                            }
                        });
                    </script>
                </div>
                <div class="col" style="font-family: 'Times New Roman, Times, serif';
                                    font-size: 17px">
                    <label for=""><input type="radio" name="naturep" value="paran" checked> Par année</label>
                    <label for=""><input type="radio" name="naturep" value="variation"> Variation</label>
                </div>

            </div>
            <div class="form-group row">
                <div class="col">
                    <label for="" style="font-size: medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif;'">
                        <strong>
                            Renseigne le document de l'Analyse :
                        </strong>
                    </label>
                </div>

                <div class="col">
                    <label for="" style="font-size: medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif;'">
                        <strong>
                            Renseigne la Période de l'Analyse:
                        </strong>
                    </label>
                </div>

                <div class="col">
                    <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif;'">
                        <strong>
                            Faites votre analyse :
                        </strong>
                    </label>
                </div>
            </div>
            <div class="form-group row" >
                <div class="col" style="font-family: 'Times New Roman, Times, serif';font-size: 17px" >
                    <label for=""><input type="radio" name="document" value="bilan" checked>&nbsp; Bilan</label>
                    <label for=""><input type="radio" name="document" value="compres">&nbsp;Compte Resultat</label>
                </div>

                <div class="col" style="align-content: center">
                    <label for=""> Exercice 1
                        <select name="exercice1" class="form-control" style="font-family: 'Times New Roman, Times, serif';font-size: 17px;height: 8%">
                            @if($lignebilans->count() > 0)
                                @foreach($lignebilans as $lignebilan)
                                    <option value="{{$lignebilan->exercice}}">{{$lignebilan->exercice}}</option>
                                @endForeach
                            @endif
                        </select>
                    </label>&nbsp;&nbsp;&nbsp;
                    <label for=""> Exercice 2
                        <select name="exercice2" class="form-control" style="font-family: 'Times New Roman, Times, serif';font-size: 17px;height: 8%">
                            @if($lignebilans->count() > 0)
                                @foreach($lignebilans as $lignebilan)
                                    <option value="{{$lignebilan->exercice}}">{{$lignebilan->exercice}}</option>
                                @endForeach
                            @else
                                No Record Found
                            @endif
                        </select>
                    </label>
                </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary" name="ajouter" style="font-family: 'Times New Roman, Times, serif';font-size: 17px">
                            <i class="icon-ok "></i>Trouver</button>
                    </div>
            </div>
        </form>
    </div>
@stop
@section('content')
    <iframe src="" name="index" style="width: 100%; height: 900px; border-width: 0"></iframe>
@stop

