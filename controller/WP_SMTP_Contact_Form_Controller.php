<?php

namespace Controller;

use Inc\WP_SMTP_Contact_Form_Help_Tab;
use Model\WP_SMTP_Contact_Form_Model;
use PHPMailer\PHPMailer\PHPMailer;


class WP_SMTP_Contact_Form_Controller
{
    //private static $_instance;
    private $model;
    private $help_tab;
    private static $plug_file = 'wp-smtp-contact-form.php';
    private static $plug_action_name;
// WP_SMTP_Contact_Form_Help_Tab::instance();

    /**
     * init plugin when all wp plugins loaded
     */

    public function __construct()
    {
        register_uninstall_hook(plugin_basename(__FILE__), array($this, 'swpsmtpcf_send_uninstall'));
        add_filter('plugin_action_links_' . self::swpsmtpcf_return_plug_name(), array($this, 'swpsmtpcf_plugin_action_links'), 10, 2);
        add_action('phpmailer_init', array($this, 'swpsmtpcf_init_smtp'));
        add_action('admin_menu', array($this, 'swpsmtpcf_admin_default_setup'));
        $this->model = new WP_SMTP_Contact_Form_Model();


        add_action('admin_enqueue_scripts', array($this, 'swpsmtpcf_admin_head'));
        add_action('admin_notices', array($this, 'swpsmtpcf_admin_notice'));
    }


    private static function swpsmtpcf_return_plug_name()
    {
        return self::$plug_action_name = trailingslashit(plugin_basename(plugin_dir_path(__DIR__))) . self::$plug_file;
    }

    public function swpsmtpcf_admin_default_setup()
    {
        $this->help_tab = WP_SMTP_Contact_Form_Help_Tab::instance();

        $WP_SMTP_Contact_Form_help_tab = add_menu_page(
            __('WP SMTP CF', 'wp_smtp_cf'),
            __('WP SMTP CF', 'wp_smtp_cf'),
            'manage_options',
            'swpsmtpcf_settings',
            [$this, 'swpsmtpcf_settings']);

        add_action('load-' . $WP_SMTP_Contact_Form_help_tab,
            [$this->help_tab, 'WP_SMTP_Contact_Form_help_tab']);

        add_submenu_page(
            'swpsmtpcf_settings',
            __('WP SMTP test', 'wp_smtp_cf'),
            __('WP SMTP test', 'wp_smtp_cf'),
            'manage_options',
            'swpsmtpcf-test-email',
            [$this, 'swpsmtpcf_test']);
    }


    /**
     * @param $actions
     * @return mixed
     */


    public function swpsmtpcf_plugin_action_links($actions)
    {
        $mylinks = array(
            '<a href="' . admin_url('options-general.php?page=swpsmtpcf_settings') . '">Settings</a>',
        );
        $actions = array_merge($actions, $mylinks);
        return $actions;
    }


    public function swpsmtpcf_admin_head()
    {
        wp_enqueue_style('swpsmtpcf_stylesheet', plugins_url('css/style.css', __FILE__));

        if (isset($_REQUEST['page']) && 'swpsmtpcf_settings' == $_REQUEST['page']) {
            wp_enqueue_script('swpsmtpcf_script', plugins_url('js/script.js', __FILE__), array('jquery'));
        }
    }

    /**
     * @param $phpmailer
     */
    public function swpsmtpcf_init_smtp(&$phpmailer)
    {
        //check if SMTP credentials have been configured.
        if (!$this->swpsmtpcf_credentials_configured()) {
            return;
        }
        $swpsmtpcf_options = get_option('swpsmtpcf_options');
        /* Set the mailer type as per config above, this overrides the already called isMail method */
        $phpmailer->IsSMTP();
        $from_email = $swpsmtpcf_options['from_email_field'];
        $phpmailer->From = $from_email;
        $from_name = $swpsmtpcf_options['from_name_field'];
        $phpmailer->FromName = $from_name;
        $phpmailer->SetFrom($phpmailer->From, $phpmailer->FromName);
        /* Set the SMTPSecure value */
        if ($swpsmtpcf_options['smtpcf_settings']['type_encryption'] !== 'none') {
            $phpmailer->SMTPSecure = $swpsmtpcf_options['smtpcf_settings']['type_encryption'];
        }

        /* Set the other options */
        $phpmailer->Host = $swpsmtpcf_options['smtpcf_settings']['host'];
        $phpmailer->Port = $swpsmtpcf_options['smtpcf_settings']['port'];

        /* If we're using smtp auth, set the username & password */
        if ('yes' == $swpsmtpcf_options['smtpcf_settings']['autentication']) {
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username = $swpsmtpcf_options['smtpcf_settings']['username'];
            $phpmailer->Password = $this->swpsmtpcf_get_password();
        }
        //PHPMailer 5.2.10 introduced this option. However, this might cause issues if the server is advertising TLS with an invalid certificate.
        $phpmailer->SMTPAutoTLS = false;
    }


    /**
     * View function the settings to send messages.
     * @return void
     */

