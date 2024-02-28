<?php
add_action('wp', 'execute_on_load_page_hook_event');

function execute_on_load_page_hook_event()
{
    $accessValid = carbon_get_post_meta( get_the_ID(), 'bora_available_for_groups' );

    if ($accessValid == []) {
        return;
    }

    // ...
}
