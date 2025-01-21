<?php

namespace Dietrichxx\FileManager\Data;

use Spatie\LaravelData\Data;

class StorageItemDeleteData extends Data
{
    public function __construct(
        public ?string $path,
        public ?string $directory_title,
        public ?int $file_id,
    ) {}
}
