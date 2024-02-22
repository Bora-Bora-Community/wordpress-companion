<?php

namespace _PhpScoper9a3678ae6a12;

/**
 * Plugin Name: Carbon Fields
 * Description: WordPress developer-friendly custom fields for post types, taxonomy terms, users, comments, widgets, options, navigation menus and more.
 * Version: 3.6.3
 * Author: htmlburger
 * Author URI: https://htmlburger.com/
 * Plugin URI: http://carbonfields.net/
 * License: GPL2
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Text Domain: carbon-fields
 * Domain Path: /languages
 */
\define('_PhpScoper9a3678ae6a12\\Carbon_Fields_Plugin\\PLUGIN_FILE', __FILE__);
\define('_PhpScoper9a3678ae6a12\\Carbon_Fields_Plugin\\RELATIVE_PLUGIN_FILE', \basename(\dirname(\_PhpScoper9a3678ae6a12\Carbon_Fields_Plugin\PLUGIN_FILE)) . '/' . \basename(\_PhpScoper9a3678ae6a12\Carbon_Fields_Plugin\PLUGIN_FILE));
add_action('after_setup_theme', 'carbon_fields_boot_plugin');
/** @internal */
function carbon_fields_boot_plugin()
{
    if (\file_exists(__DIR__ . '/vendor/autoload.php')) {
        require __DIR__ . '/vendor/autoload.php';
    }
    \_PhpScoper9a3678ae6a12\Carbon_Fields\Carbon_Fields::boot();
    if (is_admin()) {
        \_PhpScoper9a3678ae6a12\Carbon_Fields_Plugin\Libraries\Plugin_Update_Warning\Plugin_Update_Warning::boot();
    }
}
