<?php

namespace Dietrichxx\FileManager\Strategies;

use Dietrichxx\FileManager\Strategies\Interfaces\StorageStrategyInterface;
use Dietrichxx\FileManager\Strategies\Interfaces\StorageStrategyResolverInterface;
use InvalidArgumentException;

class StorageStrategyResolver implements StorageStrategyResolverInterface
{
    protected DirectoryStorageStrategy $directoryStorageStrategy;
    protected FileStorageStrategy $fileStorageStrategy;

    public function __construct(DirectoryStorageStrategy $directoryStorageStrategy, FileStorageStrategy  $fileStorageStrategy)
    {
        $this->directoryStorageStrategy = $directoryStorageStrategy;
        $this->fileStorageStrategy = $fileStorageStrategy;
    }

    /**
     * @param string $type
     * @return StorageStrategyInterface
     */
    public function resolve(string $type): StorageStrategyInterface
    {
        if($type === 'directory'){
            return $this->directoryStorageStrategy;
        }elseif ($type === 'file'){
            return $this->fileStorageStrategy;
        }
        throw new InvalidArgumentException("$type is not a valid storage handler");
    }
}
