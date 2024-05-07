<?php

/**
 * @link              https://bora-bora.io
 * @since             1.0.0
 * @package           Bora_bora
 *
 * @wordpress-plugin
 * Plugin Name:       Bora Bora
 * Plugin URI:        https://bora-bora.io
 * Description:       Bora Bora offers a complete solution for managing your community, from the subscription to the management of the users and their access to the content
 * Version:           1.0.8
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
const BORA_BORA_VERSION = '1.0.8';

/**
 * The name of the Plugin
 * @since 1.0.0
 */
const BORA_BORA_NAME = 'Bora Bora';

/**
 * The base URL of Bora Bora API
 * @since 1.0.0
 */
const BORA_BORA_API_BASE_URL = 'https://bora-bora.io/api/companion/';
const WP_ENV = 'dev';

/**
 * The timeframe for a valid subscription session
 * after this time we'll check again the BORA BORA API for the subscription status
 */
const BORA_BORA_SESSION_VALID_TIMEFRAME_IN_HOURS = 1; // 1 hour

/**
 * Plugin Path
 */
define('BORA_BORA_PATH', plugin_dir_path(__FILE__));

/**
 * User Role constant
 * @since 1.0.0
 */
const USER_MGMT_USER_NAME = 'Bora_Bora';
const USER_MGMT_USER_EMAIL = 'support@bora-bora.io';
const USER_MGMT_USER_DESC = 'Bora Bora User Management';
const USER_MGMT_ROLE_NAME = 'bora_bora';
const USER_MGMT_ROLE_DESC = 'Bora Bora User Management';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bb-activator.php
 */
function activate_bora_bora()
{
    require_once plugin_dir_path(__FILE__).'includes/class-bb-activator.php';
    BB_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bb-deactivator.php
 */
function deactivate_bora_bora()
{
    require_once plugin_dir_path(__FILE__).'includes/class-bb-deactivator.php';
    BB_Deactivator::deactivate();
}

add_action('activated_plugin', 'activate_bora_bora');
register_deactivation_hook(__FILE__, 'deactivate_bora_bora');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-bora_bora.php';

/**
 * code executed after page load
 */
require plugin_dir_path(__FILE__).'includes/class-bb-page-loaded.php';

/**
 * code executed after user login
 */
require plugin_dir_path(__FILE__).'includes/class-bb-user-login.php';

/**
 * code executed after user password change
 */
require plugin_dir_path(__FILE__).'includes/class-bb-user-password-change.php';

/**
 * hide admin bar for all users except admins
 */
require plugin_dir_path(__FILE__).'includes/class-bb-hide-adminbar.php';

/**
 * Load the plugin setting screens
 */
require plugin_dir_path(__FILE__).'includes/class-bb-settings.php';

/**
 * handle session management
 * and store the details of the current subscription of a user
 */
require plugin_dir_path(__FILE__).'includes/service/class-bb-session-manager.php';
require plugin_dir_path(__FILE__).'includes/service/class-bb-wordpress-session.php';
require plugin_dir_path(__FILE__).'includes/service/class-bb-wordpress-restrict_backend.php';

/**
 * import setting name enum
 */
require plugin_dir_path(__FILE__).'includes/enum/Setting.php';

/**
 * shortcode
 */
// shortcode for referrals
require plugin_dir_path(__FILE__).'includes/class-bb-referral-shortcode.php';
// shortcode for pw change
require plugin_dir_path(__FILE__).'includes/class-bb-pw-change-shortcode.php';

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
