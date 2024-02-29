<?php

// scoper-autoload.php @generated by PhpScoper

$loader = (static function ()
{
    // Backup the autoloaded Composer files
    $existingComposerAutoloadFiles = $GLOBALS['__composer_autoload_files'] ?? [];

    $loader = require_once __DIR__.'/autoload.php';
    // Ensure InstalledVersions is available
    $installedVersionsPath = __DIR__.'/composer/InstalledVersions.php';
    if (file_exists($installedVersionsPath)) {
        require_once $installedVersionsPath;
    }

    // Restore the backup and ensure the excluded files are properly marked as loaded
    $GLOBALS['__composer_autoload_files'] = \array_merge(
        $existingComposerAutoloadFiles,
        \array_fill_keys([], true)
    );

    return $loader;
})();

// Class aliases. For more information see:
// https://github.com/humbug/php-scoper/blob/master/docs/further-reading.md#class-aliases
if (!function_exists('humbug_phpscoper_expose_class')) {
    function humbug_phpscoper_expose_class($exposed, $prefixed)
    {
        if (!class_exists($exposed, false) && !interface_exists($exposed, false) && !trait_exists($exposed, false)) {
            spl_autoload_call($prefixed);
        }
    }
}
humbug_phpscoper_expose_class('Bora_bora_Admin', '_PhpScoper9a3678ae6a12\Bora_bora_Admin');
humbug_phpscoper_expose_class('BB_Activator', '_PhpScoper9a3678ae6a12\Bora_bora_Activator');
humbug_phpscoper_expose_class('BB_Deactivator', '_PhpScoper9a3678ae6a12\Bora_bora_Deactivator');
humbug_phpscoper_expose_class('Bora_bora_i18n', '_PhpScoper9a3678ae6a12\Bora_bora_i18n');
humbug_phpscoper_expose_class('Bora_bora_Loader', '_PhpScoper9a3678ae6a12\Bora_bora_Loader');
humbug_phpscoper_expose_class('Bora_bora', '_PhpScoper9a3678ae6a12\Bora_bora');
humbug_phpscoper_expose_class('Bora_bora_Public', '_PhpScoper9a3678ae6a12\Bora_bora_Public');
humbug_phpscoper_expose_class('ComposerAutoloaderInitfc58732b16a20f35c5db75000563c411',
    '_PhpScoper9a3678ae6a12\ComposerAutoloaderInitfc58732b16a20f35c5db75000563c411');

