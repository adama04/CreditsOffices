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
            @foreach($infoEntreprises as $infoEntreprise )
            <div class="form-row" style="font-family: 'Times New Roman'; color: #0355BF; font-size:medium">
                <div class="col-md-6">
                    Numero Registre :
                    <span>{{$infoEntreprise->numRegistre}}</span>
                </div>
                <div class="col-md-6">
                    Secteur :
                    <span>{{$infoEntreprise->nomSecteur}}</span>
                </div>
            </div>

                <div class="form-row" style="font-family: 'Times New Roman'; color: #0355BF; font-size: medium">
                    <div class="col-md-6">
                        Raison Sociale :
                        <span>
                             {{ $infoEntreprise->nomEntreprise }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        Activité principal :
                        <span>
                            {{ $infoEntreprise->nomsouSecteur }}
                        </span>
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
</div>
    <table class="table table-condensed" style="font-size: 12px">
        <thead  >
        @if($input['naturep'] == 'variation')
            <tr>
                @if($input['localite'] == 'uemoa')
                    @php ($colspan = 12)
                @else
                    @php ($colspan = 6)
                @endif
                <th style="text-align: right;" > </th>
                <th style="text-align: center; " colspan="{{ $colspan }}">Premier Exercice : {{ $exercice1 }}</th>
                <th style="text-align: center;background-color: #0000F0" colspan="{{ $colspan }}"> Dernier Exercice : {{ $exercice2 }}</th>
                <th style="text-align: center;" colspan="@if($input['localite'] == 'uemoa'){{ 6 }} @else {{ 4 }} @endif"> Variation</th>
            </tr>
        @else
            <tr style="font-size: 14px">
                <th style="text-align: right;">Exrecices : </th>
                @if($input['localite'] == 'uemoa')
                    @php ($colspan = 18)
                @else
                    @php ($colspan = 10)
                @endif
                @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )
                    <th colspan="{{ $colspan }}" style="background-color: #66CCFF; text-align: center; ">{{ $exo }}</th>
                @endfor
            </tr>
        @endif
        <tr style="text-align: center;">
            <th> </th>
            @foreach($exercices as $exercice)
                <th colspan="2">{{$infoEntreprise->Sigle }}</th>
                <th colspan="2" style="background-color: #F3F3F3; ">Pays</th>
                @if($input['localite'] == 'uemoa')
                    <th colspan="2">UEMOA</th>
                @endif
                <th colspan="@if($input['localite'] == 'uemoa') {{ 6 }} @else {{ 2 }} @endif" style="background-color: #BCDAC5">Indicateurs</th>
                @if($input['naturep'] == 'paran')
                    <th colspan="@if($input['localite'] == 'uemoa') {{ 6 }} @else {{ 4 }} @endif" style="background-color: #66CCFF">&Eacute;volution </th>
                @endif
            @endforeach
            @if($input['naturep'] == 'variation')
                @if($input['localite'] == 'uemoa')
                    @php ($colspan = 3)
                @else
                    @php ($colspan = 2)
                @endif
                <th colspan="{{ $colspan }}">Ecart des Evolutions</th>
                <th colspan="{{ $colspan }}">Rapport des Evolution</th>
            @endif
        </tr>
        <tr>
            {{-- Actifs ou charges --}}
            <th style="background-color: #D0FDEB; text-align: left;">
                @foreach($collectclassesA as $collectclasseA)
                    {{ strtoupper($collectclasseA->nature)  }}
                    @break;
                @endforeach
            </th>
            @foreach($exercices as $exercice )
                @if($exercice->exercice < $exercice1 ||$exercice->exercice > $exercice2 )
                    @continue
                @else
                    <th >M. (CFA)</th>
                    <th >% / T.E</th>
                    <th style="background-color: #F3F3F3;">M. (CFA)</th>
                    <th style="background-color: #F3F3F3">% / T.P</th>
                    @if($input['localite'] == 'uemoa')
                        <th>M. (CFA)</th>
                        <th>% / T.U</th>
                    @endif
                    <th style="background-color: #BCDAC5">P.D.M</th>
                    <th style="background-color: #BCDAC5">R.P.E.P</th>
                    @if($input['localite'] == 'uemoa')
                        <th style="background-color: #BCDAC5">PDME % U</th>
                        <th style="background-color: #BCDAC5">R.P.E.U</th>
                        <th style="background-color: #BCDAC5">PDMP % U</th>
                        <th style="background-color: #BCDAC5">R.P.P.U</th>
                    @endif
                    @if($input['naturep'] == 'paran')
                        <th style="background-color: #66CCFF">D.B.E</th>
                        <th style="background-color: #66CCFF">% / A.P</th>
                        <th style="background-color: #66CCFF">D.B.P</th>
                        <th style="background-color: #66CCFF">% / A.P</th>
                        @if($input['localite'] == 'uemoa')
                            <th style="background-color: #66CCFF">D.B.U</th>
                            <th style="background-color: #66CCFF">% / A.P</th>
                        @endif
                    @endif
                @endif
            @endforeach
            @if($input['naturep'] == 'variation')
                <th style="background-color: #66CCFF">% / E</th>
                <th style="background-color: #66CCFF">% / P</th>
                @if($input['localite'] == 'uemoa')
                    <th style="background-color: #66CCFF">% / U</th>
                @endif
                <th style="background-color: #66CCFF">% / E</th>
                <th style="background-color: #66CCFF">% / P</th>
                @if($input['localite'] == 'uemoa')
                    <th style="background-color: #66CCFF">% / U</th>
                @endif
            @endif
        </tr>
        </thead>
        <tbody>
        {{-- Classes --}}
        @foreach($classesA as $classeA)
            <tr style="font-size: 12px; text-align: right;">
                <th>{{ $classeA->nomClasse }}</th>
                {{-- Total By Classe Entreprise--}}
                @foreach($collectclassesA as $collectclasseA)
                    @if($collectclasseA->nomClasse != $classeA->nomClasse)
                        @continue
                    @else
                        {{-- Years --}}
                        @foreach($exercices as $exercice)
                            @if($collectclasseA->exercice != $exercice->exercice)
                                @continue
                            @else
                                {{-- Total By Entreprise --}}
                                @foreach($collecttotalclassesA as $collecttotalclasseA)
                                    @if($collecttotalclasseA->exercice != $exercice->exercice)
                                        @continue
                                    @else
                                        {{-- Classes Pays --}}
                                        @foreach($collectclassesAGlobal as $collectclasseAGlobal)
                                            @if($collecttotalclasseA->exercice != $collectclasseAGlobal ->exercice ||
                                            $collectclasseAGlobal->nomClasse != $collectclasseA->nomClasse)
                                                @continue
                                            @else
                                                {{-- Total Pays --}}
                                                @foreach($collecttotalclassesAGlobal as $collecttotalclasseAGlobal)
                                                    @if($collecttotalclasseA->exercice != $collecttotalclasseAGlobal->exercice)
                                                        @continue
                                                    @else

                                                        @if($input['naturep'] == 'paran')

                                                            {{-- Entreprise --}}
                                                            @foreach($collectclassesA as $collectclasseAP)
                                                                @if($collectclasseAP->exercice != ($collectclasseA->exercice -1) ||
                                                                $collectclasseAP->nomClasse != $collectclasseA->nomClasse)
                                                                    @continue
                                                                @else
                                                                    {{-- Pays --}}
                                                                    @foreach($collectclassesAGlobal as $collectclasseAGlobalP)
                                                                        @if($collectclasseAGlobalP->exercice != ($collectclasseAGlobal->exercice -1) ||
                                                                        $collectclasseAGlobalP->nomClasse != $collectclasseAGlobal->nomClasse)
                                                                            @continue
                                                                        @else
                                                                            @if($input['localite'] == 'uemoa')
                                                                                {{-- Classes UEMOA --}}
                                                                                @foreach($collectclassesAUEMOA as $collectclasseAUEMOA)
                                                                                    @if($collectclasseAUEMOA->exercice != $collectclasseAGlobal ->exercice ||
                                                                                    $collectclasseAGlobal->nomClasse != $collectclasseAUEMOA->nomClasse)
                                                                                        @continue
                                                                                    @else
                                                                                        {{-- Total UEMOA --}}
                                                                                        @foreach($collecttotalclassesAUEMOA as $collecttotalclasseAUEMOA)
                                                                                            @if($collecttotalclasseAUEMOA->exercice != $collectclasseAUEMOA->exercice)
                                                                                                @continue
                                                                                            @else
                                                                                                {{-- Classes UEMOA P--}}
                                                                                                @foreach($collectclassesAUEMOA as $collectclasseAUEMOAP)
                                                                                                    @if($collectclasseAUEMOAP->exercice != ($collectclasseAUEMOA->exercice -1) ||
                                                                                                    $collectclasseAUEMOAP->nomClasse != $collectclasseAUEMOA->nomClasse)
                                                                                                        @continue
                                                                                                    @else
                                                                                                        <td style=" text-align: center;">{{ (int) $collectclasseA->total }}</td>
                                                                                                        <td style="text-align: center;color: #0000F0">
                                                                                                            @if($collecttotalclasseA->total == 0)
                                                                                                                {{ $ce_s_te = 0 }}
                                                                                                            @else
                                                                                                                {{ $ce_s_te = round(($collectclasseA->total / $collecttotalclasseA->total)*100,2)}}
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        <td style="background-color: #F3F3F3;text-align: center;">{{ (int) $collectclasseAGlobal->total }}</td>
                                                                                                        <td style="background-color: #F3F3F3;text-align: center;">
                                                                                                            @if($collecttotalclasseAGlobal->total == 0)
                                                                                                                {{ $cp_s_tp = 0 }}
                                                                                                            @else
                                                                                                                {{ $cp_s_tp = round(($collectclasseAGlobal->total / $collecttotalclasseAGlobal->total)*100,2 ) }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        <td>{{ (int) $collectclasseAUEMOA->total }}</td>
                                                                                                        <td>
                                                                                                            @if($collecttotalclasseAUEMOA->total != 0)
                                                                                                                {{ $u_s_tu = round(($collectclasseAUEMOA->total /$collecttotalclasseAUEMOA->total )*100,2) }}
                                                                                                            @else
                                                                                                                {{ $u_s_tu = 0 }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        {{-- Calcul part de marché entreprise(exemple : caisse BHS / Caisse Senegal) sur pays--}}
                                                                                                        <td style="background-color: #BCDAC5;text-align: center;">
                                                                                                            @if($collectclasseAGlobal->total == 0)
                                                                                                                {{ $ce_s_tp = 0 }}
                                                                                                            @else
                                                                                                                {{ $ce_s_tp = round(($collectclasseA->total / $collectclasseAGlobal->total )*100,2) }}
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        {{-- Rapport des %--}}
                                                                                                        <td style="background-color: #BCDAC5;text-align: center;">
                                                                                                            @if($cp_s_tp == 0 )
                                                                                                                {{ 0 }}
                                                                                                            @else
                                                                                                                {{round( ($ce_s_te/$cp_s_tp)*100,2) }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        <td style="background-color: #BCDAC5;text-align: center;">
                                                                                                            @if($collectclasseAUEMOA->total != 0) {{  round(($collectclasseA->total /$collectclasseAUEMOA->total )*100,2) }}
                                                                                                            @else  {{ 0 }} @endif
                                                                                                        </td>
                                                                                                        <td style="background-color: #BCDAC5;text-align: center;">
                                                                                                            @if($u_s_tu == 0) {{ 0 }}
                                                                                                            @else {{ round(($ce_s_te / $u_s_tu)*100,2) }}
                                                                                                            @endif

                                                                                                        </td>
                                                                                                        <td style="background-color: #BCDAC5;text-align: center;">
                                                                                                            @if($collectclasseAUEMOA->total != 0) {{ round(($collectclasseAGlobal->total /$collectclasseAUEMOA->total )*100,2) }}
                                                                                                            @else  {{ 0 }} @endif
                                                                                                        </td>
                                                                                                        <td style="background-color: #BCDAC5;text-align: center;">
                                                                                                            @if($u_s_tu == 0) {{ 0 }}
                                                                                                            @else {{ round(($cp_s_tp / $u_s_tu)*100,2) }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        {{-- Evolution par rapport à An precedant Entreprise : Ex: caisse 2010 - caisse 2009--}}
                                                                                                        <td style="background-color: #66CCFF">{{ $collectclasseA->total - $collectclasseAP->total}}</td>
                                                                                                        {{-- % Evolution--}}
                                                                                                        <td style="background-color: #66CCFF">
                                                                                                            @if($collectclasseAP->total != 0)
                                                                                                                {{ round((($collectclasseA->total - $collectclasseAP->total) / $collectclasseAP->total)*100,2) }}
                                                                                                            @else
                                                                                                                {{ 0 }}
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        {{-- Evolution par rapport à An precedant Pays : Ex: caisse 2010 - caisse 2009--}}
                                                                                                        <td style="background-color: #66CCFF">{{ $collectclasseAGlobal->total - $collectclasseAGlobalP->total}}</td>
                                                                                                        <td style="background-color: #66CCFF">@if($collectclasseAGlobalP->total != 0)
                                                                                                                {{ round((($collectclasseAGlobal->total - $collectclasseAGlobalP->total) / $collectclasseAGlobalP->total)*100,2) }}
                                                                                                            @else
                                                                                                                {{ 0 }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        <td style="background-color: #66CCFF">
                                                                                                            {{ $collectclasseAUEMOA->total - $collectclasseAUEMOAP->total }}
                                                                                                        </td>
                                                                                                        <td style="background-color: #66CCFF">
                                                                                                            @if($collectclasseAUEMOAP->total != 0)
                                                                                                                {{ round((($collectclasseAUEMOA->total - $collectclasseAUEMOAP->total)/$collectclasseAUEMOAP->total)*100,2) }}
                                                                                                            @else
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            @else
                                                                                {{-- Si Pas UEMOA --}}
                                                                                <td style=" text-align: center;">{{ (int) $collectclasseA->total }}</td>
                                                                                <td style="text-align: center;color: #0000F0">
                                                                                    @if($collecttotalclasseA->total == 0)
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{ round(($collectclasseA->total / $collecttotalclasseA->total)*100,2)}}
                                                                                    @endif
                                                                                </td>
                                                                                <td style="background-color: #F3F3F3;text-align: center;">{{ (int) $collectclasseAGlobal->total }}</td>
                                                                                <td style="background-color: #F3F3F3;text-align: center;">
                                                                                    @if($collecttotalclasseAGlobal->total == 0)
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{ round(($collectclasseAGlobal->total / $collecttotalclasseAGlobal->total)*100,2 ) }}
                                                                                    @endif
                                                                                </td>
                                                                                {{-- Calcul part de marché entreprise(exemple : caisse BHS / Caisse Senegal) sur pays--}}
                                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                                    @if($collectclasseAGlobal->total == 0)
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{ round(($collectclasseA->total / $collectclasseAGlobal->total )*100,2) }}
                                                                                    @endif
                                                                                </td>
                                                                                {{-- Rapport des %--}}
                                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                                    @if($collectclasseAGlobal->total == 0 || $collecttotalclasseA->total == 0 )
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{round( (($collectclasseA->total * $collecttotalclasseAGlobal->total ) / ($collectclasseAGlobal->total * $collecttotalclasseA->total ))*100,2) }}
                                                                                    @endif
                                                                                </td>
                                                                                {{-- Evolution par rapport à An precedant Entreprise : Ex: caisse 2010 - caisse 2009--}}
                                                                                <td style="background-color: #66CCFF">{{ $collectclasseA->total - $collectclasseAP->total}}</td>
                                                                                {{-- % Evolution--}}
                                                                                <td style="background-color: #66CCFF">
                                                                                    @if($collectclasseAP->total != 0)
                                                                                        {{ round((($collectclasseA->total - $collectclasseAP->total) / $collectclasseAP->total)*100,2) }}
                                                                                    @else
                                                                                        {{ 0 }}
                                                                                    @endif
                                                                                </td>
                                                                                {{-- Evolution par rapport à An precedant Pays : Ex: caisse 2010 - caisse 2009--}}
                                                                                <td style="background-color: #66CCFF">{{ $collectclasseAGlobal->total - $collectclasseAGlobalP->total}}</td>
                                                                                <td style="background-color: #66CCFF">@if($collectclasseAGlobalP->total != 0)
                                                                                        {{ round((($collectclasseAGlobal->total - $collectclasseAGlobalP->total) / $collectclasseAGlobalP->total)*100,2) }}
                                                                                    @else
                                                                                        {{ 0 }}
                                                                                    @endif
                                                                                </td>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{-- Si Par variation --}}
                                                            {{-- Si UEMOA --}}
                                                            @if($input['localite'] == 'uemoa')
                                                                {{-- Classes UEMOA --}}
                                                                @foreach($collectclassesAUEMOA as $collectclasseAUEMOA)
                                                                    @if($collectclasseAUEMOA->exercice != $collectclasseAGlobal ->exercice ||
                                                                    $collectclasseAGlobal->nomClasse != $collectclasseAUEMOA->nomClasse)
                                                                        @continue
                                                                    @else
                                                                        {{-- Total UEMOA --}}
                                                                        @foreach($collecttotalclassesAUEMOA as $collecttotalclasseAUEMOA)
                                                                            @if($collecttotalclasseAUEMOA->exercice != $collectclasseAUEMOA->exercice)
                                                                                @continue
                                                                            @else
                                                                                <td style=" text-align: center;">{{ (int) $collectclasseA->total }}
                                                                                </td>
                                                                                <td style="text-align: center;color: #0000F0">
                                                                                    @if($collecttotalclasseA->total == 0)
                                                                                        {{ $ce_s_te = 0 }}
                                                                                    @else
                                                                                        {{ $ce_s_te = round(($collectclasseA->total / $collecttotalclasseA->total)*100,2)}}
                                                                                    @endif
                                                                                </td>
                                                                                <td style="background-color: #F3F3F3;text-align: center;">{{ (int) $collectclasseAGlobal->total }}

                                                                                </td>
                                                                                <td style="background-color: #F3F3F3;text-align: center;">
                                                                                    @if($collecttotalclasseAGlobal->total == 0)
                                                                                        {{ $cp_s_tp = 0 }}
                                                                                    @else
                                                                                        {{ $cp_s_tp = round(($collectclasseAGlobal->total / $collecttotalclasseAGlobal->total)*100,2 ) }}
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ (int)$collectclasseAUEMOA->total }}</td>
                                                                                <td>
                                                                                    @if($collecttotalclasseAUEMOA->total == 0) {{ $cu_s_tu = 0 }}
                                                                                    @else {{ $cu_s_tu = round(($collectclasseAUEMOA->total / $collecttotalclasseAUEMOA->total)*100,2) }} @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                                    @if($collectclasseAGlobal->total == 0)
                                                                                        {{ $ce_s_tp = 0 }}
                                                                                    @else
                                                                                        {{ $ce_s_tp = round(($collectclasseA->total / $collectclasseAGlobal->total )*100,2) }}
                                                                                    @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                                    @if($cp_s_tp == 0 )
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{round( ($ce_s_te / $cp_s_tp)*100,2) }}
                                                                                    @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                                    @if($collecttotalclasseAUEMOA->total == 0) {{ $ce_s_tu = 0 }}
                                                                                    @else {{ $ce_s_tu = round(($collectclasseA->total / $collecttotalclasseAUEMOA->total)*100,2) }} @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                                    @if($cu_s_tu == 0) {{ 0 }}
                                                                                    @else {{ round(($ce_s_te / $cu_s_tu)*100,2) }} @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                                    @if($collecttotalclasseAUEMOA->total == 0) {{ $ce_s_tu = 0 }}
                                                                                    @else {{ $cp_s_tu = round(($collectclasseAGlobal->total / $collecttotalclasseAUEMOA->total)*100,2) }} @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                                    @if($cu_s_tu == 0) {{ 0 }}
                                                                                    @else {{ round(($cp_s_tp / $cu_s_tu)*100,2) }} @endif
                                                                                </td>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                {{-- Si pas UEMOA --}}
                                                                <td style=" text-align: center;">{{ (int) $collectclasseA->total }}
                                                                </td>
                                                                <td style="text-align: center;color: #0000F0">
                                                                    @if($collecttotalclasseA->total == 0)
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{ round(($collectclasseA->total / $collecttotalclasseA->total)*100,2)}}
                                                                    @endif
                                                                </td>
                                                                <td style="background-color: #F3F3F3;text-align: center;">{{ (int) $collectclasseAGlobal->total }}

                                                                </td>
                                                                <td style="background-color: #F3F3F3;text-align: center;">
                                                                    @if($collecttotalclasseAGlobal->total == 0)
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{ round(($collectclasseAGlobal->total / $collecttotalclasseAGlobal->total)*100,2 ) }}
                                                                    @endif
                                                                </td>

                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                    @if($collectclasseAGlobal->total == 0)
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{ round(($collectclasseA->total / $collectclasseAGlobal->total )*100,2) }}
                                                                    @endif
                                                                </td>
                                                                <td style="background-color: #BCDAC5;text-align: center;">
                                                                    @if($collectclasseAGlobal->total == 0 || $collecttotalclasseA->total == 0 )
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{round( (($collectclasseA->total * $collecttotalclasseAGlobal->total ) / ($collectclasseAGlobal->total * $collecttotalclasseA->total ))*100,2) }}
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
                {{-- Si Par variation affichage des diff et ecarts --}}
                @if($input['naturep'] != 'paran')
                    @foreach($collectclassesA as $cca)
                        @if($cca->exercice != $exercice1)
                            @continue
                        @else
                            @foreach($collectclassesA as $cca1)
                                @if($cca1->exercice != $exercice2 ||
                                $classeA->nomClasse != $cca->nomClasse ||
                                $cca->nomClasse != $cca1->nomClasse)
                                    @continue
                                @else
                                    @foreach($collectclassesAGlobal as $ccg)
                                        @if($ccg->exercice != $exercice1)
                                            @continue
                                        @else
                                            @foreach($collectclassesAGlobal as $ccg1)
                                                @if($ccg1->exercice != $exercice2 ||
                                                $classeA->nomClasse != $ccg->nomClasse ||
                                                $ccg->nomClasse != $ccg1->nomClasse)
                                                    @continue
                                                @else
                                                    {{-- Si UEMOA --}}
                                                    @if($input['localite'] == 'uemoa')
                                                        @foreach($collectclassesAUEMOA as $ccu)
                                                            @if($ccu->exercice != $exercice1)
                                                                @continue
                                                            @else
                                                                @foreach($collectclassesAUEMOA as $ccu1)
                                                                    @if($ccu1->exercice != $exercice2 ||
                                                                    $classeA->nomClasse != $ccu->nomClasse ||
                                                                    $ccu->nomClasse != $ccu1->nomClasse)
                                                                        @continue
                                                                    @else
                                                                        <td style="background-color: #66CCFF">
                                                                            {{ (int) ($cca1->total - $cca->total) }}
                                                                        </td>
                                                                        <td style="background-color: #66CCFF">
                                                                            {{ (int) ($ccg1->total - $ccg->total) }}
                                                                        </td>
                                                                        <td style="background-color: #66CCFF">{{ (int) $ccu1->total - $ccu->total }}</td>
                                                                        <td style="background-color: #66CCFF">
                                                                            @if( $cca->total == 0)
                                                                                {{ 0 }}
                                                                            @else
                                                                                {{ round( (($cca1->total-$cca->total) / $cca->total)*100,2) }}
                                                                            @endif
                                                                        </td>
                                                                        <td style="background-color: #66CCFF">
                                                                            @if( $ccg->total == 0)
                                                                                {{ 0 }}
                                                                            @else
                                                                                {{ round( (($ccg1->total-$ccg->total) / $ccg->total)*100,2) }}
                                                                            @endif
                                                                        </td>
                                                                        <td style="background-color: #66CCFF">
                                                                            @if( $ccu->total == 0)
                                                                                {{ 0 }}
                                                                            @else
                                                                                {{ round( (($ccu1->total-$ccu->total) / $ccu->total)*100,2) }}
                                                                            @endif
                                                                        </td>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        {{-- Si Pas UEMOA --}}
                                                        <td style="background-color: #66CCFF">
                                                            {{ (int) ($cca1->total - $cca->total) }}
                                                        </td>
                                                        <td style="background-color: #66CCFF">
                                                            {{ (int) ($ccg1->total - $ccg->total) }}
                                                        </td>
                                                        <td style="background-color: #66CCFF">
                                                            @if( $cca->total == 0)
                                                                {{ 0 }}
                                                            @else
                                                                {{ round( (($cca1->total-$cca->total) / $cca->total)*100,2) }}
                                                            @endif
                                                        </td>
                                                        <td style="background-color: #66CCFF">
                                                            @if( $ccg->total == 0)
                                                                {{ 0 }}
                                                            @else
                                                                {{ round( (($ccg1->total-$ccg->total) / $ccg->total)*100,2) }}
                                                            @endif
                                                        </td>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
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
                @foreach($collecttotalclassesAGlobal as $collecttotalclasseAGlobal)
                    @if($collecttotalclasseAGlobal->exercice != $collecttotalclasseA ->exercice)
                        @continue
                    @else
                        @foreach($exercices as $exercice)
                            @if($collecttotalclasseAGlobal->exercice != $exercice->exercice)
                                @continue
                            @else
                                {{-- Tatal Par An --}}
                                @if($input['naturep'] == 'paran')
                                    @foreach($collecttotalclassesA as $collecttotalclasseAP)
                                        @if($collecttotalclasseAP->exercice != $exercice->exercice -1)
                                            @continue
                                        @else
                                            @foreach($collecttotalclassesAGlobal as $collecttotalclasseAGlobalP)
                                                @if($collecttotalclasseAGlobalP->exercice != $exercice->exercice -1)
                                                    @continue
                                                @else
                                                    {{-- Si UEMOA --}}
                                                    @if($input['localite'] == 'uemoa')
                                                        @foreach($collecttotalclassesAUEMOA as $collecttotalclasseAUEMOA)
                                                            @if($collecttotalclasseAUEMOA->exercice != $exercice->exercice )
                                                                @continue
                                                            @else
                                                                @foreach($collecttotalclassesAUEMOA as $collecttotalclasseAUEMOAP)
                                                                    @if($collecttotalclasseAUEMOAP->exercice != $exercice->exercice -1)
                                                                        @continue
                                                                    @else
                                                                        <th style="color: #20c997">
                                                                            {{ (int) $collecttotalclasseA->total }}
                                                                        </th>
                                                                        <th style="color: #0000F0">{{100}}</th>
                                                                        <th style="background-color: #F3F3F3;text-align: center;">{{ (int) $collecttotalclasseAGlobal->total}}</th>
                                                                        <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>
                                                                        <th>{{ (int) $collecttotalclasseAUEMOA->total }}</th>
                                                                        <th>{{ 100 }}</th>
                                                                        <th style="background-color: #BCDAC5;text-align: center;">
                                                                            @if($collecttotalclasseAGlobal->total != 0)
                                                                                {{ round(($collecttotalclasseA->total / $collecttotalclasseAGlobal->total)*100, 2) }}
                                                                            @else
                                                                                {{ 0 }}
                                                                            @endif
                                                                        </th>
                                                                        <th style="background-color: #BCDAC5;text-align: center;">
                                                                            {{100}}
                                                                        </th>
                                                                        <th style="background-color: #BCDAC5;text-align: center;">
                                                                            @if($collecttotalclasseAUEMOA->total != 0)
                                                                                {{ round(($collecttotalclasseA->total / $collecttotalclasseAUEMOA->total)*100, 2) }}
                                                                            @else
                                                                                {{ 0 }}
                                                                            @endif
                                                                        </th>
                                                                        <th style="background-color: #BCDAC5;text-align: center;">{{ 0 }}</th>
                                                                        <th style="background-color: #BCDAC5;text-align: center;">
                                                                            @if($collecttotalclasseAUEMOA->total != 0)
                                                                                {{ round(($collecttotalclasseAGlobal->total / $collecttotalclasseAUEMOA->total)*100, 2) }}
                                                                            @else
                                                                                {{ 0 }}
                                                                            @endif
                                                                        </th>
                                                                        <th style="background-color: #BCDAC5;text-align: center;">{{ 0 }}</th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">{{ $collecttotalclasseA->total - $collecttotalclasseAP->total }}</th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                                            @if($collecttotalclasseAP->total != 0)
                                                                                {{ round((($collecttotalclasseA->total - $collecttotalclasseAP->total) /  $collecttotalclasseAP->total)*100,2) }}
                                                                            @else
                                                                                {{ 0 }}
                                                                            @endif
                                                                        </th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">{{ $i = (int)$collecttotalclasseAGlobal->total - $collecttotalclasseAGlobalP->total}}</th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                                            @if($collecttotalclasseAGlobalP->total == 0) {{ 0 }}
                                                                            @else {{round(($i/ $collecttotalclasseAGlobalP->total)*100,2)}} @endif
                                                                        </th>

                                                                        <th style="background-color: #66CCFF;text-align: center;">{{$ii = (int)$collecttotalclasseAUEMOA->total - $collecttotalclasseAUEMOAP->total }}</th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                                            @if($collecttotalclasseAUEMOAP->total == 0)
                                                                                {{ 0 }}
                                                                            @else
                                                                                {{round(($ii/ $collecttotalclasseAUEMOAP->total)*100,2)}} @endif
                                                                        </th>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        {{-- Si pas UEMOA --}}
                                                        <th style="color: #20c997">
                                                            {{ (int) $collecttotalclasseA->total }}
                                                        </th>
                                                        <th style="color: #0000F0">{{100}}</th>
                                                        <th style="background-color: #F3F3F3;text-align: center;">{{ (int) $collecttotalclasseAGlobal->total}}</th>
                                                        <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>

                                                        <th style="background-color: #BCDAC5;text-align: center;">
                                                            @if($collecttotalclasseAGlobal->total != 0)
                                                                {{ round(($collecttotalclasseA->total / $collecttotalclasseAGlobal->total)*100, 2) }}
                                                            @else
                                                                {{ 0 }}
                                                            @endif
                                                        </th>
                                                        <th style="background-color: #BCDAC5;text-align: center;">{{100}}
                                                        </th>

                                                        <th style="background-color: #66CCFF;text-align: center;">{{ $collecttotalclasseA->total - $collecttotalclasseAP->total }}</th>
                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                            @if($collecttotalclasseAP->total != 0)
                                                                {{ round((($collecttotalclasseA->total - $collecttotalclasseAP->total) /  $collecttotalclasseAP->total)*100,2) }}
                                                            @else
                                                                {{ 0 }}
                                                            @endif
                                                        </th>
                                                        <th style="background-color: #66CCFF;text-align: center;">{{$r = (int)($collecttotalclasseAGlobal->total - $collecttotalclasseAGlobalP->total)}}</th>
                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                            @if($collecttotalclasseAGlobalP->total == 0)
                                                                {{0}}
                                                            @else
                                                                {{ round(($r / $collecttotalclasseAGlobalP->total)*100,2) }}
                                                            @endif
                                                        </th>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    {{-- Par Variation--}}
                                    {{-- Si UEMOA --}}
                                    @if($input['localite'] == 'uemoa')
                                        @foreach($collecttotalclassesAUEMOA as $collecttotalclasseAUEMOA)
                                            @if($collecttotalclasseAUEMOA->exercice != $exercice->exercice )
                                                @continue
                                            @else
                                                <th style="color: #20c997">
                                                    {{ (int) $collecttotalclasseA->total }}
                                                </th>
                                                <th style="color: #0000F0">{{100}}</th>
                                                <th style="background-color: #F3F3F3;text-align: center;">{{ (int) $collecttotalclasseAGlobal->total}}</th>
                                                <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>
                                                <th style="background-color: #F3F3F3;text-align: center;">{{(int) $collecttotalclasseAUEMOA->total}}</th>
                                                <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>
                                                <th style="background-color: #BCDAC5;text-align: center;">
                                                    @if($collecttotalclasseAGlobal->total != 0)
                                                        {{ round(($collecttotalclasseA->total / $collecttotalclasseAGlobal->total)*100, 2) }}
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                </th>
                                                <th style="background-color: #BCDAC5;text-align: center;">{{ 100 }}</th>
                                                <th style="background-color: #BCDAC5;text-align: center;">
                                                    @if($collecttotalclasseAUEMOA->total == 0) {{ 0 }}
                                                    @else {{ round(($collecttotalclasseA->total / $collecttotalclasseAUEMOA->total)*100, 2) }}
                                                    @endif
                                                </th >
                                                <th style="background-color: #BCDAC5;text-align: center;">{{ 100 }}</th>
                                                <th style="background-color: #BCDAC5;text-align: center;">@if($collecttotalclasseAUEMOA->total == 0) {{ 0 }}
                                                    @else {{ round(($collecttotalclasseAGlobal->total / $collecttotalclasseAUEMOA->total)*100, 2) }}
                                                    @endif
                                                </th>
                                                <th style="background-color: #BCDAC5;text-align: center;">{{ 100 }}</th>
                                            @endif
                                        @endforeach
                                    @else
                                        {{-- Si Pas UEMOA --}}
                                        <th style="color: #20c997">
                                            {{ (int) $collecttotalclasseA->total }}
                                        </th>
                                        <th style="color: #0000F0">{{100}}</th>
                                        <th style="background-color: #F3F3F3;text-align: center;">{{ (int) $collecttotalclasseAGlobal->total}}</th>
                                        <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>
                                        <th style="background-color: #BCDAC5;text-align: center;">
                                            @if($collecttotalclasseAGlobal->total != 0)
                                                {{ round(($collecttotalclasseA->total / $collecttotalclasseAGlobal->total)*100, 2) }}
                                            @else
                                                {{ 0 }}
                                            @endif
                                        </th>
                                        <th style="background-color: #BCDAC5;text-align: center;">{{ 100 }}</th>

                                    @endif
                                @endif
                            @endif
                        @endforeach

                    @endif
                @endforeach
            @endforeach
            @if($input['naturep'] == 'variation')
                @foreach($collecttotalclassesA as $cca)
                    @if($cca->exercice != $exercice1)
                        @continue
                    @else
                        @foreach($collecttotalclassesA as $cca1)
                            @if($cca1->exercice != $exercice2 )
                                @continue
                            @else
                                @foreach($collecttotalclassesAGlobal as $ccg)
                                    @if($ccg->exercice != $exercice1)
                                        @continue
                                    @else
                                        @foreach($collecttotalclassesAGlobal as $ccg1)
                                            @if($ccg1->exercice != $exercice2 )
                                                @continue
                                            @else
                                                @if($input['localite'] == 'pays')
                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $cca1->total - $cca->total }}</th>
                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $ccg1->total - $ccg->total }}</th>
                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($cca1->total - $cca->total) / $cca->total)*100,2) }}</th>
                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($ccg1->total - $ccg->total) / $ccg->total)*100,2) }}</th>
                                                @else
                                                    @foreach($collecttotalclassesAUEMOA as $ccu)
                                                        @if($ccu->exercice != $exercice1)
                                                            @continue
                                                        @else
                                                            @foreach($collecttotalclassesAUEMOA as $ccu1)
                                                                @if($ccu1->exercice != $exercice2 )
                                                                    @continue
                                                                @else
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $cca1->total - $cca->total }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $ccg1->total - $ccg->total }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $ccu1->total - $ccu->total }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($cca1->total - $cca->total) / $cca->total)*100,2) }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($ccg1->total - $ccg->total) / $ccg->total)*100,2) }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($ccu1->total - $ccu->total) / $ccu->total)*100,2) }}</th>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
        </tr>
        <tr>
            {{-- Passifs Ou Produits --}}
            <th style="background-color: #D0FDEB; text-align: left;">
                @foreach($collectclassesB as $collectclasseB)
                    {{ strtoupper($collectclasseB->nature)  }}
                    @break;
                @endforeach
            </th>
            @foreach($exercices as $exercice )
                @if($exercice->exercice < $exercice1 ||$exercice->exercice > $exercice2 )
                    @continue
                @else
                    <th ></th>
                    <th ></th>
                    <th ></th>
                    <th ></th>
                    @if($input['localite'] == 'uemoa')
                        <th></th>
                        <th></th>
                    @endif
                    <th ></th>
                    <th ></th>
                    @if($input['localite'] == 'uemoa')
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                    @endif
                    @if($input['naturep'] == 'paran')
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        <th ></th>
                        @if($input['localite'] == 'uemoa')
                            <th ></th>
                            <th ></th>
                        @endif
                    @endif
                @endif
            @endforeach
            @if($input['naturep'] == 'variation')
                <th ></th>
                <th ></th>
                @if($input['localite'] == 'uemoa')
                    <th ></th>
                @endif
                <th ></th>
                <th ></th>
                @if($input['localite'] == 'uemoa')
                    <th ></th>
                @endif
            @endif
        </tr>

        @foreach($classesB as $classeB)
            <tr style="font-size: 12px; text-align: right;">
                <th>{{ $classeB->nomClasse }}</th>
                {{-- Total By Classe Entreprise--}}
                @foreach($collectclassesB as $collectclasseB)
                    @if($collectclasseB->nomClasse != $classeB->nomClasse)
                        @continue
                    @else
                        {{-- Years --}}
                        @foreach($exercices as $exercice)
                            @if($collectclasseB->exercice != $exercice->exercice)
                                @continue
                            @else
                                {{-- Total By Entreprise --}}
                                @foreach($collecttotalclassesB as $collecttotalclasseB)
                                    @if($collecttotalclasseB->exercice != $exercice->exercice)
                                        @continue
                                    @else
                                        {{-- Classes Pays --}}
                                        @foreach($collectclassesBGlobal as $collectclasseBGlobal)
                                            @if($collecttotalclasseB->exercice != $collectclasseBGlobal ->exercice ||
                                            $collectclasseBGlobal->nomClasse != $collectclasseB->nomClasse)
                                                @continue
                                            @else
                                                {{-- Total Pays --}}
                                                @foreach($collecttotalclassesBGlobal as $collecttotalclasseBGlobal)
                                                    @if($collecttotalclasseB->exercice != $collecttotalclasseBGlobal->exercice)
                                                        @continue
                                                    @else

                                                        @if($input['naturep'] == 'paran')

                                                            {{-- Entreprise --}}
                                                            @foreach($collectclassesB as $collectclasseBP)
                                                                @if($collectclasseBP->exercice != ($collectclasseB->exercice -1) ||
                                                                $collectclasseBP->nomClasse != $collectclasseB->nomClasse)
                                                                    @continue
                                                                @else
                                                                    {{-- Pays --}}
                                                                    @foreach($collectclassesBGlobal as $collectclasseBGlobalP)
                                                                        @if($collectclasseBGlobalP->exercice != ($collectclasseBGlobal->exercice -1) ||
                                                                        $collectclasseBGlobalP->nomClasse != $collectclasseBGlobal->nomClasse)
                                                                            @continue
                                                                        @else
                                                                            @if($input['localite'] == 'uemoa')
                                                                                {{-- Classes UEMOA --}}
                                                                                @foreach($collectclassesBUEMOA as $collectclasseBUEMOA)
                                                                                    @if($collectclasseBUEMOA->exercice != $collectclasseBGlobal ->exercice ||
                                                                                    $collectclasseBGlobal->nomClasse != $collectclasseBUEMOA->nomClasse)
                                                                                        @continue
                                                                                    @else
                                                                                        {{-- Total UEMOA --}}
                                                                                        @foreach($collecttotalclassesBUEMOA as $collecttotalclasseBUEMOA)
                                                                                            @if($collecttotalclasseBUEMOA->exercice != $collectclasseBUEMOA->exercice)
                                                                                                @continue
                                                                                            @else
                                                                                                {{-- Classes UEMOA P--}}
                                                                                                @foreach($collectclassesBUEMOA as $collectclasseBUEMOAP)
                                                                                                    @if($collectclasseBUEMOAP->exercice != ($collectclasseBUEMOA->exercice -1) ||
                                                                                                    $collectclasseBUEMOAP->nomClasse != $collectclasseBUEMOA->nomClasse)
                                                                                                        @continue
                                                                                                    @else
                                                                                                        <td style=" text-align: center;">{{ (int) $collectclasseB->total }}</td>
                                                                                                        <td style="text-align: center;color: #0000F0">
                                                                                                            @if($collecttotalclasseB->total == 0)
                                                                                                                {{ $ce_s_te = 0 }}
                                                                                                            @else
                                                                                                                {{ $ce_s_te = round(($collectclasseB->total / $collecttotalclasseB->total)*100,2)}}
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        <td style="background-color: #F3F3F3;text-align: center;">{{ (int) $collectclasseBGlobal->total }}</td>
                                                                                                        <td style="background-color: #F3F3F3;text-align: center;">
                                                                                                            @if($collecttotalclasseBGlobal->total == 0)
                                                                                                                {{ $cp_s_tp = 0 }}
                                                                                                            @else
                                                                                                                {{ $cp_s_tp = round(($collectclasseBGlobal->total / $collecttotalclasseBGlobal->total)*100,2 ) }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        <td>{{ (int) $collectclasseBUEMOA->total }}</td>
                                                                                                        <td>
                                                                                                            @if($collecttotalclasseBUEMOA->total != 0)
                                                                                                                {{ $u_s_tu = round(($collectclasseBUEMOA->total /$collecttotalclasseBUEMOA->total )*100,2) }}
                                                                                                            @else
                                                                                                                {{ $u_s_tu = 0 }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        {{-- Calcul part de marché entreprise(exemple : caisse BHS / Caisse Senegal) sur pays--}}
                                                                                                        <td style="background-color: #BCDBC5;text-align: center;">
                                                                                                            @if($collectclasseBGlobal->total == 0)
                                                                                                                {{ $ce_s_tp = 0 }}
                                                                                                            @else
                                                                                                                {{ $ce_s_tp = round(($collectclasseB->total / $collectclasseBGlobal->total )*100,2) }}
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        {{-- Rapport des %--}}
                                                                                                        <td style="background-color: #BCDBC5;text-align: center;">
                                                                                                            @if($cp_s_tp == 0 )
                                                                                                                {{ 0 }}
                                                                                                            @else
                                                                                                                {{round( ($ce_s_te/$cp_s_tp)*100,2) }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        <td style="background-color: #BCDBC5;text-align: center;">
                                                                                                            @if($collectclasseBUEMOA->total != 0) {{  round(($collectclasseB->total /$collectclasseBUEMOA->total )*100,2) }}
                                                                                                            @else  {{ 0 }} @endif
                                                                                                        </td>
                                                                                                        <td style="background-color: #BCDBC5;text-align: center;">
                                                                                                            @if($u_s_tu == 0) {{ 0 }}
                                                                                                            @else {{ round(($ce_s_te / $u_s_tu)*100,2) }}
                                                                                                            @endif

                                                                                                        </td>
                                                                                                        <td style="background-color: #BCDBC5;text-align: center;">
                                                                                                            @if($collectclasseBUEMOA->total != 0) {{ round(($collectclasseBGlobal->total /$collectclasseBUEMOA->total )*100,2) }}
                                                                                                            @else  {{ 0 }} @endif
                                                                                                        </td>
                                                                                                        <td style="background-color: #BCDBC5;text-align: center;">
                                                                                                            @if($u_s_tu == 0) {{ 0 }}
                                                                                                            @else {{ round(($cp_s_tp / $u_s_tu)*100,2) }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        {{-- Evolution par rapport à An precedant Entreprise : Ex: caisse 2010 - caisse 2009--}}
                                                                                                        <td style="background-color: #66CCFF">{{ $collectclasseB->total - $collectclasseBP->total}}</td>
                                                                                                        {{-- % Evolution--}}
                                                                                                        <td style="background-color: #66CCFF">
                                                                                                            @if($collectclasseBP->total != 0)
                                                                                                                {{ round((($collectclasseB->total - $collectclasseBP->total) / $collectclasseBP->total)*100,2) }}
                                                                                                            @else
                                                                                                                {{ 0 }}
                                                                                                            @endif
                                                                                                        </td>
                                                                                                        {{-- Evolution par rapport à An precedant Pays : Ex: caisse 2010 - caisse 2009--}}
                                                                                                        <td style="background-color: #66CCFF">{{ $collectclasseBGlobal->total - $collectclasseBGlobalP->total}}</td>
                                                                                                        <td style="background-color: #66CCFF">@if($collectclasseBGlobalP->total != 0)
                                                                                                                {{ round((($collectclasseBGlobal->total - $collectclasseBGlobalP->total) / $collectclasseBGlobalP->total)*100,2) }}
                                                                                                            @else
                                                                                                                {{ 0 }}
                                                                                                            @endif
                                                                                                        </td>

                                                                                                        <td style="background-color: #66CCFF">
                                                                                                            {{ $collectclasseBUEMOA->total - $collectclasseBUEMOAP->total }}
                                                                                                        </td>
                                                                                                        <td style="background-color: #66CCFF">
                                                                                                            @if($collectclasseBUEMOAP->total != 0)
                                                                                                                {{ round((($collectclasseBUEMOA->total - $collectclasseBUEMOAP->total)/$collectclasseBUEMOAP->total)*100,2) }}
                                                                                                            @else
                                                                                                            @endif
                                                                                                        </td>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endif
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            @else
                                                                                {{-- Si Pas UEMOA --}}
                                                                                <td style=" text-align: center;">{{ (int) $collectclasseB->total }}</td>
                                                                                <td style="text-align: center;color: #0000F0">
                                                                                    @if($collecttotalclasseB->total == 0)
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{ round(($collectclasseB->total / $collecttotalclasseB->total)*100,2)}}
                                                                                    @endif
                                                                                </td>
                                                                                <td style="background-color: #F3F3F3;text-align: center;">{{ (int) $collectclasseBGlobal->total }}</td>
                                                                                <td style="background-color: #F3F3F3;text-align: center;">
                                                                                    @if($collecttotalclasseBGlobal->total == 0)
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{ round(($collectclasseBGlobal->total / $collecttotalclasseBGlobal->total)*100,2 ) }}
                                                                                    @endif
                                                                                </td>
                                                                                {{-- Calcul part de marché entreprise(exemple : caisse BHS / Caisse Senegal) sur pays--}}
                                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                                    @if($collectclasseBGlobal->total == 0)
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{ round(($collectclasseB->total / $collectclasseBGlobal->total )*100,2) }}
                                                                                    @endif
                                                                                </td>
                                                                                {{-- Rapport des %--}}
                                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                                    @if($collectclasseBGlobal->total == 0 || $collecttotalclasseB->total == 0 )
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{round( (($collectclasseB->total * $collecttotalclasseBGlobal->total ) / ($collectclasseBGlobal->total * $collecttotalclasseB->total ))*100,2) }}
                                                                                    @endif
                                                                                </td>
                                                                                {{-- Evolution par rapport à An precedant Entreprise : Ex: caisse 2010 - caisse 2009--}}
                                                                                <td style="background-color: #66CCFF">{{ $collectclasseB->total - $collectclasseBP->total}}</td>
                                                                                {{-- % Evolution--}}
                                                                                <td style="background-color: #66CCFF">
                                                                                    @if($collectclasseBP->total != 0)
                                                                                        {{ round((($collectclasseB->total - $collectclasseBP->total) / $collectclasseBP->total)*100,2) }}
                                                                                    @else
                                                                                        {{ 0 }}
                                                                                    @endif
                                                                                </td>
                                                                                {{-- Evolution par rapport à An precedant Pays : Ex: caisse 2010 - caisse 2009--}}
                                                                                <td style="background-color: #66CCFF">{{ $collectclasseBGlobal->total - $collectclasseBGlobalP->total}}</td>
                                                                                <td style="background-color: #66CCFF">@if($collectclasseBGlobalP->total != 0)
                                                                                        {{ round((($collectclasseBGlobal->total - $collectclasseBGlobalP->total) / $collectclasseBGlobalP->total)*100,2) }}
                                                                                    @else
                                                                                        {{ 0 }}
                                                                                    @endif
                                                                                </td>
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            {{-- Si Par variation --}}
                                                            {{-- Si UEMOA --}}
                                                            @if($input['localite'] == 'uemoa')
                                                                {{-- Classes UEMOA --}}
                                                                @foreach($collectclassesBUEMOA as $collectclasseBUEMOA)
                                                                    @if($collectclasseBUEMOA->exercice != $collectclasseBGlobal ->exercice ||
                                                                    $collectclasseBGlobal->nomClasse != $collectclasseBUEMOA->nomClasse)
                                                                        @continue
                                                                    @else
                                                                        {{-- Total UEMOA --}}
                                                                        @foreach($collecttotalclassesBUEMOA as $collecttotalclasseBUEMOA)
                                                                            @if($collecttotalclasseBUEMOA->exercice != $collectclasseBUEMOA->exercice)
                                                                                @continue
                                                                            @else
                                                                                <td style=" text-align: center;">{{ (int) $collectclasseB->total }}
                                                                                </td>
                                                                                <td style="text-align: center;color: #0000F0">
                                                                                    @if($collecttotalclasseB->total == 0)
                                                                                        {{ $ce_s_te = 0 }}
                                                                                    @else
                                                                                        {{ $ce_s_te = round(($collectclasseB->total / $collecttotalclasseB->total)*100,2)}}
                                                                                    @endif
                                                                                </td>
                                                                                <td style="background-color: #F3F3F3;text-align: center;">{{ (int) $collectclasseBGlobal->total }}

                                                                                </td>
                                                                                <td style="background-color: #F3F3F3;text-align: center;">
                                                                                    @if($collecttotalclasseBGlobal->total == 0)
                                                                                        {{ $cp_s_tp = 0 }}
                                                                                    @else
                                                                                        {{ $cp_s_tp = round(($collectclasseBGlobal->total / $collecttotalclasseBGlobal->total)*100,2 ) }}
                                                                                    @endif
                                                                                </td>
                                                                                <td>{{ (int)$collectclasseBUEMOA->total }}</td>
                                                                                <td>
                                                                                    @if($collecttotalclasseBUEMOA->total == 0) {{ $cu_s_tu = 0 }}
                                                                                    @else {{ $cu_s_tu = round(($collectclasseBUEMOA->total / $collecttotalclasseBUEMOA->total)*100,2) }} @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                                    @if($collectclasseBGlobal->total == 0)
                                                                                        {{ $ce_s_tp = 0 }}
                                                                                    @else
                                                                                        {{ $ce_s_tp = round(($collectclasseB->total / $collectclasseBGlobal->total )*100,2) }}
                                                                                    @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                                    @if($cp_s_tp == 0 )
                                                                                        {{ 0 }}
                                                                                    @else
                                                                                        {{round( ($ce_s_te / $cp_s_tp)*100,2) }}
                                                                                    @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                                    @if($collecttotalclasseBUEMOA->total == 0) {{ $ce_s_tu = 0 }}
                                                                                    @else {{ $ce_s_tu = round(($collectclasseB->total / $collecttotalclasseBUEMOA->total)*100,2) }} @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                                    @if($cu_s_tu == 0) {{ 0 }}
                                                                                    @else {{ round(($ce_s_te / $cu_s_tu)*100,2) }} @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                                    @if($collecttotalclasseBUEMOA->total == 0) {{ $ce_s_tu = 0 }}
                                                                                    @else {{ $cp_s_tu = round(($collectclasseBGlobal->total / $collecttotalclasseBUEMOA->total)*100,2) }} @endif
                                                                                </td>
                                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                                    @if($cu_s_tu == 0) {{ 0 }}
                                                                                    @else {{ round(($cp_s_tp / $cu_s_tu)*100,2) }} @endif
                                                                                </td>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                {{-- Si pas UEMOA --}}
                                                                <td style=" text-align: center;">{{ (int) $collectclasseB->total }}
                                                                </td>
                                                                <td style="text-align: center;color: #0000F0">
                                                                    @if($collecttotalclasseB->total == 0)
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{ round(($collectclasseB->total / $collecttotalclasseB->total)*100,2)}}
                                                                    @endif
                                                                </td>
                                                                <td style="background-color: #F3F3F3;text-align: center;">{{ (int) $collectclasseBGlobal->total }}

                                                                </td>
                                                                <td style="background-color: #F3F3F3;text-align: center;">
                                                                    @if($collecttotalclasseBGlobal->total == 0)
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{ round(($collectclasseBGlobal->total / $collecttotalclasseBGlobal->total)*100,2 ) }}
                                                                    @endif
                                                                </td>

                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                    @if($collectclasseBGlobal->total == 0)
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{ round(($collectclasseB->total / $collectclasseBGlobal->total )*100,2) }}
                                                                    @endif
                                                                </td>
                                                                <td style="background-color: #BCDBC5;text-align: center;">
                                                                    @if($collectclasseBGlobal->total == 0 || $collecttotalclasseB->total == 0 )
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{round( (($collectclasseB->total * $collecttotalclasseBGlobal->total ) / ($collectclasseBGlobal->total * $collecttotalclasseB->total ))*100,2) }}
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
                {{-- Si Par variation affichage des diff et ecarts --}}
                @if($input['naturep'] != 'paran')
                    @foreach($collectclassesB as $cca)
                        @if($cca->exercice != $exercice1)
                            @continue
                        @else
                            @foreach($collectclassesB as $cca1)
                                @if($cca1->exercice != $exercice2 ||
                                $classeB->nomClasse != $cca->nomClasse ||
                                $cca->nomClasse != $cca1->nomClasse)
                                    @continue
                                @else
                                    @foreach($collectclassesBGlobal as $ccg)
                                        @if($ccg->exercice != $exercice1)
                                            @continue
                                        @else
                                            @foreach($collectclassesBGlobal as $ccg1)
                                                @if($ccg1->exercice != $exercice2 ||
                                                $classeB->nomClasse != $ccg->nomClasse ||
                                                $ccg->nomClasse != $ccg1->nomClasse)
                                                    @continue
                                                @else
                                                    {{-- Si UEMOA --}}
                                                    @if($input['localite'] == 'uemoa')
                                                        @foreach($collectclassesBUEMOA as $ccu)
                                                            @if($ccu->exercice != $exercice1)
                                                                @continue
                                                            @else
                                                                @foreach($collectclassesBUEMOA as $ccu1)
                                                                    @if($ccu1->exercice != $exercice2 ||
                                                                    $classeB->nomClasse != $ccu->nomClasse ||
                                                                    $ccu->nomClasse != $ccu1->nomClasse)
                                                                        @continue
                                                                    @else
                                                                        <td style="background-color: #66CCFF">
                                                                            {{ (int) ($cca1->total - $cca->total) }}
                                                                        </td>
                                                                        <td style="background-color: #66CCFF">
                                                                            {{ (int) ($ccg1->total - $ccg->total) }}
                                                                        </td>
                                                                        <td style="background-color: #66CCFF">{{ (int) $ccu1->total - $ccu->total }}</td>
                                                                        <td style="background-color: #66CCFF">
                                                                            @if( $cca->total == 0)
                                                                                {{ 0 }}
                                                                            @else
                                                                                {{ round( (($cca1->total-$cca->total) / $cca->total)*100,2) }}
                                                                            @endif
                                                                        </td>
                                                                        <td style="background-color: #66CCFF">
                                                                            @if( $ccg->total == 0)
                                                                                {{ 0 }}
                                                                            @else
                                                                                {{ round( (($ccg1->total-$ccg->total) / $ccg->total)*100,2) }}
                                                                            @endif
                                                                        </td>
                                                                        <td style="background-color: #66CCFF">
                                                                            @if( $ccu->total == 0)
                                                                                {{ 0 }}
                                                                            @else
                                                                                {{ round( (($ccu1->total-$ccu->total) / $ccu->total)*100,2) }}
                                                                            @endif
                                                                        </td>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        {{-- Si Pas UEMOA --}}
                                                        <td style="background-color: #66CCFF">
                                                            {{ (int) ($cca1->total - $cca->total) }}
                                                        </td>
                                                        <td style="background-color: #66CCFF">
                                                            {{ (int) ($ccg1->total - $ccg->total) }}
                                                        </td>
                                                        <td style="background-color: #66CCFF">
                                                            @if( $cca->total == 0)
                                                                {{ 0 }}
                                                            @else
                                                                {{ round( (($cca1->total-$cca->total) / $cca->total)*100,2) }}
                                                            @endif
                                                        </td>
                                                        <td style="background-color: #66CCFF">
                                                            @if( $ccg->total == 0)
                                                                {{ 0 }}
                                                            @else
                                                                {{ round( (($ccg1->total-$ccg->total) / $ccg->total)*100,2) }}
                                                            @endif
                                                        </td>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endif
            </tr>
        @endforeach
        <tr style="font-size: 13px;text-align: center">
            <th style="text-align: right;">
                @foreach($collectclassesB as $collectclasseB)
                    {{'TOTBL '. strtoupper($collectclasseB->nature)  }}
                    @break;
                @endforeach
            </th>
            @foreach($collecttotalclassesB as $collecttotalclasseB)
                @foreach($collecttotalclassesBGlobal as $collecttotalclasseBGlobal)
                    @if($collecttotalclasseBGlobal->exercice != $collecttotalclasseB ->exercice)
                        @continue
                    @else
                        @foreach($exercices as $exercice)
                            @if($collecttotalclasseBGlobal->exercice != $exercice->exercice)
                                @continue
                            @else
                                {{-- Tatal Par An --}}
                                @if($input['naturep'] == 'paran')
                                    @foreach($collecttotalclassesB as $collecttotalclasseBP)
                                        @if($collecttotalclasseBP->exercice != $exercice->exercice -1)
                                            @continue
                                        @else
                                            @foreach($collecttotalclassesBGlobal as $collecttotalclasseBGlobalP)
                                                @if($collecttotalclasseBGlobalP->exercice != $exercice->exercice -1)
                                                    @continue
                                                @else
                                                    {{-- Si UEMOA --}}
                                                    @if($input['localite'] == 'uemoa')
                                                        @foreach($collecttotalclassesBUEMOA as $collecttotalclasseBUEMOA)
                                                            @if($collecttotalclasseBUEMOA->exercice != $exercice->exercice )
                                                                @continue
                                                            @else
                                                                @foreach($collecttotalclassesBUEMOA as $collecttotalclasseBUEMOAP)
                                                                    @if($collecttotalclasseBUEMOAP->exercice != $exercice->exercice -1)
                                                                        @continue
                                                                    @else
                                                                        <th style="color: #20c997">
                                                                            {{ (int) $collecttotalclasseB->total }}
                                                                        </th>
                                                                        <th style="color: #0000F0">{{100}}</th>
                                                                        <th style="background-color: #F3F3F3;text-align: center;">{{ (int) $collecttotalclasseBGlobal->total}}</th>
                                                                        <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>
                                                                        <th>{{ (int) $collecttotalclasseBUEMOA->total }}</th>
                                                                        <th>{{ 100 }}</th>
                                                                        <th style="background-color: #BCDBC5;text-align: center;">
                                                                            @if($collecttotalclasseBGlobal->total != 0)
                                                                                {{ round(($collecttotalclasseB->total / $collecttotalclasseBGlobal->total)*100, 2) }}
                                                                            @else
                                                                                {{ 0 }}
                                                                            @endif
                                                                        </th>
                                                                        <th style="background-color: #BCDBC5;text-align: center;">
                                                                            {{100}}
                                                                        </th>
                                                                        <th style="background-color: #BCDBC5;text-align: center;">
                                                                            @if($collecttotalclasseBUEMOA->total != 0)
                                                                                {{ round(($collecttotalclasseB->total / $collecttotalclasseBUEMOA->total)*100, 2) }}
                                                                            @else
                                                                                {{ 0 }}
                                                                            @endif
                                                                        </th>
                                                                        <th style="background-color: #BCDBC5;text-align: center;">{{ 0 }}</th>
                                                                        <th style="background-color: #BCDBC5;text-align: center;">
                                                                            @if($collecttotalclasseBUEMOA->total != 0)
                                                                                {{ round(($collecttotalclasseBGlobal->total / $collecttotalclasseBUEMOA->total)*100, 2) }}
                                                                            @else
                                                                                {{ 0 }}
                                                                            @endif
                                                                        </th>
                                                                        <th style="background-color: #BCDBC5;text-align: center;">{{ 0 }}</th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">{{ $collecttotalclasseB->total - $collecttotalclasseBP->total }}</th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                                            @if($collecttotalclasseBP->total != 0)
                                                                                {{ round((($collecttotalclasseB->total - $collecttotalclasseBP->total) /  $collecttotalclasseBP->total)*100,2) }}
                                                                            @else
                                                                                {{ 0 }}
                                                                            @endif
                                                                        </th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">{{ $i = (int)$collecttotalclasseBGlobal->total - $collecttotalclasseBGlobalP->total}}</th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                                            @if($collecttotalclasseBGlobalP->total == 0) {{ 0 }}
                                                                            @else {{round(($i/ $collecttotalclasseBGlobalP->total)*100,2)}} @endif
                                                                        </th>

                                                                        <th style="background-color: #66CCFF;text-align: center;">{{$ii = (int)$collecttotalclasseBUEMOA->total - $collecttotalclasseBUEMOAP->total }}</th>
                                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                                            @if($collecttotalclasseBUEMOAP->total == 0)
                                                                                {{ 0 }}
                                                                            @else
                                                                                {{round(($ii/ $collecttotalclasseBUEMOAP->total)*100,2)}} @endif
                                                                        </th>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        {{-- Si pas UEMOA --}}
                                                        <th style="color: #20c997">
                                                            {{ (int) $collecttotalclasseB->total }}
                                                        </th>
                                                        <th style="color: #0000F0">{{100}}</th>
                                                        <th style="background-color: #F3F3F3;text-align: center;">{{ (int) $collecttotalclasseBGlobal->total}}</th>
                                                        <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>

                                                        <th style="background-color: #BCDBC5;text-align: center;">
                                                            @if($collecttotalclasseBGlobal->total != 0)
                                                                {{ round(($collecttotalclasseB->total / $collecttotalclasseBGlobal->total)*100, 2) }}
                                                            @else
                                                                {{ 0 }}
                                                            @endif
                                                        </th>
                                                        <th style="background-color: #BCDBC5;text-align: center;">{{100}}
                                                        </th>

                                                        <th style="background-color: #66CCFF;text-align: center;">{{ $collecttotalclasseB->total - $collecttotalclasseBP->total }}</th>
                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                            @if($collecttotalclasseBP->total != 0)
                                                                {{ round((($collecttotalclasseB->total - $collecttotalclasseBP->total) /  $collecttotalclasseBP->total)*100,2) }}
                                                            @else
                                                                {{ 0 }}
                                                            @endif
                                                        </th>
                                                        <th style="background-color: #66CCFF;text-align: center;">{{$r = (int)($collecttotalclasseBGlobal->total - $collecttotalclasseBGlobalP->total)}}</th>
                                                        <th style="background-color: #66CCFF;text-align: center;">
                                                            @if($collecttotalclasseBGlobalP->total == 0)
                                                                {{0}}
                                                            @else
                                                                {{ round(($r / $collecttotalclasseBGlobalP->total)*100,2) }}
                                                            @endif
                                                        </th>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    {{-- Par Variation--}}
                                    {{-- Si UEMOA --}}
                                    @if($input['localite'] == 'uemoa')
                                        @foreach($collecttotalclassesBUEMOA as $collecttotalclasseBUEMOA)
                                            @if($collecttotalclasseBUEMOA->exercice != $exercice->exercice )
                                                @continue
                                            @else
                                                <th style="color: #20c997">
                                                    {{ (int) $collecttotalclasseB->total }}
                                                </th>
                                                <th style="color: #0000F0">{{100}}</th>
                                                <th style="background-color: #F3F3F3;text-align: center;">{{ (int) $collecttotalclasseBGlobal->total}}</th>
                                                <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>
                                                <th style="background-color: #F3F3F3;text-align: center;">{{(int) $collecttotalclasseBUEMOA->total}}</th>
                                                <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>
                                                <th style="background-color: #BCDBC5;text-align: center;">
                                                    @if($collecttotalclasseBGlobal->total != 0)
                                                        {{ round(($collecttotalclasseB->total / $collecttotalclasseBGlobal->total)*100, 2) }}
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                </th>
                                                <th style="background-color: #BCDBC5;text-align: center;">{{ 100 }}</th>
                                                <th style="background-color: #BCDBC5;text-align: center;">
                                                    @if($collecttotalclasseBUEMOA->total == 0) {{ 0 }}
                                                    @else {{ round(($collecttotalclasseB->total / $collecttotalclasseBUEMOA->total)*100, 2) }}
                                                    @endif
                                                </th >
                                                <th style="background-color: #BCDBC5;text-align: center;">{{ 100 }}</th>
                                                <th style="background-color: #BCDBC5;text-align: center;">@if($collecttotalclasseBUEMOA->total == 0) {{ 0 }}
                                                    @else {{ round(($collecttotalclasseBGlobal->total / $collecttotalclasseBUEMOA->total)*100, 2) }}
                                                    @endif
                                                </th>
                                                <th style="background-color: #BCDBC5;text-align: center;">{{ 100 }}</th>
                                            @endif
                                        @endforeach
                                    @else
                                        {{-- Si Pas UEMOA --}}
                                        <th style="color: #20c997">
                                            {{ (int) $collecttotalclasseB->total }}
                                        </th>
                                        <th style="color: #0000F0">{{100}}</th>
                                        <th style="background-color: #F3F3F3;text-align: center;">{{ (int) $collecttotalclasseBGlobal->total}}</th>
                                        <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>
                                        <th style="background-color: #BCDBC5;text-align: center;">
                                            @if($collecttotalclasseBGlobal->total != 0)
                                                {{ round(($collecttotalclasseB->total / $collecttotalclasseBGlobal->total)*100, 2) }}
                                            @else
                                                {{ 0 }}
                                            @endif
                                        </th>
                                        <th style="background-color: #BCDBC5;text-align: center;">{{ 100 }}</th>

                                    @endif
                                @endif
                            @endif
                        @endforeach

                    @endif
                @endforeach
            @endforeach
            @if($input['naturep'] == 'variation')
                @foreach($collecttotalclassesB as $cca)
                    @if($cca->exercice != $exercice1)
                        @continue
                    @else
                        @foreach($collecttotalclassesB as $cca1)
                            @if($cca1->exercice != $exercice2 )
                                @continue
                            @else
                                @foreach($collecttotalclassesBGlobal as $ccg)
                                    @if($ccg->exercice != $exercice1)
                                        @continue
                                    @else
                                        @foreach($collecttotalclassesBGlobal as $ccg1)
                                            @if($ccg1->exercice != $exercice2 )
                                                @continue
                                            @else
                                                @if($input['localite'] == 'pays')
                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $cca1->total - $cca->total }}</th>
                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $ccg1->total - $ccg->total }}</th>
                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($cca1->total - $cca->total) / $cca->total)*100,2) }}</th>
                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($ccg1->total - $ccg->total) / $ccg->total)*100,2) }}</th>
                                                @else
                                                    @foreach($collecttotalclassesBUEMOA as $ccu)
                                                        @if($ccu->exercice != $exercice1)
                                                            @continue
                                                        @else
                                                            @foreach($collecttotalclassesBUEMOA as $ccu1)
                                                                @if($ccu1->exercice != $exercice2 )
                                                                    @continue
                                                                @else
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $cca1->total - $cca->total }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $ccg1->total - $ccg->total }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{(int) $ccu1->total - $ccu->total }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($cca1->total - $cca->total) / $cca->total)*100,2) }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($ccg1->total - $ccg->total) / $ccg->total)*100,2) }}</th>
                                                                    <th style="background-color: #66CCFF;text-align: center;">{{ round((($ccu1->total - $ccu->total) / $ccu->total)*100,2) }}</th>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
        </tr>
        </tbody>
    </table>

    </div>
</div>
