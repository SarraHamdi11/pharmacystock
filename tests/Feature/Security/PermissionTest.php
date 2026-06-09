<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use Database\Seeders\RolesAndPermissionsSeeder;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
    
    $this->admin = User::factory()->create();
    $this->admin->assignRole('Admin');
    
    $this->manager = User::factory()->create();
    $this->manager->assignRole('Manager');

    $this->pharmacist = User::factory()->create();
    $this->pharmacist->assignRole('Pharmacist');

    $this->employee = User::factory()->create();
    $this->employee->assignRole('Employee');
});

test('admin has full access', function () {
    $this->actingAs($this->admin)->get('/reports')->assertStatus(200);
    $this->actingAs($this->admin)->get('/activities')->assertStatus(200);
    
    $product = Product::factory()->create();
    $this->actingAs($this->admin)->delete("/products/{$product->id}")->assertRedirect('/products');
});

test('manager can see reports but not delete products', function () {
    $this->actingAs($this->manager)->get('/reports')->assertStatus(200);
    
    $product = Product::factory()->create();
    $this->actingAs($this->manager)->delete("/products/{$product->id}")->assertStatus(403);
});

test('pharmacist can manage products and orders but not see reports', function () {
    $this->actingAs($this->pharmacist)->get('/products')->assertStatus(200);
    $this->actingAs($this->pharmacist)->get('/orders')->assertStatus(200);
    $this->actingAs($this->pharmacist)->get('/reports')->assertStatus(403);
});

test('employee can only manage orders and patients', function () {
    $this->actingAs($this->employee)->get('/orders')->assertStatus(200);
    $this->actingAs($this->employee)->get('/customers')->assertStatus(200);
    $this->actingAs($this->employee)->get('/products')->assertStatus(403);
    $this->actingAs($this->employee)->get('/reports')->assertStatus(403);
});
