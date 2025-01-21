<?php

namespace Dietrichxx\FileManager\Services\Interfaces;

use Dietrichxx\FileManager\Data\StorageItemCreateData;
use Dietrichxx\FileManager\Data\StorageItemDeleteData;
use Dietrichxx\FileManager\Data\StorageItemUpdateData;
use Dietrichxx\FileManager\Models\DirectoryStructure;

interface FileManagerInterface
{
    public function getDirectoryStructure(string $path): DirectoryStructure;

    public function create(StorageItemCreateData $storageItemData, string $type): bool;

    public function update(StorageItemUpdateData $storageItemUpdateData, string $type): bool;

    public function delete(StorageItemDeleteData $storageItemDeleteData, string $type): bool;
}
