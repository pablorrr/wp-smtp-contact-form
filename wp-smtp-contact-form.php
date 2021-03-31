<?php
/**
 * Plugin Name: WP SMTP Contact Form
 * Version: 2.0.0
 * Plugin URI:
 * Author: Paweł Kalisz
 * Author URI: https://websitecreator.cba.pl
 * Description: SMTP Settings and send email from any page via shortcode
 */


use Inc\WP_SMTP_Contact_Form;
use Inc\WP_SMTP_Contact_Form_Updater;


if (!defined('ABSPATH') || !defined('WPINC')) exit;


require_once __DIR__ . '/vendor/autoload.php';
require_once(__DIR__ . '/inc/WP_SMTP_Contact_Form.php');
function Run_WP_SMTP_Contact_Form(): WP_SMTP_Contact_Form
{
    return WP_SMTP_Contact_Form::instance();
}

Run_WP_SMTP_Contact_Form();
//todo:: integrate versioning mechanism with ProductCreator_Updater classs
//Run WP Updater for plugin version
$updater = new WP_SMTP_Contact_Form_Updater(__FILE__);
$updater->set_username('pablorrr');
$updater->set_repository('wp-smtp-contact-form');
$updater->initialize();

