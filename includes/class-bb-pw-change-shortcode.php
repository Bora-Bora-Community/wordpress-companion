<?php

use BB\API\BB_Api_Client;

// Shortcode zum Anzeigen des Passwortänderungsformulars
function bora_change_password(): string
{
    static $feedback_message = '';

    if (!is_user_logged_in()) {
        return '';
    }

    // Überprüfung und Verarbeitung des Formulars
    if ('POST' == $_SERVER['REQUEST_METHOD']) {
        if (isset($_POST['password']) && isset($_POST['submit'])) {
            if (!wp_verify_nonce($_POST['_wpnonce'], 'bb_pw_change_nonce')) {
                $feedback_message = __('Security Check failed', 'bora_bora');
            } elseif ($_POST['password'] !== $_POST['password_confirm']) {
                $feedback_message = __('Passwords does not match', 'bora_bora');
            } elseif (strlen($_POST['password']) < 8) {
                $feedback_message = __('Password needs to have at least 8 chars', 'bora_bora');
            } else {
                $user_id = get_current_user_id();
                $user = get_user_by('ID', $user_id);
                wp_set_password($_POST['password'], $user_id);
                // now update the pw via api to Bora Bora
                $boraBoraId= carbon_get_user_meta($user_id, 'bora_bora_id');
                // update the password if the bora id was loaded
                if ($boraBoraId !== [] && $boraBoraId !== '') {
                    (new BB_Api_Client())->updateCustomerPassword($boraBoraId, $_POST['password']);
                }

                // re login the user
                wp_set_auth_cookie($user_id);
                wp_set_current_user($user_id);
                do_action('wp_login', $user->user_login, $user);
                $feedback_message = __('Changed password', 'bora_bora');
            }
        }
    }

    // Nonce zum Formular hinzufügen
    $nonce = wp_nonce_field('bb_pw_change_nonce', '_wpnonce', true, false);

    // Formular-HTML mit eingefügter Feedback-Nachricht
    $form = '
    <div class="bb-feedback">'.$feedback_message.'</div>
    <form action="'.esc_url($_SERVER['REQUEST_URI']).'" method="post" class="bb-password-change-form">
        '.$nonce.'
        <p class="bb-form-row">
            <label for="password" class="bb-password-label">Neues Passwort<br />
            <input type="password" name="password" id="password" class="input bb-password" size="20"></label>
        </p>
        <p class="bb-form-row">
            <label for="password_confirm" class="bb-password-confirm-label">Passwort wiederholen<br />
            <input type="password" name="password_confirm" id="password_confirm" class="input bb-password-confirm" size="20"></label>
        </p>
        <p class="bb-form-row">
            <input type="submit" name="submit" class="bb-pw-change-submit" value="Passwort ändern">
        </p>
    </form>';

    return $form;
}

add_shortcode('bora_change_password', 'bora_change_password');