<?php
namespace Model;

class WP_SMTP_Contact_Form_Model
{

    public function __construct()
    {
        add_action('admin_init', array($this, 'swpsmtpcf_admin_init'));
    }


    /**
     * Plugin functions for init
     * @return void
     */

    public function swpsmtpcf_admin_init()
    {
        /* Internationalization, first(!) */
        load_plugin_textdomain('wp_smtp_cf', false, dirname(plugin_basename(__FILE__)) . '/languages/');

        if (isset($_REQUEST['page']) && 'swpsmtpcf_settings' == $_REQUEST['page']) {
            /* register plugin settings */
            $this->swpsmtpcf_register_settings();
        }
    }


    public function swpsmtpcf_register_settings()
    {
        $swpsmtpcf_options_default = array(
            'from_email_field' => '',
            'from_name_field' => '',
            'smtpcf_settings' => array(
                'host' => 'smtp.example.com',
                'type_encryption' => 'none',
                'port' => 25,
                'autentication' => 'yes',
                'username' => 'yourusername',
                'password' => 'yourpassword'
            )
        );

        /* install the default plugin options */
        if (!get_option('swpsmtpcf_options')) {
            add_option('swpsmtpcf_options', $swpsmtpcf_options_default, '', 'yes');
        }
    }

}