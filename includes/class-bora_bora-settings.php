<?php

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

function dbi_add_plugin_settings_page()
{
    Container::make('theme_options', BORA_BORA_NAME.' '.__('Settings', 'bora_bora'))
        ->set_icon('dashicons-money')
        ->set_page_menu_title(__('Bora Bora', 'bora_bora'))
        ->add_fields([
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
        ]);

    Container::make('post_meta', BORA_BORA_NAME)
        ->where('post_type', '=', 'page')
        ->add_fields([
            Field::make('multiselect', 'bora_available_for_groups', __('Available for Groups', 'bora_bora'))
                ->set_help_text(__('Choose the groups that have access to this page.', 'bora_bora'))
                ->add_options([
                    'red'   => 'Red',
                    'green' => 'Green',
                    'blue'  => 'Blue',
                ]),
        ]);
}

add_action('carbon_fields_register_fields', 'dbi_add_plugin_settings_page');

/**
 * Register the settings screen to Wordpress
 */
function crb_load()
{
    require_once(BORA_BORA_PATH.'/vendor/autoload.php');
    Carbon_Fields::boot();
}

add_action('after_setup_theme', 'crb_load');
