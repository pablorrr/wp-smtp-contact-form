<?php
//todo:: split into two part files - up[date settings and test send
use Controller\WP_SMTP_Contact_Form_Controller;

$display_add_options = $message = $error = $result = '';
$swpsmtpcf_options = get_option('swpsmtpcf_options');
$plugin_basename = plugin_basename(__FILE__);

/* Update settings */
if (isset($_POST['swpsmtpcf_form_submit']) && check_admin_referer($plugin_basename, 'swpsmtpcf_nonce_name')) {
    require_once(WP_SMTP_Contact_Form_Controller::path_form('settings-form.php'));
}
ob_start(); ?>
    <div class="swpsmtpcf-mail wrap" id="swpsmtpcf-mail">
        <div id="icon-options-general" class="icon32 icon32-bws"></div>
        <h2><?php _e("WP SMTP Settings CF", 'easy_wp_smtp_cf'); ?></h2>
        <div class="update-nag">Please visit the <a target="_blank"
                                                    href="https://wp-ecommerce.net/easy-wordpress-smtp-send-emails-from-your-wordpress-site-using-a-smtp-server-2197">Easy
                WP SMTP CF</a> documentation page for usage instructions.
        </div>
        <div class="updated fade" <?php if (empty($message)) echo "style=\"display:none\""; ?>>
            <p><strong><?php echo $message; ?></strong></p>
        </div>
        <div class="error" <?php if (empty($error)) echo "style=\"display:none\""; ?>>
            <p><strong><?php echo $error; ?></strong></p>
        </div>
        <div id="swpsmtpcf-settings-notice" class="updated fade" style="display:none">
            <p>
                <strong><?php _e("Notice:", 'easy_wp_smtp_cf'); ?></strong> <?php _e("The plugin's settings have been changed. In order to save them please don't forget to click the 'Save Changes' button.", 'easy_wp_smtp_cf'); ?>
            </p>
        </div>
        <!--General Settings Form -->
        <h3><?php _e('General Settings', 'easy_wp_smtp_cf'); ?></h3>
        <form id="swpsmtpcf_settings_form" method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e("From Email Address", 'easy_wp_smtp_cf'); ?></th>
                    <td>
                        <input type="text" name="swpsmtpcf_from_email"
                               value="<?php echo esc_attr($swpsmtpcf_options['from_email_field']); ?>"/><br/>
                        <span class="swpsmtpcf_info"><?php _e("This email address will be used in the 'From' field.", 'easy_wp_smtp_cf'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e("From Name", 'easy_wp_smtp_cf'); ?></th>
                    <td>
                        <input type="text" name="swpsmtpcf_from_name"
                               value="<?php echo esc_attr($swpsmtpcf_options['from_name_field']); ?>"/><br/>
                        <span class="swpsmtpcf_info"><?php _e("This text will be used in the 'FROM' field", 'easy_wp_smtp_cf'); ?></span>
                    </td>
                </tr>
                <tr class="ad_opt swpsmtpcf_smtp_options">
                    <th><?php _e('SMTP Host', 'easy_wp_smtp_cf'); ?></th>
                    <td>
                        <input type='text' name='swpsmtpcf_smtp_host'
                               value='<?php echo esc_attr($swpsmtpcf_options['smtpcf_settings']['host']); ?>'/><br/>
                        <span class="swpsmtpcf_info"><?php _e("Your mail server", 'easy_wp_smtp_cf'); ?></span>
                    </td>
                </tr>
                <tr class="ad_opt swpsmtpcf_smtp_options">
                    <th><?php _e('Type of Encription', 'easy_wp_smtp_cf'); ?></th>
                    <td>
                        <label for="swpsmtpcf_smtp_type_encryption_1"><input type="radio"
                                                                             id="swpsmtpcf_smtp_type_encryption_1"
                                                                             name="swpsmtpcf_smtp_type_encryption"
                                                                             value='none' <?php if ('none' == $swpsmtpcf_options['smtpcf_settings']['type_encryption']) echo 'checked="checked"'; ?> /> <?php _e('None', 'easy_wp_smtp_cf'); ?>
                        </label>
                        <label for="swpsmtpcf_smtp_type_encryption_2"><input type="radio"
                                                                             id="swpsmtpcf_smtp_type_encryption_2"
                                                                             name="swpsmtpcf_smtp_type_encryption"
                                                                             value='ssl' <?php if ('ssl' == $swpsmtpcf_options['smtpcf_settings']['type_encryption']) echo 'checked="checked"'; ?> /> <?php _e('SSL', 'easy_wp_smtp_cf'); ?>
                        </label>
                        <label for="swpsmtpcf_smtp_type_encryption_3"><input type="radio"
                                                                             id="swpsmtpcf_smtp_type_encryption_3"
                                                                             name="swpsmtpcf_smtp_type_encryption"
                                                                             value='tls' <?php if ('tls' == $swpsmtpcf_options['smtpcf_settings']['type_encryption']) echo 'checked="checked"'; ?> /> <?php _e('TLS', 'easy_wp_smtp_cf'); ?>
                        </label><br/>
                        <span class="swpsmtpcf_info"><?php _e("For most servers SSL is the recommended option", 'easy_wp_smtp_cf'); ?></span>
                    </td>
                </tr>
                <tr class="ad_opt swpsmtpcf_smtp_options">
                    <th><?php _e('SMTP Port', 'easy_wp_smtp_cf'); ?></th>
                    <td>
                        <input type='text' name='swpsmtpcf_smtp_port'
                               value='<?php echo esc_attr($swpsmtpcf_options['smtpcf_settings']['port']); ?>'/><br/>
                        <span class="swpsmtpcf_info"><?php _e("The port to your mail server", 'easy_wp_smtp_cf'); ?></span>
                    </td>
                </tr>
                <tr class="ad_opt swpsmtpcf_smtp_options">
                    <th><?php _e('SMTP Authentication', 'easy_wp_smtp_cf'); ?></th>
                    <td>
                        <label for="swpsmtpcf_smtp_autentication"><input type="radio"
                                                                         id="swpsmtpcf_smtp_autentication"
                                                                         name="swpsmtpcf_smtp_autentication"
                                                                         value='no' <?php if ('no' == $swpsmtpcf_options['smtpcf_settings']['autentication']) echo 'checked="checked"'; ?> /> <?php _e('No', 'easy_wp_smtp_cf'); ?>
                        </label>
                        <label for="swpsmtpcf_smtp_autentication"><input type="radio"
                                                                         id="swpsmtpcf_smtp_autentication"
                                                                         name="swpsmtpcf_smtp_autentication"
                                                                         value='yes' <?php if ('yes' == $swpsmtpcf_options['smtpcf_settings']['autentication']) echo 'checked="checked"'; ?> /> <?php _e('Yes', 'easy_wp_smtp_cf'); ?>
                        </label><br/>
                        <span class="swpsmtpcf_info"><?php _e("This options should always be checked 'Yes'", 'easy_wp_smtp_cf'); ?></span>
                    </td>
                </tr>
                <tr class="ad_opt swpsmtpcf_smtp_options">
                    <th><?php _e('SMTP username', 'easy_wp_smtp_cf'); ?></th>
                    <td>
                        <input type='text' name='swpsmtpcf_smtp_username'
                               value='<?php echo $this->swpsmtpcf_get_user_name(); ?>'/><br/>
                        <span class="swpsmtpcf_info"><?php _e("The username to login to your mail server", 'easy_wp_smtp_cf'); ?></span>
                    </td>
                </tr>
                <tr class="ad_opt swpsmtpcf_smtp_options">
                    <th><?php _e('SMTP Password', 'easy_wp_smtp_cf'); ?></th>
                    <td>
                        <input type='password' name='swpsmtpcf_smtp_password'
                               value='<?php echo $this->swpsmtpcf_get_password(); ?>'/><br/>
                        <span class="swpsmtpcf_info"><?php _e("The password to login to your mail server", 'easy_wp_smtp_cf'); ?></span>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" id="settings-form-submit" class="button-primary"
                       value="<?php _e('Save Changes', 'easy_wp_smtp_cf') ?>"/>
                <input type="hidden" name="swpsmtpcf_form_submit" value="submit"/>
                <?php wp_nonce_field(plugin_basename(__FILE__), 'swpsmtpcf_nonce_name'); ?>
            </p>
        </form>

        <div class="updated fade" <?php if (empty($result)) echo "style=\"display:none\""; ?>>
            <p><strong><?php echo $result; ?></strong></p>
        </div>

    </div><!--  #swpsmtp-mail .swpsmtp-mail -->