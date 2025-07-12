<?php
/**
 * Garo Affiliate Pro - Auth Shortcodes
 */

if (!defined('ABSPATH')) exit;

class Garo_Affiliate_Auth {

    public static function init() {
        add_shortcode('garo_affiliate_login', [__CLASS__, 'login_form']);
        add_shortcode('garo_affiliate_register', [__CLASS__, 'register_form']);
        add_action('login_redirect', [__CLASS__, 'redirect_to_dashboard'], 10, 3);
    }

    public static function login_form() {
        if (is_user_logged_in()) {
            wp_redirect(site_url(Garo_Affiliate_Settings::get('dashboard_slug')));
            exit;
        }

        ob_start();
        wp_login_form([
            'redirect' => site_url(Garo_Affiliate_Settings::get('dashboard_slug')),
        ]);
        return ob_get_clean();
    }

    public static function register_form() {
        if (is_user_logged_in()) {
            wp_redirect(site_url(Garo_Affiliate_Settings::get('dashboard_slug')));
            exit;
        }

        ob_start(); ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <?php wp_nonce_field('garo_aff_register', 'garo_aff_nonce'); ?>
            <button type="submit">Register</button>
        </form>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['garo_aff_nonce']) && wp_verify_nonce($_POST['garo_aff_nonce'], 'garo_aff_register')) {
            $user_id = wp_create_user($_POST['username'], $_POST['password'], $_POST['email']);
            if (!is_wp_error($user_id)) {
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                wp_redirect(site_url(Garo_Affiliate_Settings::get('dashboard_slug')));
                exit;
            } else {
                echo '<p style="color:red;">' . esc_html($user_id->get_error_message()) . '</p>';
            }
        }
        return ob_get_clean();
    }

    public static function redirect_to_dashboard($redirect_to, $requested_redirect_to, $user) {
        if (is_a($user, 'WP_User') && $user->has_cap('read')) {
            return site_url(Garo_Affiliate_Settings::get('dashboard_slug'));
        }
        return $redirect_to;
    }
}
