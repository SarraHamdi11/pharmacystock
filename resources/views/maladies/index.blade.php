@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des Maladies</h5>
                    <a href="{{ route('maladies.create') }}" class="btn btn-primary btn-sm">Ajouter une maladie</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($maladies as $maladie)
                                    <tr>
                                        <td>{{ $maladie->id }}</td>
                                        <td>{{ $maladie->nom }}</td>
                                        <td>{{ $maladie->description }}</td>
                                        <td>
                                            <a href="{{ route('maladies.show', ['maladie' => $maladie->id]) }}" class="btn btn-info btn-sm">Voir</a>
                                            <a href="{{ route('maladies.edit', ['maladie' => $maladie->id]) }}" class="btn btn-warning btn-sm">Modifier</a>
                                            <form action="{{ route('maladies.destroy', ['maladie' => $maladie->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette maladie ?')">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $maladies->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
