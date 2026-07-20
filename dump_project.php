<?php

$rootDir = __DIR__; // dossier racine du projet
$outputFile = 'dump.txt';

// extensions à inclure
$allowedExtensions = ['php', 'js', 'css', 'env', 'blade.php', 'json', 'md', 'txt'];

// dossiers à ignorer
$ignoredDirs = ['vendor', 'node_modules', '.git', 'storage/logs'];

$fileHandle = fopen($outputFile, 'w');

function shouldIgnore($path, $ignoredDirs) {
    foreach ($ignoredDirs as $dir) {
        if (strpos($path, $dir) !== false) {
            return true;
        }
    }
    return false;
}

function getExtension($file) {
    if (str_ends_with($file, '.blade.php')) {
        return 'blade.php';
    }
    return pathinfo($file, PATHINFO_EXTENSION);
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootDir)
);

foreach ($iterator as $file) {
    if ($file->isDir()) continue;

    $filePath = $file->getPathname();

    if (shouldIgnore($filePath, $ignoredDirs)) continue;

    $extension = getExtension($filePath);

    if (!in_array($extension, $allowedExtensions)) continue;

    fwrite($fileHandle, "==============================\n");
    fwrite($fileHandle, "FILE: $filePath\n");
    fwrite($fileHandle, "==============================\n\n");

    $content = file_get_contents($filePath);
    fwrite($fileHandle, $content . "\n\n\n");
}

fclose($fileHandle);

echo "Dump terminé dans dump.txt\n";