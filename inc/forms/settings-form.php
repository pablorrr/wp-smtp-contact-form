<?php
/* Update settings */

$swpsmtpcf_options['from_name_field'] = isset($_POST['swpsmtpcf_from_name']) ? sanitize_text_field(wp_unslash($_POST['swpsmtpcf_from_name'])) : '';
if (isset($_POST['swpsmtpcf_from_email'])) {
    if (is_email($_POST['swpsmtpcf_from_email'])) {
        $swpsmtpcf_options['from_email_field'] = $_POST['swpsmtpcf_from_email'];
    } else {
        $error .= " " . __("Please enter a valid email address in the 'FROM' field.", 'easy_wp_smtp_cf');
    }
}


$swpsmtpcf_options['smtpcf_settings']['host'] = sanitize_text_field($_POST['swpsmtpcf_smtp_host']);
$swpsmtpcf_options['smtpcf_settings']['type_encryption'] = (isset($_POST['swpsmtpcf_smtp_type_encryption'])) ? $_POST['swpsmtpcf_smtp_type_encryption'] : 'none';
$swpsmtpcf_options['smtpcf_settings']['autentication'] = (isset($_POST['swpsmtpcf_smtp_autentication'])) ? $_POST['swpsmtpcf_smtp_autentication'] : 'yes';
$swpsmtpcf_options['smtpcf_settings']['username'] = sanitize_text_field($_POST['swpsmtpcf_smtp_username']);
$smtpcf_password = trim($_POST['swpsmtpcf_smtp_password']);
$swpsmtpcf_options['smtpcf_settings']['password'] = base64_encode($smtpcf_password);


if (isset($_POST['swpsmtpcf_smtp_port'])) {
    if (empty($_POST['swpsmtpcf_smtp_port']) || 1 > intval($_POST['swpsmtpcf_smtp_port']) || (!preg_match('/^\d+$/', $_POST['swpsmtpcf_smtp_port']))) {
        $swpsmtpcf_options['smtpcf_settings']['port'] = '25';
        $error .= " " . __("Please enter a valid port in the 'SMTP Port' field.", 'easy_wp_smtp_cf');
    } else {
        $swpsmtpcf_options['smtpcf_settings']['port'] = $_POST['swpsmtpcf_smtp_port'];
    }
}


if (empty($error)) {
    update_option('swpsmtpcf_options', $swpsmtpcf_options);
    $message .= __("Settings saved.", 'easy_wp_smtp_cf');
} else {
    $error .= " " . __("Settings are not saved.", 'easy_wp_smtp_cf');
}
