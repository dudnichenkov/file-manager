<?php

namespace Dietrichxx\FileManager\Exceptions;

class DirectoryNotFoundException extends InstanceNotFoundException
{
    public function __construct(string $path)
    {
        parent::__construct("The directory at path '{$path}' not found.");
    }
}
