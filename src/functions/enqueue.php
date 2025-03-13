<?php

declare(strict_types=1);

/**
 * Enqueues the weather plugin's JavaScript file with jQuery dependency.
 */
add_action('wp_enqueue_scripts', function(): void {
    wp_enqueue_script('weather-plugin-script', plugins_url('assets/js/wpcm-script.js', WPCM_PLUGIN_FILE), ['jquery'], '1.0.0', true);
});
