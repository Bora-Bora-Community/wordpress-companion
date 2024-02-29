<?php

use BB\Service\BB_User_Manager;

/**
 * Fired during plugin deactivation
 *
 * @link       https://bora-bora.io
 * @since      1.0.0
 *
 * @package    Bora_bora
 * @subpackage Bora_bora/includes
 */
class BB_Deactivator
{
    /**
     * @since    1.0.0
     */
    public static function deactivate()
    {
        $userManager = new BB_User_Manager();

        if ($userManager->WPRoleExists(USER_MGMT_ROLE_NAME)) {
            // only delete the role if it is existing
            $userManager->deleteWPRole(USER_MGMT_ROLE_NAME);
        }

        if ($userManager->WPUserExists(USER_MGMT_USER_NAME)) {
            // only delete the user if it is existing
            $userManager->deleteWPUser(USER_MGMT_USER_NAME);
        }
    }
}
