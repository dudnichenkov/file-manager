<?php

namespace Dietrichxx\FileManager\Strategies\Interfaces;

use Dietrichxx\FileManager\Data\StorageItemCreateData;
use Dietrichxx\FileManager\Data\StorageItemDeleteData;
use Dietrichxx\FileManager\Data\StorageItemUpdateData;

interface StorageStrategyInterface
{
    public function create(StorageItemCreateData $storageItemData): bool;

    public function update(StorageItemUpdateData $storageItemUpdateData): bool;

    public function delete(StorageItemDeleteData $storageItemDeleteData): bool;
}
