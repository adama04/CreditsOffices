
{{-- # Form for entreprise--}}

<div class="card-body">

@csrf
<div class="form-group row">
    <div class="col">

        <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif'">
            <strong>
                Renseigne la Raison Sociale l'Entreprise :
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
    <div class="col">
        <label for="" style="font-size: medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif;'">
            <strong>
                Renseigne la Période de l'Analyse:
            </strong>
        </label>
    </div>
</div>
<div class="form-group row">
    <div class="col">
        <input  style="font-family: 'Times New Roman';font-size: 14px;" type="text" class="typeahead form-control" placeholder="Selectionner une entreprise" name="idEntreprise" required>
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
    <div class="col" >
        <label for=""> Exercice 1
            <select name="exercice1" class="form-control" style="font-family: 'Times New Roman, Times, serif';font-size: 17px;height: 8%">
                @if($lignebilans->count() > 0)
                    @foreach($lignebilans as $lignebilan)
                        <option value="{{$lignebilan->exercice}}">{{$lignebilan->exercice}}</option>
                    @endForeach
                @endif
            </select>
        </label>
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
        <label for="" style="font-size:medium;color: #0355AF;
                            font-weight: bold;font-family: 'Times New Roman, Times, serif;'">
            <strong>
                Renseigne l'espace communautaire de l'analyse :
            </strong>
        </label>
    </div>
</div>
<div class="form-group row" >
    <div class="col" style="font-family: 'Times New Roman, Times, serif';font-size: 17px" >
        <label for=""><input type="radio" name="document" value="bilan" checked>&nbsp; Bilan</label>
        <label for=""><input type="radio" name="document" value="compres">&nbsp;Compte Resultat</label>
    </div>
    <div class="col"  style="font-family: 'Times New Roman, Times, serif';font-size: 17px">
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

</div>
</div>
