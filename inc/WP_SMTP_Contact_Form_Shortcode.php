<?php


namespace Inc;

use Controller\WP_SMTP_Contact_Form_Controller;

class WP_SMTP_Contact_Form_Shortcode
{
    /**
     * @var
     * Singleton
     * Singleton on WP Plugin implementation inspired with
     * https://gist.github.com/goncaloneves/e0f07a8db17b06c2f968
     */


    private static $_instance;

    /**
     * @return WP_SMTP_Contact_Form_Shortcode
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * WP_SMTP_Contact_Form_Shortcode constructor.
     */
    public function __construct()
    {

        $this->actions();
    }


    private function actions()
    {
        add_shortcode('WP_SMTP_Contact_Form', [$this, 'contact_form']);
    }

    /**
     * @param $atts
     * @return string
     */
    public function contact_form()
    {//todo :: reg exp to validate fields
        //todo:: put info your email snet succesfully with bst classes css
        //todo:: relocate form to view folder
        //todo; action attr put valu request global -  check php manual
        $plugin_basename = plugin_basename(__FILE__);
        if (isset($_POST['swpsmtpcf_form_front_submit']) &&
            check_admin_referer($plugin_basename, 'swpsmtpcf_nonce_name')) {
            require_once(WP_SMTP_Contact_Form_Controller::path_form('front-end-form.php'));
        }

        ob_start(); ?>
        <form method="post" action="">
            <div class="form-group">
                <input class="form-control" type="text" name="form_subject" placeholder="Default input" value=""/><br/>
                <input class="form-control" type="text" name="form_message" placeholder="Default input" value=""/><br/>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
            <input type="hidden" name="swpsmtpcf_form_front_submit" value="submit"/>
            <?php wp_nonce_field(plugin_basename(__FILE__), 'swpsmtpcf_nonce_name'); ?>
        </form>

        <?php return ob_get_clean();

    }
    // [WP_SMTP_Contact_Form ]
}