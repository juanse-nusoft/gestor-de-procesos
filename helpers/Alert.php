<?php 

namespace Helpers;

class Alert{

    public static array $alerts = [];

    public static function setAlert(string $type, string $message): void {
        self::$alerts[$type][] = $message;
    }

    public static function getAlerts(): array {
        return self::$alerts;
    }
    
    public static function clearAlerts(): void{
        self::$alerts = [];
    }
}