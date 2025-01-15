<?php

namespace Dietrichxx\FileManager\Helpers;

use Dietrichxx\FileManager\Helpers\Interfaces\TransliteratorInterface;

class TitleTransliterator implements TransliteratorInterface
{
    public function transliterate(string $title): string
    {
        $transliterator = 'Any-Latin; Latin-ASCII; Lower(); [:Punctuation:] Remove';
        $latinTitle = transliterator_transliterate($transliterator, $title);

        return preg_replace('/\s+/', '_', $latinTitle);
    }
}
