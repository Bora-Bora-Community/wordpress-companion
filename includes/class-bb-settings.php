<?php

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
        ->add_tab(__('General Settings', 'bora_bora'), [
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
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected if he\'s not authenticated in Wordpress.'),
            Field::make('association', 'crb_redirect_without_group', __('Redirect Group Restriction', 'bora_bora'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'page',
                    ],
                ])
                ->set_max(1)
                ->set_help_text('Choose the page where the user will be redirected if he has\'t the right group assignment.'),
        ]);
}

add_action('carbon_fields_register_fields', 'bb_add_plugin_settings_page');

function called_after_saving_settings(): void
{
    $bbManager = new BB_Manager();
    $success = $bbManager->updateCommunityRoles();

    if (!$success) {
        wp_admin_notice(
            __('The settings have been saved, but the API Key is invalid. Please check the API Key and try again.', 'bora_bora'),
            [
                'type'               => 'error',
                'dismissible'        => true,
                'additional_classes' => ['inline', 'notice-alt'],
                'attributes'         => ['data-slug' => 'plugin-slug'],
            ]
        );
    }
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
 * Register the settings screen to Wordpress
 */
function bb_load()
{
    require_once(BORA_BORA_PATH.'/vendor/autoload.php');
    Carbon_Fields::boot();
}

add_action('after_setup_theme', 'bb_load');
