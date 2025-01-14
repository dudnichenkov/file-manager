<?php

namespace Dietrichxx\FileManager\Models;

use Dietrichxx\FileManager\Models\Interfaces\MediaOptimizerSettingsInterface;

class MediaOptimizerSettings implements MediaOptimizerSettingsInterface
{
    protected string $driver;
    protected bool $compressImage;
    protected int $compressionQuality;

    /**
     * @param array $mediaOptimizerSettings
     */
    public function __construct(array $mediaOptimizerSettings)
    {
        $this->driver = $mediaOptimizerSettings['driver'];
        $this->compressImage = $mediaOptimizerSettings['compress_image'];
        $this->compressionQuality = $mediaOptimizerSettings['compression_quality'];
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function isCompressImage(): bool
    {
        return $this->compressImage;
    }

    public function getCompressionQuality(): int
    {
        return $this->compressionQuality;
    }
}
