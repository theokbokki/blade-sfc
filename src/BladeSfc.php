<?php

namespace Theokbokki\BladeSfc;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BladeSfc
{
    public static function handleCss(string $filePath, string $content): void
    {
        $css = preg_replace('/<style[^>]*>|<\/style>/', '', $content);

        static::handle('css', $filePath, $css);
    }

    public static function handleJs(string $filePath, string $content): void
    {
        $js = preg_replace('/<script[^>]*>|<\/script>/', '', $content);

        static::handle('js', $filePath, $js);
    }

    private static function handle(string $filetype, string $filePath, string $content) {
        if (empty($filePath)) {
            $filePath = config('blade-sfc.default_'.$filetype.'_output');
        }

        $outputPath = resource_path(trim($filePath, "\"' "));

        File::ensureDirectoryExists(Storage::path(dirname($outputPath)));

        if (!app()->has($filetype.'_written_files')) {
            app()->instance($filetype.'_written_files', []);
        }

        $writtenFiles = app($filetype.'_written_files');
        if (!in_array($outputPath, $writtenFiles)) {
            File::put($outputPath, '');
            $writtenFiles[] = $outputPath;
            app()->instance($filetype.'_written_files', $writtenFiles);
        }
        
        File::append($outputPath, $content);
    }
}
