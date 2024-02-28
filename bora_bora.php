<?php

/**
 * @link              https://bora-bora.io
 * @since             1.0.0
 * @package           Bora_bora
 *
 * @wordpress-plugin
 * Plugin Name:       Bora Bora
 * Plugin URI:        https://bora-bora.io
 * Description:       Manage the access to your membership pages with Bora Bora
 * Version:           1.0.0
 * Author:            Bora Bora
 * Author URI:        https://bora-bora.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bora_bora
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
const BORA_BORA_VERSION = '1.0.0';

/**
 * The name of the Plugin
 */
const BORA_BORA_NAME = 'Bora Bora';

/**
 * The base URL of Bora Bora API
 */
const BORA_BORA_API_BASE_URL = 'https://bora-bora.test/api/companion/';

/**
 * Plugin Path
 */
define('BORA_BORA_PATH', plugin_dir_path(__FILE__));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bora_bora-activator.php
 */
function activate_bora_bora()
{
    require_once plugin_dir_path(__FILE__).'includes/class-bora_bora-activator.php';
    Bora_bora_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bora_bora-deactivator.php
 */
function deactivate_bora_bora()
{
    require_once plugin_dir_path(__FILE__).'includes/class-bora_bora-deactivator.php';
    Bora_bora_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_bora_bora');
register_deactivation_hook(__FILE__, 'deactivate_bora_bora');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-bora_bora.php';

/**
 * Load the plugin setting screens
 */
require plugin_dir_path(__FILE__).'includes/class-bora_bora-settings.php';

// Define the main autoloader
spl_autoload_register('bb_autoloader');
function bb_autoloader($class_name): void
{

    // These should be changed for your particular plugin requirements
    $parent_namespace = 'BB';
    $classes_subfolder = 'includes';

    if (false !== strpos($class_name, $parent_namespace)) {
        $classes_dir = realpath(plugin_dir_path(__FILE__)).DIRECTORY_SEPARATOR.$classes_subfolder.DIRECTORY_SEPARATOR;

        // Project namespace
        $project_namespace = $parent_namespace.'\\';
        $length = strlen($project_namespace);

        // Remove top-level namespace (that is the current dir)
        $class_file = substr($class_name, $length);
        // Swap underscores for dashes and lowercase
        $class_file = str_replace('_', '-', strtolower($class_file));

        // Prepend `class-` to the filename (last class part)
        $class_parts = explode('\\', $class_file);
        $last_index = count($class_parts) - 1;
        $class_parts[$last_index] = 'class-'.$class_parts[$last_index];

        // Join everything back together and add the file extension
        $class_file = implode(DIRECTORY_SEPARATOR, $class_parts).'.php';
        $location = $classes_dir.$class_file;

        if (!is_file($location)) {
            return;
        }

        require_once $location;
    }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bora_bora()
{
    $plugin = new Bora_bora();
    $plugin->run();
}

run_bora_bora();
