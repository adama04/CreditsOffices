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
                        <a href="" class="btn btn-success" style="font-family: 'Times New Roman';font-size: larger;">Export PDF</a></div>
                </div>
            </form>
        </div>
    </div>
</div>
@if($exercice2 - $exercice1 >= 5)
    <div class="alert alert-danger container">
        {{ 'Periode trop grand choisir sur 5 ans' }}
    </div>
@else
    <table class="table table-condensed">
        <thead style="font-size: 12px">
        <tr>
            <th style="text-align: right;">Exrecices : </th>
            @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )
                <th colspan="6" style="background-color: #66CCFF; text-align: center;">{{ $exo }}</th>
            @endfor
        </tr>
            <tr>
                <th></th>
                @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )
                    <th colspan="2">{{$infoEntreprise->Sigle }}</th>
                    <th colspan="2" style="background-color: #F3F3F3">Pays</th>
                    <th colspan="2" style="background-color: #BCDAC5">Indicateurs</th>
                @endfor
            </tr>
        <tr>
            {{-- Actifs ou charges --}}
            <th style="background-color: #D0FDEB; text-align: left;">
                @foreach($collectclassesA as $collectclasseA)
                    {{ strtoupper($collectclasseA->nature)  }}
                        @break;
                @endforeach
            </th>
            @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )
                <th >M. (CFA)</th>
                <th >% / T.E</th>
                <th style="background-color: #F3F3F3;">M. (CFA)</th>
                <th style="background-color: #F3F3F3">% / T.S</th>
                <th style="background-color: #BCDAC5">P.D.M</th>
                <th style="background-color: #BCDAC5">R.P.E.S</th>
            @endfor
        </tr>
        </thead>
        <tbody>
        @foreach($classesA as $classeA)
            <tr style="font-size: 12px; text-align: right;">
                <th >{{ $classeA->nomClasse }}</th>
                @foreach($collectclassesA as $collectclasseA):
                    @if($collectclasseA->nomClasse != $classeA->nomClasse):
                        @continue
                    @else
                        <td style=" text-align: center;">{{ (int) $collectclasseA->total }}</td>
                        <td style="text-align: center;">&nbsp; </td>
                        <td style="background-color: #F3F3F3;text-align: center;">&nbsp;</td>
                        <td style="background-color: #F3F3F3;text-align: center;">&nbsp;</td>
                        <td style="background-color: #BCDAC5;text-align: center;">&nbsp;</td>
                        <td style="background-color: #BCDAC5;text-align: center;">&nbsp;</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        <tr style="font-size: 13px;text-align: center">
            <th style="text-align: right;">
                @foreach($collectclassesA as $collectclasseA)
                    {{'TOTAL '. strtoupper($collectclasseA->nature)  }}
                    @break;
                @endforeach
            </th>
            @foreach($collecttotalclassesA as $collecttotalclasseA)
                <th style="color: #0000F0">
                    {{ (int) $collecttotalclasseA->total }}
                </th>
                <th ></th>
                <th style="background-color: #F3F3F3;text-align: center;"></th>
                <th style="background-color: #F3F3F3;text-align: center;"></th>
                <th style="background-color: #BCDAC5;text-align: center;"></th>
                <th style="background-color: #BCDAC5;text-align: center;"></th>
            @endforeach
        </tr>
        {{-- Passifs ou Produits --}}
        <tr>
            <th style="background-color: #D0FDEB; text-align: left;">
                @foreach($collectclassesB as $collectclasseB)
                    {{ strtoupper($collectclasseB->nature)  }}
                    @break;
                @endforeach
            </th>
            @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )
                <th ></th>
                <th ></th>
                <th style="background-color: #F3F3F3;"></th>
                <th style="background-color: #F3F3F3"></th>
                <th style="background-color: #BCDAC5"></th>
                <th style="background-color: #BCDAC5"></th>
            @endfor
        </tr>
        @foreach($classesB as $classeB)
            <tr style="font-size: 12px; text-align: right;">
                <th >{{ $classeB->nomClasse }}</th>
                @foreach($collectclassesB as $collectclasseB):
                    @if($collectclasseB->nomClasse != $classeB->nomClasse):
                        @continue
                    @else
                        <td style=" text-align: center;">{{ (int) $collectclasseB->total }}</td>
                        <td style="text-align: center;">&nbsp; </td>
                        <td style="background-color: #F3F3F3;text-align: center;">&nbsp;</td>
                        <td style="background-color: #F3F3F3;text-align: center;">&nbsp;</td>
                        <td style="background-color: #BCDAC5;text-align: center;">&nbsp;</td>
                        <td style="background-color: #BCDAC5;text-align: center;">&nbsp;</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        <tr style="font-size: 13px;text-align: center">
            <th style="text-align: right;">
                @foreach($collectclassesB as $collectclasseB)
                    {{'TOTAL '. strtoupper($collectclasseB->nature)  }}
                    @break;
                @endforeach
            </th>
            @foreach($collecttotalclassesB as $collecttotalclasseB)
                <th style="color: #0000F0">
                    {{ (int) $collecttotalclasseB->total }}
                </th>
                <th ></th>
                <th style="background-color: #F3F3F3;text-align: center;"></th>
                <th style="background-color: #F3F3F3;text-align: center;"></th>
                <th style="background-color: #BCDAC5;text-align: center;"></th>
                <th style="background-color: #BCDAC5;text-align: center;"></th>
            @endforeach
        </tr>

        </tbody>

    </table>

@endif

