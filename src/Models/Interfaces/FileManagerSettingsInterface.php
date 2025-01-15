<?php

namespace Dietrichxx\FileManager\Models\Interfaces;

interface FileManagerSettingsInterface
{
    public function getMainDirectoryTitle(): string;

    public function isTransliterationTitle(): bool;

    public function getMainDirectoryPath(): string;

    public function getValidationSettings(): ValidationSettingsInterface;

    public function getMediaOptimizerSettings(): MediaOptimizerSettingsInterface;
}
