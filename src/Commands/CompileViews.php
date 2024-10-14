<?php

namespace Theokbokki\BladeSfc\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Symfony\Component\Finder\Finder;

class CompileViews extends Command
{
    protected $signature = 'blade-sfc:compile';
    protected $description = 'Compile all Blade views and execute CSS/JS directives';

    public function handle()
    {
        // Set a compile flag to only extract JS and CSS when the command runs.
        app()->instance('blade-sfc-compiling', true);

        $directives = '';
        
        foreach (Finder::create()->files()->in(resource_path('views'))->name('*.blade.php') as $viewFile) {
            $this->info("Scanning: $viewFile");

            $content = File::get($viewFile);

            $directives .= $this->extractCssDirectives($content);
            $directives .= $this->extractJsDirectives($content);
        }

        $this->renderTemporaryBladeFile($directives);

        app()->forgetInstance('blade-sfc-compiling');

        $this->info('All Blade views have been scanned, and CSS/JS have been compiled.');
    }

    private function extractCssDirectives(string $content): string
    {
        preg_match_all('/@css\((?:\'(.*?)\'|)?\)(.*?)@endcss/s', $content, $matches);

        $directiveBlock = '';
        foreach ($matches[0] as $key => $match) {
            $filePath = $matches[1][$key] ?? '';
            $cssContent = $matches[2][$key] ?? '';
            $directiveBlock .= '@css('.($filePath ? '\''.$filePath.'\'' : null).")\n$cssContent\n@endcss\n";
        }

        return $directiveBlock;
    }

    private function extractJsDirectives(string $content): string
    {
        preg_match_all('/@javascript\((?:\'(.*?)\'|)?\)(.*?)@endjavascript/s', $content, $matches);

        $directiveBlock = '';
        foreach ($matches[0] as $key => $match) {
            $filePath = $matches[1][$key] ?? '';
            $jsContent = $matches[2][$key] ?? '';
            $directiveBlock .= '@javascript('.($filePath ? $filePath : null).")\n$jsContent\n@endjavascript\n";
        }

        return $directiveBlock;
    }

    private function renderTemporaryBladeFile(string $directives)
    {
        $tempBladeFilePath = resource_path('views/temp_sfc_compile.blade.php');

        File::put($tempBladeFilePath, $directives);

        try {
            View::make('temp_sfc_compile')->render();
            $this->info('Temporary Blade file rendered successfully.');
        } catch (\Exception $e) {
            $this->error('Error rendering the temporary Blade file: ' . $e->getMessage());
        }

        File::delete($tempBladeFilePath);
    }
}
