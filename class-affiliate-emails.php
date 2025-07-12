<?php
/**
 * Garo Affiliate Pro - Email Notification Engine
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Garo_Affiliate_Emails')) {
    class Garo_Affiliate_Emails {

        /**
         * Init method called during plugin boot.
         */
        public static function init() {
            // Reserved for future hooks if needed.
            add_action('user_register', [__CLASS__, 'send_welcome_email']);
        }

        /**
         * Send welcome email on registration
         */
        public static function send_welcome_email($user_id) {
            $user = get_user_by('id', $user_id);
            if (!$user || empty($user->user_email)) return;

            $to = sanitize_email($user->user_email);
            $subject = "🎉 Welcome to Garo Affiliate Pro!";
            $message = self::get_email_template('welcome.php', ['user' => $user]);
            self::send_email($to, $subject, $message);
        }

        /**
         * Notify affiliate of sale
         */
        public static function send_sale_email($user_id, $commission_amount, $order_id = null) {
            $user = get_user_by('id', $user_id);
            if (!$user || empty($user->user_email)) return;

            $to = sanitize_email($user->user_email);
            $subject = "💰 You earned a commission!";
            $message = self::get_email_template('sale-notice.php', [
                'user' => $user,
                'amount' => $commission_amount,
                'order_id' => $order_id
            ]);
            self::send_email($to, $subject, $message);
        }

        /**
         * Notify affiliate of payout
         */
        public static function send_payout_email($user_id, $amount) {
            $user = get_user_by('id', $user_id);
            if (!$user || empty($user->user_email)) return;

            $to = sanitize_email($user->user_email);
            $subject = "💸 You've been paid!";
            $amount_fmt = number_format(floatval($amount), 2);
            $message = "Hi {$user->display_name},<br><br>We’ve just processed your payout of <strong>KSh {$amount_fmt}</strong> via Garo Affiliate Pro.<br><br>Thank you!";
            self::send_email($to, $subject, $message);
        }

        /**
         * Notify admin of new affiliate
         */
        public static function notify_admin_new_affiliate($user_id) {
            $user = get_user_by('id', $user_id);
            if (!$user || empty($user->user_email)) return;

            $admin_email = sanitize_email(get_option('admin_email'));
            $subject = "🧍 New Affiliate Signup: " . $user->display_name;
            $message = "A new user has joined your affiliate program:<br><br>
            Name: {$user->display_name}<br>
            Email: {$user->user_email}<br>
            Profile: " . esc_url(admin_url("user-edit.php?user_id={$user_id}"));

            self::send_email($admin_email, $subject, $message);
        }

        /**
         * Email sender
         */
        public static function send_email($to, $subject, $message) {
            if (empty($to) || empty($subject) || empty($message)) return;

            $headers = [
                'Content-Type: text/html; charset=UTF-8',
                'From: Garo Affiliates <' . sanitize_email(get_option('admin_email')) . '>'
            ];

            wp_mail($to, wp_strip_all_tags($subject), $message, $headers);
        }

        /**
         * Email template loader
         */
        public static function get_email_template($template, $data = []) {
            $path = plugin_dir_path(__DIR__) . 'templates/emails/' . sanitize_file_name($template);
            ob_start();

            if (file_exists($path)) {
                extract($data, EXTR_SKIP);
                include $path;
            } else {
                echo '<p>Email template not found.</p>';
            }

            return ob_get_clean();
        }
    }
}
