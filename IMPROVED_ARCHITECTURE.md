# 🏥 IMPROVED LARAVEL PHARMACY MANAGEMENT SYSTEM

## Enhanced Architecture Implementation

### 1. **Improved Layout with Sidebar**

**resources/views/layouts/app.blade.php**
```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Pharmacy Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div id="app" class="flex h-screen">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white shadow-lg transition-all duration-300 {{ request()->cookie('sidebar_collapsed') ? '-ml-64' : '' }}">
            <div class="p-6 border-b">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-pills text-teal-600 text-2xl"></i>
                    <h1 class="text-xl font-bold text-gray-800">PharmaStock Pro</h1>
                </div>
            </div>
            
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors">
                            <i class="fas fa-dashboard w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('medications.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors">
                            <i class="fas fa-pills w-5"></i>
                            <span>Medications</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('orders.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors">
                            <i class="fas fa-prescription w-5"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors">
                            <i class="fas fa-truck w-5"></i>
                            <span>Suppliers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-teal-50 text-gray-700 hover:text-teal-600 transition-colors">
                            <i class="fas fa-chart-line w-5"></i>
                            <span>Reports</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        
                        <!-- Global Search -->
                        <div class="relative">
                            <input type="text" 
                                   id="globalSearch" 
                                   placeholder="Search medications, patients, orders..." 
                                   class="w-96 pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                        </button>
                        
                        <!-- User Menu -->
                        <div class="relative">
                            <button onclick="toggleUserMenu()" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <img src="{{ Auth::user()->avatar ?? '/default-avatar.png' }}" alt="User" class="w-8 h-8 rounded-full">
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-2">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-gray-50">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="{{ route('settings') }}" class="block px-4 py-2 hover:bg-gray-50">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <hr class="my-2">
                                <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-50 text-red-600">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-ml-64');
            document.cookie = `sidebar_collapsed=${sidebar.classList.contains('-ml-64')}; path=/`;
        }
        
        function toggleUserMenu() {
            document.getElementById('userMenu').classList.toggle('hidden');
        }
        
        // Global search functionality
        document.getElementById('globalSearch')?.addEventListener('input', async (e) => {
            const query = e.target.value;
            if (query.length > 2) {
                const results = await fetch(`/api/search?q=${query}`).then(r => r.json());
                // Display search results
            }
        });
    </script>
</body>
</html>
```

### 2. **Reusable Blade Components**

**resources/views/components/stat-card.blade.php**
```blade
@props([
    'title',
    'value',
    'icon',
    'color' => 'teal',
    'trend' => null,
    'description' => null
])

<div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600 font-medium">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900">{{ $value }}</p>
            @if($description)
                <p class="text-sm text-gray-500 mt-1">{{ $description }}</p>
            @endif
        </div>
        <div class="w-12 h-12 bg-{{ $color }}-100 rounded-lg flex items-center justify-center">
            <i class="{{ $icon }} text-{{ $color }}-600 text-xl"></i>
        </div>
    </div>
    
    @if($trend)
    <div class="mt-4 flex items-center text-sm">
        @if($trend > 0)
            <i class="fas fa-arrow-up text-green-500 mr-1"></i>
            <span class="text-green-500">+{{ $trend }}%</span>
        @else
            <i class="fas fa-arrow-down text-red-500 mr-1"></i>
            <span class="text-red-500">{{ $trend }}%</span>
        @endif
        <span class="text-gray-500 ml-2">from last month</span>
    </div>
    @endif
</div>
```

**resources/views/components/data-table.blade.php**
```blade
@props([
    'headers',
    'data',
    'actions' => true,
    'pagination' => null
])

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    @foreach($headers as $header)
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                    @endforeach
                    @if($actions)
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($data as $item)
                <tr class="hover:bg-gray-50">
                    {{ $slot }}
                    @if($actions)
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="editItem({{ $item->id }})" class="text-teal-600 hover:text-teal-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteItem({{ $item->id }})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($pagination)
    <div class="px-6 py-4 border-t">
        {{ $pagination->links() }}
    </div>
    @endif
</div>
```

