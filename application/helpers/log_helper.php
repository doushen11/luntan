<?php
class LogUtil {
    static function info($data, $method='') {
        self::log('info', $data, $method);
    }
    
    static function error($data, $method='') {
        self::log('error', $data, $method);
    }
    
    static function debug($data, $method='') {
        self::log('debug', $data, $method);
    }
    
    static function log($level, $data, $method='') {
        $array['data'] = $data;
        $array['method'] = $method;
        $message = json_encode($array);
        log_message($level, $message);
    }
}