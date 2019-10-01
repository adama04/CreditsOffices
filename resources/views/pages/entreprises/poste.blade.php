@include('templates._assets')
@if($input['exercice1'] > $input['exercice2'])
    @php
        $exercice1 = $input['exercice2'];
        $exercice2 = $input['exercice1'];

    @endphp
@else
    @php($exercice1 = $input['exercice1'])
    @php($exercice2 = $input['exercice2'])
@endif
<br>
{{--{{ dump($collectPostes) }}--}}
{{--{{ dump($collecttotalPostes) }}--}}
{{--{{ dd($collectPosteUEMOA) }}--}}
<div class="container"><hr>
    <div class="form-group row">
        <div class="col col-md-4 sm-4" style="text-align: center; color: #0000F0; font-style: italic; font-weight: bolder">
            {!! '<h3><strong>ENTREPRISE</strong></h3>'!!}
        </div>
        <div class="col col-md-4 sm-4" style="text-align: center; color: #0000F0; font-style: italic; font-weight: bolder">
            {!! '<h3><strong>POSTE</strong></h3>' !!}
        </div>
        <div class="col col-md-4 sm-4" style="text-align: center; color: #0000F0; font-style: italic; font-weight: bolder;">
            {!! '<h3><strong>PERIODE</strong></h3>' !!}
        </div>
    </div>
    <div class="form-group row">
        <div class="col col-md-4 sm-4" style="text-align: center;">
            {!! '<h4><strong>'.explode('-', $input['idEntreprise'])[1].'</strong></h4>'!!}
        </div>
        <div class="col col-md-4 sm-4" style="text-align: center;">
            {!! '<h4><strong>'.$Poste_->nomRubrique.'</strong></h4>' !!}
        </div>
        <div class="col col-md-4 sm-4" style="text-align: center;">
            @if($input['naturep'] == 'variation')
                {!! '<h4><strong>'.$exercice1 .' - '.$exercice2. '</strong></h4>' !!}
            @else
                {!! '<h4><strong>DE '.$exercice1.' À '.$exercice2.' </strong></h4>' !!}
            @endif
        </div>
    </div>
    <hr>
    <br>
    <table class="table table-condensed table-bordered" style="font-size: 12px">
        <thead>
            <tr style="font-size: 14px">
                <th style="text-align: right;">Exrecices :</th>
                @if($input['naturep'] == 'variation')
                    @if($input['localite'] == 'uemoa')
                        @php($colspan = 11)
                    @else
                        @php($colspan = 7)
                    @endif
                @else
                    @if($input['localite'] == 'uemoa')
                        @php($colspan = 13)
                    @else
                        @php($colspan = 9)
                    @endif
                @endif
                @foreach($exercices as $exercice)
                    <th style="background-color: #66CCFF; text-align: center; "
                        colspan="{{ $colspan }}">{{ $exercice->exercice }} </th>
                @endforeach
                @if ($input['naturep'] == 'variation')
                    <th colspan="2">Variation</th>
                @endif
            </tr>
            <tr style="text-align: center">
                <th></th>
                @foreach($exercices as $exercice)
                    <th colspan="2">{{ $Sigle->Sigle }}</th>
                    <th colspan="2">{{ 'PAYS' }}</th>
                    @if($input['localite'] == 'uemoa')
                        <th colspan="2">UEMOA</th>
                    @endif
                    <th colspan="@if($input['localite'] == 'uemoa') {{ 5 }} @else {{ 3 }} @endif" style="background-color: #1fffac">Indicateur</th>
                    @if ($input['naturep'] != 'variation')
                        <th colspan="2">Variation</th>
                    @endif
                @endforeach
                @if ($input['naturep'] == 'variation')
                    <th colspan="2">Variation</th>
                @endif
            </tr>
        </thead>
        <tbody>
            <th>{{ $Poste_->nomRubrique }}</th>
            @foreach($exercices as $exercice)
                @foreach($collectPostes as $collectPoste)
                    @if($collectPoste->exercice != $exercice->exercice)
                        @continue
                    @else
                        @foreach($collecttotalPostes as $collecttotalPoste)
                            @if($collecttotalPoste->exercice != $collectPoste->exercice)
                                @continue
                            @else
                                @foreach($collectSameNatures as $collectSameNature)
                                    @if($collectSameNature->exercice != $collecttotalPoste->exercice)
                                        @continue
                                    @else
                                        @foreach($collectSameNatureCountries as $collectSameNatureCountrie)
                                            @if($collectSameNatureCountrie->exercice != $collectSameNature->exercice)
                                                @continue
                                            @else
                                                <td>{{ (int)$collectPoste->brut }}</td>
                                                <td>{{ (int)$collectSameNature->total }}</td>
                                                <td>{{ (int)$collecttotalPoste->total }}</td>
                                                <td>{{ (int)$collectSameNatureCountrie->total }}</td>
                                                @if($input['localite'] == 'uemoa')
                                                    @foreach($collectPosteUEMOA as $cpUEMOA)
                                                        @if($collectSameNatureCountrie->exercice != $cpUEMOA->exercice)
                                                            @continue
                                                        @else
                                                            @foreach($collectSameNatureUEMOA as $csnUEMOA)
                                                                @if($csnUEMOA->exercice != $cpUEMOA->exercice)
                                                                    @continue
                                                                @else
                                                                    <th>{{ (int)$cpUEMOA->total }}</th>
                                                                    <th>{{ (int)$csnUEMOA->total }}</th>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <td style="background-color: #1fffac">
                                                    @if($collectSameNature->total != 0)
                                                        {{round(($collectPoste->brut / $collectSameNature->total)*100,2)}}
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                </td>
                                                <td style="background-color: #1fffac">
                                                    @if($collecttotalPoste->total != 0)
                                                        {{round(($collectPoste->brut / $collecttotalPoste->total)*100,2)}}
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                </td>
                                                <td style="background-color: #1fffac">
                                                    @if($collectSameNatureCountrie->total != 0)
                                                        {{round(($collectPoste->brut / $collectSameNatureCountrie->total)*100,2)}}
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                </td>
                                                @if($input['localite'] == 'uemoa')
                                                    @foreach($collectPosteUEMOA as $cpUEMOA)
                                                        @if($collectSameNatureCountrie->exercice != $cpUEMOA->exercice)
                                                            @continue
                                                        @else
                                                            @foreach($collectSameNatureUEMOA as $csnUEMOA)
                                                                @if($csnUEMOA->exercice != $cpUEMOA->exercice)
                                                                    @continue
                                                                @else
                                                                    <td style="background-color: #1fffac">
                                                                        @if($cpUEMOA->total != 0)
                                                                            {{round(($collectPoste->brut / $cpUEMOA->total)*100,2)}}
                                                                        @else
                                                                            {{ 0 }}
                                                                        @endif
                                                                    </td>
                                                                <td style="background-color: #1fffac">
                                                                        @if($csnUEMOA->total != 0)
                                                                            {{round(($collectPoste->brut / $csnUEMOA->total)*100,2)}}
                                                                        @else
                                                                            {{ 0 }}
                                                                        @endif
                                                                    </td>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                @endif

                                                @if ($input['naturep'] != 'variation')
                                                    @foreach($collectPostes as $collectPosteP)
                                                        @if($collectPosteP->exercice != $collectPoste->exercice - 1)
                                                            @continue
                                                        @else
                                                            <th>{{ (int) $collectPoste->brut - $collectPosteP->brut }}</th>
                                                            <th>
                                                                @if($collectPosteP->brut != 0)
                                                                    {{ round((($collectPoste->brut - $collectPosteP->brut) / $collectPosteP->brut)*100,2) }}
                                                                @else
                                                                    {{ 0 }}
                                                                @endif
                                                            </th>
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
            @endforeach
            @if($input['naturep'] == 'variation')
                @foreach($exercices as $exercice)
                    @foreach($collectPostes as $cp)
                        @if($exercice->exercice != $cp->exercice)
                            @continue
                        @else
                            @foreach($collectPostes as $cp1)
                                @if($cp1->exercice <= $cp->exercice)
                                    @continue
                                @else
                                    <th>{{ (int) $cp1->brut - $cp->brut }}</th>
                                    <th>
                                        @if($cp->brut != 0)
                                            {{ round((($cp1->brut - $cp->brut)/$cp->brut)*100,2) }}
                                        @else
                                            {{ 0 }}
                                        @endif
                                    </th>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
            @endif
        </tbody>
    </table>
</div>
