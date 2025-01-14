<?php

namespace Dietrichxx\FileManager\Models\Interfaces;

interface ValidationSettingsInterface
{
    public function getMaxFileSizeMb(): float;

    public function isAllowAllExtensions(): bool;

    public function getAllowedExtensions(): array;

    public function getDisallowedExtensions(): array;
}
