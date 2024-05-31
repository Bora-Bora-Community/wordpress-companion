<?php

use BB\Service\Boraboraio_User_Manager;

/**
 * Fired during plugin activation
 *
 * @link       https://bora-bora.io
 * @since      1.0.0
 *
 * @package    Bora_bora
 * @subpackage Bora_bora/includes
 */
class BoraBora_Activator
{
    /**
     * @since    1.0.0
     */
    public static function activate()
    {
        $userManager = new Boraboraio_User_Manager();
        if ($userManager->WPRoleDoesNotExist(BORA_BORA_USER_MGMT_ROLE_NAME)) {
            // only create the role if it is not existing
            $userManager->createWPRole(BORA_BORA_USER_MGMT_ROLE_NAME, BORA_BORA_USER_MGMT_ROLE_DESC);
        }

        if ($userManager->WPUserDoesNotExist(BORA_BORA_USER_MGMT_USER_NAME)) {
            // only create the user if it is not existing
            $newUser = $userManager->createWPUser(BORA_BORA_USER_MGMT_USER_NAME, BORA_BORA_USER_MGMT_USER_EMAIL, BORA_BORA_USER_MGMT_USER_DESC);

            // set the role for the user
            $newUser->set_role(BORA_BORA_USER_MGMT_ROLE_NAME);
        }

        // Redirect to the settings page
        exit(wp_redirect(esc_url(admin_url('admin.php?page=crb_carbon_fields_container_bora_bora_settings.php'))));
    }
}
