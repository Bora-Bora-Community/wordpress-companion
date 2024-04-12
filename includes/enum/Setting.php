<?php

namespace BB\enum;

class Setting
{
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
}
