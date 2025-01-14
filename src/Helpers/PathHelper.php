<?php

namespace Dietrichxx\FileManager\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PathHelper
{
    public function getPathFromStorage(string $path, string $title = null): string
    {
        if ($title) {
            return Storage::disk('public')->path($path . '/' . $title);
        }

        return Storage::disk('public')->path($path);
    }

    public function combinePathTitle(string $path, string $title): string
    {
        return $path . '/' . $title;
    }

    public function isDirectoryExists(string $directoryPath): bool
    {
        if(File::isDirectory($directoryPath)) {
            return true;
        }
        return false;
    }
}
