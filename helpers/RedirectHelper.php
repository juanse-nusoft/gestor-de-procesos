<?php

namespace Helpers;

class RedirectHelper {

    public static function redirect(string $url) {
        header('Location: ' . $url);
        exit;
    }
}
