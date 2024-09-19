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
        $this->mergeConfigFrom(__DIR__.'/../config/blade-sfc.php', 'blade-sfc-config');

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

        Blade::directive('js', function (string $filePath) {
            return "<?php 
                \$jsFilePath = \"".(isset($filePath) ? $filePath : null)."\";
                ob_start(); 
            ?>";
        });

        Blade::directive('endjs', function () {
            return "<?php
                \$jsContent = ob_get_clean();
                \Theokbokki\BladeSfc\BladeSfc::handleJs(\$jsFilePath, \$jsContent);
            ?>";
        });
    }
}
