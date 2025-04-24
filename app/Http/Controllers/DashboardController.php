<?php


namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Store;
use App\Models\User;
use App\Models\Maladie;
use App\Models\Medicament;
use App\Models\MaladieProduct;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = User::find(1); // Cherche l'utilisateur dont l'ID est 1
        $stats = [
            'customers' => \App\Models\Customer::count(),
            'products' => \App\Models\Product::count(),
            'suppliers' => \App\Models\Supplier::count(),
            'categories' => \App\Models\Category::count(),
            'maladies' => \App\Models\Maladie::count(),
            'orders' => \App\Models\Order::count(),
            'stocks' => \App\Models\Stock::sum('quantity_stock'),
        ];

        $medicaments = Medicament::with(['product.category'])->get();
        $categories = Category::with(['products.medicament'])->get();
        $stores = Store::with(['customers'])->get();

        return view('dashboard', [
            'pic' => $user->avatar,
            'stats' => $stats,
            'medicaments' => $medicaments,
            'categories' => $categories,
            'stores' => $stores
        ]);
    }

    public function customers(): View
    {
        return view('customers.index', [
            'customers' => Customer::paginate(10),
            'categories' => Category::all()
        ]);
    }

    public function suppliers(): View
    {
        return view('suppliers.index', [
            'suppliers' => Supplier::all()
        ]);
    }

    public function products(): View
    {
        return view('products.index', [
            'products' => Product::with(['category', 'supplier', 'stock'])->get()
        ]);
    }

    public function productsBySupplier(): View
    {
        $suppliers = Supplier::all();
        return view('products.by-supplier', compact('suppliers'));
    }

    public function getProductsBySupplier(Supplier $supplier)
    {
        $products = Product::with(['stock', 'category'])
            ->where('supplier_id', $supplier->id)
            ->get();
        return view('products._products_by_supplier', compact('products'));
    }

    public function productsByStore(): View
    {
        $stores = Store::all();
        return view('products.by-store', compact('stores'));
    }

    public function getProductsByStore(Store $store)
    {
        $products = Product::with(['category', 'stock'])
            ->whereHas('stock', function ($query) use ($store) {
                $query->where('store_id', $store->id);
            })
            ->get();

        return response()->json($products);
    }

    public function orders()
    {
        return view("orders.index");
    }

    public function saveCookie()
    {
        $name = request()->input("txtCookie");
        Cookie::queue("UserName", $name, 6000000);
        return redirect()->back();
    }

    public function saveSession(Request $request)
    {
        $name = $request->input("txtSession");
        $request->session()->put('SessionName', $name);
        return redirect()->back();
    }

    public function saveAvatar()
    {
        request()->validate([
            'avatarFile' => 'required|image',
        ]);
        $ext = request()->avatarFile->getClientOriginalExtension();
        $name = Str::random(30) . time() . "." . $ext;
        request()->avatarFile->move(public_path('storage/avatars'), $name);
        $user = User::find(1);
        $user->update(['avatar' => $name]);
        return redirect()->back();
    }

    // Maladies
    public function maladies(): View
    {
        return view('maladies.index', [
            'maladies' => Maladie::all()
        ]);
    }

    public function getMedicinesByMalady(Maladie $malady)
    {
        $medicines = Medicament::with(['productMaladies'])
            ->whereHas('productMaladies', function ($query) use ($malady) {
                $query->where('malady_id', $malady->id);
            })
            ->get();

        return response()->json($medicines);
    }

    public function productMaladies(): View
    {
        $productMaladies = MaladieProduct::all();
        return view('productmaladies.index', compact('productMaladies'));
    }

    public function saveMedicineToMalady(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'malady_id' => 'required|exists:maladies,id',
        ]);

        MaladieProduct::create([
            'product_id' => $validated['product_id'],
            'malady_id' => $validated['malady_id'],
        ]);

        return redirect()->back();
    }
}
