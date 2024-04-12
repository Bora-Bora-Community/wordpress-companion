<?php

use BB\enum\Setting;

/**
 * @param $atts
 *
 * @return string
 */
function referral_details($atts): string
{
    // Überprüfe, ob der Nutzer eingeloggt ist.
    if (!is_user_logged_in()) {
        return 'Bitte <a href="/wp-login.php">logge dich ein</a>, um diesen Inhalt zu sehen.';
    }

    if (is_admin()) {
        return 'Admins können diesen Shortcode nicht verwenden.';
    }

    $user_id = get_current_user_id();
    // Ermittle, welcher Parameter gesetzt ist.
    $atts = array_change_key_case((array) $atts, CASE_LOWER);
    // Hole die Benutzer-Metadaten basierend auf dem gesetzten Parameter.
    if (in_array('url', $atts)) {
        $output = carbon_get_user_meta($user_id, Setting::BORA_USER_REFERRAL_LINK) ?? 'n/a';
    } elseif (in_array('count', $atts)) {
        $output = carbon_get_user_meta($user_id, Setting::BORA_USER_REFERRAL_COUNT) ?? 0;
    } elseif (in_array('payout_amount', $atts)) {
        $output = numfmt_format_currency(numfmt_create('de_DE', NumberFormatter::CURRENCY), (float)carbon_get_user_meta
        ($user_id, Setting::BORA_USER_REFERRAL_TOTAL_PAYOUT) ?? 0, "EUR");
    } else {
        return 'Ungültiger Parameter (url, count oder payout_amount)';
    }

    return esc_html($output);
}

add_shortcode('referral_details', 'referral_details');
