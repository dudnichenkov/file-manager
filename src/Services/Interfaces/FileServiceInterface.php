<?php

namespace Dietrichxx\FileManager\Services\Interfaces;

use Dietrichxx\FileManager\Models\File;
use Illuminate\Support\Collection;

interface FileServiceInterface
{
    public function createFile(string $title, string $path, string $extension): File;

    public function getFilesByPath(string $directoryPath): Collection;

    public function getFileByPathByTitle(string $path, string $title): File;

    public function updateFile(File $file, string $newTitle): bool;

    public function updatePathFiles(Collection $files, string $path): bool;

    public function deleteFile(File $file): bool;

    public function getFileById(int $id): File;
}
