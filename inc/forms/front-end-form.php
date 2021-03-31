<?php
use Controller\WP_SMTP_Contact_Form_Controller;
$mail_send = new WP_SMTP_Contact_Form_Controller();
if (is_email(get_bloginfo('admin_email'))) {
    $swpsmtpcf_to = get_bloginfo('admin_email');
} else {
    $error .= " " . __("Please enter a valid email address in the 'FROM' field.", 'easy_wp_smtp_cf');
}

$swpsmtpcf_subject = isset($_POST['form_subject']) ? $_POST['form_subject'] : '';
$swpsmtpcf_message = isset($_POST['form_message']) ? $_POST['form_message'] : '';
if (!empty($swpsmtpcf_to))

    $mail_send ->swpsmtpcf_test_mail($swpsmtpcf_to, $swpsmtpcf_subject, $swpsmtpcf_message);
