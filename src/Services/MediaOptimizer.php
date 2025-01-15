<?php

namespace Dietrichxx\FileManager\Services;

use Dietrichxx\FileManager\Models\Interfaces\MediaOptimizerSettingsInterface;
use Dietrichxx\FileManager\Services\Interfaces\MediaOptimizerInterface;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Intervention\Image\Laravel\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaOptimizer implements MediaOptimizerInterface
{
    protected MediaOptimizerSettingsInterface $settings;
    protected array $allowedMediaExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    public function __construct(MediaOptimizerSettingsInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param UploadedFile $mediaFile
     * @return EncodedImageInterface|false|string
     */
    public function optimize(UploadedFile $mediaFile): EncodedImageInterface|false|string
    {
        if($this->settings->isCompressImage()){
            if(in_array($mediaFile->getClientOriginalExtension(), $this->allowedMediaExtensions)) {
                return Image::read($mediaFile)->encodeByMediaType(quality: $this->settings->getCompressionQuality());
            }else{
                return file_get_contents($mediaFile);
            }
        }
        return file_get_contents($mediaFile);
    }
}
