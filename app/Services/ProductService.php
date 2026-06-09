<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\ActivityService;

class ProductService
{
    /**
     * Get paginated products with filters.
     */
    public function getPaginatedProducts(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::with(['category', 'supplier', 'stocks']);

        if (!empty($filters['term'])) {
            $term = $filters['term'];
            $query->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('generic_name', 'like', "%{$term}%")
                  ->orWhere('code_bar', 'like', "%{$term}%")
                  ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', "%{$term}%"))
                  ->orWhereHas('supplier', fn($sq) => $sq->where('first_name', 'like', "%{$term}%")->orWhere('last_name', 'like', "%{$term}%"));
            });
        }

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new product.
     */
    public function createProduct(array $data): Product
    {
        $product = Product::create($data);
        ActivityService::log('created', "Added new medication: {$product->name}", $product);
        return $product;
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(Product $product, array $data): Product
    {
        $product->update($data);
        ActivityService::log('updated', "Updated medication details: {$product->name}", $product);
        return $product;
    }

    /**
     * Delete a product.
     */
    public function deleteProduct(Product $product): void
    {
        $name = $product->name;
        $product->delete();
        ActivityService::log('deleted', "Archived medication: {$name}");
    }
}
