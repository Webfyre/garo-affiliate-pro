<?php
/**
 * Garo Affiliate Pro - Login Template
 * Location: /templates/login-register/login.php
 */

if (!defined('ABSPATH')) exit;

if (is_user_logged_in()) {
    wp_redirect(site_url('/affiliate-dashboard'));
    exit;
}

$login_url = wp_login_url(site_url('/affiliate-dashboard'));
$register_url = site_url('/affiliate-register');
?>

<div class="garo-affiliate-auth-wrapper">
  <div class="garo-auth-card">
    <h2>Welcome Back 👋</h2>
    <p>Please login to your affiliate account</p>

    <?php if (isset($_GET['login']) && $_GET['login'] === 'failed') : ?>
      <div class="garo-auth-error">❌ Invalid login credentials. Please try again.</div>
    <?php endif; ?>

    <form name="loginform" id="loginform" action="<?php echo esc_url($login_url); ?>" method="post">
      <p>
        <label for="user_login">Email / Username</label>
        <input type="text" name="log" id="user_login" class="input" required />
      </p>
      <p>
        <label for="user_pass">Password</label>
        <input type="password" name="pwd" id="user_pass" class="input" required />
      </p>
      <p class="garo-auth-remember">
        <label><input name="rememberme" type="checkbox" checked="checked" /> Remember Me</label>
      </p>
      <p>
        <input type="submit" name="wp-submit" id="wp-submit" value="Login" class="button button-primary" />
      </p>
      <input type="hidden" name="redirect_to" value="<?php echo esc_url(site_url('/affiliate-dashboard')); ?>" />
    </form>

    <div class="garo-auth-divider"><span>OR</span></div>

    <!-- Placeholder buttons for future integrations -->
    <div class="garo-social-login">
      <button class="garo-btn-google" disabled title="Coming soon">Login with Google</button>
      <button class="garo-btn-phone" disabled title="Coming soon">Login with Phone</button>
    </div>

    <p class="garo-auth-footer">
      Don't have an account? <a href="<?php echo esc_url($register_url); ?>">Register here</a>
    </p>
  </div>
</div>