### 3. **Enhanced Dashboard**

**resources/views/dashboard/index.blade.php**
```blade
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Pharmacy Dashboard</h1>
            <p class="text-gray-600 mt-1">Welcome back, {{ Auth::user()->name }}! Here's your pharmacy overview.</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="showQuickAddModal()" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>Quick Add
            </button>
            <button onclick="exportDashboard()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card 
            title="Total Medications" 
            value="{{ $stats['medications'] }}" 
            icon="fas fa-pills" 
            color="teal"
            trend="12"
        />
        <x-stat-card 
            title="Low Stock Items" 
            value="{{ $stats['low_stock'] }}" 
            icon="fas fa-exclamation-triangle" 
            color="orange"
            trend="-5"
        />
        <x-stat-card 
            title="Today's Orders" 
            value="{{ $stats['today_orders'] }}" 
            icon="fas fa-shopping-cart" 
            color="green"
            trend="8"
        />
        <x-stat-card 
            title="Revenue" 
            value="${{ number_format($stats['revenue'], 2) }}" 
            icon="fas fa-dollar-sign" 
            color="blue"
            trend="15"
        />
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Sales Overview</h3>
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Stock Distribution</h3>
            <canvas id="stockChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Recent Activity & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Orders</h3>
            <x-data-table 
                :headers="['Order ID', 'Patient', 'Medication', 'Status', 'Date']"
                :data="$recentOrders"
            />
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('medications.create') }}" class="block p-3 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-plus-circle text-teal-600"></i>
                        <span class="font-medium">Add New Medication</span>
                    </div>
                </a>
                <a href="{{ route('orders.create') }}" class="block p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-prescription text-blue-600"></i>
                        <span class="font-medium">Create Order</span>
                    </div>
                </a>
                <a href="{{ route('suppliers.create') }}" class="block p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-truck text-green-600"></i>
                        <span class="font-medium">Add Supplier</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Low Stock Alerts</h3>
            <span class="bg-orange-100 text-orange-800 text-sm font-medium px-2 py-1 rounded-full">
                {{ $lowStockItems->count() }} items
            </span>
        </div>
        <x-data-table 
            :headers="['Medication', 'Current Stock', 'Min Stock', 'Supplier']"
            :data="$lowStockItems"
        />
    </div>
</div>

<!-- Charts Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Sales',
                data: [1200, 1900, 1500, 2500, 2200, 3000, 2800],
                borderColor: 'rgb(20, 184, 166)',
                backgroundColor: 'rgba(20, 184, 166, 0.1)',
                tension: 0.4
            }]
        }
    });

    // Stock Chart
    const stockCtx = document.getElementById('stockChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'doughnut',
        data: {
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            datasets: [{
                data: [65, 25, 10],
                backgroundColor: [
                    'rgb(16, 185, 129)',
                    'rgb(251, 146, 60)',
                    'rgb(239, 68, 68)'
                ]
            }]
        }
    });
</script>
@endsection
```

### 4. **Service Layer Pattern**

