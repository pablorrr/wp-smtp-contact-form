<?php


namespace Inc;


use Exception;

class WP_SMTP_Contact_Form_Logger
{
    /**
     * @var
     * Singleton
     * Singleton on WP Plugin implementation inspired with
     * https://gist.github.com/goncaloneves/e0f07a8db17b06c2f968
     */

//todo:: cretae  log file from PHP praogramatically
    private static $_instance;
    private static $dir_sep;


    /**
     * @return WP_SMTP_Contact_Form_Logger
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }


    /**
     * WP_SMTP_Contact_Form_Logger constructor.
     * notice : define( 'WP_DEBUG', true );
     * define( 'SAVEQUERIES', true ); must be introduced in wp-config.php before  usage
     */
    public function __construct()
    {
        self::$dir_sep = DIRECTORY_SEPARATOR;

        add_action('shutdown', [$this, 'WP_SMTP_CF_query_logger']);
        add_action('shutdown', [$this, 'WP_SMTP_CF_log']);
    }

    /**
     * @param $message
     * @throws Exception
     *
     * example- how to use it in code to debug(remeber be sure hook was run before check log file!!!)
     * $logger = WP_SMTP_Contact_Form_Logger::instance();
     * $logger->WP_SMTP_CF_log($plugin_basename);
     * */

    public function WP_SMTP_CF_log($message)
    {

        if (defined('WP_DEBUG') && WP_DEBUG) {


            $file_path = __DIR__ . self::$dir_sep . 'logs/app_dev.log';


            $time = date('Y-m-d H:i:s');

            if (is_array($message) || is_object($message)) {
                $message = print_r($message, TRUE);
            }
            if (is_string($message)) {
                echo $message;
            }

            $log_line = "$time\t{$message}\n";

            if (file_exists($file_path)) {
                if (!file_put_contents($file_path, $log_line, FILE_APPEND)) {
                    throw new Exception("Log file '{$file_path}' cannot be opened or created. Check permissions.");
                }
            }

        }
    }

    /**
     * @throws Exception
     * make dump all executable queries during refresh plugin page
     */

    //todo:: transient usage
    public function WP_SMTP_CF_query_logger()
    {
        if ((defined('WP_DEBUG') && WP_DEBUG) && (defined('SAVEQUERIES') && SAVEQUERIES)) {

            global $wpdb;

            $dump = array();
            if (!empty($wpdb->queries)) {

                foreach ($wpdb->queries as $i => $qrow) {
                    $query = $qrow[0];
                    $time = number_format(sprintf('%0.2f', $qrow[1] * 1000), 2, '.', ',');
                    $path = $qrow[2];

                    $dump[] = "[{$i}]\t- Query: {$query}\n\t- Time: {$time}ms\n\t- Path: {$path}";
                }
            } else {
                $dump[] = 'No queries...';
            }

            $label = '-- SQL Dump at ' . date('Y-m-d H:i:s') . ' --';
            $footer = '-- DUMP END --';

            $file_name = 'sql_dump.log';


            $file_path = __DIR__ . self::$dir_sep . 'logs' . self::$dir_sep . $file_name;

            $content = $label . "\n\n" . implode("\n\n", $dump) . "\n\n" . $footer . "\n\n";
            if (file_exists($file_path)) {
                if (!file_put_contents($file_path, $content, FILE_APPEND)) {
                    throw new Exception("Log file '{$file_path}' cannot be opened or created. Check permissions.");
                }
            }
        }
    }


}