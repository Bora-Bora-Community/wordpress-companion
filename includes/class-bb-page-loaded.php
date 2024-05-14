<?php

use BB\enum\Setting;
use BB\Service\BB_Session_Manager;

add_action('wp', 'execute_on_load_page_hook_event');

function execute_on_load_page_hook_event(): void
{
    // if the redirect is not enabled, we don't need to do anything
    // this setting could be used to setup the plugin without restrictions
    if (!carbon_get_theme_option(Setting::PLUGIN_ENABLED)) {
        return;
    }
    // user is admin and can access all pages 🤗
    if (current_user_can('administrator')) {
        return;
    }

    $sessionManager = new BB_Session_Manager();
    $accessValidFor = carbon_get_post_meta(get_the_ID(), Setting::BORA_AVAILABLE_FOR_GROUPS);

    // page is public
    if ($accessValidFor == [] || in_array('guest', $accessValidFor)) {
        return;
    }
    // user is not authenticated
    if (!$sessionManager->getUserSession(get_current_user_id())) {
        // redirect to the page that is set in the settings
        exit(wp_redirect(esc_url(get_permalink(carbon_get_theme_option(Setting::REDIRECT_NO_AUTH)[0]['id'] ?? 0))));
    }

    // user is authenticated
    if (in_array('all', $accessValidFor) && $sessionManager->getUserSession(get_current_user_id())) {
        return;
    }

    // page is restricted to certain groups
    if (in_array($sessionManager->getUserSession(get_current_user_id()), $accessValidFor)) {
        return;
    }
    // redirect to the page that is set in the settings
    exit(wp_redirect(esc_url(get_permalink(carbon_get_theme_option(Setting::REDIRECT_WRONG_GROUP)[0]['id'] ?? 0))));
}
