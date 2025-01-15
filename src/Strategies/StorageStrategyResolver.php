<?php

namespace Dietrichxx\FileManager\Strategies;

use Dietrichxx\FileManager\Strategies\Interfaces\StorageStrategyInterface;
use Dietrichxx\FileManager\Strategies\Interfaces\StorageStrategyResolverInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use InvalidArgumentException;

class StorageStrategyResolver implements StorageStrategyResolverInterface
{
    /**
     * @param string $type
     * @return StorageStrategyInterface
     * @throws BindingResolutionException
     */
    public function resolve(string $type): StorageStrategyInterface
    {
        if($type === 'directory'){
            return app()->make(DirectoryStorageStrategy::class);
        }elseif ($type === 'file'){
            return app()->make(FileStorageStrategy::class);
        }
        throw new InvalidArgumentException("$type is not a valid storage handler");
    }
}
