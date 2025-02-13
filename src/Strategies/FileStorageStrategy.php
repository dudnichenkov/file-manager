<?php

namespace Dietrichxx\FileManager\Strategies;

use Dietrichxx\FileManager\Data\StorageItemCreateData;
use Dietrichxx\FileManager\Data\StorageItemDeleteData;
use Dietrichxx\FileManager\Data\StorageItemUpdateData;
use Dietrichxx\FileManager\Exceptions\FileNotFoundException;
use Dietrichxx\FileManager\Helpers\PathHelper;
use Dietrichxx\FileManager\Models\File;
use Dietrichxx\FileManager\Rules\FileValidation;
use Dietrichxx\FileManager\Services\Interfaces\FileServiceInterface;
use Dietrichxx\FileManager\Services\Interfaces\MediaOptimizerInterface;
use Dietrichxx\FileManager\Services\Interfaces\TitleProcessorInterface;
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
    protected TitleProcessorInterface $titleProcessor;
    protected FileValidation $fileValidation;
    protected MediaOptimizerInterface $mediaOptimizer;

    public function __construct(
        FileServiceInterface $fileService,
        PathHelper $pathHelper,
        TitleProcessorInterface $titleProcessor,
        FileValidation $fileValidation,
        MediaOptimizer $mediaOptimizer
    ){
        $this->fileService = $fileService;
        $this->pathHelper = $pathHelper;
        $this->titleProcessor = $titleProcessor;
        $this->fileValidation = $fileValidation;
        $this->mediaOptimizer = $mediaOptimizer;
    }

    /**
     * @param StorageItemCreateData $storageItemData
     * @return bool
     * @throws ValidationException
     */
    public function create(StorageItemCreateData $storageItemData): bool
    {
        $uploadedFile = $storageItemData->file;

        if (!$this->validateFile($uploadedFile)) {
            return false;
        }

        $file = $this->createFileEntry($uploadedFile, $storageItemData->path);
        if (!$file) {
            return false;
        }

        $fileTitleWithId = $this->generateUniqueTitle($uploadedFile, $file->id);
        $this->fileService->updateFile($file, $fileTitleWithId);

        return $this->saveOptimizedFile($uploadedFile, $storageItemData->path, $fileTitleWithId);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string $path
     * @return File
     */
    protected function createFileEntry(UploadedFile $uploadedFile, string $path): File
    {
        $fileTitle = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $fileExtension = $uploadedFile->getClientOriginalExtension();

        return $this->fileService->createFile($fileTitle, $path, $fileExtension);
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param int $fileId
     * @return string
     */
    protected function generateUniqueTitle(UploadedFile $uploadedFile, int $fileId): string
    {
        $fileTitle = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        return $this->titleProcessor->process($fileTitle)
            ->addUniquePrefix($fileId)
            ->getTitle();
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string $path
     * @param string $fileTitleWithId
     * @return bool
     */
    protected function saveOptimizedFile(UploadedFile $uploadedFile, string $path, string $fileTitleWithId): bool
    {
        $fileExtension = $uploadedFile->getClientOriginalExtension();
        $optimizedFile = $this->mediaOptimizer->optimize($uploadedFile);

        return Storage::disk('public')->put(
            $this->pathHelper->combinePathTitleExtension($path, $fileTitleWithId, $fileExtension),
            $optimizedFile
        );
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
     * @param StorageItemUpdateData $storageItemUpdateData
     * @return bool
     * @throws FileNotFoundException
     */
    public function update(StorageItemUpdateData $storageItemUpdateData): bool
    {
        $file = $this->fileService->getFileByID($storageItemUpdateData->file_id);

        $oldPath = $this->pathHelper->combinePathTitleExtension($file->path, $file->title, $file->extension);
        $newFilePath = $this->pathHelper->combinePathTitleExtension($file->path, $storageItemUpdateData->new_title, $file->extension);

        if(Storage::disk('public')->exists($file->path)){
            if (Storage::disk('public')->move($oldPath, $newFilePath)) {
                return $this->fileService->updateFile($file, $storageItemUpdateData->new_title);
            }
            return false;
        }
        throw new FileNotFoundException(($file->path));
    }

    /**
     * @param StorageItemDeleteData $storageItemDeleteData
     * @return bool
     * @throws FileNotFoundException
     */
    public function delete(StorageItemDeleteData $storageItemDeleteData): bool
    {
        $file = $this->fileService->getFileById($storageItemDeleteData->file_id);
        $filePath = $this->pathHelper->combinePathTitleExtension($file->path, $file->title, $file->extension);

        if(Storage::disk('public')->exists($filePath)) {
            if (Storage::disk('public')->delete($filePath)) {
                return $this->fileService->deleteFile($file);
            }
            return false;
        }
        throw new FileNotFoundException($filePath);
    }
}
