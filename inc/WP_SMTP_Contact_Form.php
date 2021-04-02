<?php

namespace Inc;

use Controller\WP_SMTP_Contact_Form_Controller;


//TODO: ADD '5.4.99' compare version if lower add admin notice and close plugin check plugin from WC
if (!class_exists('WP_SMTP_Contact_Form')):
    class  WP_SMTP_Contact_Form
    {
        /**
         * @var
         * Singleton
         * Singleton on WP Plugin implementation inspired with
         * https://gist.github.com/goncaloneves/e0f07a8db17b06c2f968
         */

        private static $_instance;

        private $controller;

        public static function instance(): WP_SMTP_Contact_Form
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self;
            }
            return self::$_instance;
        }


        /**
         * Constructor.
         */
        public function __construct()
        {

            $this->actions();
        }

        /**
         * init plugin when all wp plugins loaded
         */

        private function actions()
        {
            add_action('plugins_loaded', array($this, 'init'));
        }


        public function init()
        {
            $this->controller = new WP_SMTP_Contact_Form_Controller();
            WP_SMTP_Contact_Form_Shortcode::instance();
        }


    }
endif;
