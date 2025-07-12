<?php
/**
 * Plugin Name: Garo Affiliate Pro
 * Plugin URI: https://www.webfyre.co.ke/
 * Description: Scalable affiliate program for WooCommerce — with tiers, MLM, fraud detection, and beautiful dashboards.
 * Version: 1.0.0
 * Author: WebFyre | Vinaywa
 * Author URI: https://www.webfyre.co.ke
 * License: GPL2+
 * Text Domain: garo-affiliate-pro
 */

if (!defined('ABSPATH')) exit;

// Define constants
define('GARO_AFF_VER', '1.0.0');
define('GARO_AFF_PATH', plugin_dir_path(__FILE__));
define('GARO_AFF_URL', plugin_dir_url(__FILE__));
if (function_exists('opcache_reset')) { opcache_reset(); }

// Load Install + Register Activation Hook
require_once GARO_AFF_PATH . 'install/install-tables.php';
register_activation_hook(__FILE__, ['Garo_Affiliate_Install', 'install']);

// Autoload all required modules
require_once GARO_AFF_PATH . 'includes/class-affiliate-core.php';
require_once GARO_AFF_PATH . 'includes/class-affiliate-tracker.php';
require_once GARO_AFF_PATH . 'includes/class-affiliate-commission.php';
require_once GARO_AFF_PATH . 'includes/class-affiliate-mlm.php';
require_once GARO_AFF_PATH . 'includes/class-affiliate-payouts.php';
require_once GARO_AFF_PATH . 'includes/class-affiliate-emails.php';
require_once GARO_AFF_PATH . 'includes/class-affiliate-admin-ui.php';
require_once GARO_AFF_PATH . 'includes/class-affiliate-api.php';

// Load styles/scripts
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_style('garo-aff-admin-style', GARO_AFF_URL . 'assets/css/admin-style.css');
});

add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('garo-aff-dashboard', GARO_AFF_URL . 'assets/js/dashboard.js', ['jquery'], GARO_AFF_VER, true);
});

// Initialize Core
add_action('plugins_loaded', function() {
    if (class_exists('Garo_Affiliate_Core')) {
        Garo_Affiliate_Core::init();
    }
});
