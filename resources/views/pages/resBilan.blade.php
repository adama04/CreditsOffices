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
{{--{{ dd($collectclassesA) }}--}}
{{--{{ dump($collecttotalclassesA) }}--}}
{{--{{ dd($collecttotalclassesAGlobal) }}--}}
{{--{{ dd($exercices) }}--}}
{{--{{ dd($collecttotalclassesA) }}--}}
<div class="container">
    <div class="card">
        <div class="card-body">
            @foreach($infoEntreprises as $infoEntreprise )
                <div class="form-row" style="font-family: 'Times New Roman'; color: #3f9ae5; font-size: 11px">
                    <div class="col-md-6">
                        <label for="">
                            Numero Registre : {{$infoEntreprise->numRegistre}}
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label for="">
                            Secteur : {{$infoEntreprise->nomSecteur}}
                        </label>
                    </div>
                </div>
                <div class="form-row" style="font-family: 'Times New Roman'; color: #3f9ae5; font-size: 13px">
                    <div class="col-md-6">
                        <label for="">
                            Raison Sociale : {{ $infoEntreprise->nomEntreprise }}
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label for="">
                            Activité principal :
                            {{ $infoEntreprise->nomsouSecteur }}
                        </label>
                    </div>
                </div>
                <div class="form-row" style="font-family: 'Times New Roman'; color: #3f9ae5; font-size: 13px">
                    <div class="col-md-6">
                        <label for="">
                            Adresse : {{$infoEntreprise->Adresse}}
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label for="">
                            Services :
                            {{$infoEntreprise->nomService}}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@if($exercice2 - $exercice1 >= 5)
    <div class="alert alert-danger container">
        {{ 'Periode trop grand choisir sur 5 ans' }}
    </div>
