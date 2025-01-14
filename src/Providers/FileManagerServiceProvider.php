<?php

namespace Dietrichxx\FileManager\Providers;

use Dietrichxx\FileManager\Models\FileManagerSettings;
use Dietrichxx\FileManager\Models\Interfaces\FileManagerSettingsInterface;
use Dietrichxx\FileManager\Models\Interfaces\MediaOptimizerSettingsInterface;
use Dietrichxx\FileManager\Models\Interfaces\ValidationSettingsInterface;
use Dietrichxx\FileManager\Models\MediaOptimizerSettings;
use Dietrichxx\FileManager\Models\ValidationSettings;
use Dietrichxx\FileManager\Services\FileManager;
use Dietrichxx\FileManager\Services\FileService;
use Dietrichxx\FileManager\Services\Interfaces\FileManagerInterface;
use Dietrichxx\FileManager\Services\Interfaces\FileServiceInterface;
use Dietrichxx\FileManager\Services\Interfaces\MediaOptimizerInterface;
use Dietrichxx\FileManager\Services\Interfaces\StorageInitializerInterface;
use Dietrichxx\FileManager\Services\MediaOptimizer;
use Dietrichxx\FileManager\Strategies\Interfaces\StorageStrategyResolverInterface;
use Dietrichxx\FileManager\Strategies\StorageStrategyResolver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
//use Intervention\Image\Drivers\Gd\Driver as GdDriver;
//use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
//use Intervention\Image\ImageManager;

class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MediaOptimizerSettingsInterface::class, function () {
            $mediaOptimizerSettings = Config::get('filemanager.media_optimizer');
            return new MediaOptimizerSettings($mediaOptimizerSettings);
        });

        $this->app->singleton(ValidationSettingsInterface::class, function () {
            $validationSettings = Config::get('filemanager.validation');
            return new ValidationSettings($validationSettings);
        });

        $this->app->singleton(FileManagerSettingsInterface::class, function ($app) {
            $mainDirectoryTitle = Config::get('filemanager.main_directory_title', 'files');
            $validationSettings = $app->make(ValidationSettingsInterface::class);
            $mediaOptimizerSettings = $app->make(MediaOptimizerSettingsInterface::class);

            return new FileManagerSettings($mainDirectoryTitle, $validationSettings, $mediaOptimizerSettings);
        });

        $this->app->singleton(MediaOptimizerInterface::class, function ($app) {
            $mediaOptimizerSettings = $app->make(MediaOptimizerSettingsInterface::class);

//            $imageDriver = Config::get('filemanager[media_optimizer][driver]');
//            dd($imageDriver);
//            if($imageDriver === 'gb'){
//                $manager = new ImageManager(new GdDriver());
//            }elseif ($imageDriver === 'imagick'){
//                $manager = new ImageManager(new ImagickDriver());
//            }

            return new MediaOptimizer($mediaOptimizerSettings, 5);
        });

        $this->app->bind(FileServiceInterface::class, FileService::class);
        $this->app->bind(StorageStrategyResolverInterface::class, StorageStrategyResolver::class);

        $this->app->bind(FileManagerInterface::class, FileManager::class);
        $this->app->bind(StorageInitializerInterface::class, FileManager::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/filemanager.php' => config_path('filemanager.php'),
            __DIR__.'/../database/migrations/2025_01_06_112747_create_files_table.php' => 'database/migrations/2025_01_06_112747_create_files_table.php',
        ]);

        $this->app->make(StorageInitializerInterface::class)->initStorage();
    }
}
