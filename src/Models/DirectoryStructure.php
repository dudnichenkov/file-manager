<?php

namespace Dietrichxx\FileManager\Models;

use Illuminate\Support\Collection;

class DirectoryStructure
{
    protected Collection $files;
    protected array $directories;

    public function __construct(Collection $files, array $directories = [])
    {
        $this->files = $files;
        $this->directories = $directories;
    }

    public function getDirectories(): array
    {
        return $this->directories;
    }

    public function getFiles(): Collection
    {
        return $this->files;
    }
}
