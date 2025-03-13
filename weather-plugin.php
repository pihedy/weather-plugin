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
 * Defines the OpenWeatherMap API key constant.
 */
if (!defined('WPCM_OPENWEATHERMAP_API_KEY')) {
    wp_die('Please declare the constant "WPCM_OPENWEATHERMAP_API_KEY" in the wp-config file with your OpenWeatherMap API key.');
}

/**
 * Defines the absolute path to the main plugin file for use in other plugin components.
 */
define('WPCM_PLUGIN_FILE', __FILE__);

/**
 * Defines the absolute path to the plugin's directory for use in other plugin components.
 */
define('WPCM_PLUGIN_DIR', plugin_dir_path(__FILE__));

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

    require $file;
}, null, null);

$srcDir = __DIR__ . '/src';
$files = [];

scanFiles($srcDir, $files);

foreach ($files as $index => $file) {
    $Require($index, $file);
}
