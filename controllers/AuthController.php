<?php

namespace Controllers;

use MVC\Router;
use Services\AuthService;
use Helpers\Alert;
use Helpers\SessionHelper;
use Helpers\RedirectHelper;
use Helpers\ValidationHelper;

class AuthController {

    public static function login(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            ValidationHelper::validateFields(compact('email', 'password'));

            if (empty(Alert::getAlerts())) {
                $user = AuthService::validateLogin($email, $password);

                if ($user) {
                    SessionHelper::startSessionIfNotStarted();
                    SessionHelper::setUserSession($user);

                    $userDivision = AuthService::getUserDivision($user->id);
                    if ($userDivision) {
                        $_SESSION['division_id'] = $userDivision->division_id;
                    }

                    RedirectHelper::redirect('/dashboard');
                }
            }
        }

        $router->render('auth/login', [
            'alerts' => Alert::getAlerts()
        ]);
    }

    public static function logout() {
        SessionHelper::startSessionIfNotStarted();
        $_SESSION = [];
        RedirectHelper::redirect('/');
    }

    public static function forgotPassword(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';

            ValidationHelper::validateFields(compact('email'));

            if (empty(Alert::getAlerts())) {
                $user = AuthService::validateForgotPassword($email);

                if ($user) {
                    $user->createToken();
                    $user->save();


                    Alert::setAlert('success', 'Check your email to reset your password');
                }
            }
        }

        $router->render('auth/forgot-password', [
            'alerts' => Alert::getAlerts()
        ]);
    }

    public static function recoverPassword(Router $router) {
        $token = $_GET['token'] ?? '';

        if (!$token) {
            RedirectHelper::redirect('/');
        }

        $user = AuthService::validateRecoveryToken($token);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['password'] ?? '';

            ValidationHelper::validateFields(compact('newPassword'));

            if (empty(Alert::getAlerts()) && $user) {
                AuthService::updatePassword($user, $newPassword);
                Alert::setAlert('success', 'Password updated successfully');
                RedirectHelper::redirect('/');
            }
        }

        $router->render('auth/recover-password', [
            'alerts' => Alert::getAlerts()
        ]);
    }
}
