<?php

namespace Dietrichxx\FileManager\Strategies\Interfaces;

interface StorageStrategyResolverInterface
{
    public function resolve(string $type): StorageStrategyInterface;
}
