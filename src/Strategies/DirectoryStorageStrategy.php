<?php

namespace Dietrichxx\FileManager\Strategies;

use Dietrichxx\FileManager\Exceptions\DirectoryAlreadyExistsException;
use Dietrichxx\FileManager\Exceptions\DirectoryNotFoundException;
use Dietrichxx\FileManager\Helpers\PathHelper;
use Dietrichxx\FileManager\Services\Interfaces\FileServiceInterface;
use Dietrichxx\FileManager\Services\Interfaces\TitleProcessorInterface;
use Dietrichxx\FileManager\Strategies\Interfaces\StorageStrategyInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @param string $path
     * @param string|UploadedFile $createdInstance
     * @return bool
     * @throws DirectoryAlreadyExistsException
     */
    public function create(string $path, string|UploadedFile $createdInstance): bool
    {
        $directoryTitle = $this->titleProcessor->process($createdInstance)->getTitle();

        $pathFromStorage = $this->pathHelper->getPathFromStorage($path, $directoryTitle);

        if($this->pathHelper->isDirectoryExists($pathFromStorage)){
            throw new DirectoryAlreadyExistsException($pathFromStorage);
        }else{
            return File::makeDirectory($pathFromStorage, 0755, true);
        }
    }

    /**
     * @param string $path
     * @param string $oldTitle
     * @param string $newTitle
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function update(string $path, string $oldTitle, string $newTitle): bool
    {
        $oldFilePath = $this->pathHelper->combinePathTitle($path, $oldTitle);
        $newFilePath = $this->pathHelper->combinePathTitle($path, $newTitle);

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
     * @param string $path
     * @param string $title
     * @return bool
     * @throws DirectoryNotFoundException
     */
    public function delete(string $path, string $title): bool
    {
        $directoryPath = $this->pathHelper->combinePathTitle($path, $title);
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
