<?php
namespace ArtistudioPopup;

class Popup_CPT {
    public function __construct() {
        add_action('init', [$this, 'register_popup_cpt']);
    }

    public function register_popup_cpt() {
        register_post_type('artistudio_popup', [
            'label' => 'Popups',
            'public' => false,
            'show_ui' => true,
            'supports' => ['title', 'editor'],
            'meta_box_cb' => [$this, 'add_custom_fields'],
        ]);
    }

    public function add_custom_fields($post) {
        add_meta_box('popup_page', 'Halaman Pop-up', function() use ($post) {
            $value = get_post_meta($post->ID, 'popup_page', true);
            echo '<input type="text" name="popup_page" value="'.esc_attr($value).'">';
        }, 'artistudio_popup');
    }
}

new Popup_CPT();
