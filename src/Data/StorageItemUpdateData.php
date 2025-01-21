<?php

namespace Dietrichxx\FileManager\Data;

use Spatie\LaravelData\Data;

class StorageItemUpdateData extends Data
{
    public function __construct(
        public ?string $path,
        public ?string $old_directory_title,
        public string $new_title,
        public ?int $file_id,
    ) {}
}
