# File Manager Package


## Описание

Пакет предоставляет класс `FileManager`, который упрощает работу с файловой системой проекта в директории `storage`. Он позволяет управлять файлами и каталогами, обеспечивая удобные методы для их обработки.

## Установка

Чтобы установить данный пакет, выполните следующую команду в консоли:

```bash
composer require dietrichxx/file-manager
```

## Регистрация провайдера

Добавьте следующую строку в массив `providers` файла `config/app.php`:

```php
'providers' => [
    // ...
    Dietrichxx\FileManager\Providers\FileManagerServiceProvider::class,
];
```

## Публикация файлов

Для публикации конфигурационных файлов выполните команду:

```bash
php artisan vendor:publish --provider="Dietrichxx\FileManager\Providers\FileManagerServiceProvider"
```
