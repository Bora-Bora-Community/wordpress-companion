<?php

/**
 * Fired during plugin activation
 *
 * @link       https://bora-bora.io
 * @since      1.0.0
 *
 * @package    Bora_bora
 * @subpackage Bora_bora/includes
 */
class Bora_bora_Activator
{
    /**
     * @since    1.0.0
     */
    public static function activate()
    {
        exit(wp_redirect(admin_url('admin.php?page=crb_carbon_fields_container_bora_bora_settings.php')));
    }
}
