@extends('templates.master')
@section('title', 'Bilan')
@section("header")
    @php
    if (!$pays)
        $pays = 201;
    @endphp
    <div class="card-header">
        <div class="form-group row ">
                      <span class="title_icon">
                        <img src={{asset("images/bullet.jpg")}} alt="" title="" />
                    </span>
            <span class="title_icon" style="font-family: 'Times New Roman, Times, serif';
                                    font-size: 15px">
                        <label>L'INFORMATION L'EGAL SUR LES ENTREPRISES</label>
                    </span>
            <div class=" title_icon" style="background-color: powderblue;height: 5%;font-family: 'Times New Roman, Times, serif';
                                    font-size:large">
                <label for="">Analyse Financ&egrave;re</label> :&nbsp;&nbsp;
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.bilan.create', ['pays' => 24]) }}">
                    <img src={{asset("images/Benin.jpg")}} title="Benin" alt="Benin" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.bilan.create', ['pays' => 34]) }}">
                    <img src={{asset("images/Burkina.jpg")}} title="Burkina" alt="Burkina" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.bilan.create', ['pays' => 48]) }}">
                    <img src={{asset("images/Cotedivoire.jpg")}} title="Cotedivoire" alt="Cote d'ivoire"  style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.bilan.create', ['pays' => 81]) }}">
                    <img src={{asset("images/Guinneabissao.jpg")}} title="GuineeBissau" alt="Guinnee Bissau" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.bilan.create', ['pays' => 134]) }}">
                    <img src={{asset("images/Mali.jpg")}} title="Mali" alt="Mali" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.bilan.create', ['pays' => 154]) }}">
                    <img src={{asset("images/Niger.jpg")}} title="Niger" alt="Niger" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.bilan.create', ['pays' => 201]) }}">
                    <img src={{asset("images/Senegal.jpg")}} title="Senegal" alt="Senegal" style=""/></a>
            </div>
            <div class="col-sm title_icon"><a href="{{ route('entreprise.bilan.create', ['pays' => 223]) }}">
                    <img src={{asset("images/Togo.jpg")}} title="Togo" alt="Togo" style=""/></a>
            </div>
        </div>
    </div>
    @stop
@section("forms")


<div class="card-body">

    <form action="{{ route('entreprise.bilan.store', ['pays' => $pays]) }}" method="post" target="index">
        @include('forms._entreprise_form')
        <div class="col offset-4">
            <button type="submit" class="btn btn-primary" name="ajouter" style="font-family: 'Times New Roman, Times, serif';font-size: 17px">
                <i class="icon-ok "></i>Trouver</button>
        </div>
    </form>
</div>
@stop

@section('content')
    <iframe src="" name="index" style="width: 100%; height: 900px; border-width: 0"></iframe>
@stop


