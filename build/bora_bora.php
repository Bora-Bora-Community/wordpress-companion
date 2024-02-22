<?php

namespace _PhpScoper9a3678ae6a12;

/**
 * The plugin bootstrap file
 *
 * @link              https://bora-bora.io
 * @since             1.0.0
 * @package           Bora_bora
 *
 * @wordpress-plugin
 * Plugin Name:       Bora Bora
 * Plugin URI:        https://bora-bora.io
 * Description:       Manage the access to your membership pages with Bora-Bora
 * Version:           1.0.0
 * Author:            Bora Bora
 * Author URI:        https://bora-bora.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bora_bora
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!\defined('WPINC')) {
    die;
}
/**
 * Currently plugin version.
 */
\define('BORA_BORA_VERSION', '1.0.0');
/**
 * The name of the Plugin
 */
\define('BORA_BORA_NAME', 'Bora Bora');
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bora_bora-activator.php
 * @internal
 */
function activate_bora_bora()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-bora_bora-activator.php';
    Bora_bora_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bora_bora-deactivator.php
 * @internal
 */
function deactivate_bora_bora()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-bora_bora-deactivator.php';
    Bora_bora_Deactivator::deactivate();
}
register_activation_hook(__FILE__, 'activate_bora_bora');
register_deactivation_hook(__FILE__, 'deactivate_bora_bora');
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-bora_bora.php';
/**
 * Load the plugin setting screens
 */
require plugin_dir_path(__FILE__) . 'includes/class-bora_bora-settings.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 * @internal
 */
function run_bora_bora()
{
    $plugin = new Bora_bora();
    $plugin->run();
}
run_bora_bora();
