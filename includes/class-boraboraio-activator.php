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
        if ($userManager->WPRoleDoesNotExist(BORABORAIO_USER_MGMT_ROLE_NAME)) {
            // only create the role if it is not existing
            $userManager->createWPRole(BORABORAIO_USER_MGMT_ROLE_NAME, BORABORAIO_USER_MGMT_ROLE_DESC);
        }

        if ($userManager->WPUserDoesNotExist(BORABORAIO_USER_MGMT_USER_NAME)) {
            // only create the user if it is not existing
            $newUser = $userManager->createWPUser(BORABORAIO_USER_MGMT_USER_NAME, BORABORAIO_USER_MGMT_USER_EMAIL, BORABORAIO_USER_MGMT_USER_DESC);

            // set the role for the user
            $newUser->set_role(BORABORAIO_USER_MGMT_ROLE_NAME);
        }

        // Redirect to the settings page
        wp_safe_redirect(admin_url('admin.php?page=crb_carbon_fields_container_bora_bora_settings.php'));
        exit;
//        exit(wp_redirect(esc_url(admin_url('admin.php?page=crb_carbon_fields_container_bora_bora_settings.php'))));
    }
}
