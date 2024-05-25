<?php

use BB\API\BB_Api_Client;
use BB\enum\Setting;
use BB\Service\BB_Manager;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Add the setting screens
 */
function bb_add_plugin_settings_page(): void
{
    Container::make('theme_options', BORA_BORA_NAME.' '.__('Settings', 'bora_bora'))
        ->set_icon('dashicons-money')
        ->set_page_menu_title(__('Bora Bora', 'bora_bora'))
        ->add_tab(__('Bora Bora Connection', 'bora_bora'), [
            Field::make('text', Setting::API_KEY, BORA_BORA_NAME.' API Key')
                ->set_attribute('maxLength', 36)
                ->set_attribute('min', 36)
                ->set_required(true)
                ->help_text(__('The API key is used to authenticate the plugin with the server. You receive it in your Bora Bora Dashboard.',
                    'bora_bora')),
        ])->add_tab(__('Redirect Settings', 'bora_bora'), [
            Field::make('checkbox', Setting::PLUGIN_ENABLED, __('Activate Bora Bora / Enable Redirects', 'bora_bora'))
                ->set_help_text(__('If enabled, the user will be redirected to the selected page. Otherwise the plugin will do nothing.',
                    'bora_bora'))
                ->set_default_value(false),

            Field::make('association', Setting::REDIRECT_AFTER_LOGIN, __('Redirect after Login', 'bora_bora'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected after login.'),

            Field::make('separator', 'crb_style_restrictions', __('Redirects with restrictions', 'bora_bora'))
                ->help_text(__('The following settings are used to redirect the user if he has no access to a page.',
                    'bora_bora')),

            Field::make('association', Setting::REDIRECT_NO_AUTH, __('Redirect Unauthenticated Users', 'bora_bora'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected if he\'s not logged in Wordpress.'),

            Field::make('association', Setting::REDIRECT_WRONG_GROUP, __('Redirect Group Restriction', 'bora_bora'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected if he has\'t the right group assignment.'),

            Field::make('separator', 'crb_style_payment', __('Redirects from the booking', 'bora_bora'))
                ->help_text(__('The following settings are used to redirect the user after a successful or failed payment.',
                    'bora_bora')),

            Field::make('association', Setting::REDIRECT_PAYMENT_SUCCESS, __('Payment successful', 'bora_bora'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('This page will be shown after a successful payment.'),

            Field::make('association', Setting::REDIRECT_PAYMENT_FAILED, __('Payment failed', 'bora_bora'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('This page will be shown after a failed payment.'),

        ])->add_tab(__('User Session', 'bora_bora'), [
            Field::make('select', Setting::SESSION_LENGTH_ACTIVE, __('Enable permanent login', 'bora_bora'))
                ->add_options([
                    'yes' => 'Yes',
                    'no'  => 'No',
                ]),
            Field::make('select', Setting::SESSION_LENGTH, __('Automatic Logout Time', 'bora_bora'))
                ->set_help_text(__('Select how long a login session lasts.', 'bora_bora'))
                ->set_conditional_logic([
                    'relation' => 'AND', // Optional, defaults to "AND"
                    [
                        'field'   => Setting::SESSION_LENGTH_ACTIVE,
                        'value'   => 'yes',
                        // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ])
                ->add_options([
                    '3600'     => __('1 hour', 'bora_bora'),
                    '7200'     => __('2 hours', 'bora_bora'),
                    '14400'    => __('4 hours', 'bora_bora'),
                    '28800'    => __('8 hours', 'bora_bora'),
                    '43200'    => __('12 hours', 'bora_bora'),
                    '86400'    => __('24 hours', 'bora_bora'),
                    '604800'   => __('1 week', 'bora_bora'),
                    '1209600'  => __('2 weeks', 'bora_bora'),
                    '2592000'  => __('1 month', 'bora_bora'),
                    '5184000'  => __('2 months', 'bora_bora'),
                    '7776000'  => __('3 months', 'bora_bora'),
                    '15552000' => __('6 months', 'bora_bora'),
                    '31536000' => __('1 year', 'bora_bora'),
                ])
                ->set_required(true)
                ->set_default_value('31536000'),
        ]);
}

add_action('carbon_fields_register_fields', 'bb_add_plugin_settings_page');

function called_after_saving_settings(): void
{
    // store the api key in the WordPress metadata
    update_option(Setting::API_KEY, carbon_get_theme_option(Setting::API_KEY));
    (new BB_Manager())->updateCommunityRoles();

    $bbApiClient = new BB_Api_Client();
    // first check if api key is valid
    $apiKeyInvalid = $bbApiClient->apiKeyInvalid();

    if ($apiKeyInvalid) {
        wp_admin_notice(
            __('The settings have been saved, but the API Key is invalid. Please check the API Key and try again.',
                'bora_bora'),
            [
                'type'               => 'error',
                'dismissible'        => true,
                'additional_classes' => ['inline', 'notice-alt'],
                'attributes'         => ['data-slug' => 'plugin-slug'],
            ]
        );

        return;
    }

    // now we can publish the WordPress uri to the Bora Bora backend
    $bbApiClient->publishWordpressUri(
        paymentSuccessPageId: carbon_get_theme_option(Setting::REDIRECT_PAYMENT_SUCCESS)[0]['id'],
        paymentFailedPageId : carbon_get_theme_option(Setting::REDIRECT_PAYMENT_FAILED)[0]['id']
    );
}

add_filter('carbon_fields_theme_options_container_saved', 'called_after_saving_settings');

/**
 * Settings for the post meta
 * to restrict access to certain groups
 */
function bb_add_post_setting_fields(): void
{
    // first load the roles from local DB
    $roles = (new BB_Manager)->getCommunityRoles();
    $roleOptions = [];
    $roleOptions['all'] = 'All Members';
    $roleOptions['guest'] = 'Guest Users / Public';
    foreach ($roles as $role) {
        $roleOptions[$role['discord_id']] = $role['name'];
    }

    Container::make('post_meta', BORA_BORA_NAME)
        ->where('post_type', '=', 'page')
        ->add_fields([
            Field::make('multiselect', Setting::BORA_AVAILABLE_FOR_GROUPS,
                __('This Page is Available for these Groups:', 'bora_bora'))
                ->set_help_text(__('Choose the groups that have access to this page. At least select one user group',
                    'bora_bora'))
                ->add_options($roleOptions),
        ]);
}

add_action('carbon_fields_register_fields', 'bb_add_post_setting_fields');

/**
 * add user metadata to the edit user screen
 * metadata is provided by the Bora Bora API
 */
function bb_add_user_meta_data(): void
{
    Container::make('user_meta', 'Bora Bora')
        ->add_fields([
            Field::make('text', Setting::BORA_USER_ID, 'Bora Bora User ID')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_NAME, 'Bora Bora User Name')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_EMAIL, 'Bora Bora User Email')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_LOCALE, 'Bora Bora User Language')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_DISCORD_ID, 'Discord ID')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_DISCORD_USERNAME, 'Discord Username')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_REFERRAL_LINK, 'Referral URL')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_REFERRAL_COUNT, 'Referral Count (Total)')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_REFERRAL_TOTAL_EARNING, 'Referral Payout Amount')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_REFERRAL_CURRENT_BALANCE, 'Current Customer Balance')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Setting::BORA_USER_BILLING_PORTAL_URL, 'Billing Portal URL')
                ->set_attribute('readOnly', 'readonly'),
        ]);
}

add_action('carbon_fields_register_fields', 'bb_add_user_meta_data');
