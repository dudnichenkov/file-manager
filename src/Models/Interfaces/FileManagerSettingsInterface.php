<?php

namespace Dietrichxx\FileManager\Models\Interfaces;

interface FileManagerSettingsInterface
{
    public function getMainDirectoryTitle(): string;

    public function getMainDirectoryPath(): string;

    public function getValidationSettings(): ValidationSettingsInterface;
}
