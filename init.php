<?php
/**
 * Admin Controller
 *
 * Plugin Name: Admin Controller
 * Plugin URI:  https://wordpress.org/plugins/classic-editor/
 * Description: Enables the WordPress Admin Controller
 * Version:     1.0.0
 * Author:      autocircle
 * Author URI:  https://github.com/autocircled/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: ac-admin-controller
 * Domain Path: /languages
 * Requires at least: 4.9
 * Requires PHP: 5.2.4
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ( ! defined( 'ABSPATH' ) )
{
	die( 'Invalid request.' );
}

if ( ! class_exists( 'AC_Admin_Controller' ) ) :
class AC_Admin_Controller {
    private function __construct() {}

    public static function init_actions()
    {
        add_action('admin_init', [ __CLASS__, 'disable_file_edit']);
        add_action('admin_init', [ __CLASS__, 'settings_init']);
        add_action('admin_menu', [ __CLASS__, 'admin_settings_menu']);
    }

    public static function disable_file_edit()
    {
        $ac_options = get_option('ac_ac_enable_super_power');
        if (is_array($ac_options) && isset($ac_options['theme_editor']) && $ac_options['theme_editor'] == 1){
            define( 'DISALLOW_FILE_EDIT', false );
            define( 'DISALLOW_FILE_MODS', true );
        }
    }

    public static function admin_settings_menu()
    {
        add_options_page(
            'Admin Controller',        // Page title
            'Advanced',             // Menu title
            'manage_options',              // Capability
            'admin-controller',        // Menu slug
            [ __CLASS__, 'admin_controller_callback' ] // Callback function
        );
    }

    public static function admin_controller_callback()
    {
        ?>
        <style>
            @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);

            html, body {
                height: 100%;
                font-size: 15px;
                width: 350px;
            }
            html, body {
                font-family: "Open Sans", "Helvetica Neue", Arial, Helvetica, sans-serif;
            }
        </style>
        <div class="wrap">
            <h1>Super Admin Controller</h1>
            <?php
            $ac_options = get_option('ac_ac_enable_super_power');
            $is_enabled = false;
            if (is_array($ac_options) && isset($ac_options['theme_editor']) && $ac_options['theme_editor'] == 1){
                $is_enabled = true;
            }
            if ($is_enabled) {
                ?>
                <div class="notice notice-success"> 
                    <p><strong>Super power not enabled</strong></p>
                </div>
                <?php
            } else {
                ?>
                <div class="notice notice-error"> 
                    <p><strong>Super Power Enabled</strong></p>
                </div>
                <?php
            }
            ?>
            <div class="ui bottom attached segment">
                <p><strong>WARNING:</strong> Editing a theme via WordPress's file editor is <strong>very dangerious!</strong></p>
                <p>Modifying a theme's files without knowing the proper programming languages can often lead to your site going down.
                If you <i>must</i> edit this theme's files, it is highly recommended that you use an IDE.</p>
            </div>
            <form method="post" action="options.php">
                <?php
                // Output security fields for the registered setting
                settings_fields('ac_admin_controller_settings');
                
                // Output setting sections and their fields
                do_settings_sections('admin-controller');
                
                // Output save settings button
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    public static function settings_init()
    {
        register_setting('ac_admin_controller_settings', 'ac_ac_enable_super_power');
        add_settings_section(
            'super_power_section',    // Section ID
            'Super Administrative',    // Section title
            [ __CLASS__, 'super_power_section_callback' ], // Section callback
            'admin-controller'        // Page slug
        );

        add_settings_field(
            'ac_ac_enable_super_power',      // Field ID
            'Enable Theme Plugin Editing',              // Field title
            [ __CLASS__, 'enable_super_power_callback'], // Field callback
            'admin-controller',       // Page slug
            'super_power_section'     // Section ID
        );
    }

    public static function super_power_section_callback() {}
    public static function enable_super_power_callback() {
        $ac_options = get_option('ac_ac_enable_super_power');
        // pretify($ac_options);
        $is_enabled = false;
        if (is_array($ac_options) && isset($ac_options['theme_editor']) && $ac_options['theme_editor'] == 1){
            $is_enabled = true;
        }
        
        ?>
        <div class="field">
            <div class="ui toggle checkbox">
                <input type="checkbox" value="1" name="ac_ac_enable_super_power[theme_editor]" <?php echo $is_enabled ? 'checked' : ''; ?>>
                <label><?php echo $is_enabled ? "Uncheck to enable" : "Check to disable"?></label>
            </div>
        </div>
        <?php
    }

    
}

add_action( 'plugins_loaded', array( 'AC_Admin_Controller', 'init_actions' ) );

endif;