<?php

namespace Dietrichxx\FileManager\Services\Interfaces;

use Dietrichxx\FileManager\Models\DirectoryStructure;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileManagerInterface
{
    public function getDirectoryStructure(string $path): DirectoryStructure;
    public function create(string $path, string|UploadedFile $createdInstance, string $type): bool;

    public function update(string $path, string $oldTitle, string $newTitle, string $type): bool;

    public function delete(string $path, string $title, string $type): bool;
}
