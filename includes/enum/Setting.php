<?php

namespace BB\enum;

class Setting
{
    /*
     * plugin settings
     */
    const PLUGIN_ENABLED = 'bora_plugin_enabled';
    const API_KEY = 'bora_api_key';
    const API_USER = 'bora_api_user';
    const API_USER_PW = 'bora_api_user_password';
    const SESSION_LENGTH = 'bora_session_length';
    // redirects
    const REDIRECT_AFTER_LOGIN = 'bora_redirect_after_login';
    const REDIRECT_NO_AUTH = 'bora_redirect_no_auth';
    const REDIRECT_WRONG_GROUP = 'bora_redirect_without_group';
    const REDIRECT_PAYMENT_SUCCESS = 'bora_redirect_payment_success';
    const REDIRECT_PAYMENT_FAILED = 'bora_redirect_payment_failed';
    /*
     * user settings
     */
    const BORA_USER_ID = 'bora_bora_id';
    const BORA_USER_NAME = 'bora_bora_name';
    const BORA_USER_EMAIL = 'bora_bora_email';
    const BORA_USER_LOCALE = 'bora_bora_locale';
    const BORA_USER_DISCORD_ID = 'bora_bora_discord_id';
    const BORA_USER_DISCORD_USERNAME = 'bora_bora_discord_username';
    const BORA_USER_REFERRAL_LINK = 'bora_bora_referral_link';
    const BORA_USER_REFERRAL_COUNT = 'bora_bora_referral_count';
    const BORA_USER_REFERRAL_CURRENT_BALANCE = 'bora_bora_referral_current_balance';
    const BORA_USER_REFERRAL_TOTAL_EARNING = 'bora_bora_referral_total_earning';
    /*
     * page settings
     */
    const BORA_AVAILABLE_FOR_GROUPS = 'bora_available_for_groups';
}
