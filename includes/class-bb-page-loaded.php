<?php

use BB\Service\BB_Session_Manager;

add_action('wp', 'execute_on_load_page_hook_event');

function execute_on_load_page_hook_event(): void
{
    // if the redirect is not enabled, we don't need to do anything
    // this setting could be used to setup the plugin without restrictions
    if (!carbon_get_theme_option('crb_plugin_enabled')) {
//        ray('redirect is not enabled, do nothing');

        return;
    }
    // user is admin and can access all pages 🤗
    if (current_user_can('administrator')) {
//        ray('admin can access all pages');

        return;
    }

    $sessionManager = new BB_Session_Manager();
    $accessValidFor = carbon_get_post_meta(get_the_ID(), 'bora_available_for_groups');
//    ray([$accessValidFor, $sessionManager->getUserSession(get_current_user_id())]);

    // page is public
    if ($accessValidFor == [] || in_array('guest', $accessValidFor)) {
//        ray('public page');

        return;
    }
    // user is not authenticated
    if (!$sessionManager->getUserSession(get_current_user_id())) {
//        ray('user is not authenticated');
        // redirect to the page that is set in the settings
        exit(wp_redirect(esc_url(get_permalink(carbon_get_theme_option('crb_redirect_no_auth')[0]['id'] ?? 0))));
    }

    // user is authenticated
    if (in_array('all', $accessValidFor) && $sessionManager->getUserSession(get_current_user_id())) {
//        ray('all members can access this page');

        return;
    }

    // page is restricted to certain groups
    if (in_array($sessionManager->getUserSession(get_current_user_id()), $accessValidFor)) {
//        ray(['user can access this page', $sessionManager->getUserSession(get_current_user_id()), $accessValidFor]);

        return;
    }
//    ray('redirect');
    // redirect to the page that is set in the settings
    exit(wp_redirect(esc_url(get_permalink(carbon_get_theme_option('crb_redirect_without_group')[0]['id'] ?? 0))));
}
