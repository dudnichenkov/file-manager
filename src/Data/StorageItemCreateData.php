<?php

namespace Dietrichxx\FileManager\Data;

use Spatie\LaravelData\Data;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StorageItemCreateData extends Data
{
    public function __construct(
        public string $path,
        public ?UploadedFile $file,
        public ?string $directory_title,
    ) {}
}
