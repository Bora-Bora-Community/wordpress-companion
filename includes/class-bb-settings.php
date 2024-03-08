<?php

use BB\API\BB_Api_Client;
use BB\Service\BB_Manager;
use Carbon_Fields\Carbon_Fields;
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
            Field::make('text', 'bora_api_key', BORA_BORA_NAME.' API Key')
                ->set_attribute('maxLength', 36)
                ->set_attribute('min', 36)
                ->set_required(true)
                ->help_text(__('The API key is used to authenticate the plugin with the server. You receive it in your Bora Bora Dashboard.',
                    'bora_bora')),

            Field::make('separator', 'crb_separator', __('Application Access Settings'))
                ->set_help_text(__('The following settings are used to create new users in Wordpress after creating their subscription.',
                    'bora_bora')),
            Field::make('association', 'bora_api_user', __('Choose a user', 'bora_bora'))
                ->set_types([
                    [
                        'type' => 'user',
                    ],
                ])
                ->set_max(1)
                ->set_required(true)
                ->set_help_text(__('Choose a user that will be used to create new users in Wordpress after creating their subscription. This user needs the right to create users.',
                    'bora_bora')),
            Field::make('text', 'bora_api_user_password', __('User Application Password', 'bora_bora'))
                ->set_required(true)
                ->set_help_text(__('Create an application password in the user settings.', 'bora_bora')),
        ])->add_tab(__('Redirect Settings', 'bora_bora'), [
            Field::make('association', 'crb_redirect_no_auth', __('Redirect Unauthenticated Users', 'bora_bora'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected if he\'s not authenticated in Wordpress.'),
            Field::make('association', 'crb_redirect_without_group', __('Redirect Group Restriction', 'bora_bora'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected if he has\'t the right group assignment.'),

            Field::make('association', 'crb_redirect_payment_success', __('Payment successful', 'bora_bora'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_required(true)
                ->set_max(1)
                ->set_help_text('This page will be shown after a successful payment.'),

            Field::make('association', 'crb_redirect_payment_failed', __('Payment failed', 'bora_bora'))
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
            Field::make('select', 'bora_session_length', __('Automatic Logout Time', 'bora_bora'))
                ->set_help_text(__('Select how long a login session lasts.', 'bora_bora'))
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
                ->set_default_value('2592000'),
        ]);
}

add_action('carbon_fields_register_fields', 'bb_add_plugin_settings_page');

function called_after_saving_settings(): void
{
    // store the api key in the wordpress metadata
    update_option('bora_api_key', carbon_get_theme_option('bora_api_key'));

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

    // now we can publish the wordpress uri to the bora bora backend
    $bbApiClient->publishWordpressUri(
        paymentSuccessPageId: carbon_get_theme_option('crb_redirect_payment_success')[0]['id'],
        paymentFailedPageId: carbon_get_theme_option('crb_redirect_payment_failed')[0]['id']
    );

    // publish the application user and password
    $userLoginName = get_user_by('ID', (carbon_get_theme_option('bora_api_user')[0]['id']))->user_login;
    $userApplicationPassword = carbon_get_theme_option('bora_api_user_password');
    $bbApiClient->registerWordpressCompanionUser(username: $userLoginName, password: $userApplicationPassword);
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
    $roleOptions['all'] = 'All groups';
    $roleOptions['guest'] = 'Public / All Users';
    foreach ($roles as $role) {
        $roleOptions[$role['discord_id']] = $role['name'];
    }

    Container::make('post_meta', BORA_BORA_NAME)
        ->where('post_type', '=', 'page')
        ->add_fields([
            Field::make('multiselect', 'bora_available_for_groups', __('Available for Groups', 'bora_bora'))
                ->set_help_text(__('Choose the groups that have access to this page.', 'bora_bora'))
                ->add_options($roleOptions),
        ]);
}

add_action('carbon_fields_register_fields', 'bb_add_post_setting_fields');

/**
 * add user meta data to the edit user screen
 * meta data is provided by the Bora Bora API
 */
function bb_add_user_meta_data(): void
{
    Container::make('user_meta', 'Bora Bora')
        ->add_fields([
            Field::make('text', 'bora_bora_id', 'Bora Bora User ID'),
            Field::make('text', 'bora_bora_name', 'Bora Bora User Name'),
            Field::make('text', 'bora_bora_email', 'Bora Bora User Email'),
            Field::make('text', 'bora_bora_locale', 'Bora Bora User Language'),
            Field::make('text', 'bora_bora_discord_id', 'Discord ID'),
            Field::make('text', 'bora_bora_discord_username', 'Discord Username'),
        ]);
}

add_action('carbon_fields_register_fields', 'bb_add_user_meta_data');

/**
 * Register the settings screen to Wordpress
 */
function bb_load()
{
    require_once(BORA_BORA_PATH.'/vendor/autoload.php');
    Carbon_Fields::boot();
}

add_action('after_setup_theme', 'bb_load');
