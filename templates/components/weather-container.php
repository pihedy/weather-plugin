<?php

declare(strict_types=1);

/**
 * No direct access.
 */
if (!defined('ABSPATH')) {
    return;
}

$city = get_query_var('location');

?>

<div id="weather-container" data-location="<?php echo esc_attr($city); ?>" style="text-align: center;">
