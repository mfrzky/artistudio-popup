<?php
namespace ArtistudioPopup;

use ArtistudioPopup\Traits\Singleton;

class Plugin {
    use Singleton;

    private function __construct() {
        add_action('init', [$this, 'register_custom_post_type']);
        add_action('rest_api_init', [$this, 'register_api_routes']);
    }

    public function register_custom_post_type() {
        require_once plugin_dir_path(__FILE__) . 'class-popup-cpt.php';
        new Popup_CPT();
    }

    public function register_api_routes() {
        require_once plugin_dir_path(__FILE__) . 'class-popup-api.php';
        new Popup_API();
    }
}