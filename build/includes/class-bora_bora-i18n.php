<?php

namespace _PhpScoper9a3678ae6a12;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://bora-bora.io
 * @since      1.0.0
 *
 * @package    Bora_bora
 * @subpackage Bora_bora/includes
 * @internal
 */
class Bora_bora_i18n
{
    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain('bora_bora', \false, \dirname(\dirname(plugin_basename(__FILE__))) . '/languages/');
    }
}
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://bora-bora.io
 * @since      1.0.0
 *
 * @package    Bora_bora
 * @subpackage Bora_bora/includes
 * @internal
 */
\class_alias('_PhpScoper9a3678ae6a12\\Bora_bora_i18n', 'Bora_bora_i18n', \false);
