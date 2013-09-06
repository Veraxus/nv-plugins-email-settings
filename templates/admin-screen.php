<?php
    /**
     * This is an admin-page template file that controls the new WordPress Admin Notifications screen.
     */

    if ( ! current_user_can('manage_options') ){
        wp_die( __( 'Cheatin&#8217; uh?' ) );
    }

    $message = false;

    if ( isset($_REQUEST['action']) && 'update'===$_REQUEST['action'] )
    {
        if ( ! wp_verify_nonce($_REQUEST['_wpnonce'],'notification_nonce') || ! current_user_can('manage_options') )
        {
            die(__('Cheatin&#8217; uh?'));
        }

        //START SAVE
        update_option('register_notify',    isset($_REQUEST['register_notify']) );
        update_option('password_notify',    isset($_REQUEST['password_notify']) );
        update_option('comments_notify',    isset($_REQUEST['comments_notify']) );
        update_option('moderation_notify',  isset($_REQUEST['moderation_notify']) );
        update_option('moderation_notify',  $_REQUEST['admin_email'] );
        //END SAVE

        $message = 'updated';
    }

?>
<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div><h2><?php _e('Email Notification Settings','email-notifications'); ?></h2>

    <!-- message controls -->
    <?php
    switch( $message )
    {
        case 'updated':
            echo '<div id="message-generic" class="updated"><p><b>'.__('Settings saved.','email-notifications') . '</b></p></div>';
            break;
        default:break;
    }
    ?>
    <!-- /message controls -->
    <p><?php _e('Use these settings to control when administrators should be sent notification emails.','email-notifications'); ?></p>
    <form action="options-general.php?page=notifications" method="post">
        <?php //settings_fields('email-notifications_notification_settings'); ?>
        <?php wp_nonce_field('notification_nonce'); ?>
        <input type="hidden" name="action" value="update" />
        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <label for="admin_email"><?php _e('E-mail Address','email-notifications') ?></label>
                </th>
                <td>
                    <input type="text" name="admin_email" id="admin_email" value="<?php echo get_option('admin_email') ?>" /> <p class="description"><?php _e('This is the address where you would like to receive notifications','email-notifications') ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="register_notify"><?php _e('Registrations','email-notifications') ?></label>
                </th>
                <td>
                    <input type="checkbox" name="register_notify" id="register_notify" <?php checked(get_option('register_notify',true)) ?> /> <label for="register_notify"><?php _e('Email me when anyone registers','email-notifications') ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="password_notify"><?php _e('Password Resets','email-notifications') ?></label>
                </th>
                <td>
                    <input type="checkbox" name="password_notify" id="password_notify" <?php checked(get_option('password_notify',true)) ?> /> <label for="password_notify"><?php _e('Email me when anyone resets their password','email-notifications') ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="comments_notify"><?php _e('Comments','email-notifications') ?></label>
                </th>
                <td>
                    <input type="checkbox" name="comments_notify" id="comments_notify" <?php checked(get_option('comments_notify')) ?> /> <label for="comments_notify"><?php _e('Email me when anyone posts a comment','email-notifications') ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="moderation_notify"><?php _e('Moderation','email-notifications') ?></label>
                </th>
                <td>
                    <input type="checkbox" name="moderation_notify" id="moderation_notify" <?php checked(get_option('moderation_notify')) ?> /> <label for="moderation_notify"><?php _e('Email me when a comment is held for moderation','email-notifications') ?></label>
                </td>
            </tr>
        </table>
        <p>
            <button name="notifications_submit" type="submit" class="button-primary"><?php _e('Save Changes', 'email-notifications'); ?></button>
        </p>
    </form>

</div>