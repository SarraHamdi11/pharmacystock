<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    $this->admin = User::factory()->create();
    $this->admin->assignRole('Admin');
    
    $this->pharmacist = User::factory()->create();
    $this->pharmacist->assignRole('Pharmacist');

    $this->employee = User::factory()->create();
    $this->employee->assignRole('Employee');
});

test('admin can view products list', function () {
    $response = $this->actingAs($this->admin)->get('/products');
    $response->assertStatus(200);
});

test('pharmacist can view products list', function () {
    $response = $this->actingAs($this->pharmacist)->get('/products');
    $response->assertStatus(200);
});

test('employee cannot view products list', function () {
    $response = $this->actingAs($this->employee)->get('/products');
    $response->assertStatus(403);
});

test('admin can create a product', function () {
    $category = Category::factory()->create();
    $supplier = Supplier::factory()->create();

    $productData = [
        'name' => 'New Medicine',
        'generic_name' => 'Generic Name',
        'category_id' => $category->id,
        'supplier_id' => $supplier->id,
        'price' => 10.50,
        'cost_price' => 8.00,
        'min_stock' => 5,
        'track_expiry' => false
    ];

    $response = $this->actingAs($this->admin)->post('/products', $productData);
    
    $response->assertRedirect('/products');
    $this->assertDatabaseHas('products', ['name' => 'New Medicine']);
});

test('admin can delete a product', function () {
    $product = Product::factory()->create();

    $response = $this->actingAs($this->admin)->delete("/products/{$product->id}");
    
    $response->assertRedirect('/products');
    $this->assertSoftDeleted('products', ['id' => $product->id]);
});

test('pharmacist cannot delete a product', function () {
    $product = Product::factory()->create();

    $response = $this->actingAs($this->pharmacist)->delete("/products/{$product->id}");
    
    $response->assertStatus(403);
});
