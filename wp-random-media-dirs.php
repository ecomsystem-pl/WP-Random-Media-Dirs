<?php
/**
 * @wordpress-plugin
 * Plugin Name:       WP Random Media Dirs
 * Plugin URI:        https://ecomsystem.pl/
 * Description:       Organizes uploaded media files into random numbered directories (1-99/1-99) instead of date-based folders. Prevents directory overloading and hides site creation date.
 * Version:           1.01
 * Author:            Ecom System
 * Author URI:        https://ecomsystem.pl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
  */

// Add checkbox for file organization options
function RD_media_settings_field() {
    $options = get_option('RD_media_options');
    ?>
    <label>
        <input type="checkbox" name="RD_media_options[enable_random_media]" value="1" <?php checked(1, isset($options['enable_random_media']) ? $options['enable_random_media'] : 0); ?> id="RD_enable_random_media" />
        Organize my uploads into random /[1-99]/[1-99]/ folders
    </label>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Function to toggle visibility of WordPress default option
            function toggleDefaultOption() {
                if ($('#RD_enable_random_media').is(':checked')) {
                    // Hide the WordPress default option when our option is checked
                    $('label[for="uploads_use_yearmonth_folders"]').parent().css('opacity', '0.3');
                    $('label[for="uploads_use_yearmonth_folders"]').parent().css('pointer-events', 'none');
                    // Uncheck WordPress default option
                    $('#uploads_use_yearmonth_folders').prop('checked', false);
                } else {
                    // Show the WordPress default option when our option is unchecked
                    $('label[for="uploads_use_yearmonth_folders"]').parent().css('opacity', '1');
                    $('label[for="uploads_use_yearmonth_folders"]').parent().css('pointer-events', 'auto');
                }
            }

            // Initial run on page load
            toggleDefaultOption();

            // Toggle when our checkbox is clicked
            $('#RD_enable_random_media').change(function() {
                toggleDefaultOption();
            });
        });
    </script>
    <?php
}

// Add section and field to media settings
function RD_media_settings_section() {
    ?>
    <div id="RD-media-settings">
        <?php RD_media_settings_field(); ?>
    </div>
    <?php
}

// Add option to media settings
function RD_add_media_settings_option() {
    add_settings_section('RD_media_section', 'Random Media Directory', 'RD_media_settings_section', 'media');
    register_setting('media', 'RD_media_options', 'RD_sanitize_random_media_options');
}
add_action('admin_init', 'RD_add_media_settings_option');

// Enqueue jQuery if not already loaded
function RD_enqueue_admin_scripts($hook) {
    if ('options-media.php' === $hook) {
        wp_enqueue_script('jquery');
    }
}
add_action('admin_enqueue_scripts', 'RD_enqueue_admin_scripts');

// Sanitize options
function RD_sanitize_random_media_options($input) {
    $output = array();
    if(isset($input['enable_random_media'])) {
        $output['enable_random_media'] = 1;
        
        // When random media is enabled, disable WordPress default organization
        update_option('uploads_use_yearmonth_folders', 0);
    } else {
        $output['enable_random_media'] = 0;
    }
    return $output;
}

// Alternative approach using wp_handle_upload_prefilter
function RD_set_upload_directory($file) {
    $options = get_option('RD_media_options');
    
    if ($options && isset($options['enable_random_media']) && $options['enable_random_media'] == 1) {
        // This hook is called during file upload, so we know it's a real upload
        add_filter('upload_dir', 'RD_force_random_upload_directory');
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'RD_set_upload_directory');

function RD_force_random_upload_directory($dirs) {
    // Generate random numbers for directory structure
    $first_dir = str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);
    $second_dir = str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT);
    $random_path = '/' . $first_dir . '/' . $second_dir;
    
    $dirs['subdir'] = $random_path;
    $dirs['path'] = $dirs['basedir'] . $random_path;
    $dirs['url'] = $dirs['baseurl'] . $random_path;
    
    // Remove this filter after use to avoid affecting other calls
    remove_filter('upload_dir', 'RD_force_random_upload_directory');
    
    return $dirs;
}
