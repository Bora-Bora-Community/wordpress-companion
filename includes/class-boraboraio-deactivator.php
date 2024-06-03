<?php

use Boraboraio\Service\Boraboraio_User_Manager;

/**
 * Fired during plugin deactivation
 *
 * @link       https://bora-bora.io
 * @since      1.0.0
 *
 * @package    Bora_bora
 * @subpackage Bora_bora/includes
 */
class Boraboraio_Deactivator
{
    /**
     * @since    1.0.0
     */
    public static function deactivate(): void
    {
        $userManager = new Boraboraio_User_Manager();

        if ($userManager->WPRoleExists(BORABORAIO_USER_MGMT_ROLE_NAME)) {
            // only delete the role if it is existing
            $userManager->deleteWPRole(BORABORAIO_USER_MGMT_ROLE_NAME);
        }

        if ($userManager->WPUserExists(BORABORAIO_USER_MGMT_USER_NAME)) {
            // only delete the user if it is existing
            $userManager->deleteWPUser(BORABORAIO_USER_MGMT_USER_NAME);
        }
    }
}
