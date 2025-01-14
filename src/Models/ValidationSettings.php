<?php

namespace Dietrichxx\FileManager\Models;

use Dietrichxx\FileManager\Models\Interfaces\ValidationSettingsInterface;
use Illuminate\Support\Facades\Config;

class ValidationSettings implements ValidationSettingsInterface
{
    protected float $maxFileSizeMb;
    protected bool $allowAllExtensions;
    protected array $allowedExtensions;
    protected array $disallowedExtensions;

    /**
     * @param array $validationSettings
     */
    public function __construct(array $validationSettings)
    {
        $this->maxFileSizeMb = $validationSettings['max_file_size_mb'];
        $this->allowAllExtensions = $validationSettings['allow_all_extensions'];
        $this->allowedExtensions = $validationSettings['allowed_extensions'];
        $this->disallowedExtensions = $validationSettings['disallowed_extensions'];
    }

    public function getMaxFileSizeMb(): float
    {
        return $this->maxFileSizeMb;
    }

    public function isAllowAllExtensions(): bool
    {
        return $this->allowAllExtensions;
    }

    public function getAllowedExtensions(): array
    {
        return $this->allowedExtensions;
    }

    public function getDisallowedExtensions(): array
    {
        return $this->disallowedExtensions;
    }
}
