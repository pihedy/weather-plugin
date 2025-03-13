<?php

declare(strict_types=1);

/**
 * Registers location rewrite rules when the plugin is activated.
 */
register_activation_hook(WPCM_PLUGIN_FILE, function (): void {
    addLocationRewriteRule();
    flush_rewrite_rules();
});


/**
 * Initializes location rewrite rules during WordPress initialization.
 */
add_action('init', function (): void {
    addLocationRewriteRule();
});

/**
 * Registers a custom rewrite tag and rule for city-based location URLs.
 */
function addLocationRewriteRule(): void
{
    add_rewrite_tag('%location%', '([^&]+)');

    add_rewrite_rule(
        '^city/([^/]*)/?',
        'index.php?location=$matches[1]',
        'top'
    );
}
