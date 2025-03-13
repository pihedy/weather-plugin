<?php

declare(strict_types=1);

/**
 * No direct access.
 */
if (!defined('ABSPATH')) {
    return;
}

get_header();

include_once __DIR__ . '/components/weather-container.php';

get_footer();
