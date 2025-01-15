<?php

namespace Dietrichxx\FileManager\Services\Interfaces;

interface TitleProcessorInterface
{
    public function process(string $title): self;

    public function addUniquePrefix(string $uniqueValue): self;

    public function getTitle(): string;
}
