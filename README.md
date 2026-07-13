# Wallet Checker - Eligibility Checker WordPress Plugin

A WordPress plugin to check if Ethereum wallets are eligible based on a CSV whitelist.

## Features

- ✅ **CSV-Based Eligibility** - Upload CSV file with eligible wallet addresses
- ✅ **Quick Lookup** - Instant eligibility check (no API calls)
- ✅ **Address Validation** - Automatic validation of Ethereum addresses
- ✅ **Visual Feedback** - Green badge for eligible, red badge for not eligible
- ✅ **Responsive Design** - Works perfectly on mobile and desktop
- ✅ **Secure** - AJAX with nonce verification, input sanitization
- ✅ **Easy Management** - Admin panel to upload and preview CSV files

## Installation

1. Download the plugin files
2. Upload to `/wp-content/plugins/wallet-checker/`
3. Activate the plugin from WordPress Admin Dashboard
4. Go to **Admin Panel > Wallet Checker** and upload your CSV file
5. Add the shortcode to any page or post

## Setup Guide

### Step 1: Prepare Your CSV File

Create a CSV file with Ethereum addresses in the first column, one address per row:

```
0x1f9090aae28b8a3dceadf46b155ced7ee7ff7a07
0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb
0xd8dA6BF26964aF9D7eEd9e03E53415D37AA96045
```

**CSV Format:**
- One Ethereum address per row
- Address in first column (0x followed by 40 hex characters)
- Can have additional columns (will be ignored)
- No header row needed (but can include one)

### Step 2: Upload CSV File

1. Go to WordPress Admin Panel
2. Navigate to **Wallet Checker** in the left menu
3. Click **Upload CSV File** button
4. Select your CSV file
5. Click **Upload CSV File** button

The previous CSV file will be automatically replaced.

### Step 3: Add to Your Website

Add this shortcode to any page or post:

```
[wallet_checker]
```

## Usage

### Basic Shortcode

```
[wallet_checker]
```

Just add this to any page or post where you want users to check wallet eligibility.

### How Users Use It

1. Enter an Ethereum wallet address (starting with 0x)
2. Click "Check Eligibility"
3. See if wallet is eligible (✓ ELIGIBLE) or not (✗ NOT ELIGIBLE)

## CSV File Examples

### Minimal CSV
```
0x1f9090aae28b8a3dceadf46b155ced7ee7ff7a07
0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb
0xd8dA6BF26964aF9D7eEd9e03E53415D37AA96045
```

### CSV with Additional Data (extra columns ignored)
```
address,name,points
0x1f9090aae28b8a3dceadf46b155ced7ee7ff7a07,Vitalik,1000
0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb,Alice,500
0xd8dA6BF26964aF9D7eEd9e03E53415D37AA96045,Bob,750
```

### CSV with Header Row
```
address
0x1f9090aae28b8a3dceadf46b155ced7ee7ff7a07
0x742d35Cc6634C0532925a3b844Bc9e7595f0bEb
0xd8dA6BF26964aF9D7eEd9e03E53415D37AA96045
```

## Admin Panel

The admin panel shows:

- **Upload Section** - Upload new CSV file
- **Current File Info** - Shows filename, total addresses, upload date, file size
- **Preview** - Shows first 10 addresses from CSV
- **Shortcode Usage** - Copy-paste shortcode for pages

## Features

### Real-Time Checking
- Addresses checked instantly against uploaded CSV
- No API calls required
- Works offline
- Lightning fast results

### Address Validation
- Validates Ethereum address format
- Must start with `0x`
- Must be exactly 42 characters long
- Shows error for invalid formats

### Privacy
- No external API calls
- No tracking
- Addresses not logged or stored (except in your CSV)
- Completely self-contained

## Security

✅ **AJAX Protection** - All requests use WordPress nonces  
✅ **Input Validation** - Address format verified  
✅ **Input Sanitization** - All user input sanitized  
✅ **File Security** - CSV uploaded to secure directory  
✅ **Case Insensitive** - Works regardless of address case  

## Troubleshooting

### "Invalid Ethereum address format"
- Address must start with `0x`
- Address must be exactly 42 characters long
- Example: `0x1234567890123456789012345678901234567890`

### No CSV file uploaded
1. Go to **Admin Panel > Wallet Checker**
2. Upload a CSV file with eligible addresses
3. Use the preview to verify addresses loaded correctly

### Checking always shows "NOT ELIGIBLE"
1. Verify addresses in CSV file are correct format
2. Check that addresses in CSV start with lowercase or match case
3. Re-upload CSV file and refresh

### Can't upload CSV file
1. Ensure file is in `.csv` format
2. Check file is not corrupted
3. Check WordPress upload directory has write permissions

## Database

This plugin does NOT store sensitive data. Only reference info:
- CSV filename
- Upload timestamp
- Number of addresses

## Performance

- **No Database Queries** - CSV stored in file system
- **Fast Lookup** - Linear search through CSV
- **Scalable** - Works with thousands of addresses
- **Low Memory** - Streams through CSV file

## API

No external APIs required. All checking is local.

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- jQuery (usually included in WordPress)

## FAQ

**Q: How many addresses can I have in the CSV?**  
A: Thousands! No limit, but larger files take slightly longer to search.

**Q: Is the wallet address case-sensitive?**  
A: No, addresses are converted to lowercase for comparison.

**Q: Can I have duplicate addresses in CSV?**  
A: Yes, they'll still work. First match is used.

**Q: What if I upload a new CSV?**  
A: The old CSV is automatically deleted and replaced.

**Q: Can I have extra columns in the CSV?**  
A: Yes! Only the first column (address) is used.

**Q: Is this secure?**  
A: Yes! All AJAX requests are protected with nonces, and input is validated.

## Support

For issues or suggestions, please contact support.

## License

GPL v2 or later

## Changelog

### Version 1.0.0
- Initial release
- CSV file upload
- Wallet eligibility checking
- Admin management panel
- Responsive design
- Address validation

---

**Made for community airdrops and whitelists! 🚀**