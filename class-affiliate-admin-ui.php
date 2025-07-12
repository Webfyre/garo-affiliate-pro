<?php
/**
 * Garo Affiliate Pro - Admin UI Manager
 */

if (!defined('ABSPATH')) exit;

class Garo_Affiliate_Admin_UI {

    public static function init() {
        add_action('admin_menu', [__CLASS__, 'register_menus']);
    }

    /**
     * Register top-level & submenu pages
     */
    public static function register_menus() {
        add_menu_page(
            'Affiliate Dashboard',
            'Garo Affiliates',
            'manage_options',
            'garo-affiliates',
            [__CLASS__, 'render_dashboard'],
            'dashicons-groups',
            58
        );

        add_submenu_page(
            'garo-affiliates',
            'Affiliates',
            'Affiliates',
            'manage_options',
            'garo-affiliates-list',
            [__CLASS__, 'render_affiliates']
        );

        add_submenu_page(
            'garo-affiliates',
            'Payouts',
            'Payouts',
            'manage_options',
            'garo-affiliates-payouts',
            [__CLASS__, 'render_payouts']
        );

        add_submenu_page(
            'garo-affiliates',
            'Settings',
            'Settings',
            'manage_options',
            'garo-affiliates-settings',
            [__CLASS__, 'render_settings']
        );
    }

    public static function render_dashboard() {
        self::load_template('dashboard.php');
    }

    public static function render_affiliates() {
        self::load_template('affiliates.php');
    }

    public static function render_payouts() {
        self::load_template('payouts.php');
    }

    public static function render_settings() {
        self::load_template('settings.php');
    }

    /**
     * Load from /templates/admin/
     */
    private static function load_template($file) {
        $path = plugin_dir_path(__DIR__) . 'templates/admin/' . $file;
        if (file_exists($path)) {
            include $path;
        } else {
            echo '<div class="notice notice-error"><p>Missing template: ' . esc_html($file) . '</p></div>';
        }
    }
}
