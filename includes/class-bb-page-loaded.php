<?php

use BB\Service\BB_Session_Manager;

add_action('wp', 'execute_on_load_page_hook_event');

function execute_on_load_page_hook_event()
{
    $sessionManager = new BB_Session_Manager();
    $accessValidFor = carbon_get_post_meta(get_the_ID(), 'bora_available_for_groups');
    ray([$accessValidFor, $sessionManager->getDiscordRoleId()]);

    // page is public
    if ($accessValidFor == [] || in_array('guest', $accessValidFor)) {
        ray('public page');

        return;
    }
    // user is not authenticated
    if (!$sessionManager->getDiscordRoleId()) {
        ray('user is not authenticated');
        // redirect to the page that is set in the settings
        exit(wp_redirect(get_permalink(carbon_get_theme_option('crb_redirect_no_auth')[0]['id'] ?? 0)));
    }


    // user is admin and can access all pages 🤗
    if (current_user_can('administrator')) {
        ray('admin can access all pages');

        return;
    }
    // user is authenticated
    if (in_array('all', $accessValidFor) && $sessionManager->getDiscordRoleId()) {
        ray('all members can access this page');

        return;
    }

    // page is restricted to certain groups
    if (in_array($sessionManager->getDiscordRoleId(), $accessValidFor)) {
        ray(['user can access this page', $sessionManager->getDiscordRoleId(), $accessValidFor]);

        return;
    }
    ray('redirect');
    // redirect to the page that is set in the settings
    exit(wp_redirect(get_permalink(carbon_get_theme_option('crb_redirect_without_group')[0]['id'] ?? 0)));
}
