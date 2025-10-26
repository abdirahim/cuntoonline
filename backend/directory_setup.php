<?php

$directories = [
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
        echo "Created: $directory\n";
    } else {
        echo "Exists: $directory\n";
    }
}

echo "Done!\n";
