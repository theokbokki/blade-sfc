<?php

namespace Theokbokki\BladeSfc;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BladeSfc
{
    public static function handleCss(string $filePath, string $content): void
    {
        if (empty($filePath)) {
            $filePath = config('blade-sfc.default_css_output');
        }

        $cssOutputPath = resource_path(trim($filePath, "\"' "));

        File::ensureDirectoryExists(Storage::path(dirname($cssOutputPath)));

        if (!app()->has('css_written_files')) {
            app()->instance('css_written_files', []);
        }

        $writtenFiles = app('css_written_files');
        if (!in_array($cssOutputPath, $writtenFiles)) {
            File::put($cssOutputPath, '');
            $writtenFiles[] = $cssOutputPath;
            app()->instance('css_written_files', $writtenFiles);
        }

        $css = preg_replace('/<style[^>]*>|<\/style>/', '', $content);
        
        File::append($cssOutputPath, $css);
    }
}
