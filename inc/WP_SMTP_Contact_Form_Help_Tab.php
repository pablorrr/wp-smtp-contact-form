<?php


namespace Inc;


class WP_SMTP_Contact_Form_Help_Tab
{

    /**
     * @var
     * Singleton
     * Singleton on WP Plugin implementation inspired with
     * https://gist.github.com/goncaloneves/e0f07a8db17b06c2f968
     */


    private static $_instance;

    /**
     * @return WP_SMTP_Contact_Form_Help_Tab
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }


    public function WP_SMTP_Contact_Form_help_tab()
    {
        $screen = get_current_screen();
        $text = 'Afterwards you save your settings, dont forget to paste this shortcode: [geocoder_map]
 in your posts or page. To see results of your changes on a map you must refresh website before.';
        // Add my_help_tab if current screen is My Admin Page
        $screen->add_help_tab(array(
            'id' => 'help_tab',
            'title' => __('Welcome', 'geocoder-map'),
            'content' => '<p>' . __('Thx you have choosen my plugin', 'geocoder-map') . '</p>',
        ));

        $screen->add_help_tab(array(
            'id' => 'help_tab_two',
            'title' => __('How to use plugin', 'geocoder-map'),
            'content' => '<p>' . __('Before you apply your settings please paste your GM api key', 'simple-google-map-plugin') . '</p>',

        ));
        $screen->add_help_tab(array(
            'id' => 'help_tab_three',
            'title' => __('Another clues', 'geocoder-map'),
            'content' => '<p>' . __($text, 'geocoder-map') . '</p>',
        ));

        $screen->set_help_sidebar(
            '<p><strong>' . __('Quick Links', 'geocoder-map') . '</strong></p>' .
            '<p><a href="http://websitecreator.pl" target="_blank">' . __('Author website', 'geocoder-map') . '
			</a></p>' .
            '<p><a href="https://www.google.pl/maps" target="_blank">Google Maps</a></p>' .
            '<p><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">' . __('Obtain Geoogle Map key link', 'geocoder-map') . '</a></p>'
        );
    }

}