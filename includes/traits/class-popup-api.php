<?php
namespace ArtistudioPopup;

class Popup_API {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
        register_rest_route('artistudio/v1', '/popup', [
            'methods'  => 'GET',
            'callback' => [$this, 'get_popup_data'],
            'permission_callback' => [$this, 'check_permissions']
        ]);
    }

    public function get_popup_data($request) {
        $args = ['post_type' => 'popup', 'posts_per_page' => -1];
        $query = new \WP_Query($args);
        $popups = [];

        while ($query->have_posts()) {
            $query->the_post();
            $popups[] = [
                'id'          => get_the_ID(),
                'title'       => get_the_title(),
                'description' => get_the_content(),
                'page'        => get_post_meta(get_the_ID(), '_popup_page', true) // Saya gunakan untuk sesuaikan meta key
            ];
        }

        wp_reset_postdata(); // Gunakan untuk reset postdata
        return rest_ensure_response($popups);
    }

    public function check_permissions() {
        return is_user_logged_in() ? true : new \WP_Error('unauthorized', 'Anda harus login.', ['status' => 401]);
    }
}

new Popup_API();
