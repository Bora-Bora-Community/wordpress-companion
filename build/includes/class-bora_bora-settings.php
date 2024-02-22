<?php

namespace _PhpScoper9a3678ae6a12;

use _PhpScoper9a3678ae6a12\Carbon_Fields\Carbon_Fields;
use _PhpScoper9a3678ae6a12\Carbon_Fields\Container;
use _PhpScoper9a3678ae6a12\Carbon_Fields\Field;
/** @internal */
function dbi_add_plugin_settings_page()
{
    Container::make('theme_options', \BORA_BORA_NAME . ' ' . __('Settings', 'bora_bora'))->set_page_parent('options-general.php')->add_fields([Field::make('text', 'dbi_api_key', 'API Key')->set_attribute('maxLength', 32), Field::make('text', 'dbi_results_limit', 'Results Limit')->set_attribute('min', 1)->set_attribute('max', 100)->set_default_value(10), Field::make('date', 'dbi_start_date', 'Start Date')]);
}
add_action('carbon_fields_register_fields', 'dbi_add_plugin_settings_page');
add_action('after_setup_theme', 'crb_load');
/** @internal */
function crb_load()
{
    require_once 'vendor/autoload.php';
    Carbon_Fields::boot();
}
