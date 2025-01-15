<?php

namespace Dietrichxx\FileManager\Services;

use Dietrichxx\FileManager\Helpers\PathHelper;
use Dietrichxx\FileManager\Models\File;
use Dietrichxx\FileManager\Services\Interfaces\FileServiceInterface;
use Illuminate\Support\Collection;

class FileService implements FileServiceInterface
{
    protected PathHelper $pathHelper;

    public function __construct(PathHelper $pathHelper)
    {
        $this->pathHelper = $pathHelper;
    }

    public function createFile(string $title, string $path, string $extension): File
    {
        return File::create([
            'title' => $title,
            'path' => $path,
            'extension' => $extension
        ]);
    }

    public function getFilesByPath(string $directoryPath): Collection
    {
        return File::where('path', $directoryPath)->get();
    }

    public function getFileByPathByTitle(string $path, string $title): File
    {
        return File::where('path', $path)->where('title', $title)->first();
    }

    public function updateFile(File $file, string $newTitle): bool
    {
        return $file->update([
            'title' => $newTitle
        ]);
    }

    public function updatePathFiles(Collection $files, string $path): bool
    {
        $ids = $files->pluck('id')->toArray();
        return File::whereIn('id', $ids)->update(['path' => $path]);
    }

    public function deleteFile(File $file): bool
    {
        return $file->delete();
    }
}
