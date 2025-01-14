<?php

namespace Dietrichxx\FileManager\Strategies;

use Dietrichxx\FileManager\Exceptions\FileNotFoundException;
use Dietrichxx\FileManager\Helpers\PathHelper;
use Dietrichxx\FileManager\Rules\FileValidation;
use Dietrichxx\FileManager\Services\Interfaces\FileServiceInterface;
use Dietrichxx\FileManager\Services\Interfaces\MediaOptimizerInterface;
use Dietrichxx\FileManager\Services\MediaOptimizer;
use Dietrichxx\FileManager\Strategies\Interfaces\StorageStrategyInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileStorageStrategy implements StorageStrategyInterface
{
    protected FileServiceInterface $fileService;
    protected PathHelper $pathHelper;
    protected FileValidation $fileValidation;
    protected MediaOptimizerInterface $mediaOptimizer;

    public function __construct(
        FileServiceInterface $fileService,
        PathHelper $pathHelper,
        FileValidation $fileValidation,
        MediaOptimizer $mediaOptimizer
    ){
        $this->fileService = $fileService;
        $this->pathHelper = $pathHelper;
        $this->fileValidation = $fileValidation;
        $this->mediaOptimizer = $mediaOptimizer;
    }

    /**
     * @param string $path
     * @param string|UploadedFile $createdInstance
     * @return bool
     * @throws ValidationException
     */
    public function create(string $path, string|UploadedFile $createdInstance): bool
    {
        if($this->validateFile($createdInstance)){
//            $mediaFile = $this->mediaOptimizer->optimize($createdInstance);
            dd($this->mediaOptimizer );
            $this->fileService->createFile($createdInstance->getClientOriginalName(), $path, $createdInstance->getClientOriginalExtension());
            return $createdInstance->storeAs($path, $createdInstance->getClientOriginalName(), 'public');
        }
    }

    /**
     * @param UploadedFile $file
     * @return bool
     * @throws ValidationException
     */
    protected function validateFile(UploadedFile $file): bool
    {
        $validator = Validator::make(
            ['file' => $file],
            [
                'file' => [$this->fileValidation],
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return true;
    }

    /**
     * @param string $path
     * @param string $oldTitle
     * @param string $newTitle
     * @return bool
     * @throws FileNotFoundException
     */
    public function update(string $path, string $oldTitle, string $newTitle): bool
    {
        $oldFilePath = $this->pathHelper->combinePathTitle($path, $oldTitle);
        $newFilePath = $this->pathHelper->combinePathTitle($path, $newTitle);

        if(Storage::disk('public')->exists($oldFilePath)){
            if (Storage::disk('public')->move($oldFilePath, $newFilePath)) {
                $file = $this->fileService->getFileByPathByTitle($path, $oldTitle);
                return $this->fileService->updateFile($file, $newTitle);
            }
            return false;
        }
        throw new FileNotFoundException($oldFilePath);
    }

    /**
     * @param string $path
     * @param string $title
     * @return bool
     * @throws FileNotFoundException
     */
    public function delete(string $path, string $title): bool
    {
        $file = $this->fileService->getFileByPathByTitle($path, $title);
        $filePath = $this->pathHelper->combinePathTitle($path, $title);

        if(Storage::disk('public')->exists($filePath)) {
            if (Storage::disk('public')->delete($filePath)) {
                return $this->fileService->deleteFile($file);
            }
            return false;
        }
        throw new FileNotFoundException($filePath);
    }
}
