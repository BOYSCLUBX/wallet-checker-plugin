<?php

class Wallet_Checker_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array($this, 'handle_csv_upload'));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Wallet Checker',
            'Wallet Checker',
            'manage_options',
            'wallet-checker',
            array($this, 'render_settings_page'),
            'dashicons-money-alt',
            25
        );
    }
    
    public function register_settings() {
        register_setting('wallet_checker_settings', 'wallet_checker_csv_file');
    }
    
    public function handle_csv_upload() {
        // Handle file upload
        if (!isset($_POST['wallet_checker_upload_nonce'])) {
            return;
        }
        
        if (!wp_verify_nonce($_POST['wallet_checker_upload_nonce'], 'wallet_checker_upload')) {
            return;
        }
        
        if (!current_user_can('manage_options')) {
            return;
        }
        
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            add_settings_error('wallet_checker_notices', 'file_error', 'Please upload a CSV file', 'error');
            return;
        }
        
        $file = $_FILES['csv_file'];
        
        // Validate file type
        $file_type = wp_check_filetype($file['name']);
        if ($file_type['ext'] !== 'csv') {
            add_settings_error('wallet_checker_notices', 'file_type', 'Please upload a valid CSV file', 'error');
            return;
        }
        
        // Create uploads directory if doesn't exist
        if (!is_dir(WALLET_CHECKER_UPLOADS_DIR)) {
            wp_mkdir_p(WALLET_CHECKER_UPLOADS_DIR);
        }
        
        // Remove old CSV files
        $old_files = glob(WALLET_CHECKER_UPLOADS_DIR . '*.csv');
        foreach ($old_files as $old_file) {
            @unlink($old_file);
        }
        
        // Save new file
        $filename = 'eligible-wallets-' . time() . '.csv';
        $destination = WALLET_CHECKER_UPLOADS_DIR . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            add_settings_error('wallet_checker_notices', 'success', 'CSV file uploaded successfully!', 'success');
        } else {
            add_settings_error('wallet_checker_notices', 'save_error', 'Failed to save CSV file', 'error');
        }
    }
    
    public function render_settings_page() {
        $current_file = $this->get_current_csv_file();
        $file_info = $current_file ? $this->get_csv_info($current_file) : null;
        ?>
        <div class="wrap">
            <h1>Wallet Checker - CSV Management</h1>
            
            <?php settings_errors('wallet_checker_notices'); ?>
            
            <div class="card" style="margin-top: 20px; padding: 20px; max-width: 600px;">
                <h2>Upload Eligibility CSV</h2>
                <form method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('wallet_checker_upload', 'wallet_checker_upload_nonce'); ?>
                    
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="csv_file">CSV File</label>
                            </th>
                            <td>
                                <input 
                                    type="file" 
                                    id="csv_file" 
                                    name="csv_file" 
                                    accept=".csv"
                                    required
                                />
                                <p class="description">
                                    Upload a CSV file with Ethereum addresses in the first column.
                                    <br/>Example format: One address per row, starting with 0x
                                    <br/><a href="<?php echo esc_url(admin_url('admin.php?page=wallet-checker')); ?>" target="_blank">Download sample CSV</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                    
                    <?php submit_button('Upload CSV File'); ?>
                </form>
            </div>
            
            <?php if ($file_info): ?>
            <div class="card" style="margin-top: 20px; padding: 20px; max-width: 600px; background-color: #f0f6fc; border-left: 4px solid #667eea;">
                <h2>Current CSV File</h2>
                <table class="form-table">
                    <tr>
                        <th scope="row">Filename:</th>
                        <td><strong><?php echo esc_html($file_info['filename']); ?></strong></td>
                    </tr>
                    <tr>
                        <th scope="row">Total Addresses:</th>
                        <td><strong><?php echo $file_info['count']; ?></strong></td>
                    </tr>
                    <tr>
                        <th scope="row">Uploaded:</th>
                        <td><?php echo date('Y-m-d H:i:s', filemtime($file_info['path'])); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">File Size:</th>
                        <td><?php echo size_format(filesize($file_info['path']), 2); ?></td>
                    </tr>
                </table>
                
                <h3 style="margin-top: 20px;">Preview (First 10 Addresses)</h3>
                <div style="background: white; padding: 15px; border-radius: 4px; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px; line-height: 1.6;">
                    <?php echo esc_html($file_info['preview']); ?>
                </div>
                
                <p style="margin-top: 15px; color: #28a745; font-weight: bold;">✓ CSV file is ready for wallet eligibility checks!</p>
            </div>
            <?php else: ?>
            <div class="notice notice-warning" style="margin-top: 20px;">
                <p><strong>No CSV file uploaded yet.</strong> Upload a CSV file to start checking wallet eligibility.</p>
            </div>
            <?php endif; ?>
            
            <div class="card" style="margin-top: 20px; padding: 20px; max-width: 600px;">
                <h2>How to Use</h2>
                <ol>
                    <li>Create a CSV file with Ethereum wallet addresses in the first column</li>
                    <li>Upload the CSV file using the form above</li>
                    <li>Add the shortcode to any page: <code>[wallet_checker]</code></li>
                    <li>Users can now check if their wallet is eligible!</li>
                </ol>
            </div>

            <div class="card" style="margin-top: 20px; padding: 20px; max-width: 600px;">
                <h2>CSV Format Example</h2>
                <p><strong>Simple Format (Address Only):</strong></p>
                <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto;">0x1f9090aae28b8a3dceadf46b155ced7ee7ff7a07
0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb
0xd8dA6BF26964aF9D7eEd9e03E53415D37AA96045</pre>

                <p style="margin-top: 15px;"><strong>Advanced Format (With Metadata):</strong></p>
                <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto;">address,name,points,tier
0x1f9090aae28b8a3dceadf46b155ced7ee7ff7a07,Vitalik,5000,gold
0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb,Alice,3000,silver</pre>
            </div>
            
            <div class="card" style="margin-top: 20px; padding: 20px; max-width: 600px;">
                <h2>Shortcode Usage</h2>
                <p>Add this shortcode to any page or post:</p>
                <code style="display: block; background: #f5f5f5; padding: 10px; border-radius: 4px; margin: 10px 0;">
                    [wallet_checker]
                </code>
            </div>
        </div>
        <?php
    }
    
    private function get_current_csv_file() {
        $files = glob(WALLET_CHECKER_UPLOADS_DIR . '*.csv');
        return !empty($files) ? $files[0] : null;
    }
    
    private function get_csv_info($file_path) {
        if (!file_exists($file_path)) {
            return null;
        }
        
        $handle = fopen($file_path, 'r');
        if (!$handle) {
            return null;
        }
        
        $count = 0;
        $preview = '';
        $preview_count = 0;
        
        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row[0])) continue;
            
            $count++;
            
            if ($preview_count < 10) {
                $preview .= trim($row[0]) . "\n";
                $preview_count++;
            }
        }
        
        fclose($handle);
        
        return array(
            'filename' => basename($file_path),
            'path' => $file_path,
            'count' => $count,
            'preview' => $preview
        );
    }
}

new Wallet_Checker_Admin();
