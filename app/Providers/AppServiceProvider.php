<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadCustomLanguageFiles();
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
