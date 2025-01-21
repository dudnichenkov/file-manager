<?php

namespace Dietrichxx\FileManager\Strategies;

use Dietrichxx\FileManager\Data\StorageItemCreateData;
use Dietrichxx\FileManager\Data\StorageItemDeleteData;
use Dietrichxx\FileManager\Data\StorageItemUpdateData;
use Dietrichxx\FileManager\Exceptions\DirectoryAlreadyExistsException;
use Dietrichxx\FileManager\Exceptions\DirectoryNotFoundException;
use Dietrichxx\FileManager\Helpers\PathHelper;
use Dietrichxx\FileManager\Services\Interfaces\FileServiceInterface;
use Dietrichxx\FileManager\Services\Interfaces\TitleProcessorInterface;
use Dietrichxx\FileManager\Strategies\Interfaces\StorageStrategyInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DirectoryStorageStrategy implements StorageStrategyInterface
{
    protected FileServiceInterface $fileService;
    protected PathHelper $pathHelper;
    protected TitleProcessorInterface $titleProcessor;

    public function __construct(FileServiceInterface $fileService, PathHelper $pathHelper, TitleProcessorInterface $titleProcessor)
    {
        $this->fileService = $fileService;
        $this->pathHelper = $pathHelper;
        $this->titleProcessor = $titleProcessor;
    }

    /**
     * @param StorageItemCreateData $storageItemData
     * @return bool
     * @throws DirectoryAlreadyExistsException
     */
    public function create(StorageItemCreateData $storageItemData): bool
    {
        $directoryTitle = $this->titleProcessor->process($storageItemData->directory_title)->getTitle();

        $pathFromStorage = $this->pathHelper->getPathFromStorage($storageItemData->path, $directoryTitle);

        if($this->pathHelper->isDirectoryExists($pathFromStorage)){
            throw new DirectoryAlreadyExistsException($pathFromStorage);
        }else{
            return File::makeDirectory($pathFromStorage, 0755, true);
        }
    }

    /**
     * @param StorageItemUpdateData $storageItemUpdateData
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function update(StorageItemUpdateData $storageItemUpdateData): bool
    {
        $oldFilePath = $this->pathHelper->combinePathTitle($storageItemUpdateData->path, $storageItemUpdateData->old_directory_title);
        $newFilePath = $this->pathHelper->combinePathTitle($storageItemUpdateData->path, $storageItemUpdateData->new_title);

        if(Storage::disk('public')->exists($oldFilePath)){
            $result = rename(
                Storage::disk('public')->path($oldFilePath),
                Storage::disk('public')->path($newFilePath)
            );

            if($result){
                $files = $this->fileService->getFilesByPath($oldFilePath);
                return $this->fileService->updatePathFiles($files, $newFilePath);
            }else{
                return false;
            }
        }
        throw new DirectoryNotFoundException($oldFilePath);
    }

    /**
     * @param StorageItemDeleteData $storageItemDeleteData
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function delete(StorageItemDeleteData $storageItemDeleteData): bool
    {
        $directoryPath = $this->pathHelper->combinePathTitle($storageItemDeleteData->path, $storageItemDeleteData->directory_title);
        $files = $this->fileService->getFilesByPath($directoryPath);

        if(Storage::disk('public')->exists($directoryPath)) {
            if (Storage::disk('public')->deleteDirectory($directoryPath)) {
                foreach ($files as $file) {
                    $this->fileService->deleteFile($file);
                }
                return true;
            }
            return false;
        }
        throw new DirectoryNotFoundException($directoryPath);
    }
}
