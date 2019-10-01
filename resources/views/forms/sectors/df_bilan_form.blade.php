@extends('templates.master')
@section('title', '(+/-)')
@section("header")
    @php
        if (!$pays)
            $pays = 201;
    @endphp
@stop

@section("forms")

    <div class="card-body">
        <form action="{{ route('sa.df.bilan.store', ['pays' => $pays]) }}" method="post" target="index">
            @csrf
            <div class="form-group row">
                <div class="col">
                    <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times'">
                        <strong>
                            Renseigne le Secteur D'Analyse :
                        </strong>
                    </label>
                </div>
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
            </div>
            <div class="form-group row">
                <div class="col">
                    <input style="font-family: 'Times New Roman';font-size: medium;" type="text"
                           class="typeahead form-control" placeholder="Selectionner le Secteur D'analyse"
                           name="secteur" required>
                    <script type="text/javascript">
                        var path = "{{ route('autocompleteSector', ['pays' => $pays]) }}";
                        $('input.typeahead').typeahead({
                            source: function (query, process) {
                                return $.get(path, {query: query}, function (data) {
                                    return process(data);
                                });
                            }
                        });
                    </script>
                </div>
                <div class="col">
                    <input style="font-family: 'Times New Roman';font-size: medium;" type="text"
                           class="typeahead form-control" placeholder="Selectionner L'Entreprise De Référence" name="idEntrepriser"
                           required>
                </div>
                <div class="col">
                    <input style="font-family: 'Times New Roman';font-size: medium;" type="text"
                           class="typeahead form-control" placeholder="Selectionner L'Entreprise D'Analyse" name="idEntreprise"
                           required>
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
                            Renseigner la Periodicité :
                        </strong>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col" style="font-family: 'Times New Roman, Times, serif';font-size: 17px">
                    <label for=""><input type="radio" name="document" value="bilan" checked>&nbsp; Bilan</label>
                    <label for=""><input type="radio" name="document" value="compres">&nbsp;Compte Resultat</label>
                </div>
                <div class="col" style="align-content: center">
                    <label for=""> Exercice 1
                        <select name="exercice1" class="form-control"
                                style="font-family: 'Times New Roman, Times, serif';font-size: 17px;height: 8%">
                            @if($lignebilans->count() > 0)
                                @foreach($lignebilans as $lignebilan)
                                    <option value="{{$lignebilan->exercice}}">{{$lignebilan->exercice}}</option>
                                @endForeach
                            @endif
                        </select>
                    </label>&nbsp;&nbsp;&nbsp;
                    <label for=""> Exercice 2
                        <select name="exercice2" class="form-control"
                                style="font-family: 'Times New Roman, Times, serif';font-size: 17px;height: 8%">
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
                <div class="col" style="font-family: 'Times New Roman, Times, serif';
                                    font-size: 17px">
                    <label for=""><input type="radio" name="naturep" value="paran" checked> Par année</label>
                    <label for=""><input type="radio" name="naturep" value="variation"> Variation</label>
                </div>
            </div>
            <div class="form-group">
                <div class="col col-md-5 col-sm-6">

                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary" name="ajouter"
                            style="font-family: 'Times New Roman, Times, serif';font-size: 17px">
                        <i class="icon-ok "></i>Trouver
                    </button>
                </div>
            </div>
        </form>
    </div>
@stop
@section('content')
    <iframe src="" name="index" style="width: 100%; height: 900px; border-width: 0"></iframe>
@stop
