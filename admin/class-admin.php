<?php

class Wallet_Checker_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
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
    
    public function render_settings_page() {
        // Handle file upload
        if (isset($_POST['submit']) && isset($_FILES['csv_file']) && wp_verify_nonce($_POST['_wpnonce'], 'wallet_checker_nonce')) {
            $this->handle_csv_upload($_FILES['csv_file']);
        }
        
        $current_file = $this->get_current_csv_file();
        $file_info = $current_file ? $this->get_csv_info($current_file) : null;
        ?>
        <div class="wrap">
            <h1>Wallet Checker - CSV Management</h1>
            
            <div class="card" style="margin-top: 20px; padding: 20px; max-width: 600px;">
                <h2>Upload Eligibility CSV</h2>
                <form method="post" enctype="multipart/form-data">
                    <?php wp_nonce_field('wallet_checker_nonce'); ?>
                    
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
                <div style="background: white; padding: 15px; border-radius: 4px; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                    <?php echo esc_html($file_info['preview']); ?>
                </div>
            </div>
            <?php else: ?>
            <div class="notice notice-warning" style="margin-top: 20px;">
                <p>No CSV file uploaded yet. Upload a CSV file to start checking wallet eligibility.</p>
            </div>
            <?php endif; ?>
            
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
    
    private function handle_csv_upload($file) {
        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            wp_die('File upload error');
        }
        
        if ($file['type'] !== 'text/csv' && $file['type'] !== 'text/plain') {
            wp_die('Please upload a CSV file');
        }
        
        // Create uploads directory if doesn't exist
        if (!is_dir(WALLET_CHECKER_UPLOADS_DIR)) {
            wp_mkdir_p(WALLET_CHECKER_UPLOADS_DIR);
        }
        
        // Remove old CSV files
        $old_files = glob(WALLET_CHECKER_UPLOADS_DIR . '*.csv');
        foreach ($old_files as $old_file) {
            unlink($old_file);
        }
        
        // Save new file
        $filename = 'eligible-wallets-' . time() . '.csv';
        $destination = WALLET_CHECKER_UPLOADS_DIR . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Success message
            add_action('admin_notices', function() {
                ?>
                <div class="notice notice-success is-dismissible">
                    <p>CSV file uploaded successfully!</p>
                </div>
                <?php
            });
        } else {
            wp_die('Failed to save CSV file');
        }
    }
    
    private function get_current_csv_file() {
        $files = glob(WALLET_CHECKER_UPLOADS_DIR . '*.csv');
        return !empty($files) ? $files[0] : null;
    }
    
    private function get_csv_info($file_path) {
        $handle = fopen($file_path, 'r');
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