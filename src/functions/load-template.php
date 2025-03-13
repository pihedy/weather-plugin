<?php

declare(strict_types=1);

/**
 * Filters and modifies the template to be used for displaying city weather pages.
 *
 * @param string $template The original template path.
 *
 * @return string The modified template path for city weather pages.
 */
add_filter('template_include', function (string $template): string {
    $location = get_query_var('location');

    if (empty($location)) {
        return $template;
    }

    $template = WPCM_PLUGIN_DIR . 'templates/city-weather-page.php';

    if (!file_exists($template)) {
        return $template;
    }

    return $template;
});
