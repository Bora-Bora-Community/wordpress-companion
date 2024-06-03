<?php

use Boraboraio\API\Boraboraio_Api_Client;
use Boraboraio\enum\Boraboraio_Setting;
use Boraboraio\Service\Boraboraio_Manager;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Add the setting screens
 */
function boraboraio_add_plugin_settings_page(): void
{
    Container::make('theme_options', BORA_BORA_NAME.' '.__('Settings', 'Boraboraio'))
        ->set_icon('dashicons-money')
        ->set_page_menu_title(__('Bora Bora', 'Boraboraio'))
        ->add_tab(__('Bora Bora Connection', 'Boraboraio'), [
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_API_KEY, BORA_BORA_NAME.' API Key')
                ->set_attribute('maxLength', 36)
                ->set_attribute('min', 36)
                ->set_required(true)
                ->help_text(__('The API key is used to authenticate the plugin with the server. You receive it in your Bora Bora Dashboard.',
                    'Boraboraio')),
        ])->add_tab(__('Redirect Settings', 'Boraboraio'), [
            Field::make('checkbox', Boraboraio_Setting::BORA_BORA_IO_PLUGIN_ENABLED, __('Activate Bora Bora / Enable Redirects', 'Boraboraio'))
                ->set_help_text(__('If enabled, the user will be redirected to the selected page. Otherwise the plugin will do nothing.',
                    'Boraboraio'))
                ->set_default_value(false),

            Field::make('association', Boraboraio_Setting::BORA_BORA_IO_REDIRECT_AFTER_LOGIN, __('Redirect after Login', 'Boraboraio'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected after login.'),

            Field::make('separator', 'crb_style_restrictions', __('Redirects with restrictions', 'Boraboraio'))
                ->help_text(__('The following settings are used to redirect the user if he has no access to a page.',
                    'Boraboraio')),

            Field::make('association', Boraboraio_Setting::BORA_BORA_IO_REDIRECT_NO_AUTH, __('Redirect Unauthenticated Users', 'Boraboraio'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected if he\'s not logged in Wordpress.'),

            Field::make('association', Boraboraio_Setting::BORA_BORA_IO_REDIRECT_WRONG_GROUP, __('Redirect Group Restriction', 'Boraboraio'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected if he has\'t the right group assignment.'),

            Field::make('separator', 'crb_style_payment', __('Redirects from the booking', 'Boraboraio'))
                ->help_text(__('The following settings are used to redirect the user after a successful or failed payment.',
                    'Boraboraio')),

            Field::make('association', Boraboraio_Setting::BORA_BORA_IO_REDIRECT_PAYMENT_SUCCESS, __('Payment successful', 'Boraboraio'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('This page will be shown after a successful payment.'),

            Field::make('association', Boraboraio_Setting::BORA_BORA_IO_REDIRECT_PAYMENT_FAILED, __('Payment failed', 'Boraboraio'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('This page will be shown after a failed payment.'),

        ])->add_tab(__('User Session', 'Boraboraio'), [
            Field::make('select', Boraboraio_Setting::BORA_BORA_IO_SESSION_LENGTH_ACTIVE, __('Enable permanent login', 'Boraboraio'))
                ->add_options([
                    'yes' => 'Yes',
                    'no'  => 'No',
                ]),
            Field::make('select', Boraboraio_Setting::BORA_BORA_IO_SESSION_LENGTH, __('Automatic Logout Time', 'Boraboraio'))
                ->set_help_text(__('Select how long a login session lasts.', 'Boraboraio'))
                ->set_conditional_logic([
                    'relation' => 'AND', // Optional, defaults to "AND"
                    [
                        'field'   => Boraboraio_Setting::BORA_BORA_IO_SESSION_LENGTH_ACTIVE,
                        'value'   => 'yes',
                        // Optional, defaults to "". Should be an array if "IN" or "NOT IN" operators are used.
                        'compare' => '=', // Optional, defaults to "=". Available operators: =, <, >, <=, >=, IN, NOT IN
                    ],
                ])
                ->add_options([
                    '3600'     => __('1 hour', 'Boraboraio'),
                    '7200'     => __('2 hours', 'Boraboraio'),
                    '14400'    => __('4 hours', 'Boraboraio'),
                    '28800'    => __('8 hours', 'Boraboraio'),
                    '43200'    => __('12 hours', 'Boraboraio'),
                    '86400'    => __('24 hours', 'Boraboraio'),
                    '604800'   => __('1 week', 'Boraboraio'),
                    '1209600'  => __('2 weeks', 'Boraboraio'),
                    '2592000'  => __('1 month', 'Boraboraio'),
                    '5184000'  => __('2 months', 'Boraboraio'),
                    '7776000'  => __('3 months', 'Boraboraio'),
                    '15552000' => __('6 months', 'Boraboraio'),
                    '31536000' => __('1 year', 'Boraboraio'),
                ])
                ->set_required(true)
                ->set_default_value('31536000'),
        ]);
}

add_action('carbon_fields_register_fields', 'boraboraio_add_plugin_settings_page');

function boraboraio_called_after_saving_settings(): void
{
    // store the api key in the WordPress metadata
    update_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY, carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_API_KEY));

    $bbApiClient = new Boraboraio_Api_Client();
    // first check if api key is valid
    $apiKeyInvalid = $bbApiClient->apiKeyInvalid();

    if ($apiKeyInvalid) {
        wp_admin_notice(
            __('The settings have been saved, but the API Key is invalid. Please check the API Key and try again.',
                'Boraboraio'),
            [
                'type'               => 'error',
                'dismissible'        => true,
                'additional_classes' => ['inline', 'notice-alt'],
                'attributes'         => ['data-slug' => 'plugin-slug'],
            ]
        );

        return;
    }
    // update the available roles locally
    (new Boraboraio_Manager())->updateCommunityRoles();

    // now we can publish the WordPress uri to the Bora Bora backend
    $bbApiClient->publishWordpressUri(
        paymentSuccessPageId: carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_REDIRECT_PAYMENT_SUCCESS)[0]['id'],
        paymentFailedPageId : carbon_get_theme_option(Boraboraio_Setting::BORA_BORA_IO_REDIRECT_PAYMENT_FAILED)[0]['id']
    );
}

add_filter('carbon_fields_theme_options_container_saved', 'boraboraio_called_after_saving_settings');

/**
 * Settings for the post meta
 * to restrict access to certain groups
 */
function boraboraio_add_post_setting_fields(): void
{
    // first load the roles from local DB
    $roles = (new Boraboraio_Manager)->getCommunityRoles();
    $roleOptions = [];
    $roleOptions['all'] = 'All Members';
    $roleOptions['guest'] = 'Guest Users / Public';
    foreach ($roles as $role) {
        $roleOptions[$role['discord_id']] = esc_html($role['name']);
    }

    Container::make('post_meta', BORA_BORA_NAME)
        ->where('post_type', '=', 'page')
        ->add_fields([
            Field::make('multiselect', Boraboraio_Setting::BORA_BORA_IO_AVAILABLE_FOR_GROUPS,
                __('This Page is Available for these Groups:', 'Boraboraio'))
                ->set_help_text(__('Choose the groups that have access to this page. At least select one user group',
                    'Boraboraio'))
                ->add_options($roleOptions),
        ]);
}

add_action('carbon_fields_register_fields', 'boraboraio_add_post_setting_fields');

/**
 * add user metadata to the edit user screen
 * metadata is provided by the Bora Bora API
 */
function boraboraio_add_user_meta_data(): void
{
    Container::make('user_meta', 'Bora Bora')
        ->add_fields([
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_ID, 'Bora Bora User ID')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_NAME, 'Bora Bora User Name')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_EMAIL, 'Bora Bora User Email')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_LOCALE, 'Bora Bora User Language')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_DISCORD_ID, 'Discord ID')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_BOOKED_PRICE_NAME, 'Booked Price')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_SUBSCRIPTION_STATUS, 'Subscription Status')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_DISCORD_USERNAME, 'Discord Username')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_LINK, 'Referral URL')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_COUNT, 'Referral Count (Total)')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_TOTAL_EARNING, 'Referral Payout Amount')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_REFERRAL_CURRENT_BALANCE, 'Current Customer Balance')
                ->set_attribute('readOnly', 'readonly'),
            Field::make('text', Boraboraio_Setting::BORA_BORA_IO_USER_BILLING_PORTAL_URL, 'Billing Portal URL')
                ->set_attribute('readOnly', 'readonly'),
        ]);
}

add_action('carbon_fields_register_fields', 'boraboraio_add_user_meta_data');
