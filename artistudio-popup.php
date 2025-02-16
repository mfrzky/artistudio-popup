<?php
/**
 * Plugin Name: Artistudio Popup Test
 * Description: Plugin untuk menampilkan pop-up di WordPress.
 * Version: 1.0.0
 * Author: M.Fariz Rizky
 */

namespace ArtistudioPopup;

if (!defined('ABSPATH')) {
    exit;
}

// Helper Trait untuk sanitasi input
trait Helper {
    public function sanitize_input($input) {
        return sanitize_text_field($input);
    }
}

// Interface untuk Popup
interface PopupInterface {
    public function register_popup_post_type();
}

// Kelas utama plugin
class ArtistudioPopup implements PopupInterface {
    use Helper;

    private static $instance = null;

    private function __construct() {
        add_action('init', [$this, 'register_popup_post_type']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_dependencies']);
        add_action('wp_footer', [$this, 'enqueue_popup_script']);
    }

    // Singleton Pattern untuk memastikan hanya ada satu instance
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Daftarkan Custom Post Type untuk popup
    public function register_popup_post_type() {
        $labels = [
            'name'          => 'Popups',
            'singular_name' => 'Popup',
            'add_new'       => 'Tambah Popup',
            'add_new_item'  => 'Tambah Pop-up Baru',
            'edit_item'     => 'Edit Pop-up',
            'new_item'      => 'Pop-up Baru',
            'view_item'     => 'Lihat Pop-up',
            'search_items'  => 'Cari Pop-up',
            'not_found'     => 'Tidak ada pop-up ditemukan',
            'not_found_in_trash' => 'Tidak ada pop-up di tong sampah',
        ];

        $args = [
            'labels'        => $labels,
            'public'        => true,
            'show_ui'       => true,
            'show_in_menu'  => true,
            'menu_position' => 20,
            'menu_icon'     => 'dashicons-welcome-widgets-menus',
            'supports'      => ['title', 'editor'],
            'has_archive'   => true,
            'rewrite'       => ['slug' => 'popup'],
        ];

        register_post_type('popup', $args);
    }

    // Memuat dependensi (React & React-DOM) jika belum ada di WordPress
    public function enqueue_dependencies() {
        if (!wp_script_is('react', 'registered')) {
            wp_enqueue_script('react', 'https://unpkg.com/react@17/umd/react.production.min.js', [], null, true);
        }

        if (!wp_script_is('react-dom', 'registered')) {
            wp_enqueue_script('react-dom', 'https://unpkg.com/react-dom@17/umd/react-dom.production.min.js', [], null, true);
        }
    }

    // Memuat script dan style untuk popup
    public function enqueue_popup_script() {
        $plugin_path = plugin_dir_path(__FILE__) . 'assets/popup-frontend/build/static/';
        $plugin_url  = plugin_dir_url(__FILE__) . 'assets/popup-frontend/build/static/';

        $js_files  = glob($plugin_path . 'js/main.*.js');
        $css_files = glob($plugin_path . 'css/main.*.css');

        if (!empty($js_files) && !empty($css_files)) {
            $js_file  = basename($js_files[0]);
            $css_file = basename($css_files[0]);

            // Enqueue JavaScript
            wp_enqueue_script(
                'artistudio-popup-frontend',
                $plugin_url . 'js/' . $js_file,
                ['wp-api-fetch'],
                filemtime($js_files[0]),
                true
            );

            // Enqueue CSS
            wp_enqueue_style(
                'artistudio-popup-style',
                $plugin_url . 'css/' . $css_file,
                [],
                filemtime($css_files[0])
            );

            // Kirim data REST API ke frontend
            wp_localize_script(
                'artistudio-popup-frontend',
                'artistudioData',
                [
                    'apiUrl' => rest_url('artistudio/v1/popup'),
                    'nonce'  => wp_create_nonce('wp_rest')
                ]
            );

            // Tambahkan div root untuk React
            echo '<div id="artistudio-popup-root"></div>';
        }
    }
}

// Hook aktivasi plugin
register_activation_hook(__FILE__, function() {
    ArtistudioPopup::get_instance()->register_popup_post_type();
    flush_rewrite_rules();
});

// Hook deaktivasi plugin
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});

// **Mengganti api.php dengan class-popup-api.php**
require_once plugin_dir_path(__FILE__) . 'includes/traits/class-popup-api.php';

// Buat instance plugin
ArtistudioPopup::get_instance();