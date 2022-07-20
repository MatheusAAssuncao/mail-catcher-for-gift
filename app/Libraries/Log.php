<?php

namespace App\Libraries;

use DateTimeZone;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log {

    const DEBUG = 'debug';
    const INFO = 'info';
    const NOTICE = 'notice';
    const WARNING = 'warning';
    const ERROR = 'error';
    const CRITICAL = 'critical';
    const ALERT = 'alert';
    const EMERGENCY = 'emergency';

    /**
     * Create log register in /log/app.log
     * 
     * @param string $channelName className::method
     * @param string $type use constraints: debug, info, notice, warning, error, critical, alert, emergency
     * @param string|array text or data array
     * @param array optional data array
     */
    public static function add(string $channelName, string $type = "", string $message = "", array $data = []) {
        $logger = new Logger($channelName);
        $logger->setTimezone(new DateTimeZone(app_timezone()));
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log'));

        if (is_callable(array($logger, $type))){
            $logger->$type($message, $data);
        }
    }
}