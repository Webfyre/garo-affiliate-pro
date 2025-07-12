<?php
/**
 * Garo Affiliate Pro - Click & Referral Tracker
 */

if (!defined('ABSPATH')) exit;

class Garo_Affiliate_Tracker {

    public static function init() {
        add_action('init', [__CLASS__, 'capture_affiliate_cookie']);
        add_action('template_redirect', [__CLASS__, 'log_affiliate_click']);
    }

    // Detect ?ref=username and store cookie
    public static function capture_affiliate_cookie() {
        if (isset($_GET['ref']) || isset($_GET['affiliate'])) {
            $ref = sanitize_text_field($_GET['ref'] ?? $_GET['affiliate']);
            $user = get_user_by('login', $ref);
            if ($user && in_array('garo_affiliate', $user->roles)) {
                setcookie('garo_affiliate_ref', $user->ID, time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN);
                $_COOKIE['garo_affiliate_ref'] = $user->ID;
            }
        }
    }

    // Log the visit for the first time only
    public static function log_affiliate_click() {
        if (!isset($_COOKIE['garo_affiliate_ref'])) return;

        $affiliate_id = absint($_COOKIE['garo_affiliate_ref']);
        if (!$affiliate_id || is_user_logged_in()) return;

        // IP Address
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $browser = self::get_browser($user_agent);
        $device = wp_is_mobile() ? 'Mobile' : 'Desktop';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';

        // Prevent duplicate IP/browser logs within 24h
        global $wpdb;
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}garo_aff_clicks
             WHERE affiliate_id = %d AND ip_address = %s AND browser = %s AND DATE(created_at) = CURDATE()",
            $affiliate_id, $ip, $browser
        ));

        if ($exists > 0) return;

        $wpdb->insert("{$wpdb->prefix}garo_aff_clicks", [
            'affiliate_id' => $affiliate_id,
            'ip_address'   => $ip,
            'browser'      => $browser,
            'device'       => $device,
            'referer'      => $referer,
            'created_at'   => current_time('mysql')
        ]);
    }

    // Parse browser from user agent
    private static function get_browser($user_agent) {
        if (stripos($user_agent, 'Chrome') !== false) return 'Chrome';
        if (stripos($user_agent, 'Safari') !== false) return 'Safari';
        if (stripos($user_agent, 'Firefox') !== false) return 'Firefox';
        if (stripos($user_agent, 'Edge') !== false) return 'Edge';
        if (stripos($user_agent, 'Opera') !== false) return 'Opera';
        if (stripos($user_agent, 'MSIE') !== false || stripos($user_agent, 'Trident') !== false) return 'IE';
        return 'Unknown';
    }
}
