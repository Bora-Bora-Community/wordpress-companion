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
        // todo: pull data from globals
        $userMgmtUserName = 'Bora_Bora2';
        $userMgmtRoleName = 'bora_bora';

        $userManager = new BB_User_Manager();

        if ($userManager->WPRoleExists($userMgmtRoleName)) {
            // only delete the role if it is existing
            $userManager->deleteWPRole($userMgmtRoleName);
        }

        if ($userManager->WPUserExists($userMgmtUserName)) {
            // only delete the user if it is existing
            $userManager->deleteWPUser($userMgmtUserName);
        }
    }
}
