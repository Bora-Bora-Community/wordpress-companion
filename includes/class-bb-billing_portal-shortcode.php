<?php

use BB\enum\Setting;

if (!defined('ABSPATH')) {
    exit;
}
/**
 * @return string
 */
function boraboraio_getBillingPortalUrl(): string
{
    // Überprüfe, ob der Nutzer eingeloggt ist.
    if (!is_user_logged_in()) {
        return 'Bitte <a href="/wp-login.php">logge dich ein</a>, um diesen Inhalt zu sehen.';
    }

    if (is_admin()) {
        return 'Admins können diesen Shortcode nicht verwenden.';
    }

    $user_id = get_current_user_id();

    return esc_url(carbon_get_user_meta($user_id, Setting::BORA_USER_BILLING_PORTAL_URL)) ?? 'n/a';
}

add_shortcode('billing_portal', 'boraboraio_getBillingPortalUrl');
