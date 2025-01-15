<?php

namespace Dietrichxx\FileManager\Services\Interfaces;

use Intervention\Image\Interfaces\EncodedImageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface MediaOptimizerInterface
{
    public function optimize(UploadedFile $mediaFile): EncodedImageInterface|false|string;
}