    public function swpsmtpcf_settings()
    {
        require_once trailingslashit(plugin_dir_path(__DIR__)) . 'view/settings-form.php';

    }

    public function swpsmtpcf_test()
    {
//todo: add messege has beeen send succesfully, view code line:208
        require_once trailingslashit(plugin_dir_path(__DIR__)) . 'view/test-form.php';

    }


    /**
     * Function to test mail sending
     * @param $to_email
     * @param $subject
     * @param $message
     * @return text or errors
     *
     * @return string|void
     * @throws \PHPMailer\PHPMailer\Exception
     */

    public function swpsmtpcf_test_mail($to_email, $subject, $message)
    {
        if (!$this->swpsmtpcf_credentials_configured()) {
            return;
        }
        $errors = '';

        $swpsmtpcf_options = get_option('swpsmtpcf_options');
        //TODO: CHECK ths require with older wp ver
        //warning works only on '5.4.99' wp ver and above
        require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
        require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
        $mail = new PHPMailer(true);

        try {
            $charset = get_bloginfo('charset');
            $mail->CharSet = $charset;

            $from_name = $swpsmtpcf_options['from_name_field'];
            $from_email = $swpsmtpcf_options['from_email_field'];

            $mail->IsSMTP();

            /* If using smtp auth, set the username & password */
            if ('yes' == $swpsmtpcf_options['smtpcf_settings']['autentication']) {
                $mail->SMTPAuth = true;
                $mail->Username = $swpsmtpcf_options['smtpcf_settings']['username'];
                $mail->Password = $this->swpsmtpcf_get_password();
            }

            /* Set the SMTPSecure value, if set to none, leave this blank */
            if ($swpsmtpcf_options['smtpcf_settings']['type_encryption'] !== 'none') {
                $mail->SMTPSecure = $swpsmtpcf_options['smtpcf_settings']['type_encryption'];
            }

            /* PHPMailer 5.2.10 introduced this option.
            However, this might cause issues if the server is advertising TLS with an invalid certificate. */
            $mail->SMTPAutoTLS = false;

            /* Set the other options */
            $mail->Host = $swpsmtpcf_options['smtpcf_settings']['host'];
            $mail->Port = $swpsmtpcf_options['smtpcf_settings']['port'];
            $mail->SetFrom($from_email, $from_name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->MsgHTML($message);
            $mail->AddAddress($to_email);
            $mail->SMTPDebug = 0;

            /* Send mail and return result */
            if (!$mail->Send())
                $errors = $mail->ErrorInfo;

            $mail->ClearAddresses();
            $mail->ClearAllRecipients();

            if (!empty($errors)) {
                return $errors;
            } else {
                return 'Test mail was sent';
            }

        } catch (\Exception $e) {
            $ret['error'] = $mail->ErrorInfo;
        } catch
        (\Throwable $e) {//php 7.0 required
            $ret['error'] = $mail->ErrorInfo;
        }

    }


    /**
     * Performed at uninstal.
     * @return void
     */

    public function swpsmtpcf_send_uninstall()
    {
        /* delete plugin options */
        delete_site_option('swpsmtpcf_options');
        delete_option('swpsmtpcf_options');
    }

    /**
     * @return false|mixed|string
     */

    public function swpsmtpcf_get_password()
    {
        $swpsmtpcf_options = get_option('swpsmtpcf_options');
        $temp_password = $swpsmtpcf_options['smtpcf_settings']['password'];
        $password = "";
        $decoded_pass = base64_decode($temp_password);
        /* no additional checks for servers that aren't configured with mbstring enabled */
        if (!function_exists('mb_detect_encoding')) {
            return $decoded_pass;
        }
        /* end of mbstring check */
        if (base64_encode($decoded_pass) === $temp_password) {  //it might be encoded
            if (false === mb_detect_encoding($decoded_pass)) {  //could not find character encoding.
                $password = $temp_password;
            } else {
                $password = base64_decode($temp_password);
            }
        } else { //not encoded
            $password = $temp_password;
        }
        return $password;
    }


    public function swpsmtpcf_admin_notice()
    {
        if (!$this->swpsmtpcf_credentials_configured()) {
            ?>
            <div class="error">
                <p><?php _e('Please configure your SMTP credentials in the settings in order to send email using Easy WP SMTP plugin.', 'easy-wp-smtp-cf'); ?></p>
            </div>
            <?php
        }
    }


    public function swpsmtpcf_credentials_configured()
    {
        $swpsmtpcf_options = get_option('swpsmtpcf_options');
        $credentials_configured = true;
        if (!isset($swpsmtpcf_options['from_email_field']) || empty($swpsmtpcf_options['from_email_field'])) {
            $credentials_configured = false;
        }
        if (!isset($swpsmtpcf_options['from_name_field']) || empty($swpsmtpcf_options['from_name_field'])) {
            $credentials_configured = false;;
        }
        return $credentials_configured;
    }


    public static function path_form($form)
    {
        return trailingslashit(plugin_dir_path(__DIR__)) . 'inc/forms/' . $form;
    }

}