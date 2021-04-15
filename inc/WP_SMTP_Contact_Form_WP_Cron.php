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
        add_action('wpsmtpcf_cron_job', [$this, 'wpsmtpcf_wpcron_log']);
        add_filter('cron_schedules', [$this, 'wpsmtpcf_wpcron_everyhour']);
    }

    public function clear_file($file_name)
    {
        $file_path = __DIR__ . self::$dir_sep . 'logs' . self::$dir_sep . $file_name;
        $fp = fopen($file_path, "r+");
        // clear content to 0 bits
        ftruncate($fp, 0);
        //close file
        fclose($fp);
    }


    public function wpsmtpcf_wpcron_log()
    {
        $this->clear_file('app_dev.log');
        $this->clear_file('sql_dump.log');
    }

    public function wpsmtpcf_wpcron_everyhour($schedules)
    {
        $schedules['everyhour'] = array(
            'interval' => 15,//15 sec for testing purposes
            'display' => 'one per hour'
        );

        return $schedules;

    }
}