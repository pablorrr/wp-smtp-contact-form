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
        $text_one = '1st step
Check your SMTP settings with your hosting provider and enter them into the form in the first link of the plugin.
Step 2
Save the settings
 Step 3. Go to edit any post or page and paste the following shortcode: [WP_SMTP_Contact_Form] and save the changes.
Cool!! Now you can send email from anywhere on your website';

        $text_two = 'Before first use, test your SMTP settings in the second plugin form in the second plugin link -WP SMTP test';
        // Add my_help_tab if current screen is My Admin Page
        $screen->add_help_tab(array(
            'id' => 'help_tab',
            'title' => __('Welcome', 'wp_smtp_cf'),
            'content' => '<p>' . __('Thanks for chosen my plugin ') . '</p>',
        ));

        $screen->add_help_tab(array(
            'id' => 'help_tab_two',
            'title' => __('How to use plugin', 'wp_smtp_cf'),
            'content' => '<p>' . $text_one . '</p>',

        ));
        $screen->add_help_tab(array(
            'id' => 'help_tab_three',
            'title' => __('Another clues', 'wp_smtp_cf'),
            'content' => '<p>' . __($text_two, 'wp_smtp_cf') . '</p>',
        ));

        $screen->set_help_sidebar(
            '<p><strong>' . __('Quick Links', 'wp_smtp_cf') . '</strong></p>' .
            '<p><a href="http://websitecreator.cba.pl" target="_blank">' . __('Author website', 'wp_smtp_cf') . '
			</a></p>'

        );
    }

}