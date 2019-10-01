@extends('templates.master')
@section('title', '(+/-)')
@section("header")
    @php
        if (!$pays)
            $pays = 201;
    @endphp
@endsection
@section('forms')
    <div class="card-body">
        <form action="{{ route('sa.poste.bilan.store', ['pays' => $pays]) }}" method="post" target="index">
            @csrf
            <div class="form-group row">
                <div class="col">
                    <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif'">
                        <strong>
                            Renseigne le Secteur D&apos;Analyse :
                        </strong>
                    </label>
                </div>
                <div class="col">
                    <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times'">
                        <strong>
                            Renseigne la nature :
                        </strong>
                    </label>
                </div>
                <div class="col">
                    <label for="" style="font-size: medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif;'">
                        <strong>
                            Renseigne la Rubirique d'Analyse:
                        </strong>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col">
                    <input style="font-family: 'Times New Roman, Times, serif';font-size: 17px;" type="text"
                           class="typeahead form-control" placeholder="Selectionner le Secteur" name="secteur" required >
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
                    <select name="nature" class="form-control" style="font-family: 'Times New Roman, Times, serif';">
                        @if($natures->count() > 0)
                            @foreach($natures as $nature)
                                <option value="{{$nature->nature}}">{{ strtoupper($nature->nature) }}</option>
                            @endForeach
                        @endif
                    </select>
{{--                    <input style="font-family: 'Times New Roman';font-size: 14px;" type="text"--}}
{{--                           class="typeahead form-control" placeholder="Selectionner la Nature" name="nature" required>--}}
                </div>
                <div class="col">
                    <select name="poste" class="form-control"
                            style="font-family: 'Times New Roman, Times, serif';">
                        @if($postes->count() > 0)
                            @foreach($postes as $poste)
                                <option value="{{$poste->idRubrique}}">{{$poste->nomRubrique}}</option>
                            @endForeach
                        @endif
                    </select>
{{--                    <input style="font-family: 'Times New Roman';font-size: 14px;" type="text"--}}
{{--                           class="typeahead form-control" placeholder="Selectionner Une Rubrique" name="poste" required>--}}
                </div>
            </div>
            <div class="form-group row">
                <div class="col">
                    <label for="" style="font-size: medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif;'">
                        <strong>
                            Préciser L'Etendue d'Analyse :
                        </strong>
                    </label>
                </div>
                <div class="col">
                    <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif;'">
                        <strong>
                            Renseigne la Periodicité d'Analyse :
                        </strong>
                    </label>
                </div>

                <div class="col">
                    <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif;'">
                        <strong>
                            Renseigne la Periode d'Analyse :
                        </strong>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col" style="font-family: 'Times New Roman, Times, serif';font-size: 17px">
                    <label for=""><input type="radio" name="localite" value="pays" checked>
                        @if($pays == 24) {{'BENIN'}}  @endif
                        @if($pays == 34) {{'BURKINA'}}    @endif
                        @if($pays == 48) {{'COTE D\'IVOIR'}}  @endif
                        @if($pays == 81) {{'GUINNE BISSAU'}}    @endif
                        @if($pays == 134) {{'MALI'}}  @endif
                        @if($pays == 154) {{'NIGER'}}    @endif
                        @if($pays == 201) {{'SENEGAL'}}  @endif
                        @if($pays == 223) {{'TOGO'}}    @endif
                    </label>
                    <label for=""><input type="radio" name="localite" value="group">&nbsp; GROUPE</label>
                    <label for=""> <input type="radio" name="localite" value="uemoa">&nbsp; UMEOA</label>
                </div>
                <div class="col" style="font-family: 'Times New Roman, Times, serif';
                                    font-size: 17px">
                    <label for=""><input type="radio" name="naturep" value="paran" checked> Par année</label>
                    <label for=""><input type="radio" name="naturep" value="variation"> Variation</label>
                </div>
                <div class="col">
                    <label for=""> Exercice 1
                        <select name="exercice1" class="form-control"
                                style="font-family: 'Times New Roman, Times, serif';font-size: 17px;height: 8%">
                            @if($lignebilans->count() > 0)
                                @foreach($lignebilans as $lignebilan)
                                    <option value="{{$lignebilan->exercice}}">{{$lignebilan->exercice}}</option>
                                @endForeach
                            @endif
                        </select>
                    </label>
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
            </div>
            <div class="form-group row">
                <div class="col col-md-5 col-sm-8">
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
