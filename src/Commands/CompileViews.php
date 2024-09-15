<?php

namespace Theokbokki\BladeSfc\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;

class CompileViews extends Command
{
    protected $signature = 'blade-sfc:compile';

    protected $description = 'Compile all Blade views to trigger CSS and JS generation';

    public function handle()
    {
        foreach (glob(resource_path('views/**/*.blade.php')) as $viewFile) {
            $viewName = str_replace([resource_path('views/'), '.blade.php'], '', $viewFile);

            View::make($viewName)->render();
        }

        $this->info('All Blade views have been compiled and CSS and JS updated.');
    }
}
