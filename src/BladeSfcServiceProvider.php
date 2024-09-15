<?php

namespace Theokbokki\BladeSfc;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeSfcServiceProvider extends ServiceProvider
{
    public function register()
    {}

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/blade-sfc.php' => config_path('blade-sfc.php'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\CompileViews::class,
            ]);
        }

        Blade::directive('css', function (string $filePath) {
            return "<?php 
                \$cssFilePath = \"".(isset($filePath) ? $filePath : null)."\";
                ob_start(); 
            ?>";
        });

        Blade::directive('endcss', function () {
            return "<?php
                \$cssContent = ob_get_clean();
                \Theokbokki\BladeSfc\BladeSfc::handleCss(\$cssFilePath, \$cssContent);
            ?>";
        });
    }
}
