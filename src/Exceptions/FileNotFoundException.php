<?php

namespace Dietrichxx\FileManager\Exceptions;

class FileNotFoundException extends InstanceNotFoundException
{
    public function __construct(string $path)
    {
        parent::__construct("The file at path '{$path}' not found.");
    }
}
