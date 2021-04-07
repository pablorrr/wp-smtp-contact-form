<?php


namespace Inc;


class WP_SMTP_Contact_Form_WP_Cron
{
    /**
     * @var
     * Singleton
     * Singleton on WP Plugin implementation inspired with
     * https://gist.github.com/goncaloneves/e0f07a8db17b06c2f968
     */


    private static $_instance;
    private static $dir_sep;

    /**
     * @return WP_SMTP_Contact_Form_WP_Cron
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function __construct()
    {
        self::$dir_sep = DIRECTORY_SEPARATOR;
        add_action('edu_cron_job', [$this, 'edu_wpcron_log']);
        add_filter('cron_schedules', [$this, 'edu_wpcron_every15sec']);


    }

    public function edu_wpcron_log()
    {//todo:: clrear content of a file  instead delete it

        $file_name = 'app_dev.log';
        $file_path = __DIR__ . self::$dir_sep . 'logs' . self::$dir_sep . $file_name;


        $fp = fopen($file_path, "r+");
// clear content to 0 bits
        ftruncate($fp, 0);
//close file
        fclose($fp);
        // unlink($file_path);
    }

    public function edu_wpcron_every15sec($schedules)
    {

        $schedules['every15sec'] = array(
            'interval' => 15,
            'display' => 'Raz na 15 sec'
        );

        return $schedules;

    }



}