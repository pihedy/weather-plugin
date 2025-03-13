<?php

/**
 * Plugin Name: Weather Plugin (Centrál Mediacsoport Zrt. feladat)
 * Plugin URI:  https://github.com/pihedy/weather-plugin
 * Description: Ez a plugin az OpenWeatherMap API segítségével mutatja az aktuális időjárást.
 * Version:     1.0.0
 * Author:      Pihe Edmond
 * Author URI:  https://pihedy.github.io/
 * Text Domain: wpcm
 */

declare(strict_types=1);

/**
 * Recursively scans a directory for PHP files and populates the files array.
 *
 * @param string $dir The directory path to scan.
 * @param array  &$files Reference to an array that will be populated with PHP file paths.
 */
function scanFiles(string $dir, array &$files): void
{
    $iterator = new DirectoryIterator($dir);

    foreach ($iterator as $info) {
        if ($info->isDot()) {
            continue;
        }

        if ($info->isDir()) {
            scanFiles($info->getPathname(), $files);

            continue;
        }

        if (strtolower($info->getExtension()) === 'php') {
            $files[] = $info->getPathname();
        }
    }
}

/**
 * Autoloads files based on a provided identifier and file path.
 */
$Require = \Closure::bind(static function (mixed $identifier, string $file): void {
    if (!empty($GLOBALS['_wpcm_autoload_files'][$identifier])) {
        return;
    }

    $GLOBALS['_wpcm_autoload_files'][$identifier] = true;

    require __DIR__ . $file;
}, null, null);

$srcDir = __DIR__ . '/src';
$files = [];

scanFiles($srcDir, $files);

foreach ($files as $index => $file) {
    $Require($index, $file);
}
