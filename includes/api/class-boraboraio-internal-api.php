<?php

namespace Boraboraio\API;

use Boraboraio\enum\Boraboraio_Setting;
use Boraboraio\Service\Boraboraio_User_Manager;

/**
 * Internal API Endpoints for Bora Bora
 * @package API
 * @since 1.2.0
 */
class Boraboraio_Internal_Api
{
    /**
     * check if request is from https://bora-bora.io
     */
    public static function is_request_from_bora_bora(): bool|WP_Error
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (!str_contains($referer, 'https://bora-bora.io')) {
            return new WP_Error('unauthorized', 'Unauthorized access', ['status' => 401]);
        }

        return true;
    }

    /**
     * register endpoints for internal API
     */
    public function register_endpoints()
    {
        register_rest_route('boraboraio/v1', '/reload-user-details/(?P<boraboraio_user_id>[^\/]+)', [
            'methods'  => WP_REST_Server::EDITABLE,
            'callback' => [$this, 'reload_user_details'],
            'args'     => [
                'boraboraio_user_id',
            ],
        ]);
    }

    /**
     * reload user details
     */
    public function reload_user_details($data): array|WP_Error
    {
        $boraBoraId = isset($_REQUEST['boraboraio_user_id'])
            ? sanitize_text_field($_REQUEST['boraboraio_user_id'])
            : '';

        // fetch all wordpress users and check if the bora_bora_id matches
        $users = get_users();
        foreach ($users as $user) {
            if (carbon_get_user_meta($user->ID, Boraboraio_Setting::BORA_BORA_IO_USER_ID) === $boraBoraId) {
                // in this case, reload the user data and overwrite the local data
                $bbClient = new Boraboraio_Api_Client();
                $userDetails = $bbClient->loadUserDetails($boraBoraId);
                if (empty($userDetails) || !isset($userDetails['subscription'])) {
                    return new WP_Error('invalid_user_data', 'Invalid user data.', ['status' => 400]);
                } else {
                    (new Boraboraio_User_Manager)->updateUserData($user->ID, $userDetails);

                    return ['message' => 'user details updated', 'status' => 200];
                }
            }
        }

        // Gibt einen Fehler zurück, wenn keine gültige Variable angegeben wurde
        return new WP_Error('invalid_parameter', 'Invalid parameter.', ['status' => 400]);
    }
}
