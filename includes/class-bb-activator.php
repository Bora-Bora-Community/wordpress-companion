<?php

use BB\Service\BB_User_Manager;

/**
 * Fired during plugin activation
 *
 * @link       https://bora-bora.io
 * @since      1.0.0
 *
 * @package    Bora_bora
 * @subpackage Bora_bora/includes
 */
class BB_Activator
{
    /**
     * @since    1.0.0
     */
    public static function activate()
    {
        $userMgmtUserName = USER_MGMT_USER_NAME;
        $userMgmtUserEmail = USER_MGMT_USER_EMAIL;
        $userMgmtUserDescription = USER_MGMT_USER_DESC;

        $userMgmtRoleName = USER_MGMT_ROLE_NAME;
        $userMgmtRoleDescription = USER_MGMT_ROLE_DESC;

        $userManager = new BB_User_Manager();
        if ($userManager->WPRoleDoesNotExist($userMgmtRoleName)) {
            // only create the role if it is not existing
            $userManager->createWPRole($userMgmtRoleName, $userMgmtRoleDescription);
        }

        if ($userManager->WPUserDoesNotExist($userMgmtUserName)) {
            // only create the user if it is not existing
            $newUser = $userManager->createWPUser($userMgmtUserName, $userMgmtUserEmail, $userMgmtUserDescription);

            // set the role for the user
            $newUser->set_role($userMgmtRoleName);
        }

        // Redirect to the settings page
        exit(wp_redirect(admin_url('admin.php?page=crb_carbon_fields_container_bora_bora_settings.php')));
    }
}
