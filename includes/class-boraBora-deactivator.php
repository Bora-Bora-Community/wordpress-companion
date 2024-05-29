<?php

use BB\Service\BoraBora_User_Manager;

/**
 * Fired during plugin deactivation
 *
 * @link       https://bora-bora.io
 * @since      1.0.0
 *
 * @package    Bora_bora
 * @subpackage Bora_bora/includes
 */
class BoraBora_Deactivator
{
    /**
     * @since    1.0.0
     */
    public static function deactivate(): void
    {
        $userManager = new BoraBora_User_Manager();

        if ($userManager->WPRoleExists(BORA_BORA_USER_MGMT_ROLE_NAME)) {
            // only delete the role if it is existing
            $userManager->deleteWPRole(BORA_BORA_USER_MGMT_ROLE_NAME);
        }

        if ($userManager->WPUserExists(BORA_BORA_USER_MGMT_USER_NAME)) {
            // only delete the user if it is existing
            $userManager->deleteWPUser(BORA_BORA_USER_MGMT_USER_NAME);
        }
    }
}