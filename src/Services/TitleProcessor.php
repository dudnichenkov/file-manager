<?php

namespace Dietrichxx\FileManager\Services;

use Dietrichxx\FileManager\Helpers\Interfaces\TransliteratorInterface;
use Dietrichxx\FileManager\Services\Interfaces\TitleProcessorInterface;

class TitleProcessor implements TitleProcessorInterface
{
    protected TransliteratorInterface $transliterator;
    protected bool $isTransliterationTitle;
    protected string $title;

    public function __construct(TransliteratorInterface $transliterator, bool $isTransliterationTitle)
    {
        $this->transliterator = $transliterator;
        $this->isTransliterationTitle = $isTransliterationTitle;
    }

    public function process(string $title): self
    {
        $noPunctuationTitle = preg_replace('/[[:punct:]]+/', '', $title);
        $formattedTitle = preg_replace('/[\s\-]+/', '_', $noPunctuationTitle);

        $this->title = $formattedTitle;

        if($this->isTransliterationTitle){
            $this->title = $this->transliterator->transliterate($title);
        }

        return $this;
    }

    public function addUniquePrefix(string $uniqueValue): self
    {
        $this->title = "{$uniqueValue}_{$this->title}";
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
