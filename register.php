<?php
/**
 * Garo Affiliate Pro - Register Template
 * Location: /templates/login-register/register.php
 */

if (!defined('ABSPATH')) exit;

if (is_user_logged_in()) {
    wp_redirect(site_url('/affiliate-dashboard'));
    exit;
}

$login_url = site_url('/affiliate-login');
?>

<div class="garo-affiliate-auth-wrapper">
  <div class="garo-auth-card">
    <h2>Join the Garo Affiliate Program 🚀</h2>
    <p>Earn commission by sharing our products</p>

    <?php if (isset($_GET['reg']) && $_GET['reg'] === 'failed') : ?>
      <div class="garo-auth-error">❌ Registration failed. Please try again or use a different email.</div>
    <?php endif; ?>

    <form method="post" action="">
      <p>
        <label for="garo_aff_name">Full Name</label>
        <input type="text" name="garo_aff_name" id="garo_aff_name" required />
      </p>
      <p>
        <label for="garo_aff_email">Email</label>
        <input type="email" name="garo_aff_email" id="garo_aff_email" required />
      </p>
      <p>
        <label for="garo_aff_phone">WhatsApp Number</label>
        <input type="text" name="garo_aff_phone" id="garo_aff_phone" placeholder="+254..." required />
      </p>
      <p>
        <label for="garo_aff_password">Password</label>
        <input type="password" name="garo_aff_password" id="garo_aff_password" required />
      </p>
      <p>
        <label for="garo_aff_referral">Referral Code (Optional)</label>
        <input type="text" name="garo_aff_referral" id="garo_aff_referral" />
      </p>

      <?php wp_nonce_field('garo_aff_register_nonce', 'garo_aff_register_nonce_field'); ?>

      <p>
        <button type="submit" name="garo_aff_register_submit" class="button button-primary">Create Account</button>
      </p>
    </form>

    <div class="garo-auth-divider"><span>OR</span></div>

    <div class="garo-social-login">
      <button class="garo-btn-google" disabled title="Coming soon">Register with Google</button>
      <button class="garo-btn-phone" disabled title="Coming soon">Register with Phone</button>
    </div>

    <p class="garo-auth-footer">
      Already have an account? <a href="<?php echo esc_url($login_url); ?>">Login here</a>
    </p>
  </div>
</div>
