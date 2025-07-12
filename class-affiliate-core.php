<?php
/**
 * Garo Affiliate Pro - Core Class
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Garo_Affiliate_Core')) {
    class Garo_Affiliate_Core {

        public static function init() {
            self::define_constants();
            self::load_files();
            self::init_modules();

            // Enqueue styles and scripts
            add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_assets']);
            add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_assets']);
        }

        private static function define_constants() {
            if (!defined('GARO_AFF_PLUGIN_URL')) define('GARO_AFF_PLUGIN_URL', GARO_AFF_URL);
            if (!defined('GARO_AFF_PLUGIN_PATH')) define('GARO_AFF_PLUGIN_PATH', GARO_AFF_PATH);
        }

        private static function load_files() {
            $files = [
                'install/install-tables.php',
                'includes/class-affiliate-tracker.php',
                'includes/class-affiliate-commission.php',
                'includes/class-affiliate-mlm.php',
                'includes/class-affiliate-payouts.php',
                'includes/class-affiliate-emails.php',
                'includes/class-affiliate-admin-ui.php',
                'includes/class-affiliate-api.php',
                'config/settings.php',
                'includes/class-affiliate-shortcodes.php',
                'includes/class-affiliate-auth.php',
            ];

            foreach ($files as $file) {
                $path = GARO_AFF_PLUGIN_PATH . $file;
                if (file_exists($path)) {
                    require_once $path;
                } else {
                    error_log("[Garo Affiliate Pro] Missing required file: $path");
                }
            }
        }

        private static function init_modules() {
            // Only call init() if the class exists and has that method
            $modules = [
                'Garo_Affiliate_Settings',
                'Garo_Affiliate_Tracker',
                'Garo_Affiliate_Commission',
                'Garo_Affiliate_MLM',
                'Garo_Affiliate_Payouts',
                'Garo_Affiliate_Emails',
                'Garo_Affiliate_Admin_UI',
                'Garo_Affiliate_API',
                'Garo_Affiliate_Shortcodes',
                'Garo_Affiliate_Auth',
            ];

            foreach ($modules as $module) {
                if (class_exists($module) && method_exists($module, 'init')) {
                    call_user_func([$module, 'init']);
                } else {
                    error_log("[Garo Affiliate Pro] Init skipped for $module — class or init() method missing.");
                }
            }
        }

        public static function enqueue_assets() {
            wp_enqueue_style(
                'garo-affiliate-style',
                GARO_AFF_PLUGIN_URL . 'assets/css/admin-style.css',
                [],
                GARO_AFF_VER
            );

            wp_enqueue_script(
                'garo-affiliate-js',
                GARO_AFF_PLUGIN_URL . 'assets/js/dashboard.js',
                ['jquery'],
                GARO_AFF_VER,
                true
            );
        }

        public static function enqueue_admin_assets() {
            wp_enqueue_style(
                'garo-affiliate-style',
                GARO_AFF_PLUGIN_URL . 'assets/css/admin-style.css',
                [],
                GARO_AFF_VER
            );
        }
    }
}
