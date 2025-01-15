<?php

namespace Dietrichxx\FileManager\Strategies\Interfaces;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface StorageStrategyInterface
{
    public function create(string $path, string|UploadedFile $createdInstance): bool;

    public function update(string $path, string $oldTitle, string $newTitle): bool;

    public function delete(string $path, string $title): bool;
}
