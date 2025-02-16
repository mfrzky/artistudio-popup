<?php
namespace ArtistudioPopup\Traits;

trait Singleton {
    private static $instance;

    public static function get_instance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}
}