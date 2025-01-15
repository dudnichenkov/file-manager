<?php

return [
    'main_directory_title' => 'media',                                  // Имя корневой директории
    'transliteration_title' => false,                                   // Tранслитерация названий файлов и папок
    'media_optimizer' => [
        'driver' => 'gd',
        'compress_image' => true,
        'compression_quality' => 40
    ],
    'validation' => [
        'max_file_size_mb' => 5,                                        // Максимальный размер файла в MB
        'allow_all_extensions' => true,                                 // Можно поставить true, чтобы разрешить все расширения, или false что бы делать проверку по полю allowed_extensions
        'allowed_extensions' => [],                                     // Разрешённые расширения
        'disallowed_extensions' => ['exe', 'bat', 'js'],                // Запрещённые расширения
    ]
];
