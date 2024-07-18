<?php
use Psr\Log\LoggerInterface;

class Todo_Logger implements LoggerInterface {
    private static $log_file = 'todo_plugin.log';

    public static function log($message) {
        error_log($message . "\n", 3, plugin_dir_path(__FILE__) . self::$log_file);
    }

    // Implement required methods from LoggerInterface...
    public function emergency($message, array $context = array()) {}
    public function alert($message, array $context = array()) {}
    public function critical($message, array $context = array()) {}
    public function error($message, array $context = array()) {}
    public function warning($message, array $context = array()) {}
    public function notice($message, array $context = array()) {}
    public function info($message, array $context = array()) {}
    public function debug($message, array $context = array()) {}
    public function log($level, $message, array $context = array()) {}
}