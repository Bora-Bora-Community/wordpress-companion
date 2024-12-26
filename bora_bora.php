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
 * Version:           1.2.1
 * Author:            Bora Bora
 * Author URI:        https://bora-bora.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       boraboraio
 * Domain Path:       /languages
 * Requires at least: 6.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
const BORABORAIO_VERSION = '1.2.1';

/**
 * The name of the Plugin
 * @since 1.0.0
 */
const BORABORAIO_NAME = 'Bora Bora';

/**
 * The base URL of Bora Bora API
 * @since 1.0.0
 */
const BORABORAIO_API_BASE_URL = 'https://bora-bora.io/api/companion/';
const BORABORAIO_WP_ENV = 'dev';

/**
 * The timeframe for a valid subscription session
 * after this time we'll check again the BORA BORA API for the subscription status
 */
const BORABORAIO_SESSION_VALID_TIMEFRAME_IN_HOURS = 1; // 1 hour

/**
 * Plugin Path
 */
define('BORABORAIO_PATH', plugin_dir_path(__FILE__));

/**
 * User Role constant
 * @since 1.0.0
 */
const BORABORAIO_USER_MGMT_USER_NAME = 'Bora_Bora';
const BORABORAIO_USER_MGMT_USER_EMAIL = 'support@bora-bora.io';
const BORABORAIO_USER_MGMT_USER_DESC = 'Bora Bora User Management';
const BORABORAIO_USER_MGMT_ROLE_NAME = 'bora_bora';
const BORABORAIO_USER_MGMT_ROLE_DESC = 'Bora Bora User Management';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bb-activator.php
 */
function boraboraio_activate(string $pluginName): void
{
    $boraBoraPluginName = 'bora_bora/bora_bora.php';
    if ($pluginName !== $boraBoraPluginName) {
        return;
    }
    require_once plugin_dir_path(__FILE__).'includes/class-boraboraio-activator.php';
    Boraboraio_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function boraboraio_deactivate(): void
{
    require_once plugin_dir_path(__FILE__).'includes/class-boraboraio-deactivator.php';
    Boraboraio_Deactivator::deactivate();
}

add_action('activated_plugin', 'boraboraio_activate');
register_deactivation_hook(__FILE__, 'boraboraio_deactivate');

/**
 * Load the plugin setting screens
 */
require plugin_dir_path(__FILE__).'includes/class-boraboraio-settings.php';

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__).'includes/class-boraboraio.php';

/**
 * code executed after page load
 */
require plugin_dir_path(__FILE__).'includes/class-boraboraio-page-loaded.php';

/**
 * code executed after user login
 */
require plugin_dir_path(__FILE__).'includes/class-boraboraio-user-login.php';

/**
 * code executed after user password change
 */
require plugin_dir_path(__FILE__).'includes/class-boraboraio-user-password-change.php';

/**
 * hide admin bar for all users except admins
 */
require plugin_dir_path(__FILE__).'includes/class-boraboraio-hide-adminbar.php';

/**
 * handle session management
 * and store the details of the current subscription of a user
 */
require plugin_dir_path(__FILE__).'includes/service/class-boraboraio-session-manager.php';
require plugin_dir_path(__FILE__).'includes/service/class-boraboraio-wordpress-session.php';
require plugin_dir_path(__FILE__).'includes/service/class-boraboraio-wordpress-restrict_backend.php';

/**
 * import setting name enum
 */
require plugin_dir_path(__FILE__).'includes/enum/Boraboraio_Setting.php';

/**
 * shortcode
 */
// shortcode for referrals
require plugin_dir_path(__FILE__).'includes/class-boraboraio-referral-shortcode.php';
// shortcode for pw change
require plugin_dir_path(__FILE__).'includes/class-boraboraio-pw-change-shortcode.php';
// shortcode for billing portal
require plugin_dir_path(__FILE__).'includes/class-boraboraio-billing_portal-shortcode.php';

/**
 * internal API
 */
require plugin_dir_path(__FILE__).'includes/api/class-boraboraio-internal-api.php';

// Define the main autoloader
spl_autoload_register('boraboraio_autoloader');
function boraboraio_autoloader($class_name): void
{
    // These should be changed for your particular plugin requirements
    $parent_namespace = 'Boraboraio';
    $classes_subfolder = 'includes';

    if (str_contains($class_name, $parent_namespace)) {
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
function boraboraio_run()
{
    $plugin = new Boraboraio();
    $plugin->run();
}

boraboraio_run();
