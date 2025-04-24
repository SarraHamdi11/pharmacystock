 @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails de la catégorie</h5>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">Retour</a>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="fw-bold">Nom:</h6>
                        <p>{{ $category->name }}</p>
                        
                        @if($category->description)
                        <h6 class="fw-bold">Description:</h6>
                        <p>{{ $category->description }}</p>
                        @endif
                        
                        <h6 class="fw-bold">Date de création:</h6>
                        <p>{{ $category->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div class="mt-4">
                        <h5 class="mb-3">Produits dans cette catégorie</h5>
                        @if($category->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Prix</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->products as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->price }} DH</td>
                                                <td>{{ $product->stock }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">Aucun produit dans cette catégorie.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection