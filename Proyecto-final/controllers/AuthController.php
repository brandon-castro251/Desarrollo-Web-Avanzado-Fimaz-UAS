<?php
namespace Controllers;
use Config\Csrf;
use Models\UsuarioModel;

/* La clase `AuthController` en PHP maneja la autenticación de usuarios, la gestión de sesiones y la redirección
según si el inicio de sesión es exitoso o falla. */
class AuthController
{
/**
 * La función `showLogin` requiere e incluye el archivo de la vista de inicio de sesión en una aplicación PHP,
 * para mostrar la interfaz de login.
 */
    public function showLogin(): void
    {
        require_once __DIR__ . '/../views/auth/login.php';
    }

/**
 * La función `login` en PHP maneja la autenticación de usuarios, la gestión de sesiones y la redirección según
 * si el inicio de sesión es exitoso o falla.
 */
    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!Csrf::validarToken($_POST['csrf_token'] ?? '')) {
            die('Token CSRF inválido');
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            $_SESSION['error'] = 'Todos los campos son obligatorios.';
            header('Location: /TIENDA/login');
            exit;
        }

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->buscarPorUsername($username);

        if ($usuario && password_verify($password, $usuario['password'])) {

            $_SESSION['admin'] = [
                'id' => $usuario['id'],
                'username' => $usuario['username'],
                'nombre_completo' => $usuario['nombre_completo']
            ];

            $_SESSION['success'] = 'Bienvenido, ' . $usuario['nombre_completo'];

            header('Location: /TIENDA/productos');
            exit;
        }

        $_SESSION['error'] = 'Credenciales incorrectas.';
        header('Location: /TIENDA/login');
        exit;
    }

/**
 * La función `logout` en PHP cierra la sesión del usuario destruyendo la sesión y redirigiendo a la
 * página de productos.
 */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header('Location: /TIENDA/productos');
        exit;
    }
}