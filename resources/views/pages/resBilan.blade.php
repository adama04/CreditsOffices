<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
@if($input['exercice1'] > $input['exercice2'])
    @php
        $exercice1 = $input['exercice2'];
        $exercice2 = $input['exercice1'];
    @endphp
@else
    @php($exercice1 = $input['exercice1'])
    @php($exercice2 = $input['exercice2'])
@endif
<div class="container">
    <div class="card">
        <div class="card-body">
            <div class="container">
            @foreach($infoEntreprises as $infoEntreprise )
            <div class="form-row" style="font-family: 'Times New Roman'; color: #0355AF; font-size:medium">
                <div class="col-md-6">
                    Numero Registre :
                    <span>{{$infoEntreprise->numRegistre}}</span>
                </div>
                <div class="col-md-6">
                    Secteur :
                    <span>{{$infoEntreprise->nomSecteur}}</span>
                </div>
            </div>
                <div class="form-row" style="font-family: 'Times New Roman'; color: #0355AF; font-size: medium">
                    <div class="col-md-6">
                        Raison Sociale :
                        <span>{{$infoEntreprise->nomEntreprise}}</span>
                    </div>
                    <div class="col-md-6">
                        Activité principal :
                        <span>{{$infoEntreprise->nomsouSecteur}}</span>
                    </div>
            </div>
                <div class="form-row" style="font-family: 'Times New Roman'; color: #0355AF; font-size: medium">
                    <div class="col-md-6">
                        Adresse :
                        <span>{{$infoEntreprise->Adresse}}</span>
                    </div>
                    <div class="col-md-6">
                        Services :
                        <span>{{$infoEntreprise->nomService}}</span>
                    </div>
                </div>
            @endforeach
            </div>
            <form action="{{route('import')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-4">
                        <input type="file" name="file" class="form-control" style="font-family: 'Times New Roman';font-size: larger ;" >
                    </div>
                    <div class="col-md-2">
                    <button  class="btn btn-success" style="font-family: 'Times New Roman';font-size: larger;"><span class='glyphicon glyphicon-import'></span>Importer </button>
                    </div>
                    <div class="col-md-2">
                    <a href="{{route('export')}}" class="btn btn-warning" style="font-family: 'Times New Roman';font-size: larger;"><span class='glyphicon glyphicon-export' ></span>Exporter</a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('export_pdf') }}" class="btn btn-success" style="font-family: 'Times New Roman';font-size: larger;">Export PDF</a></div>
                </div>
            </form>
        </div>
    </div>
@if($exercice2 - $exercice1 >= 5)
    <div class="alert alert-danger container">
        {{ 'Periode trop grand choisir sur 5 ans' }}
    </div>
@else
    <table class="table table-condensed"></table>
</div>
@endif

