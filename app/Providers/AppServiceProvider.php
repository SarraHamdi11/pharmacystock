<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->loadCustomLanguageFiles();

        View::composer('layouts.app', function ($view) {
            try {
                $lowStockCount = Stock::where('quantity_stock', '<=', 10)->count();
                $expiringCount = Product::where('track_expiry', true)
                    ->whereNotNull('expiry_date')
                    ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                    ->count();

                $alerts = Product::with('stocks')
                    ->whereHas('stocks', fn ($q) => $q->where('quantity_stock', '<=', 10))
                    ->limit(5)
                    ->get()
                    ->map(fn ($p) => [
                        'type' => 'low_stock',
                        'message' => "{$p->name} — {$p->stocks->sum('quantity_stock')} units left",
                        'url' => route('products.edit', $p->id),
                    ]);

                $expiring = Product::where('track_expiry', true)
                    ->whereNotNull('expiry_date')
                    ->whereBetween('expiry_date', [now(), now()->addDays(14)])
                    ->orderBy('expiry_date')
                    ->limit(3)
                    ->get()
                    ->map(fn ($p) => [
                        'type' => 'expiring',
                        'message' => "{$p->name} expires {$p->expiry_date->format('M j')}",
                        'url' => route('products.edit', $p->id),
                    ]);

                $view->with([
                    'notificationCount' => $lowStockCount + $expiringCount,
                    'notifications' => $alerts->concat($expiring)->take(8),
                ]);
            } catch (\Exception $e) {
                $view->with(['notificationCount' => 0, 'notifications' => collect()]);
            }
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }

    /**
     * Charger les fichiers de langue personnalisés depuis un répertoire externe.
     *
     * @return void
     */
    protected function loadCustomLanguageFiles()
    {
        $langPath = base_path('chemin/vers/ton/dossier/lang');  // Remplace avec le chemin vers ton dossier de langues
        
        if (File::exists($langPath)) {
            foreach (File::directories($langPath) as $langDir) {
                $lang = basename($langDir);
                Lang::addNamespace($lang, $langDir);
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
