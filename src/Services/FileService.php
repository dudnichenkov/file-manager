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

    /**
     * @param string $title
     * @param string $path
     * @param string $extension
     * @return File
     */
    public function createFile(string $title, string $path, string $extension): File
    {
        return File::create([
            'title' => $title,
            'path' => $path,
            'extension' => $extension
        ]);
    }

    /**
     * @param string $directoryPath
     * @return Collection
     */
    public function getFilesByPath(string $directoryPath): Collection
    {
        return File::where('path', $directoryPath)->get();
    }

    /**
     * @param string $path
     * @param string $title
     * @return File
     */
    public function getFileByPathByTitle(string $path, string $title): File
    {
        return File::where('path', $path)->where('title', $title)->first();
    }

    /**
     * @param File $file
     * @param string $newTitle
     * @return bool
     */
    public function updateFile(File $file, string $newTitle): bool
    {
        return $file->update([
            'title' => $newTitle
        ]);
    }

    /**
     * @param Collection $files
     * @param string $path
     * @return bool
     */
    public function updatePathFiles(Collection $files, string $path): bool
    {
        if ($files->isEmpty()) {
            return true;
        }

        $ids = $files->pluck('id')->toArray();
        return File::whereIn('id', $ids)->update(['path' => $path]);
    }

    /**
     * @param File $file
     * @return bool
     */
    public function deleteFile(File $file): bool
    {
        return $file->delete();
    }

    /**
     * @param int $id
     * @return File
     */
    public function getFileById(int $id): File
    {
        return File::findOrFail($id);
    }
}
