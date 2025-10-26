# WP Random Media Dirs

WordPress plugin that organizes uploaded media files into random numbered directories (1-99/1-99) instead of date-based folders.

## Description

This plugin solves two common problems:

1. **Directory Overloading**: When importing large numbers of products/media files, WordPress date-based folders can become overloaded with thousands of files in a single directory
2. **Privacy**: Hides your site's creation date by not using year/month folder structure

Instead of `/2025/01/filename.jpg`, files are organized into random directories like `/42/15/filename.jpg`.

## Features

- Organizes uploads into random `/1-99/1-99/` directory structure
- Automatically disables WordPress default year/month folders when enabled
- Simple checkbox option in Media Settings
- Works with all file uploads (media library, product imports, etc.)

## Installation

1. Download the plugin
2. Upload to `/wp-content/plugins/`
3. Activate in WordPress admin
4. Go to Settings → Media and check "Organize my uploads into random /[1-99]/[1-99]/ folders"

## Requirements

- WordPress 5.0+
- PHP 7.0+

## License

GPL v2

## Author

EcomSystem - https://ecomsystem.pl