@else
    <table class="table table-condensed" style="font-size: 12px">
        <thead  >
        <tr style="font-size: 14px">
            <th style="text-align: right;">Exrecices : </th>
            @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )
                <th colspan="8" style="background-color: #66CCFF; text-align: center; ">{{ $exo }}</th>
            @endfor
        </tr>
            <tr style="text-align: center;">
                <th></th>
                @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )
                    <th colspan="2">{{$infoEntreprise->Sigle }}</th>
                    <th colspan="2" style="background-color: #F3F3F3; ">Pays</th>
                    <th colspan="2" style="background-color: #BCDAC5">Indicateurs</th>
                    <th colspan="2" style="background-color: #3f9ae5">&Eacute;volution </th>
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
                <th style="background-color: #3f9ae5">Brut</th>
                <th style="background-color: #3f9ae5">%</th>
            @endfor
        </tr>
        </thead>
        <tbody>
        @foreach($classesA as $classeA)
            <tr style="font-size: 12px; text-align: right;">
                <th >{{ $classeA->nomClasse }}</th>
                @foreach($collectclassesA as $collectclasseA)
                    @if($collectclasseA->nomClasse != $classeA->nomClasse)
                        @continue
                    @else
                        @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )
                            @if($collectclasseA->exercice != $exo)
                                @continue
                            @else
                                @foreach($collecttotalclassesA as $collecttotalclasseA)
                                    @if($collecttotalclasseA->exercice != $exo)
                                        @continue
                                    @else
                                        @foreach($collectclassesAGlobal as $collectclasseAGlobal)
                                            @if($collecttotalclasseA->exercice != $collectclasseAGlobal ->exercice ||
                                            $collectclasseAGlobal->nomClasse != $collectclasseA->nomClasse)
                                                @continue
                                            @else
                                                @foreach($collecttotalclassesAGlobal as $collecttotalclasseAGlobal)
                                                    @if($collecttotalclasseA->exercice != $collecttotalclasseAGlobal->exercice)
                                                        @continue
                                                    @else
                                                        @foreach($collectclassesA as $collectclasseAP)
                                                            @if($collectclasseAP->exercice != ($collectclasseA->exercice -1) ||
                                                            $collectclasseAP->nomClasse != $collectclasseA->nomClasse)
                                                                @continue
                                                            @else
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
                                                                <td style="background-color: #3f9ae5">{{ $collectclasseA->total - $collectclasseAP->total}}</td>
                                                                <td style="background-color: #3f9ae5">
                                                                    @if($collectclasseAP->total != 0)
                                                                        {{ round((($collectclasseA->total - $collectclasseAP->total) / $collectclasseAP->total)*100,2) }}
                                                                    @else
                                                                        {{ 0 }}
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endfor
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
                @foreach($collecttotalclassesAGlobal as $collecttotalclasseAGlobal)
                    @if($collecttotalclasseAGlobal->exercice != $collecttotalclasseA ->exercice)
                        @continue
                    @else
                        @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )
                            @if($collecttotalclasseAGlobal->exercice != $exo)
                                @continue
                            @else
                                @foreach($collecttotalclassesA as $collecttotalclasseAP)
                                    @if($collecttotalclasseAP->exercice != $exo -1)
                                        @continue
                                    @else
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
                                        <th style="background-color: #BCDAC5;text-align: center;">{{ 1 }}</th>
                                        <th style="background-color: #3f9ae5;text-align: center;">{{ $collecttotalclasseA->total - $collecttotalclasseAP->total }}</th>
                                        <th style="background-color: #3f9ae5;text-align: center;">
                                            @if($collecttotalclasseAP->total != 0)
                                                {{ round((($collecttotalclasseA->total - $collecttotalclasseAP->total) /  $collecttotalclasseAP->total)*100,2) }}
                                            @else
                                                {{ 0 }}
                                            @endif
                                        </th>
                                    @endif
                                @endforeach
                            @endif
                        @endfor
                    @endif
                @endforeach
            @endforeach
        </tr>
        {{-- Passifs ou Produits --}}
{{--        <tr>--}}
{{--            <th style="background-color: #D0FDEB; text-align: left;">--}}
{{--                @foreach($collectclassesB as $collectclasseB)--}}
{{--                    {{ strtoupper($collectclasseB->nature)  }}--}}
{{--                    @break;--}}
{{--                @endforeach--}}
{{--            </th>--}}
{{--            @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )--}}
{{--                <th ></th>--}}
{{--                <th ></th>--}}
{{--                <th ></th>--}}
{{--                <th ></th>--}}
{{--                <th ></th>--}}
{{--                <th ></th>--}}
{{--            @endfor--}}
{{--        </tr>--}}
{{--        @foreach($classesB as $classeB)--}}
{{--            <tr style="font-size: 12px; text-align: right;">--}}
{{--                <th >{{ $classeB->nomClasse }}</th>--}}
{{--                @foreach($collectclassesB as $collectclasseB)--}}
{{--                    @if($collectclasseB->nomClasse != $classeB->nomClasse)--}}
{{--                        @continue--}}
{{--                    @else--}}
{{--                        @foreach($exercices as $exercice)--}}
{{--                            @if($collectclasseB->exercice != $exercice->exercice)--}}
{{--                                @continue--}}
{{--                            @else--}}
{{--                                @foreach($collecttotalclassesB as $collecttotalclasseB)--}}
{{--                                    @if($collecttotalclasseB->exercice != $exercice->exercice)--}}
{{--                                        @continue--}}
{{--                                    @else--}}
{{--                                        @foreach($collectclassesBGlobal as $collectclasseBGlobal)--}}
{{--                                            @if($collecttotalclasseB->exercice != $collectclasseBGlobal ->exercice ||--}}
{{--                                            $collectclasseBGlobal ->nomClasse != $collectclasseB->nomClasse )--}}
{{--                                                @continue--}}
{{--                                            @else--}}
{{--                                                @foreach($collecttotalclassesBGlobal as $collecttotalclasseBGlobal)--}}
{{--                                                    @if($collecttotalclasseB->exercice != $collecttotalclasseBGlobal->exercice)--}}
{{--                                                        @continue--}}
{{--                                                    @else--}}
{{--                                                        @foreach($collectclassesB as $collectclasseBP)--}}
{{--                                                            @if($collectclasseBP->exercice != (1 + $collectclasseB->exercice) ||--}}
{{--                                                            $collectclasseBP->nomClasse != $collectclasseB->nomClasse)--}}
{{--                                                                @continue--}}
{{--                                                            @else--}}
{{--                                                                <td style=" text-align: center;">{{ (int) $collectclasseB->total }}</td>--}}
{{--                                                                <td style="text-align: center;color: #0000F0">--}}
{{--                                                                    @if($collecttotalclasseB->total == 0)--}}
{{--                                                                        {{ 0 }}--}}
{{--                                                                    @else--}}
{{--                                                                        {{ round(($collectclasseB->total / $collecttotalclasseB->total)*100,2)}}--}}
{{--                                                                    @endif--}}
{{--                                                                </td>--}}
{{--                                                                <td style="background-color: #F3F3F3;text-align: center;">{{ (int) $collectclasseBGlobal->total }}</td>--}}
{{--                                                                <td style="background-color: #F3F3F3;text-align: center;">--}}
{{--                                                                    @if($collecttotalclasseBGlobal->total == 0)--}}
{{--                                                                        {{ 0 }}--}}
{{--                                                                    @else--}}
{{--                                                                        {{ round(($collectclasseBGlobal->total / $collecttotalclasseBGlobal->total)*100,2 ) }}--}}
{{--                                                                    @endif--}}
{{--                                                                </td>--}}
{{--                                                                <td style="background-color: #BCDAC5;text-align: center;">--}}
{{--                                                                    @if($collectclasseBGlobal->total == 0)--}}
{{--                                                                        {{ 0 }}--}}
{{--                                                                    @else--}}
{{--                                                                        {{ round(($collectclasseB->total / $collectclasseBGlobal->total )*100,2) }}--}}
{{--                                                                    @endif--}}

