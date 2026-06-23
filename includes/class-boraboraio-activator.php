<?php

use Boraboraio\Service\Boraboraio_User_Manager;

/**
 * Fired during plugin activation
 *
 * @link       https://bora-bora.io
 * @since      1.0.0
 *
 * @package    Bora_bora
 * @subpackage Bora_bora/includes
 */
class Boraboraio_Activator
{
    /**
     * @since    1.0.0
     */
    public static function activate()
    {
        $userManager = new Boraboraio_User_Manager();

        // Ensure the management role and user exist locally. No network calls
        // happen here — the credential exchange with Bora Bora runs on settings
        // save, once a valid API key has been entered.
        $userManager->ensureCompanionUser();

        // Redirect to the settings page
        wp_safe_redirect(admin_url('admin.php?page=crb_carbon_fields_container_bora_bora_settings.php'));
        exit;
//        exit(wp_redirect(esc_url(admin_url('admin.php?page=crb_carbon_fields_container_bora_bora_settings.php'))));
    }
}
