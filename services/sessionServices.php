<?php

namespace Services;

class sessionServices{

    public static function startSession(): void {
        if(session_status() !== PHP_SESSION_ACTIVE){
            session_start([
                'cookie_lifetime' => 86400, // 1 day
                'cookie_httponly' => true,
                'cookie_secure' => true
            ]);
        }
    }

    public static function setUserSession(array $userData): void {
        $_SESSION['user'] = $userData;
    }

    public static function destroySession(): void {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
}