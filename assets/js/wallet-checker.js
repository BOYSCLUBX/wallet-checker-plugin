jQuery(document).ready(function($) {
    
    // Handle both shortcode and Elementor versions
    function initWalletChecker() {
        var $document = $(document);
        
        // For shortcode version
        $document.on('click', '#check-wallet-btn', function(e) {
            e.preventDefault();
            checkWalletEligibility($(this));
        });
        
        // For Elementor version
        $document.on('click', '.btn-check-elementor', function(e) {
            e.preventDefault();
            checkWalletEligibility($(this));
        });
        
        // Enter key for shortcode
        $document.on('keypress', '#wallet-address', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $('#check-wallet-btn').click();
            }
        });
        
        // Enter key for Elementor
        $document.on('keypress', '.wallet-address-elementor', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $(this).closest('.elementor-widget-wallet-checker').find('.btn-check-elementor').click();
            }
        });
    }
    
    function checkWalletEligibility($button) {
        var $container = $button.closest('.wallet-checker-container');
        var $input = $container.find('input[type="text"]').first();
        var walletAddress = $input.val().trim();
        
        if (!walletAddress) {
            showError($container, 'Please enter a wallet address');
            return;
        }
        
        // Validate address format
        if (!/^0x[a-fA-F0-9]{40}$/.test(walletAddress)) {
            showError($container, 'Invalid Ethereum address format. Address should start with 0x and be 42 characters long.');
            return;
        }
        
        $button.prop('disabled', true).text('Checking...');
        $container.find('.wallet-error').hide();
        $container.find('.wallet-result').hide();
        
        $.ajax({
            url: walletChecker.ajaxUrl,
            type: 'POST',
            data: {
                action: 'check_wallet_eligibility',
                wallet_address: walletAddress,
                nonce: walletChecker.nonce
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    
                    if (data.eligible) {
                        $container.find('.result-address').text(data.address);
                        $container.find('.result-eligible').fadeIn();
                        $container.find('.result-not-eligible').hide();
                    } else {
                        $container.find('.result-address-not').text(data.address);
                        $container.find('.result-not-eligible').fadeIn();
                        $container.find('.result-eligible').hide();
                    }
                    
                    $container.find('.wallet-result').fadeIn();
                } else {
                    showError($container, response.data);
                }
            },
            error: function() {
                showError($container, 'An error occurred while checking the wallet.');
            },
            complete: function() {
                var originalText = $button.closest('.wallet-checker-container').find('.btn-check, .btn-check-elementor').data('original-text') || 'Check Eligibility';
                $button.prop('disabled', false).text(originalText);
            }
        });
    }
    
    function showError($container, message) {
        $container.find('.wallet-error').text(message).fadeIn();
        $container.find('.wallet-result').hide();
    }
    
    // Initialize on document ready
    initWalletChecker();
    
    // Also handle Elementor's dynamic rendering
    if (window.elementorFrontend) {
        elementorFrontend.hooks.addAction('frontend/element_ready/wallet_checker_widget.default', function($scope, $) {
            // Re-initialize for newly added elements
            initWalletChecker();
        });
    }
});