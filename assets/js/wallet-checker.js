jQuery(document).ready(function($) {
    $('#check-wallet-btn').on('click', function(e) {
        e.preventDefault();
        
        var walletAddress = $('#wallet-address').val().trim();
        
        if (!walletAddress) {
            showError('Please enter a wallet address');
            return;
        }
        
        // Validate address format
        if (!/^0x[a-fA-F0-9]{40}$/.test(walletAddress)) {
            showError('Invalid Ethereum address format. Address should start with 0x and be 42 characters long.');
            return;
        }
        
        $(this).prop('disabled', true).text('Checking...');
        $('#wallet-error').hide();
        $('#wallet-result').hide();
        
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
                        $('#result-address').text(data.address);
                        $('#result-eligible').fadeIn();
                        $('#result-not-eligible').hide();
                    } else {
                        $('#result-address-not').text(data.address);
                        $('#result-not-eligible').fadeIn();
                        $('#result-eligible').hide();
                    }
                    
                    $('#wallet-result').fadeIn();
                } else {
                    showError(response.data);
                }
            },
            error: function() {
                showError('An error occurred while checking the wallet.');
            },
            complete: function() {
                $('#check-wallet-btn').prop('disabled', false).text('Check Eligibility');
            }
        });
    });
    
    // Allow Enter key to trigger check
    $('#wallet-address').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            $('#check-wallet-btn').click();
        }
    });
    
    function showError(message) {
        $('#wallet-error').text(message).fadeIn();
        $('#wallet-result').hide();
    }
});