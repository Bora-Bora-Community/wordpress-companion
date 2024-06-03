<?php

use Boraboraio\API\Boraboraio_Api_Client;
use Boraboraio\enum\Boraboraio_Setting;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * @param $atts
 *
 * @return string
 */
function boraboraio_referral_details($atts): string
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
        $refLink = carbon_get_user_meta($user_id, Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_LINK);
        $output = esc_url($refLink) ?? 'n/a';
    } elseif (in_array('count', $atts)) {
        $output = ((int) carbon_get_user_meta($user_id, Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_COUNT)) ?? 0;
    } elseif (in_array('total_earning', $atts)) {
        $output = numfmt_format_currency(formatter: numfmt_create(locale: 'de_DE',
                                                                  style : NumberFormatter::CURRENCY),
                                         amount   : (float) carbon_get_user_meta($user_id,
            Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_TOTAL_EARNING) ?? 0,
                                         currency : "EUR");
    } elseif (in_array('current_balance', $atts)) {
        // fetch the current balance in realtime
        $balance = (new Boraboraio_Api_Client())
            ->fetchCustomerStripeBalance(carbon_get_user_meta($user_id, Boraboraio_Setting::BORA_BORA_IO_USER_ID));
        carbon_set_user_meta($user_id, Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_CURRENT_BALANCE,
            $balance);
        $output = numfmt_format_currency(formatter: numfmt_create(locale: 'de_DE', style: NumberFormatter::CURRENCY),
                                         amount   : $balance,
                                         currency : "EUR");
    } else {
        return 'Ungültiger Parameter (url, count, total_earning oder current_balance)';
    }

    return $output;
}

add_shortcode('boraboraio_referral_details', 'boraboraio_referral_details');
