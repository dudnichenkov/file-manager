<?php

namespace Dietrichxx\FileManager\Services;

use Dietrichxx\FileManager\Models\Interfaces\MediaOptimizerSettingsInterface;
use Dietrichxx\FileManager\Services\Interfaces\MediaOptimizerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaOptimizer implements MediaOptimizerInterface
{
    protected MediaOptimizerSettingsInterface $settings;
    protected int $test;

    public function __construct(MediaOptimizerSettingsInterface $settings, int $test)
    {
        $this->settings = $settings;
        $this->test = $test;
    }

    public function optimize(UploadedFile $mediaFile)
    {
//        if($this->settings->isCompressImage()) {
//            $manager = ImageManager::gd();
//            $image = $manager->read($mediaFile);
//            $image->encode(new AutoEncoder(quality: $this->settings->getCompressionQuality()));
//
//            // Возвращаем оптимизированное изображение
//            return $image;
//        }
    }
}
