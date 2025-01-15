<?php

namespace Dietrichxx\FileManager\Exceptions;

use Exception;

class DirectoryAlreadyExistsException extends Exception
{
    public function __construct(string $directoryPath)
    {
        parent::__construct("The directory at path '{$directoryPath}' already exists.");
    }
}