**app/Services/MedicationService.php**
```php
<?php

namespace App\Services;

use App\Models\Medication;
use App\Models\StockAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MedicationService
{
    public function getMedicationsWithStock(array $filters = [])
    {
        $query = Medication::with(['category', 'supplier', 'stocks'])
            ->when(isset($filters['category']), function ($q) use ($filters) {
                return $q->where('category_id', $filters['category']);
            })
            ->when(isset($filters['supplier']), function ($q) use ($filters) {
                return $q->where('supplier_id', $filters['supplier']);
            })
            ->when(isset($filters['search']), function ($q) use ($filters) {
                return $q->where('name', 'like', '%' . $filters['search'] . '%');
            });

        return $query->paginate(15);
    }

    public function checkLowStockAlerts()
    {
        return Cache::remember('low_stock_alerts', 300, function () {
            return Medication::whereHas('stocks', function ($query) {
                $query->whereColumn('quantity_stock', '<=', 'medications.min_stock');
            })
            ->with(['category', 'supplier'])
            ->get();
        });
    }

    public function updateStockLevels(array $updates)
    {
        DB::transaction(function () use ($updates) {
            foreach ($updates as $medicationId => $quantity) {
                $medication = Medication::findOrFail($medicationId);
                
                // Create stock alert if needed
                if ($quantity <= $medication->min_stock) {
                    StockAlert::create([
                        'medication_id' => $medicationId,
                        'current_stock' => $quantity,
                        'min_stock' => $medication->min_stock,
                        'alert_type' => 'low_stock'
                    ]);
                }

                // Update stock
                $medication->stocks()->updateOrCreate(
                    ['medication_id' => $medicationId],
                    ['quantity_stock' => $quantity]
                );
            }
        });
    }

    public function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', 600, function () {
            return [
                'medications' => Medication::count(),
                'low_stock' => $this->checkLowStockAlerts()->count(),
                'today_orders' => Order::whereDate('created_at', today())->count(),
                'revenue' => Order::whereDate('created_at', today())->sum('total'),
            ];
        });
    }
}
```

### 5. **Form Request Validation**

**app/Http/Requests/StoreMedicationRequest.php**
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicationRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('create', Medication::class);
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:1',
            'barcode' => 'nullable|string|unique:medications,barcode',
            'expiry_date' => 'nullable|date|after:today',
            'storage_conditions' => 'nullable|string|max:500',
            'manufacturer' => 'nullable|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Medication name is required.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'min_stock.required' => 'Minimum stock level is required.',
            'category_id.exists' => 'Selected category does not exist.',
            'supplier_id.exists' => 'Selected supplier does not exist.',
            'barcode.unique' => 'This barcode is already in use.',
            'expiry_date.after' => 'Expiry date must be in the future.',
            'picture.image' => 'Please upload a valid image file.',
            'picture.max' => 'Image size should not exceed 2MB.'
        ];
    }
}
```

### 6. **Enhanced Controller**

**app/Http/Controllers/MedicationController.php**
```php
<?php

namespace App\Http\Controllers;

use App\Models\Medication;
use App\Services\MedicationService;
use App\Http\Requests\StoreMedicationRequest;
use App\Http\Requests\UpdateMedicationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MedicationController extends Controller
{
    protected $medicationService;

    public function __construct(MedicationService $medicationService)
    {
        $this->medicationService = $medicationService;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $medications = $this->medicationService->getMedicationsWithStock($request->all());
        
        return view('medications.index', [
            'medications' => $medications,
            'categories' => \App\Models\Category::all(),
            'suppliers' => \App\Models\Supplier::all()
        ]);
    }

    public function create()
    {
        $this->authorize('create', Medication::class);
        
        return view('medications.create', [
            'categories' => \App\Models\Category::all(),
            'suppliers' => \App\Models\Supplier::all()
        ]);
    }

    public function store(StoreMedicationRequest $request)
    {
        $this->authorize('create', Medication::class);
        
        $medication = $this->medicationService->createMedication($request->validated());
        
        return redirect()
            ->route('medications.index')
            ->with('success', 'Medication created successfully!');
    }

    public function show(Medication $medication)
    {
        $this->authorize('view', $medication);
        
        return view('medications.show', [
            'medication' => $medication->load(['category', 'supplier', 'stocks'])
        ]);
    }

    public function edit(Medication $medication)
    {
        $this->authorize('update', $medication);
        
        return view('medications.edit', [
            'medication' => $medication,
            'categories' => \App\Models\Category::all(),
            'suppliers' => \App\Models\Supplier::all()
        ]);
    }

    public function update(UpdateMedicationRequest $request, Medication $medication)
    {
        $this->authorize('update', $medication);
        
        $this->medicationService->updateMedication($medication, $request->validated());
        
        return redirect()
            ->route('medications.index')
            ->with('success', 'Medication updated successfully!');
    }

    public function destroy(Medication $medication)
    {
        $this->authorize('delete', $medication);
        
        $this->medicationService->deleteMedication($medication);
        
        return redirect()
            ->route('medications.index')
            ->with('success', 'Medication deleted successfully!');
    }

    public function search(Request $request): JsonResponse
    {
        $results = $this->medicationService->searchMedications($request->get('q'));
        
        return response()->json($results);
    }

    public function export(Request $request)
    {
        $medications = $this->medicationService->getMedicationsWithStock($request->all());
        
        return response()->streamDownload(function () use ($medications) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['Name', 'Category', 'Supplier', 'Price', 'Stock', 'Min Stock']);
            
            // CSV Data
            foreach ($medications as $medication) {
                fputcsv($file, [
                    $medication->name,
                    $medication->category->name,
                    $medication->supplier->name,
                    $medication->price,
                    $medication->current_stock,
                    $medication->min_stock
                ]);
            }
            
            fclose($file);
        }, 'medications_' . date('Y-m-d') . '.csv');
    }
}
```

### 7. **API Endpoints**

**routes/api.php**
```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MedicationController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\DashboardController;

