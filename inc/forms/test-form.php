<?php
if (isset($_POST['swpsmtpcf_to'])) {
    if (is_email($_POST['swpsmtpcf_to'])) {
        $swpsmtpcf_to = $_POST['swpsmtpcf_to'];
    } else {
        $error .= " " . __("Please enter a valid email address in the 'FROM' field.", 'easy_wp_smtp_cf');
    }
}
$swpsmtpcf_subject = isset($_POST['swpsmtpcf_subject']) ? $_POST['swpsmtpcf_subject'] : '';
$swpsmtpcf_message = isset($_POST['swpsmtpcf_message']) ? $_POST['swpsmtpcf_message'] : '';
if (!empty($swpsmtpcf_to))
    //todo: rename swpsmtpcf_test_mail to swpsmtpcf_mail
    $result = $this->swpsmtpcf_test_mail($swpsmtpcf_to, $swpsmtpcf_subject, $swpsmtpcf_message);