<?php

namespace Dietrichxx\FileManager\Models\Interfaces;

interface MediaOptimizerSettingsInterface
{
    public function getDriver(): string;

    public function isCompressImage(): bool;

    public function getCompressionQuality(): int;
}
