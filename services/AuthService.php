<?php

namespace Services;

use Model\User;
use Models\UserDivision;
use Helpers\Alert;
use Validators\UserValidator;   

class AuthService {

    public static function validateLogin(string $email, string $password): ?User {

        
        $user = User::findByEmail($email);

        if (!$user) {
            Alert::setAlert('error', 'Usuario no encontrado');
            return null;
        }

        if (!$user->verifyPassword($password)) {
            Alert::setAlert('error', 'Contraseña incorrecta');
            return null;
        }

        if (!$user->isConfirmed()) {
            Alert::setAlert('error', 'Cuenta no confirmada');
            return null;
        }

        return $user;
    }

    public static function validateForgotPassword(string $email): ?User {
        $user = User::findByEmail($email);

        if (!$user) {
            Alert::setAlert('error', 'Usuario no encontrado');
            return null;
        }

        if (!$user->isConfirmed()) {
            Alert::setAlert('error', 'Cuenta no confirmada');
            return null;
        }

        return $user;
    }

    public static function validateRecoveryToken(string $token): ?User {
        $user = User::findByToken($token);

        if (!$user) {
            Alert::setAlert('error', 'Token invalido o expirado');
            return null;
        }

        return $user;
    }

    public static function updatePassword(User $user, string $newPassword): void {
        $user->password = $newPassword;
        $user->hashPassword();
        $user->token = null;
        $user->save();
    }

    public static function getUserDivision(int $userId): ?UserDivision {
        return UserDivision::findByUserId($userId);
    }
}

