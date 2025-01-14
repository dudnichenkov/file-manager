<?php

namespace Dietrichxx\FileManager\Services;

use Dietrichxx\FileManager\Helpers\PathHelper;
use Dietrichxx\FileManager\Models\DirectoryStructure;
use Dietrichxx\FileManager\Models\Interfaces\FileManagerSettingsInterface;
use Dietrichxx\FileManager\Rules\FileValidation;
use Dietrichxx\FileManager\Services\Interfaces\FileManagerInterface;
use Dietrichxx\FileManager\Services\Interfaces\FileServiceInterface;
use Dietrichxx\FileManager\Services\Interfaces\StorageInitializerInterface;
use Dietrichxx\FileManager\Strategies\Interfaces\StorageStrategyResolverInterface;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager implements FileManagerInterface, StorageInitializerInterface
{
    protected FileServiceInterface $fileService;
    protected FileManagerSettingsInterface $fileManagerSettings;
    protected StorageStrategyResolverInterface $storageHandlerResolver;
    protected PathHelper $pathHelper;

    public function __construct(
        FileServiceInterface             $fileService,
        FileManagerSettingsInterface     $fileManagerSettings,
        StorageStrategyResolverInterface $storageHandlerResolver,
        PathHelper                       $pathHelper,
    ){
        $this->fileService = $fileService;
        $this->fileManagerSettings = $fileManagerSettings;
        $this->storageHandlerResolver = $storageHandlerResolver;
        $this->pathHelper = $pathHelper;
    }

    /**
     * @return bool
     */
    public function initStorage(): bool
    {
        $mainDirectoryPath = $this->fileManagerSettings->getMainDirectoryPath();

        try {
            if (!File::exists($mainDirectoryPath)) {
                File::makeDirectory($mainDirectoryPath, 0755, true);
            }

            $publicStoragePath = public_path('storage');
            if (!File::exists($publicStoragePath)) {
                Artisan::call('storage:link');
            }
            return true;
        }catch (Exception $exception){
            Log::error('Storage initialization error: ' . $exception->getMessage(), [
                'path' => $mainDirectoryPath,
            ]);
            return false;
        }
    }

    public function create(string $path, string|UploadedFile $createdInstance, string $type): bool
    {
        $storageHandler = $this->storageHandlerResolver->resolve($type);
        return $storageHandler->create($path, $createdInstance);
    }

    /**
     * @param string $path
     * @param string $oldTitle
     * @param string $newTitle
     * @param string $type
     * @return bool
     */
    public function update(string $path, string $oldTitle, string $newTitle, string $type): bool
    {
        $storageHandler = $this->storageHandlerResolver->resolve($type);
        return $storageHandler->update($path, $oldTitle, $newTitle);
    }

    /**
     * @param string $path
     * @param string $title
     * @param string $type
     * @return bool
     */
    public function delete(string $path, string $title, string $type): bool
    {
        $storageHandler = $this->storageHandlerResolver->resolve($type);
        return $storageHandler->delete($path, $title);
    }

    /**
     * @param string $path
     * @return DirectoryStructure
     */
    public function getDirectoryStructure(string $path): DirectoryStructure
    {
        return new DirectoryStructure(
            $this->getInternalFiles($path),
            $this->getInternalDirectories($path)
        );
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getInternalDirectories(string $path): array
    {
        return File::directories($this->pathHelper->getPathFromStorage($path));
    }

    /**
     * @param string $path
     * @return Collection
     */
    protected function getInternalFiles(string $path): Collection
    {
        return $this->fileService->getFilesByPath($path);
    }
}
