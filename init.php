<?php
/*
Plugin Name: Admin Email Notifications
Plugin URI: http://nouveauframework.com/plugins/
Description: Allows admins to control which email notifications they want to receive from their WordPress site.
Version: 0.1
Author: Matt Van Andel
Author URI: http://mattstoolbox.com/
License: GPLv2 or later
*/

//Initialize...
MT_Admin_Email_Settings::init();

/**
 * Core logic for this plugin.
 */
class MT_Admin_Email_Settings {

    /**
     * Initialize hooks
     */
    public static function init(){
        //Check for pluggable conflicts and alert if needed
        if ( function_exists('wp_password_change_notification') || function_exists('wp_new_user_notification') ) {
            add_action('admin_notices', array(__CLASS__,'conflictAlert') );
        }

        //Load new pluggable functions
        require_once 'includes/pluggable.php';

        //Ensure the new settings are added to the database
        register_activation_hook( __FILE__, array(__CLASS__,'activatePlugin') );

        //Ensure the notifications page is created
        add_action( 'admin_menu', array(__CLASS__,'menus') );
    }

    /**
     * Set up any default values needed for theme options. If a default value
     * is needed, it can be provided as a second parameter. This will NOT
     * overwrite any existing options with these names.
     */
    public static function activatePlugin(){
        add_option('register_notify', true); //Setting for registration notifications to admins
        add_option('password_notify', true); //Setting for password reset notifications to admins
    }

    /**
     * Allows customization of WordPress menus.
     */
    public static function menus(){
        //Add new "Notifications" setting page (works like above, but no need to specify parent)
        add_options_page(
            __( 'Notifications', 'email-notifications' ), //Text to use in <title>
            __( 'Notifications', 'email-notifications' ), //Text to use in the menu
            'manage_options', //What capability is needed to see this menu item?
            'notifications', //What to use as the menu slug (unique id for this admin page)
            array(__CLASS__,'renderNotificationScreen') //Callback to render the admin screen itself
        );
    }

    /**
     * Renders the notification admin screen by requiring the admin screen file.
     */
    public static function renderNotificationScreen(){
        //We have this in a separate file to keep code neat and clean
        require_once 'templates/admin-screen.php';
    }

    /**
     * This is triggered if there is a conflict.
     */
    public static function conflictAlert(){
        echo '<div class="notice updated"><p>'.__('<b>Notice:</b> The Admin Email Notifications plugin may have been pre-empted by another plugin. Your email notification settings might be ignored.','email-notifications').'</p></div>';
    }

    /**
     * Allows help text to be added to admin
     *
     * @see add_action('admin_head', ... )
     * @global WP_Screen $current_screen Information about the current admin screen
     */
    public static function help() {
        global $wp_meta_boxes;
        $current_screen = get_current_screen();

        //Add new help text
        switch ( $current_screen->id ) {

            case 'settings_page_notifications':
                $current_screen->remove_help_tabs();
                $current_screen->add_help_tab( array(
                    'id'      => 'overview',
                    'title'   => __( 'Overview', 'email-notifications' ),
                    'content' => '<p>' . __( 'You can choose specifically which types of events you want to emailed about.', 'email-notifications' ) . '</p>' .
                        '<p>' . __( 'Check a box to receive an email about that event, or uncheck it if you do <em>not</em> want to receive any emails for that event.', 'email-notifications' ) . '</p>',
                ) );
                $current_screen->set_help_sidebar(
                    '<p><strong>' . __( 'For more information:', 'email-notifications' ) . '</strong></p>' .
                        '<p>' . __( '<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>' ) . '</p>'
                );
                break;

            default:
                break;
        }
    }

}