// Function aliases. For more information see:
// https://github.com/humbug/php-scoper/blob/master/docs/further-reading.md#function-aliases
if (!function_exists('__')) {
    function __()
    {
        return \_PhpScoper9a3678ae6a12\__(...func_get_args());
    }
}
if (!function_exists('_e')) {
    function _e()
    {
        return \_PhpScoper9a3678ae6a12\_e(...func_get_args());
    }
}
if (!function_exists('activate_bora_bora')) {
    function activate_bora_bora()
    {
        return \_PhpScoper9a3678ae6a12\activate_bora_bora(...func_get_args());
    }
}
if (!function_exists('add_action')) {
    function add_action()
    {
        return \_PhpScoper9a3678ae6a12\add_action(...func_get_args());
    }
}
if (!function_exists('add_filter')) {
    function add_filter()
    {
        return \_PhpScoper9a3678ae6a12\add_filter(...func_get_args());
    }
}
if (!function_exists('apply_filters')) {
    function apply_filters()
    {
        return \_PhpScoper9a3678ae6a12\apply_filters(...func_get_args());
    }
}
if (!function_exists('carbon_field_exists')) {
    function carbon_field_exists()
    {
        return \_PhpScoper9a3678ae6a12\carbon_field_exists(...func_get_args());
    }
}
if (!function_exists('carbon_fields_boot_plugin')) {
    function carbon_fields_boot_plugin()
    {
        return \_PhpScoper9a3678ae6a12\carbon_fields_boot_plugin(...func_get_args());
    }
}
if (!function_exists('carbon_get')) {
    function carbon_get()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get(...func_get_args());
    }
}
if (!function_exists('carbon_get_comment_meta')) {
    function carbon_get_comment_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get_comment_meta(...func_get_args());
    }
}
if (!function_exists('carbon_get_nav_menu_item_meta')) {
    function carbon_get_nav_menu_item_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get_nav_menu_item_meta(...func_get_args());
    }
}
if (!function_exists('carbon_get_network_option')) {
    function carbon_get_network_option()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get_network_option(...func_get_args());
    }
}
if (!function_exists('carbon_get_post_meta')) {
    function carbon_get_post_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get_post_meta(...func_get_args());
    }
}
if (!function_exists('carbon_get_term_meta')) {
    function carbon_get_term_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get_term_meta(...func_get_args());
    }
}
if (!function_exists('carbon_get_the_network_option')) {
    function carbon_get_the_network_option()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get_the_network_option(...func_get_args());
    }
}
if (!function_exists('carbon_get_the_post_meta')) {
    function carbon_get_the_post_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get_the_post_meta(...func_get_args());
    }
}
if (!function_exists('carbon_get_theme_option')) {
    function carbon_get_theme_option()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get_theme_option(...func_get_args());
    }
}
if (!function_exists('carbon_get_user_meta')) {
    function carbon_get_user_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_get_user_meta(...func_get_args());
    }
}
if (!function_exists('carbon_hex_to_rgba')) {
    function carbon_hex_to_rgba()
    {
        return \_PhpScoper9a3678ae6a12\carbon_hex_to_rgba(...func_get_args());
    }
}
if (!function_exists('carbon_set')) {
    function carbon_set()
    {
        return \_PhpScoper9a3678ae6a12\carbon_set(...func_get_args());
    }
}
if (!function_exists('carbon_set_comment_meta')) {
    function carbon_set_comment_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_set_comment_meta(...func_get_args());
    }
}
if (!function_exists('carbon_set_nav_menu_item_meta')) {
    function carbon_set_nav_menu_item_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_set_nav_menu_item_meta(...func_get_args());
    }
}
if (!function_exists('carbon_set_network_option')) {
    function carbon_set_network_option()
    {
        return \_PhpScoper9a3678ae6a12\carbon_set_network_option(...func_get_args());
    }
}
if (!function_exists('carbon_set_post_meta')) {
    function carbon_set_post_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_set_post_meta(...func_get_args());
    }
}
if (!function_exists('carbon_set_term_meta')) {
    function carbon_set_term_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_set_term_meta(...func_get_args());
    }
}
if (!function_exists('carbon_set_theme_option')) {
    function carbon_set_theme_option()
    {
        return \_PhpScoper9a3678ae6a12\carbon_set_theme_option(...func_get_args());
    }
}
if (!function_exists('carbon_set_user_meta')) {
    function carbon_set_user_meta()
    {
        return \_PhpScoper9a3678ae6a12\carbon_set_user_meta(...func_get_args());
    }
}
if (!function_exists('content_url')) {
    function content_url()
    {
        return \_PhpScoper9a3678ae6a12\content_url(...func_get_args());
    }
}
if (!function_exists('crb_get_default_sidebar_options')) {
    function crb_get_default_sidebar_options()
    {
        return \_PhpScoper9a3678ae6a12\crb_get_default_sidebar_options(...func_get_args());
    }
}
if (!function_exists('crb_load')) {
    function crb_load()
    {
        return \_PhpScoper9a3678ae6a12\crb_load(...func_get_args());
    }
}
if (!function_exists('dbi_add_plugin_settings_page')) {
    function dbi_add_plugin_settings_page()
    {
        return \_PhpScoper9a3678ae6a12\dbi_add_plugin_settings_page(...func_get_args());
    }
}
if (!function_exists('deactivate_bora_bora')) {
    function deactivate_bora_bora()
    {
        return \_PhpScoper9a3678ae6a12\deactivate_bora_bora(...func_get_args());
    }
}
if (!function_exists('do_action')) {
    function do_action()
    {
        return \_PhpScoper9a3678ae6a12\do_action(...func_get_args());
    }
}
if (!function_exists('esc_attr')) {
    function esc_attr()
    {
        return \_PhpScoper9a3678ae6a12\esc_attr(...func_get_args());
    }
}
if (!function_exists('get_blog_status')) {
    function get_blog_status()
    {
        return \_PhpScoper9a3678ae6a12\get_blog_status(...func_get_args());
    }
}
if (!function_exists('get_current_screen')) {
    function get_current_screen()
    {
        return \_PhpScoper9a3678ae6a12\get_current_screen(...func_get_args());
    }
}
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        return \_PhpScoper9a3678ae6a12\getallheaders(...func_get_args());
    }
}
if (!function_exists('includeIfExists')) {
    function includeIfExists()
    {
        return \_PhpScoper9a3678ae6a12\includeIfExists(...func_get_args());
    }
}
if (!function_exists('is_admin')) {
    function is_admin()
    {
        return \_PhpScoper9a3678ae6a12\is_admin(...func_get_args());
    }
}
if (!function_exists('load_plugin_textdomain')) {
    function load_plugin_textdomain()
    {
        return \_PhpScoper9a3678ae6a12\load_plugin_textdomain(...func_get_args());
    }
}
if (!function_exists('plugin_basename')) {
    function plugin_basename()
    {
        return \_PhpScoper9a3678ae6a12\plugin_basename(...func_get_args());
    }
}
if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path()
    {
        return \_PhpScoper9a3678ae6a12\plugin_dir_path(...func_get_args());
    }
}
if (!function_exists('plugin_dir_url')) {
    function plugin_dir_url()
    {
        return \_PhpScoper9a3678ae6a12\plugin_dir_url(...func_get_args());
    }
}
if (!function_exists('plugins_url')) {
    function plugins_url()
    {
        return \_PhpScoper9a3678ae6a12\plugins_url(...func_get_args());
    }
}
if (!function_exists('register_activation_hook')) {
    function register_activation_hook()
    {
        return \_PhpScoper9a3678ae6a12\register_activation_hook(...func_get_args());
    }
}
if (!function_exists('register_block_type')) {
    function register_block_type()
    {
        return \_PhpScoper9a3678ae6a12\register_block_type(...func_get_args());
    }
}
if (!function_exists('register_deactivation_hook')) {
    function register_deactivation_hook()
    {
        return \_PhpScoper9a3678ae6a12\register_deactivation_hook(...func_get_args());
    }
}
if (!function_exists('run_bora_bora')) {
    function run_bora_bora()
    {
        return \_PhpScoper9a3678ae6a12\run_bora_bora(...func_get_args());
    }
}
if (!function_exists('sanitize_title')) {
    function sanitize_title()
    {
        return \_PhpScoper9a3678ae6a12\sanitize_title(...func_get_args());
    }
}
if (!function_exists('site_url')) {
    function site_url()
    {
        return \_PhpScoper9a3678ae6a12\site_url(...func_get_args());
    }
}
if (!function_exists('trailingslashit')) {
    function trailingslashit()
    {
        return \_PhpScoper9a3678ae6a12\trailingslashit(...func_get_args());
    }
}
if (!function_exists('trigger_deprecation')) {
    function trigger_deprecation()
    {
        return \_PhpScoper9a3678ae6a12\trigger_deprecation(...func_get_args());
    }
}
if (!function_exists('untrailingslashit')) {
    function untrailingslashit()
    {
        return \_PhpScoper9a3678ae6a12\untrailingslashit(...func_get_args());
    }
}
if (!function_exists('wp_enqueue_script')) {
    function wp_enqueue_script()
    {
        return \_PhpScoper9a3678ae6a12\wp_enqueue_script(...func_get_args());
    }
}
if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style()
    {
        return \_PhpScoper9a3678ae6a12\wp_enqueue_style(...func_get_args());
    }
}

return $loader;
