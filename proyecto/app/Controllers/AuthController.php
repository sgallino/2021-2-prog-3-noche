<?php

namespace App\Controllers;


use App\Auth\Auth;
use App\Models\Usuario;
use App\View;

class AuthController
{
    public static function registroForm()
    {
        $view = new View();
        $view->render('auth/registro');
    }

    public static function registro()
    {
        $email              = $_POST['email'];
        $password           = $_POST['password'];
        $password_confirm   = $_POST['password_confirm'];

        // TODO: Validar...

        $user = new Usuario();
        $user = $user->create([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        if($user === null) {
            $_SESSION['old_data'] = $_POST;
            $_SESSION['message_error'] = "Ocurrió un problema y el usuario no puedo ser creado.";
            header("Location: registro");
            exit;
        }

        // Autenticamos al usuario recién registrado.
        $auth = new Auth();
        $auth->authenticate($user);

        $_SESSION['message_success'] = "¡Registro completado con éxito!";
        header("Location: productos");
        exit;
    }

    public static function loginForm()
    {
        $view = new View();
        $view->render('auth/login');
    }

    public static function login()
    {
        $email      = $_POST['email'];
        $password   = $_POST['password'];

        $auth = new Auth();

        if(!$auth->login(['email' => $email, 'password' => $password])) {
            $_SESSION['old_data'] = $_POST;
            $_SESSION['message_error'] = 'Las credenciales ingresadas no coinciden con nuestros registros.';
            header('Location: iniciar-sesion');
            exit;
        }

        $_SESSION['message_success'] = "¡Autenticación exitosa!";
        header('Location: productos');
        exit;
    }

    public static function logout()
    {
        $auth = new Auth();

        $auth->logout();

        $_SESSION['message_success'] = 'Sesión cerrada con éxito. ¡Te esperamos de vuelta pronto!';
        header('Location: iniciar-sesion');
        exit;
    }
}
