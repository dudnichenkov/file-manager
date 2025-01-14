<?php

namespace Dietrichxx\FileManager\Models;

use Dietrichxx\FileManager\Models\Interfaces\FileManagerSettingsInterface;
use Dietrichxx\FileManager\Models\Interfaces\MediaOptimizerSettingsInterface;
use Dietrichxx\FileManager\Models\Interfaces\ValidationSettingsInterface;

class FileManagerSettings implements FileManagerSettingsInterface
{
    protected string $mainDirectoryTitle;
    protected string $mainDirectoryPath;
    protected ValidationSettingsInterface $validationSettings;
    protected MediaOptimizerSettingsInterface $mediaOptimizerSettings;

    public function __construct(string $mainDirectoryTitle, ValidationSettingsInterface $validationSettings, MediaOptimizerSettingsInterface $mediaOptimizerSettings)
    {
        $this->mainDirectoryTitle = $mainDirectoryTitle;
        $this->mainDirectoryPath = storage_path('app/public/' . $this->mainDirectoryTitle);

        $this->validationSettings = $validationSettings;
        $this->mediaOptimizerSettings = $mediaOptimizerSettings;
    }

    public function getMainDirectoryTitle(): string
    {
        return $this->mainDirectoryTitle;
    }

    public function getMainDirectoryPath(): string
    {
        return $this->mainDirectoryPath;
    }

    public function getValidationSettings(): ValidationSettingsInterface
    {
        return $this->validationSettings;
    }
}
