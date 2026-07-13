<?php
/**
 * Plugin Name: Wallet Checker
 * Plugin URI: https://example.com/wallet-checker
 * Description: Check if Ethereum wallet is eligible based on CSV data
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: wallet-checker
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('WALLET_CHECKER_VERSION', '1.0.0');
define('WALLET_CHECKER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WALLET_CHECKER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WALLET_CHECKER_UPLOADS_DIR', wp_upload_dir()['basedir'] . '/wallet-checker/');

// Load plugin files
require_once WALLET_CHECKER_PLUGIN_DIR . 'includes/class-wallet-checker.php';
require_once WALLET_CHECKER_PLUGIN_DIR . 'admin/class-admin.php';

// Activation
register_activation_hook(__FILE__, array('Wallet_Checker', 'activate'));

// Initialize
add_action('plugins_loaded', function() {
    new Wallet_Checker();
});