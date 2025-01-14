<?php

namespace Dietrichxx\FileManager\Services\Interfaces;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface MediaOptimizerInterface
{
    public function optimize(UploadedFile $mediaFile);
}
