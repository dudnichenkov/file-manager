<?php

namespace Dietrichxx\FileManager\Helpers\Interfaces;

interface TransliteratorInterface
{
    public function transliterate(string $title): string;
}
