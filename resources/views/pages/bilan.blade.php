
@extends('templates.master')

@section('title', 'Bilan')
@php($pays = 201)
@if($dbs == 'sensyyg2_beninbd')
    @php($pays = 24)
@elseif($dbs == 'sensyyg2_burkinabd')
    @php($pays = 34)
@elseif($dbs == 'sensyyg2_coteivoirbd')
    @php($pays = 48)
@elseif($dbs == 'sensyyg2_guinnebissaubd')
    @php($pays = 81)
@elseif($dbs == 'sensyyg2_malibd')
    @php($pays = 134)
@elseif($dbs == 'sensyyg2_nigerbd')
    @php($pays = 154)
@elseif($dbs == 'sensyyg2_senegalbd')
    @php($pays = 201)
@elseif($dbs == 'sensyyg2_togobd')
    @php($pays = 223)
@endif

@section('content')

        <iframe src="" frameborder="0" name="index" class="col-md-12" style="background: none"></iframe>
@stop

