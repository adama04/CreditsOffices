@extends('templates.master')

@section('nature', 'Bilan')


@section('content')
    <br/>
    <br/>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <div class="container ">
            <div class="card">
            <div class="card-header"><h3>L'INFORMATION L'EGAL SUR LES ENTREPRISES</h3></div>
            <div class="card-body">
                <div class="form-group row ">
                    <div class="col-md-4" style="font-size: medium">
                        <h3>Renseigne la Raison Sociale l'Entreprise :</h3>
               <select class="form-control m-bot15" name="idEntreprise">
                   @if($entreprises->count() > 0)
                        @foreach($entreprises as $entreprise)
                            <option value="Renseigne la Raison Sociale l'Entreprise :"></option>
                            <option value="{{$entreprise->idEntreprise}}">{{$entreprise->nomEntreprise}}</option>
                        @endForeach
                    @else
                        No Record Found
                    @endif
                </select>
                    </div>
                    <div class="col-md-4" style="font-size: medium">
                        <h3>Renseigne le document de l'Analyse :</h3>
                        <form>
                            <label class="radio-inline">
                                <input type="radio" name="optradio" checked>Bilan
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio">Compte résultat
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio">DEC 2000
                            </label>
                        </form>
                    </div>
                    <div class="col-md-4" style="font-size: medium">
                        <h3>Renseigne l'espace communautaire :</h3>
                        <form>
                            <label class="radio-inline">
                                <input type="radio" name="optradio" checked>SENEGAL 123
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio">GROUPE
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio">UEMOA
                            </label>
                        </form>
                    </div>

                {{--<div class="input-group col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Search...">
                    <span class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                </div>--}}
            </div>
                <div class="form-group row ">
                    <div class="col-md-4" style="font-size: medium">
                        <h3>Renseigne la nature de la prériodicité :</h3>
                        <form>
                            <label class="radio-inline">
                                <input type="radio" name="optradio" checked>Par année
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="optradio">Variation
                            </label>
                        </form>
                    </div>
                    <div class="col-md-4" style="font-size: medium">
                        <h3>Renseigne la Période de l'Analyse:</h3>
                        EXERCICE1:
                        <select>
                          @if($lignebilans->count() > 0)
                                @foreach($lignebilans as $lignebilan)
                                    <option value="{{$lignebilan->exercice}}">{{$lignebilan->exercice}}</option>
                                @endForeach
                            @else
                                No Record Found
                            @endif
                        </select>
                        EXERCICE2:
                        <select>
                            @if($lignebilans->count() > 0)
                                @foreach($lignebilans as $lignebilan)
                                    <option value="{{$lignebilan->exercice}}">{{$lignebilan->exercice}}</option>
                                @endForeach
                            @else
                                No Record Found
                            @endif
                        </select>
                    </div>
                    <div class="col-md-4">
                        <h3>Analyser le bilan:</h3>
                        <a href="" class="btn btn-success btn-md" style="font-size: large" title="Add New Permission">
                            <i class="fa fa-plus" aria-hidden="true"></i>Trouver
                        </a>
                    </div>
                </div>
                <div class="table-striped">
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Nature</th>
            <th>Code</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach($classes as $classe)

            <tr>
                <td>{{$classe->idClasse}}</td>
                <td>{{$classe->nomClasse}}</td>
                <td>{{$classe->nature}}</td>
                <td>{{$classe->code}}</td>

                <td><a href="#" class="btn btn-warning">Edit</a></td>
                <td><a href="#" class="btn btn-danger">Delete</a></td>
                <td><a href="#" class="btn btn-info">View</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $classes->links() }}
</div>
            </div>
            </div>
        </div>
@endsection
