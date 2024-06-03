<?php

namespace Boraboraio\enum;

class Boraboraio_Setting
{
    /*
     * plugin settings
     */
    const BORA_BORA_IO_PLUGIN_ENABLED = 'bora_plugin_enabled';
    const BORA_BORA_IO_API_KEY = 'bora_api_key';
    const BORA_BORA_IO_SESSION_LENGTH_ACTIVE = 'bora_session_length_active';
    const BORA_BORA_IO_SESSION_LENGTH = 'bora_session_length';
    // redirects
    const BORA_BORA_IO_REDIRECT_AFTER_LOGIN = 'bora_redirect_after_login';
    const BORA_BORA_IO_REDIRECT_NO_AUTH = 'bora_redirect_no_auth';
    const BORA_BORA_IO_REDIRECT_INACTIVE_SUBSCRIPTION = 'bora_redirect_inactive_subscription';
    const BORA_BORA_IO_REDIRECT_WRONG_GROUP = 'bora_redirect_without_group';
    const BORA_BORA_IO_REDIRECT_PAYMENT_SUCCESS = 'bora_redirect_payment_success';
    const BORA_BORA_IO_REDIRECT_PAYMENT_FAILED = 'bora_redirect_payment_failed';
    /*
     * user settings
     */
    const BORA_BORA_IO_USER_ID = 'bora_bora_id';
    const BORA_BORA_IO_USER_NAME = 'bora_bora_name';
    const BORA_BORA_IO_USER_EMAIL = 'bora_bora_email';
    const BORA_BORA_IO_USER_LOCALE = 'bora_bora_locale';
    const BORA_BORA_IO_USER_DISCORD_ID = 'bora_bora_discord_id';
    const BORA_BORA_IO_BOOKED_PRICE_NAME = 'bora_bora_booked_price_name';
    const BORA_BORA_IO_USER_SUBSCRIPTION_STATUS = 'bora_bora_subscription_status';
    const BORA_BORA_IO_USER_DISCORD_USERNAME = 'bora_bora_discord_username';
    const BORA_BORA_IO_USER_REFERRAL_LINK = 'bora_bora_referral_link';
    const BORA_BORA_IO_USER_REFERRAL_COUNT = 'bora_bora_referral_count';
    const BORA_BORA_IO_USER_REFERRAL_CURRENT_BALANCE = 'bora_bora_referral_current_balance';
    const BORA_BORA_IO_USER_REFERRAL_TOTAL_EARNING = 'bora_bora_referral_total_earning';
    const BORA_BORA_IO_USER_BILLING_PORTAL_URL = 'bora_bora_billing_portal_url';
    /*
     * page settings
     */
    const BORA_BORA_IO_AVAILABLE_FOR_GROUPS = 'bora_available_for_groups';
}
