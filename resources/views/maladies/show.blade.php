@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $maladie->nom }}</h5>
                    <div>
                        <a href="{{ route('maladies.edit', ['maladie' => $maladie->id]) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <form action="{{ route('maladies.destroy', ['maladie' => $maladie->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette maladie ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Description:</h6>
                        <p>{{ $maladie->description }}</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Symptômes:</h6>
                        <p>{{ $maladie->symptomes }}</p>
                    </div>
                    @if($maladie->medicaments && $maladie->medicaments->count() > 0)
                    <div>
                        <h6 class="font-weight-bold">Médicaments associés:</h6>
                        <ul class="list-group">
                            @foreach($maladie->medicaments as $medicament)
                            <li class="list-group-item">
                                {{ $medicament->nom }}
                                @if($medicament->description)
                                <small class="text-muted d-block">{{ $medicament->description }}</small>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('maladies.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