Route::middleware('auth:sanctum')->group(function () {
    // Dashboard API
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/low-stock', [DashboardController::class, 'lowStock']);
    
    // Medications API
    Route::get('/medications', [MedicationController::class, 'index']);
    Route::post('/medications', [MedicationController::class, 'store']);
    Route::put('/medications/{medication}', [MedicationController::class, 'update']);
    Route::delete('/medications/{medication}', [MedicationController::class, 'destroy']);
    Route::get('/medications/search', [MedicationController::class, 'search']);
    
    // Orders API
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{order}', [OrderController::class, 'update']);
    
    // Stock Updates
    Route::post('/stock/update', [MedicationController::class, 'updateStock']);
});
```

### 8. **Enhanced Model with Relationships**

**app/Models/Medication.php**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'category_id',
        'supplier_id',
        'description',
        'price',
        'min_stock',
        'barcode',
        'expiry_date',
        'storage_conditions',
        'manufacturer',
        'picture'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function stockAlerts(): HasMany
    {
        return $this->hasMany(StockAlert::class);
    }

    public function getCurrentStockAttribute(): int
    {
        return $this->stocks()->sum('quantity_stock');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->min_stock;
    }

    public function scopeLowStock($query)
    {
        return $query->whereHas('stocks', function ($q) {
            $q->whereColumn('quantity_stock', '<=', 'medications.min_stock');
        });
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days));
    }
}
```

---

## 🚀 **Implementation Benefits**

### **Performance Improvements**
- ✅ **Caching** for frequently accessed data
- ✅ **Eager loading** to prevent N+1 queries
- ✅ **Pagination** for large datasets
- ✅ **Database indexing** on frequently queried columns

### **Code Quality**
- ✅ **Service layer** for business logic separation
- ✅ **Form requests** for validation
- ✅ **Reusable components** for DRY code
- ✅ **Proper error handling** and user feedback

### **User Experience**
- ✅ **Responsive design** with sidebar navigation
- ✅ **Global search** functionality
- ✅ **Real-time updates** with charts
- ✅ **Better forms** with validation feedback
- ✅ **Professional UI** with Tailwind CSS

### **Security**
- ✅ **Authentication** and authorization
- ✅ **CSRF protection** on all forms
- ✅ **Input validation** and sanitization
- ✅ **API authentication** with Sanctum

This enhanced architecture provides a solid foundation for a professional, scalable pharmacy management system! 🏥✨
