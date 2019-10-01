<div class="form-group row">
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                <form action="{{route('export')}}" method="POST" enctype="multipart/form-data">
@csrf
<div class="form-group row ">
    <div class="col-md-2">
        <button class="btn btn-success" type="submit" style="font-family: 'Times New Roman';font-size: large;"><span class='glyphicon glyphicon-export' ></span>Exporter Excel</button>
    </div>
    <div class="form-group row" hidden="hidden">
        <div class="col">
            <input  style="font-family: 'Times New Roman';font-size: medium;" type="text" class="typeahead form-control" placeholder="Selectionner une entreprise" name="idEntreprise" value="{{ $input['idEntreprise'] }}">
        </div>
        <div class="col">
            <input  style="font-family: 'Times New Roman';font-size: medium;" type="text" class="form-control" placeholder="Selectionner une entreprise" name="exercice1" value="{{ $input['exercice1'] }}">
        </div>
        <div class="col">
            <input  style="font-family: 'Times New Roman';font-size: medium;" type="text" class="form-control" placeholder="Selectionner une entreprise" name="exercice2" value="{{ $input['exercice2'] }}">
        </div>
        <div class="col" style="font-family: 'Times New Roman, Times, serif';
                                        font-size: 17px">
            <label for=""><input type="radio" name="naturep" value="paran" checked> Par année</label>
            <label for=""><input type="radio" name="naturep" value="variation"> Variation</label>
        </div>
    </div>
    <div class="form-group row" hidden="hidden" >
        <div class="col" style="font-family: 'Times New Roman, Times, serif';font-size: 17px" >
            <label for=""><input type="radio" name="document" value="bilan" checked>&nbsp; Bilan</label>
            <label for=""><input type="radio" name="document" value="compres">&nbsp;Compte Resultat</label>
        </div>
        <div class="col"  style="font-family: 'Times New Roman, Times, serif';font-size: 17px">
            <label for=""><input type="radio" name="localite" value="sensyyg2_senegalbd" checked>SENEGAL</label>
            <label for=""><input type="radio" name="localite" value="sensyyg2_umeoabd1">&nbsp; GROUPE</label>
            <label for=""> <input type="radio" name="localite" value="sensyyg2_umeoabd">&nbsp; UMEOA</label>
        </div>

    </div>
</div>
</form>
</div>
<div class="col-md-3">
    <form action="{{route('export_pdf')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group row col-md-12">
            <div class="col-md-2">
                <button class="btn btn-primary" type="submit" style="font-family: 'Times New Roman';font-size: large;"><span class='glyphicon glyphicon-export' ></span>Exporter PDF</button>
            </div>
            <div class="form-group row" hidden="hidden">
                <div class="col">
                    <input  style="font-family: 'Times New Roman';font-size: medium;" type="text" class="typeahead form-control" placeholder="Selectionner une entreprise" name="idEntreprise" value="{{ $input['idEntreprise'] }}">
                </div>
                <div class="col">
                    <input  style="font-family: 'Times New Roman';font-size: medium;" type="text" class="form-control" placeholder="Selectionner une entreprise" name="exercice1" value="{{ $input['exercice1'] }}">
                </div>
                <div class="col">
                    <input  style="font-family: 'Times New Roman';font-size: medium;" type="text" class="form-control" placeholder="Selectionner une entreprise" name="exercice2" value="{{ $input['exercice2'] }}">
                </div>
                <div class="col" style="font-family: 'Times New Roman, Times, serif';
                                    font-size: 17px">
                    <label for=""><input type="radio" name="naturep" value="paran" checked> Par année</label>
                    <label for=""><input type="radio" name="naturep" value="variation"> Variation</label>
                </div>
            </div>
            <div class="form-group row" hidden="hidden" >
                <div class="col" style="font-family: 'Times New Roman, Times, serif';font-size: 17px" >
                    <label for=""><input type="radio" name="document" value="bilan" checked>&nbsp; Bilan</label>
                    <label for=""><input type="radio" name="document" value="compres">&nbsp;Compte Resultat</label>
                </div>
                <div class="col"  style="font-family: 'Times New Roman, Times, serif';font-size: 17px">
                    <label for=""><input type="radio" name="localite" value="sensyyg2_senegalbd" checked>SENEGAL</label>
                    <label for=""><input type="radio" name="localite" value="sensyyg2_umeoabd1">&nbsp; GROUPE</label>
                    <label for=""> <input type="radio" name="localite" value="sensyyg2_umeoabd">&nbsp; UMEOA</label>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
