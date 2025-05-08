<?php

namespace Helpers;

class SessionHelper
{
    public static function startSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start([
                'cookie_lifetime' => 86400, // 1 day
                'cookie_secure' => true,    // Only send over HTTPS
                'cookie_httponly' => true   // Not accessible by JavaScript
            ]);
            session_regenerate_id(true); // Prevent session fixation
        }
    }

    public static function setUser(array $userData): void
    {
        $_SESSION['user'] = $userData;
    }

    public static function getUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function destroySession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
}
