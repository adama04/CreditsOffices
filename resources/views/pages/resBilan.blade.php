<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<div class="container">
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body">
            @foreach($infoEntreprises as $infoEntreprise )
            <div class="form-row" style="font-family: 'Times New Roman'; color: #3f9ae5; font-size: 11px">
                <div class="col-md-6">
                    Numero Registre :
                    <span>{{$infoEntreprise->numRegistre}}</span>
                </div>
                <div class="col-md-6">
                    Secteur :
                    <span>{{$infoEntreprise->nomSecteur}}</span>
                </div>
            </div>
                <div class="form-row" style="font-family: 'Times New Roman'; color: #3f9ae5; font-size: 13px">
                    <div class="col-md-6">
                        Raison Sociale :
                        <span>{{$infoEntreprise->nomEntreprise}}</span>
                    </div>
                    <div class="col-md-6">
                        Activité principal :
                        <span>{{$infoEntreprise->nomsouSecteur}}</span>
                    </div>
            </div>
                <div class="form-row" style="font-family: 'Times New Roman'; color: #3f9ae5; font-size: 13px">
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
            <table class="container table table-striped">

                <thead>
                <tr>
                    <td>IdEntreprise</td>
                    <td>Entreprise</td>
                    <td>Exo 1</td>
                    <td>Exo 2</td>
                    <td>Periodicite</td>
                    <td>Document</td>
                    <td>Espace com</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ explode("-",$input['idEntreprise'])[0] }}</td>
                    <td>{{ explode("-",$input['idEntreprise'])[1]  }}</td>
                    <td>{{$input['exercice1']}}</td>
                    <td>{{$input['exercice2']}}</td>
                    <td>{{$input['naturep']}}</td>
                    <td>{{$input['document']}}</td>
                    <td>{{$input['localite']}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
