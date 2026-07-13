<?php

class Wallet_Checker {
    
    public static function activate() {
        // Create uploads directory for CSV files
        if (!is_dir(WALLET_CHECKER_UPLOADS_DIR)) {
            wp_mkdir_p(WALLET_CHECKER_UPLOADS_DIR);
        }
    }
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('wallet_checker', array($this, 'render_shortcode'));
        add_action('wp_ajax_check_wallet_eligibility', array($this, 'ajax_check_wallet_eligibility'));
        add_action('wp_ajax_nopriv_check_wallet_eligibility', array($this, 'ajax_check_wallet_eligibility'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script(
            'wallet-checker-script',
            WALLET_CHECKER_PLUGIN_URL . 'assets/js/wallet-checker.js',
            array('jquery'),
            WALLET_CHECKER_VERSION
        );
        
        wp_enqueue_style(
            'wallet-checker-style',
            WALLET_CHECKER_PLUGIN_URL . 'assets/css/wallet-checker.css',
            array(),
            WALLET_CHECKER_VERSION
        );
        
        wp_localize_script('wallet-checker-script', 'walletChecker', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wallet_checker_nonce')
        ));
    }
    
    public function render_shortcode($atts) {
        ob_start();
        ?>
        <div class="wallet-checker-container">
            <h2>Check Wallet Eligibility</h2>
            <div class="wallet-checker-form">
                <input type="text" id="wallet-address" placeholder="Enter Ethereum wallet address (0x...)" />
                <button id="check-wallet-btn" class="btn-check">Check Eligibility</button>
            </div>
            
            <div id="wallet-result" class="wallet-result" style="display:none;">
                <div id="result-eligible" class="result-eligible" style="display:none;">
                    <span class="badge-eligible">✓ ELIGIBLE</span>
                    <div class="result-content">
                        <p><strong>Address:</strong> <span id="result-address"></span></p>
                    </div>
                </div>
                <div id="result-not-eligible" class="result-not-eligible" style="display:none;">
                    <span class="badge-not-eligible">✗ NOT ELIGIBLE</span>
                    <div class="result-content">
                        <p><strong>Address:</strong> <span id="result-address-not"></span></p>
                    </div>
                </div>
            </div>
            
            <div id="wallet-error" class="wallet-error" style="display:none;"></div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function ajax_check_wallet_eligibility() {
        check_ajax_referer('wallet_checker_nonce', 'nonce');
        
        $wallet_address = sanitize_text_field($_POST['wallet_address']);
        
        // Validate Ethereum address format
        if (!preg_match('/^0x[a-fA-F0-9]{40}$/', $wallet_address)) {
            wp_send_json_error('Invalid Ethereum wallet address format');
            wp_die();
        }
        
        // Convert to lowercase for comparison
        $wallet_address_lower = strtolower($wallet_address);
        
        // Check eligibility from CSV
        $is_eligible = $this->check_eligibility($wallet_address_lower);
        
        if ($is_eligible !== null) {
            wp_send_json_success(array(
                'address' => $wallet_address,
                'eligible' => $is_eligible
            ));
        } else {
            wp_send_json_error('Unable to check eligibility. Please try again later.');
        }
        
        wp_die();
    }
    
    private function check_eligibility($wallet_address) {
        $csv_file = $this->get_csv_file();
        
        if (!file_exists($csv_file)) {
            return null;
        }
        
        $handle = fopen($csv_file, 'r');
        if ($handle === false) {
            return null;
        }
        
        $found = false;
        
        // Read CSV file
        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row[0])) continue;
            
            $csv_address = strtolower(trim($row[0]));
            
            if ($csv_address === $wallet_address) {
                $found = true;
                break;
            }
        }
        
        fclose($handle);
        
        return $found;
    }
    
    private function get_csv_file() {
        // Look for CSV file in uploads directory
        $files = glob(WALLET_CHECKER_UPLOADS_DIR . '*.csv');
        
        if (!empty($files)) {
            return $files[0]; // Return first CSV file found
        }
        
        return null;
    }
}