{{--                                                                </td>--}}
{{--                                                                <td style="background-color: #BCDAC5;text-align: center;">--}}
{{--                                                                    @if($collectclasseBGlobal->total == 0 || $collecttotalclasseB->total == 0 )--}}
{{--                                                                        {{ 0 }}--}}
{{--                                                                    @else--}}
{{--                                                                        {{round( (($collectclasseB->total * $collecttotalclasseBGlobal->total ) / ($collectclasseBGlobal->total * $collecttotalclasseB->total ))*100,2) }}--}}
{{--                                                                    @endif--}}
{{--                                                                </td>--}}
{{--                                                                <td style="background-color: #3f9ae5">{{ $collectclasseBP->total - $collectclasseB->total}}</td>--}}
{{--                                                                <td style="background-color: #3f9ae5">--}}
{{--                                                                    @if($collectclasseB->total != 0)--}}
{{--                                                                        {{ round((($collectclasseBP->total - $collectclasseB->total) / $collectclasseB->total)*100,2) }}--}}
{{--                                                                    @else--}}
{{--                                                                        {{ 0 }}--}}
{{--                                                                    @endif--}}
{{--                                                                </td>--}}
{{--                                                            @endif--}}
{{--                                                        @endforeach--}}
{{--                                                    @endif--}}
{{--                                                @endforeach--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                            @endif--}}
{{--                        @endforeach--}}
{{--                    @endif--}}
{{--                @endforeach--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--        <tr style="font-size: 13px;text-align: center">--}}
{{--            <th style="text-align: right;">--}}
{{--                @foreach($collectclassesB as $collectclasseB)--}}
{{--                    {{'TOTAL '. strtoupper($collectclasseB->nature)  }}--}}
{{--                    @break;--}}
{{--                @endforeach--}}
{{--            </th>--}}
{{--            @foreach($collecttotalclassesB as $collecttotalclasseB)--}}
{{--                @foreach($collecttotalclassesBGlobal as $collecttotalclasseBGlobal)--}}
{{--                    @if($collecttotalclasseBGlobal->exercice != $collecttotalclasseB ->exercice)--}}
{{--                        @continue--}}
{{--                    @else--}}
{{--                        @for ($exo = $exercice1; $exo<=$exercice2; $exo++ )--}}
{{--                            @if($collecttotalclasseBGlobal->exercice != $exo)--}}
{{--                                @continue--}}
{{--                            @else--}}
{{--                                <th style="color: #20c997">--}}
{{--                                    {{ (int) $collecttotalclasseB->total }}--}}
{{--                                </th>--}}
{{--                                <th style="color: #0000F0">{{100}}</th>--}}
{{--                                <th style="background-color: #F3F3F3;text-align: center;">{{ (int) $collecttotalclasseBGlobal->total}}</th>--}}
{{--                                <th style="background-color: #F3F3F3;text-align: center;">{{ 100 }}</th>--}}
{{--                                <th style="background-color: #BCDAC5;text-align: center;">--}}
{{--                                    @if($collecttotalclasseBGlobal->total != 0)--}}
{{--                                        {{ round(($collecttotalclasseB->total / $collecttotalclasseBGlobal->total)*100, 2) }}--}}
{{--                                    @else--}}
{{--                                        {{ 0 }}--}}
{{--                                    @endif--}}
{{--                                </th>--}}
{{--                                <th style="background-color: #BCDAC5;text-align: center;">{{ 1 }}</th>--}}
{{--                                <th style="background-color: #3f9ae5;text-align: center;"></th>--}}
{{--                                <th style="background-color: #3f9ae5;text-align: center;"></th>--}}
{{--                            @endif--}}
{{--                        @endfor--}}
{{--                    @endif--}}
{{--                @endforeach--}}
{{--            @endforeach--}}
{{--        </tr>--}}
        </tbody>
    </table>
@endif

