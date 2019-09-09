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

            <div class="form-row col-md-5 " style="font-family: 'Times New Roman'; color: #0355BF; font-size:medium">
                @foreach($infoEntreprisesR as $infoEntrepriseR )
                    <div class="row">
                        <h1>{{'ENTREPRISE DE REFERENCE'}}</h1>
                    </div>
                    <div class="row">
                        Raison Sociale :
                        <span>
                             {{ $infoEntrepriseR->nomEntreprise }}
                        </span>
                    </div>
                    <div class="row">
                        Service : <span>{{$infoEntrepriseR->nomService}}</span>
                    </div>
                @endforeach
            </div>
            <div class="form-row col-md-5 " style="font-family: 'Times New Roman'; color: #0355BF; font-size:medium;margin-left: 10%">
                @foreach($infoEntreprises as $infoEntreprise )
                    <div class="row">
                        <h1>{{'ENTREPRISE D\'ANALYSE'}}</h1>
                    </div>
                    <div class="row">
                        Raison Sociale :
                        <span>
                             {{ $infoEntreprise->nomEntreprise }}
                        </span>
                    </div>
                    <div class="row">
                        Service : <span>{{$infoEntreprise->nomService}}</span>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
    <table class="table table-condensed" style="font-size: 12px">
        <thead >
        @if($input['naturep'] == 'variation')
            <tr>
                <th style="text-align: right;" > </th>
                <th style="text-align: center; background-color: #7abaff" colspan="7">{{$infoEntrepriseR->Sigle}}</th>
                <th style="text-align: center; background-color: #929fba" colspan="7">{{$infoEntreprise->Sigle}}</th>
                <th style="text-align: center; background-color: #5cd08d" colspan="6"> {{'Variation '.$infoEntreprise->Sigle .' % à '.$infoEntrepriseR->Sigle}}</th>
            </tr>
            <tr>
                <th></th>
                @foreach($exercices as $exercice)
                    <th style="text-align: center; background-color: #9e9e9e" colspan="2">{{$exercice1}}</th>
                    <th style="text-align: center; background-color: #1d68a7" colspan="2">{{$exercice2}}</th>
                    <th style="text-align: center; " colspan="3">{{'Variation'}}</th>
                @endforeach
                <th style="text-align: center; background-color: #9e9e9e" colspan="3">{{$exercice1}}</th>
                <th style="text-align: center; background-color: #1d68a7" colspan="3">{{$exercice2}}</th>
            </tr>
            <tr>
                {{-- Actifs ou charges --}}
                <th style="background-color: #D0FDEB; text-align: left;">
                    @foreach($collectclassesA as $collectclasseA)
                        {{ strtoupper($collectclasseA->nature)  }}
                        @break;
                    @endforeach
                </th>
                @foreach($exercices as $exercice)
                    <th >{{'M. (CFA)'}}</th>
                    <th >{{'% / T.E'}}</th>
                    <th >{{'M. (CFA)'}}</th>
                    <th >{{'% / T.E'}}</th>
                    <th > {{ 'D.B' }}</th>
                    <th >{{ 'R.P.M' }}</th>
                    <th >{{ 'D.P.M' }}</th>
                @endforeach
                <th>{{'D.B'}}</th>
                <th>{{'R.P.M'}}</th>
                <th>{{'D.P.M'}}</th>
                <th>{{'D.B'}}</th>
                <th>{{'R.P.M'}}</th>
                <th>{{'D.P.M'}}</th>
            </tr>
        @else
            <tr style="font-size: 14px">
                <th style="text-align: right;">Exrecices : </th>
                @foreach($exercices as $exercice)
                    <th colspan="7" style="background-color: #66CCFF; text-align: center; ">{{ $exercice->exercice }}</th>
                @endforeach
            </tr>
            <tr style="font-size: 14px">
                <th style="text-align: right;"> </th>
                @foreach($exercices as $exercice)
                    <th colspan="2" style="background-color: #929fba; text-align: center; ">{{ $infoEntrepriseR->Sigle }}</th>
                    <th colspan="2" style="background-color: #66CCFF; text-align: center; ">{{ $infoEntreprise->Sigle }}</th>
                    <th colspan="3" style="background-color: #20c997; text-align: center; ">{{ 'Variation' }}</th>
                @endforeach
            </tr>
            <tr>
                {{-- Actifs ou charges --}}
                <th style="background-color: #D0FDEB; text-align: left;">
                    @foreach($collectclassesA as $collectclasseA)
                        {{ strtoupper($collectclasseA->nature)  }}
                        @break;
                    @endforeach
                </th>
                @foreach($exercices as $exercice)
                    <th >{{'M. (CFA)'}}</th>
                    <th >{{'% / T.E'}}</th>
                    <th >{{'M. (CFA)'}}</th>
                    <th >{{'% / T.E'}}</th>
                    <th > {{ 'D.B' }}</th>
                    <th >{{ 'R.P' }}</th>
                    <th >{{ 'D.P' }}</th>
                @endforeach
            </tr>
        @endif

        </thead>
        <tbody>
        @foreach($classesA as $classeA)
            <tr style="font-size: 12px; text-align: right;">
                <th>{{ $classeA->nomClasse }}</th>

                @if($input['naturep'] == 'paran')
                    @foreach($exercices as $exercice)
                        @foreach($collectclassesAR as $collectclasseAR)
                            @foreach($collectclassesA as $collectclasseA)
                                @if($exercice->exercice != $collectclasseAR->exercice ||
                                $exercice->exercice != $collectclasseA->exercice ||
                                $collectclasseA->nomClasse != $classeA->nomClasse ||
                                $collectclasseA->nomClasse != $collectclasseAR->nomClasse ||
                                $collectclasseAR->nomClasse != $classeA->nomClasse
                                )
                                    @continue
                                @else
                                    @foreach($collecttotalclassesAR as $collecttotalclasseAR)
                                        @foreach($collecttotalclassesA as $collecttotalclasseA)
                                            @if($collecttotalclasseA->exercice != $exercice->exercice ||
                                            $collecttotalclasseAR->exercice != $exercice->exercice ||
                                            $collecttotalclasseAR->exercice != $collecttotalclasseA->exercice
                                            )
                                                @continue
                                            @else
                                                <td>{{ (int)$collectclasseAR->total }}</td>
                                                <td>
                                                    @if($collecttotalclasseAR->total != 0)
                                                        {{ $a = round(($collectclasseAR->total / $collecttotalclasseAR->total)*100,2) }}
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                </td>
                                                <td>{{ (int)$collectclasseA->total }}</td>
                                                <td>
                                                    @if($collecttotalclasseA->total != 0)
                                                        {{ $b = round(($collectclasseA->total / $collecttotalclasseA->total)*100,2) }}
                                                    @else
                                                        {{ 0 }}
                                                    @endif

                                                </td>
                                                <td>{{(int) $collectclasseA->total-$collectclasseAR->total }}</td>
                                                <td>
                                                    @if($collectclasseAR->total != 0)
                                                        {{round(( ($collectclasseA->total-$collectclasseAR->total) / $collectclasseAR->total)*100,2) }}
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $b - $a }}
                                                </td>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                @else
                    @foreach($entreprises as $entreprise)
                        @foreach($exercices as $exercice)
                            @foreach($collectclassesAR as $collectclasseAR)
                                @foreach($collecttotalclassesAR as $collecttotalclasseAR)
                                    @if($collecttotalclasseAR->exercice != $exercice->exercice ||
                                    $collecttotalclasseAR->idEntreprise != $entreprise->idEntreprise ||
                                    $collectclasseAR->nomClasse != $classeA->nomClasse ||
                                    $collectclasseAR->idEntreprise != $entreprise->idEntreprise ||
                                    $collectclasseAR->exercice != $exercice->exercice)
                                        @continue
                                    @else
                                        <th>{{ $a =(int) ($collectclasseAR->total) }}</th>
                                        <th>
                                            @if($collecttotalclasseAR->total != 0)
                                                {{ $ar = round(($collectclasseAR->total/$collecttotalclasseAR->total)*100,2) }}
                                            @else
                                                {{ 0 }}
                                            @endif
                                        </th>

                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach

                        @foreach($exercices as $exercice)
                            @foreach($collectclassesA as $collectclasseA)
                                @foreach($collecttotalclassesA as $collecttotalclasseA)
                                    @if($collecttotalclasseA->exercice != $exercice->exercice ||
                                    $collecttotalclasseA->idEntreprise != $entreprise->idEntreprise ||
                                    $collectclasseA->nomClasse != $classeA->nomClasse ||
                                    $collectclasseA->idEntreprise != $entreprise->idEntreprise ||
                                    $collectclasseA->exercice != $exercice->exercice)
                                        @continue
                                    @else
                                        <th>{{ $a = (int) ($collectclasseA->total) }}</th>
                                        <th>
                                            @if($collecttotalclasseA->total != 0)
                                                {{ $ar = round(($collectclasseA->total/$collecttotalclasseA->total)*100,2) }}
                                            @else
                                                {{ 0 }}
                                            @endif
                                        </th>
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                            {{-- Les Variations Partielles --}}

                        @foreach($collectclassesAR as $cca)
                                    @if($cca->exercice != $exercice1 ||
                                    $cca->nomClasse != $classeA->nomClasse ||
                                    $cca->idEntreprise != $entreprise->idEntreprise
                                    )
                                        @continue
                                    @else
                                        @foreach($collectclassesAR as $cca1)
                                            @if($cca1->exercice != $exercice2 ||
                                            $classeA->nomClasse != $cca->nomClasse ||
                                            $cca->nomClasse != $cca1->nomClasse ||
                                            $cca1->idEntreprise != $entreprise->idEntreprise ||
                                            $cca->exercice == $cca1->exercice)
                                                @continue
                                            @else
                                                @foreach($collecttotalclassesAR as $ctc)
                                                    @if($ctc->exercice != $exercice1 ||
                                                    $ctc->idEntreprise != $entreprise->idEntreprise
                                                    )
                                                        @continue
                                                    @else
                                                        @foreach($collecttotalclassesAR as $ctc1)
                                                            @if($ctc1->exercice != $exercice2 ||
                                                            $ctc1->idEntreprise != $entreprise->idEntreprise ||
                                                            $ctc1->exercice == $ctc->exercice
                                                            )
                                                                @continue
                                                            @else
                                                                <td>{{ (int)($cca1->total - $cca->total) }}</td>
                                                                <td>
                                                                    @if($ctc->total == 0 || $cca->total == 0 || $ctc->total == 0)
                                                                        {{ 0 }}
                                                                    @else
                                                                        {{ round(round(($cca1->total / $ctc1->total)*100,2) / round(($cca->total / $ctc->total)*100,2)*100,2) }}
                                                                    @endif
                                                                </td>
                                                                <td> {{ round(($cca1->total / $ctc1->total)*100,2) - round(($cca->total / $ctc->total)*100,2) }} </td>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @foreach($collectclassesA as $cca)
                                @if($cca->exercice != $exercice1 ||
                                $cca->nomClasse != $classeA->nomClasse ||
                                $cca->idEntreprise != $entreprise->idEntreprise
                                )
                                    @continue
                                @else
                                    @foreach($collectclassesA as $cca1)
                                        @if($cca1->exercice != $exercice2 ||
                                        $classeA->nomClasse != $cca->nomClasse ||
                                        $cca->nomClasse != $cca1->nomClasse ||
                                        $cca1->idEntreprise != $entreprise->idEntreprise ||
                                        $cca->exercice == $cca1->exercice)
                                            @continue
                                        @else

                                            @foreach($collecttotalclassesA as $ctc)
                                                @if($ctc->exercice != $exercice1 ||
                                                $ctc->idEntreprise != $entreprise->idEntreprise
                                                )
                                                    @continue
                                                @else
                                                    @foreach($collecttotalclassesA as $ctc1)
                                                        @if($ctc1->exercice != $exercice2 ||
                                                        $ctc1->idEntreprise != $entreprise->idEntreprise ||
                                                        $ctc1->exercice == $ctc->exercice
                                                        )
                                                            @continue
                                                        @else
                                                            <td>{{ (int)($cca1->total - $cca->total) }}</td>
                                                            <td>
                                                                @if($ctc->total == 0 || $cca->total == 0 || $ctc->total == 0)
                                                                    {{ 0 }}
                                                                @else
                                                                    {{ round(round(($cca1->total / $ctc1->total)*100,2) / round(($cca->total / $ctc->total)*100,2)*100,2) }}
                                                                @endif
                                                            </td>
                                                            <td> {{ round(($cca1->total / $ctc1->total)*100,2) - round(($cca->total / $ctc->total)*100,2) }} </td>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                    @endforeach
                        {{-- Si Par variation affichage des diff et ecarts --}}
                        @foreach($exercices as $exercice)
                            @foreach($collectclassesA as $cca)
                                @if($cca->exercice != $exercice->exercice ||
                                $cca->nomClasse != $classeA->nomClasse)
                                    @continue
                                @else
                                    @foreach($collectclassesAR as $cca1)
                                        @if($cca1->exercice != $exercice->exercice ||
                                        $classeA->nomClasse != $cca->nomClasse ||
                                        $cca->nomClasse != $cca1->nomClasse ||
                                        $cca->exercice != $cca1->exercice)
                                            @continue
                                        @else
                                            @foreach($collecttotalclassesA as $ctca)
                                                @if($ctca->exercice != $exercice->exercice )
                                                    @continue
                                                @else
                                                    @foreach($collecttotalclassesAR as $ctcar)
                                                        @if($ctcar->exercice != $exercice->exercice ||
                                                         $ctcar->exercice != $ctca->exercice)
                                                            @continue
                                                        @else
                                                            {{-- Diff brute --}}
                                                            <td>{{ (int)$cca->total - $cca1->total }}</td>
                                                        {{-- Rap Part de marché --}}
                                                            <td>
                                                                @if($cca1->total == 0 || $ctca->total == 0 || $ctcar->total == 0)
                                                                    {{ 0 }}
                                                                    @else
                                                                    {{ round(round(($cca->total / $ctca->total)*100,2)/(round(($cca1->total/$ctcar->total)*100,2))*100,2) }}
                                                                @endif
                                                            </td>
                                                        {{-- Diff PDM --}}
                                                            <td>
                                                                @if($cca1->total == 0 || $ctca->total == 0 || $ctcar->total == 0)
                                                                    {{ 0 }}
                                                                @else
                                                                    {{ round(($cca->total / $ctca->total)*100,2) - round(($cca1->total/$ctcar->total)*100,2) }}
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
            @if($input['naturep'] == 'paran')
                @foreach($exercices as $exercice)
                    @foreach($collecttotalclassesA as $collecttotalclasseA)
                        @foreach($collecttotalclassesAR as $collecttotalclasseAR)
                            @if($collecttotalclasseA->exercice != $exercice->exercice ||
                            $collecttotalclasseAR->exercice != $exercice->exercice ||
                            $collecttotalclasseAR->exercice != $collecttotalclasseA->exercice
                            )
                                @continue
                            @else
                                <th>{{ (int)$collecttotalclasseAR->total }}</th>
                                <th>{{ 100 }}</th>
                                <th>{{ (int) $collecttotalclasseA->total }}</th>
                                <th>{{ 100 }}</th>

                                <th>{{ (int)($collecttotalclasseA->total - $collecttotalclasseAR->total) }}</th>
                                <th>
                                    @if($collecttotalclasseAR->total != 0)
                                        {{ round((($collecttotalclasseA->total - $collecttotalclasseAR->total)/$collecttotalclasseAR->total)*100,2) }}
                                    @else
                                        {{ 0 }}
                                    @endif
                                </th>
                                <th>{{ 0 }}</th>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            @else
                @foreach($entreprises as $entreprise)
                    @foreach($exercices as $exercice)
                        @foreach($collecttotalclassesAR as $collecttotalclasseAR)
                            @if($collecttotalclasseAR->exercice != $exercice->exercice ||
                            $collecttotalclasseAR->idEntreprise != $entreprise->idEntreprise)
                                @continue
                            @else
                                <th>{{  (int) $collecttotalclasseAR->total }}</th>
                                <th>{{ 100 }}</th>
                             @endif
                        @endforeach
                    @endforeach
                    @foreach($exercices as $exercice)
                            @foreach($collecttotalclassesA as $collecttotalclasseA)
                                @if($collecttotalclasseA->exercice != $exercice->exercice ||
                                $collecttotalclasseA->idEntreprise != $entreprise->idEntreprise)
                                    @continue
                                @else
                                    <th>{{  (int) $collecttotalclasseA->total }}</th>
                                    <th>{{ 100 }}</th>

                                @endif
                            @endforeach
                        @endforeach
                        @foreach($collecttotalclassesAR as $cca)
                            @if($cca->exercice != $exercice1 ||
                            $cca->idEntreprise != $entreprise->idEntreprise
                            )
                                @continue
                            @else
                                @foreach($collecttotalclassesAR as $cca1)
                                    @if($cca1->exercice != $exercice2 ||
                                    $cca1->idEntreprise != $entreprise->idEntreprise ||
                                    $cca->exercice == $cca1->exercice)
                                        @continue
                                    @else
                                        <td>{{ (int)($cca1->total - $cca->total) }}</td>
                                        <td> {{ 100 }}</td>
                                        <td>  {{ 0 }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @foreach($collecttotalclassesA as $cca)
                            @if($cca->exercice != $exercice1 ||
                            $cca->idEntreprise != $entreprise->idEntreprise
                            )
                                @continue
                            @else
                                @foreach($collecttotalclassesA as $cca1)
                                    @if($cca1->exercice != $exercice2 ||
                                     $cca1->idEntreprise != $entreprise->idEntreprise ||
                                    $cca->exercice == $cca1->exercice)
                                        @continue
                                    @else
                                        <td>{{ (int)($cca1->total - $cca->total) }}</td>
                                        <td>{{ 100 }}</td>
                                        <td>{{ 0 }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                @endforeach
                    @foreach($exercices as $exercice)
                        @foreach($collecttotalclassesA as $collecttotalclasseA)
                            @foreach($collecttotalclassesBR as $collecttotalclasseAR)
                                @if(
                                $collecttotalclasseAR->exercice != $exercice->exercice ||
                                $collecttotalclasseA->exercice != $exercice->exercice ||
                                $collecttotalclasseAR->exercice != $collecttotalclasseA->exercice
                                )
                                    @continue
                                @else
                                    <td>{{ (int)($collecttotalclasseA->total - $collecttotalclasseAR->total) }}</td>
                                    <td>{{ '100' }}</td>
                                    <td>{{ '0' }}</td>
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
            @endif
        </tr>

        {{-- PASSIF OU PRODUIT --}}
        <tr>
            <th style="background-color: #D0FDEB; text-align: left;">
                @foreach($collectclassesB as $collectclasseB)
                    {{ strtoupper($collectclasseB->nature)  }}
                    @break;
                @endforeach
            </th>
            @foreach($exercices as $exercice)
                <th colspan="7"></th>
            @endforeach
            <th colspan="6"></th>
        </tr>

        @foreach($classesB as $classeB)
            <tr style="font-size: 12px; text-align: right;">
                <th>{{ $classeB->nomClasse }}</th>

                @if($input['naturep'] == 'paran')
                    @foreach($exercices as $exercice)
                        @foreach($collectclassesBR as $collectclasseBR)
                            @foreach($collectclassesB as $collectclasseB)
                                @if($exercice->exercice != $collectclasseBR->exercice ||
                                $exercice->exercice != $collectclasseB->exercice ||
                                $collectclasseB->nomClasse != $classeB->nomClasse ||
                                $collectclasseB->nomClasse != $collectclasseBR->nomClasse ||
                                $collectclasseBR->nomClasse != $classeB->nomClasse
                                )
                                    @continue
                                @else
                                    @foreach($collecttotalclassesBR as $collecttotalclasseBR)
                                        @foreach($collecttotalclassesB as $collecttotalclasseB)
                                            @if($collecttotalclasseB->exercice != $exercice->exercice ||
                                            $collecttotalclasseBR->exercice != $exercice->exercice ||
                                            $collecttotalclasseBR->exercice != $collecttotalclasseB->exercice
                                            )
                                                @continue
                                            @else
                                                <td>{{ (int)$collectclasseBR->total }}</td>
                                                <td>
                                                    @if($collecttotalclasseBR->total != 0)
                                                        {{ $a = round(($collectclasseBR->total / $collecttotalclasseBR->total)*100,2) }}
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                </td>
                                                <td>{{ (int)$collectclasseB->total }}</td>
                                                <td>
                                                    @if($collecttotalclasseB->total != 0)
                                                        {{ $b = round(($collectclasseB->total / $collecttotalclasseB->total)*100,2) }}
                                                    @else
                                                        {{ 0 }}
                                                    @endif

                                                </td>
                                                <td>{{(int) $collectclasseB->total-$collectclasseBR->total }}</td>
                                                <td>
                                                    @if($collectclasseBR->total != 0)
                                                        {{round(( ($collectclasseB->total-$collectclasseBR->total) / $collectclasseBR->total)*100,2) }}
                                                    @else
                                                        {{ 0 }}
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $b - $a }}
                                                </td>
                                            @endif
                                        @endforeach
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
                @else
                    {{-- Si Par variation Passif ou produit --}}
                    @foreach($entreprises as $entreprise)
                        @foreach($exercices as $exercice)
                            @foreach($collectclassesBR as $collectclasseBR)
                                @foreach($collecttotalclassesBR as $collecttotalclasseBR)
                                    @if($collecttotalclasseBR->exercice != $exercice->exercice ||
                                    $collecttotalclasseBR->idEntreprise != $entreprise->idEntreprise ||
                                    $collectclasseBR->nomClasse != $classeB->nomClasse ||
                                    $collectclasseBR->idEntreprise != $entreprise->idEntreprise ||
                                    $collectclasseBR->exercice != $exercice->exercice)
                                        @continue
                                    @else
                                        <th>{{ $a =(int) ($collectclasseBR->total) }}</th>
                                        <th>
                                            @if($collecttotalclasseBR->total != 0)
                                                {{ $ar = round(($collectclasseBR->total/$collecttotalclasseBR->total)*100,2) }}
                                            @else
                                                {{ 0 }}
                                            @endif
                                        </th>

                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach

                        @foreach($exercices as $exercice)
                            @foreach($collectclassesB as $collectclasseB)
                                @foreach($collecttotalclassesB as $collecttotalclasseB)
                                    @if($collecttotalclasseB->exercice != $exercice->exercice ||
                                    $collecttotalclasseB->idEntreprise != $entreprise->idEntreprise ||
                                    $collectclasseB->nomClasse != $classeB->nomClasse ||
                                    $collectclasseB->idEntreprise != $entreprise->idEntreprise ||
                                    $collectclasseB->exercice != $exercice->exercice)
                                        @continue
                                    @else
                                        <th>{{ $a = (int) ($collectclasseB->total) }}</th>
                                        <th>
                                            @if($collecttotalclasseB->total != 0)
                                                {{ $ar = round(($collectclasseB->total/$collecttotalclasseB->total)*100,2) }}
                                            @else
                                                {{ 0 }}
                                            @endif
                                        </th>
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                        {{-- Les Variations Partielles --}}

                        @foreach($collectclassesBR as $cca)
                            @if($cca->exercice != $exercice1 ||
                            $cca->nomClasse != $classeB->nomClasse ||
                            $cca->idEntreprise != $entreprise->idEntreprise
                            )
                                @continue
                            @else
                                @foreach($collectclassesBR as $cca1)
                                    @if($cca1->exercice != $exercice2 ||
                                    $classeB->nomClasse != $cca->nomClasse ||
                                    $cca->nomClasse != $cca1->nomClasse ||
                                    $cca1->idEntreprise != $entreprise->idEntreprise ||
                                    $cca->exercice == $cca1->exercice)
                                        @continue
                                    @else
                                        @foreach($collecttotalclassesBR as $ctc)
                                            @if($ctc->exercice != $exercice1 ||
                                            $ctc->idEntreprise != $entreprise->idEntreprise
                                            )
                                                @continue
                                            @else
                                                @foreach($collecttotalclassesBR as $ctc1)
                                                    @if($ctc1->exercice != $exercice2 ||
                                                    $ctc1->idEntreprise != $entreprise->idEntreprise ||
                                                    $ctc1->exercice == $ctc->exercice
                                                    )
                                                        @continue
                                                    @else
                                                        <td>{{ (int)($cca1->total - $cca->total) }}</td>
                                                        <td>
                                                            @if($ctc->total == 0 || $cca->total == 0 || $ctc->total == 0)
                                                                {{ 0 }}
                                                            @else
                                                                {{ round(round(($cca1->total / $ctc1->total)*100,2) / round(($cca->total / $ctc->total)*100,2)*100,2) }}
                                                            @endif
                                                        </td>
                                                        <td> {{ round(($cca1->total / $ctc1->total)*100,2) - round(($cca->total / $ctc->total)*100,2) }} </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        @foreach($collectclassesB as $cca)
                            @if($cca->exercice != $exercice1 ||
                            $cca->nomClasse != $classeB->nomClasse ||
                            $cca->idEntreprise != $entreprise->idEntreprise
                            )
                                @continue
                            @else
                                @foreach($collectclassesB as $cca1)
                                    @if($cca1->exercice != $exercice2 ||
                                    $classeB->nomClasse != $cca->nomClasse ||
                                    $cca->nomClasse != $cca1->nomClasse ||
                                    $cca1->idEntreprise != $entreprise->idEntreprise ||
                                    $cca->exercice == $cca1->exercice)
                                        @continue
                                    @else

                                        @foreach($collecttotalclassesB as $ctc)
                                            @if($ctc->exercice != $exercice1 ||
                                            $ctc->idEntreprise != $entreprise->idEntreprise
                                            )
                                                @continue
                                            @else
                                                @foreach($collecttotalclassesB as $ctc1)
                                                    @if($ctc1->exercice != $exercice2 ||
                                                    $ctc1->idEntreprise != $entreprise->idEntreprise ||
                                                    $ctc1->exercice == $ctc->exercice
                                                    )
                                                        @continue
                                                    @else
                                                        <td>{{ (int)($cca1->total - $cca->total) }}</td>
                                                        <td>
                                                            @if($ctc->total == 0 || $cca->total == 0 || $ctc->total == 0)
                                                                {{ 0 }}
                                                            @else
                                                                {{ round(round(($cca1->total / $ctc1->total)*100,2) / round(($cca->total / $ctc->total)*100,2)*100,2) }}
                                                            @endif
                                                        </td>
                                                        <td> {{ round(($cca1->total / $ctc1->total)*100,2) - round(($cca->total / $ctc->total)*100,2) }} </td>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endforeach
                    {{-- Si Par variation affichage des diff et ecarts --}}
                    {{-- ========================== --}}
                    @foreach($exercices as $exercice)
                        @foreach($collectclassesB as $cca)
                            @if($cca->exercice != $exercice->exercice ||
                            $cca->nomClasse != $classeB->nomClasse)
                                @continue
                            @else
                                @foreach($collectclassesBR as $cca1)
                                    @if($cca1->exercice != $exercice->exercice ||
                                    $classeB->nomClasse != $cca->nomClasse ||
                                    $cca->nomClasse != $cca1->nomClasse ||
                                    $cca->exercice != $cca1->exercice)
                                        @continue
                                    @else
                                        @foreach($collecttotalclassesB as $ctca)
                                            @if($ctca->exercice != $exercice->exercice )
                                                @continue
                                            @else
                                                @foreach($collecttotalclassesBR as $ctcar)
                                                    @if($ctcar->exercice != $exercice->exercice ||
                                                     $ctcar->exercice != $ctca->exercice)
                                                        @continue
                                                    @else
                                                        {{-- Diff brute --}}
                                                        <td>{{ (int)$cca->total - $cca1->total }}</td>
                                                        {{-- Rap Part de marché --}}
                                                        <td>
                                                            @if($cca1->total == 0 || $ctca->total == 0 || $ctcar->total == 0)
                                                                {{ 0 }}
                                                            @else
                                                                {{ round(round(($cca->total / $ctca->total)*100,2)/(round(($cca1->total/$ctcar->total)*100,2))*100,2) }}
                                                            @endif
                                                        </td>
                                                        {{-- Diff PDM --}}
                                                        <td>
                                                            @if($cca1->total == 0 || $ctca->total == 0 || $ctcar->total == 0)
                                                                {{ 0 }}
                                                            @else
                                                                {{ round(($cca->total / $ctca->total)*100,2) - round(($cca1->total/$ctcar->total)*100,2) }}
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
                    @endforeach
                    {{-- ========================== --}}
                @endif
            </tr>
        @endforeach
        {{-- Affichage des totals Passif ou Produit --}}
        <tr style="font-size: 13px;text-align: center">
            <th style="text-align: right;">
                @foreach($collectclassesB as $collectclasseB)
                    {{'TOTAL '. strtoupper($collectclasseB->nature)  }}
                    @break;
                @endforeach
            </th>
            @if($input['naturep'] != 'variation')
                @foreach($exercices as $exercice)
                    @foreach($collecttotalclassesB as $collecttotalclasseB)
                        @foreach($collecttotalclassesBR as $collecttotalclasseBR)
                            @if($collecttotalclasseB->exercice != $exercice->exercice ||
                            $collecttotalclasseBR->exercice != $exercice->exercice ||
                            $collecttotalclasseBR->exercice != $collecttotalclasseB->exercice
                            )
                                @continue
                            @else
                                <th>{{ (int)$collecttotalclasseBR->total }}</th>
                                <th>{{ 100 }}</th>
                                <th>{{ (int) $collecttotalclasseB->total }}</th>
                                <th>{{ 100 }}</th>

                                <th>{{ (int)($collecttotalclasseB->total - $collecttotalclasseBR->total) }}</th>
                                <th>
                                    @if($collecttotalclasseBR->total != 0)
                                        {{ round((($collecttotalclasseB->total - $collecttotalclasseBR->total)/$collecttotalclasseBR->total)*100,2) }}
                                    @else
                                        {{ 0 }}
                                    @endif
                                </th>
                                <th>{{ 0 }}</th>
                            @endif
                        @endforeach
                    @endforeach
                @endforeach
            @else
                @foreach($entreprises as $entreprise)
                    @foreach($exercices as $exercice)
                        @foreach($collecttotalclassesBR as $collecttotalclasseBR)
                            @if($collecttotalclasseBR->exercice != $exercice->exercice ||
                            $collecttotalclasseBR->idEntreprise != $entreprise->idEntreprise)
                                @continue
                            @else
                                <th>{{  (int) $collecttotalclasseBR->total }}</th>
                                <th>{{ 100 }}</th>
                            @endif
                        @endforeach
                    @endforeach
                    @foreach($exercices as $exercice)
                        @foreach($collecttotalclassesB as $collecttotalclasseB)
                            @if($collecttotalclasseB->exercice != $exercice->exercice ||
                            $collecttotalclasseB->idEntreprise != $entreprise->idEntreprise)
                                @continue
                            @else
                                <th>{{  (int) $collecttotalclasseB->total }}</th>
                                <th>{{ 100 }}</th>

                            @endif
                        @endforeach
                    @endforeach
                    @foreach($collecttotalclassesBR as $cca)
                        @if($cca->exercice != $exercice1 ||
                        $cca->idEntreprise != $entreprise->idEntreprise
                        )
                            @continue
                        @else
                            @foreach($collecttotalclassesBR as $cca1)
                                @if($cca1->exercice != $exercice2 ||
                                $cca1->idEntreprise != $entreprise->idEntreprise ||
                                $cca->exercice == $cca1->exercice)
                                    @continue
                                @else
                                    <td>{{ (int)($cca1->total - $cca->total) }}</td>
                                    <td> {{ 100 }}</td>
                                    <td>  {{ 0 }}</td>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                    @foreach($collecttotalclassesB as $cca)
                        @if($cca->exercice != $exercice1 ||
                        $cca->idEntreprise != $entreprise->idEntreprise
                        )
                            @continue
                        @else
                            @foreach($collecttotalclassesB as $cca1)
                                @if($cca1->exercice != $exercice2 ||
                                 $cca1->idEntreprise != $entreprise->idEntreprise ||
                                $cca->exercice == $cca1->exercice)
                                    @continue
                                @else
                                    <td>{{ (int)($cca1->total - $cca->total) }}</td>
                                    <td>{{ 100 }}</td>
                                    <td> {{ 0 }}</td>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @endforeach
                    @foreach($exercices as $exercice)
                        @foreach($collecttotalclassesB as $collecttotalclasseB)
                            @foreach($collecttotalclassesBR as $collecttotalclasseBR)
                                @if(
                                $collecttotalclasseBR->exercice != $exercice->exercice ||
                                $collecttotalclasseB->exercice != $exercice->exercice ||
                                $collecttotalclasseBR->exercice != $collecttotalclasseB->exercice
                                )
                                    @continue
                                @else
                                    <td>{{ (int)($collecttotalclasseB->total - $collecttotalclasseBR->total) }}</td>
                                    <td>{{ '100' }}</td>
                                    <td>{{ '0' }}</td>
                                @endif
                            @endforeach
                        @endforeach
                    @endforeach
            @endif
        </tr>
        </tbody>
    </table>
</div>
