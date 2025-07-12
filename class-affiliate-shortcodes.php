<?php
// /includes/class-affiliate-shortcodes.php

if (!defined('ABSPATH')) exit;

class Garo_Affiliate_Shortcodes {

    public static function init() {
        add_shortcode('garo_affiliate_dashboard', [__CLASS__, 'dashboard']);
        add_shortcode('garo_affiliate_login', [__CLASS__, 'login_form']);
        add_shortcode('garo_affiliate_register', [__CLASS__, 'register_form']);

        add_action('template_redirect', [__CLASS__, 'handle_login']);
        add_action('template_redirect', [__CLASS__, 'handle_register']);
    }

    public static function dashboard() {
        ob_start();
        include plugin_dir_path(__DIR__) . '../templates/dashboard/main.php';
        return ob_get_clean();
    }

    public static function login_form() {
        if (is_user_logged_in()) {
            wp_redirect(site_url(Garo_Affiliate_Settings::get('dashboard_slug')));
            exit;
        }

        ob_start();
        include plugin_dir_path(__DIR__) . '../templates/login-register/login.php';
        return ob_get_clean();
    }

    public static function register_form() {
        if (is_user_logged_in()) {
            wp_redirect(site_url(Garo_Affiliate_Settings::get('dashboard_slug')));
            exit;
        }

        ob_start();
        include plugin_dir_path(__DIR__) . '../templates/login-register/register.php';
        return ob_get_clean();
    }

    public static function handle_login() {
        if (isset($_POST['garo_aff_login_nonce']) && wp_verify_nonce($_POST['garo_aff_login_nonce'], 'garo_aff_login')) {
            $creds = [
                'user_login'    => sanitize_text_field($_POST['username']),
                'user_password' => $_POST['password'],
                'remember'      => true
            ];

            $user = wp_signon($creds, false);
            if (!is_wp_error($user)) {
                wp_redirect(site_url(Garo_Affiliate_Settings::get('dashboard_slug')));
                exit;
            } else {
                wp_redirect(add_query_arg('login', 'failed', wp_get_referer()));
                exit;
            }
        }
    }

    public static function handle_register() {
        if (isset($_POST['garo_aff_register_nonce']) && wp_verify_nonce($_POST['garo_aff_register_nonce'], 'garo_aff_register')) {
            $username = sanitize_user($_POST['username']);
            $email    = sanitize_email($_POST['email']);
            $password = $_POST['password'];

            $user_id = wp_create_user($username, $password, $email);

            if (!is_wp_error($user_id)) {
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                Garo_Affiliate_Emails::send_welcome_email($user_id);
                wp_redirect(site_url(Garo_Affiliate_Settings::get('dashboard_slug')));
                exit;
            } else {
                wp_redirect(add_query_arg('register', 'failed', wp_get_referer()));
                exit;
            }
        }
    }
}
