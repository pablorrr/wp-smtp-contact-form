<?php

//todo:: split into two part files - up[date settings and test send
use Controller\WP_SMTP_Contact_Form_Controller;

$display_add_options = $message = $error = $result = '';
$swpsmtpcf_options = get_option('swpsmtpcf_options');
$plugin_basename = plugin_basename(__FILE__);
//todo: add notice message "form sent properly" or "failed"

/* Send test letter*/
if (isset($_POST['swpsmtpcf_test_submit']) && check_admin_referer($plugin_basename, 'swpsmtpcf_nonce_name')) {
    require_once WP_SMTP_Contact_Form_Controller::path_form('test-form.php');
}
?>
<div class="swpsmtpcf-mail wrap" id="swpsmtpcf-mail">
    <h3><?php _e('Testing And Debugging Settings', 'easy_wp_smtp_cf'); ?></h3>
    <!--Test Form-->
    <form id="swpsmtpcf_settings_form" method="post" action="">
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e("To", 'easy_wp_smtp_cf'); ?>:</th>
                <td>
                    <input type="text" name="swpsmtpcf_to" value=""/><br/>
                    <span class="swpsmtpcf_info"><?php _e("Enter the email address to recipient", 'easy_wp_smtp_cf'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Subject", 'easy_wp_smtp_cf'); ?>:</th>
                <td>
                    <input type="text" name="swpsmtpcf_subject" value=""/><br/>
                    <span class="swpsmtpcf_info"><?php _e("Enter a subject for your message", 'easy_wp_smtp_cf'); ?></span>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e("Message", 'easy_wp_smtp_cf'); ?>:</th>
                <td>
                    <textarea name="swpsmtpcf_message" id="swpsmtpcf_message" rows="5"></textarea><br/>
                    <span class="swpsmtpcf_info"><?php _e("Write your message", 'easy_wp_smtp_cf'); ?></span>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" id="settings-form-submit" class="button-primary"
                   value="<?php _e('Send Test Email', 'easy_wp_smtp_cf') ?>"/>
            <input type="hidden" name="swpsmtpcf_test_submit" value="submit"/>
            <?php wp_nonce_field(plugin_basename(__FILE__), 'swpsmtpcf_nonce_name'); ?>
        </p>
    </form>
</div>
<?php
ob_end_flush();
?>

