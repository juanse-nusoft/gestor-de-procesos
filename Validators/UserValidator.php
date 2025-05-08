<?php

namespace Validators;

use Model\User;
use Helpers\Alert;

class UserValidator
{
    public static function validateLogin(User $user): void
    {
        Alert::clearAlerts();

        // Validate email
        if (empty($user->email)) {
            Alert::setAlert('error', 'El email es obligatorio');
        } else {
            $sanitizedEmail = filter_var($user->email, FILTER_SANITIZE_EMAIL);

            if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
                Alert::setAlert('error', 'Formato de email no válido');
            } else {
                $domain = substr(strrchr($sanitizedEmail, "@"), 1);
                if (strtolower($domain) !== 'nusoft.com.co') {
                    Alert::setAlert('error', 'Solo se permiten correos corporativos');
                }
            }
        }

        // Validate password
        if (empty($user->password)) {
            Alert::setAlert('error', 'La contraseña es obligatoria');
        } elseif (strlen($user->password) < 8) {
            Alert::setAlert('error', 'La contraseña debe tener al menos 8 caracteres');
        }
    }
}
