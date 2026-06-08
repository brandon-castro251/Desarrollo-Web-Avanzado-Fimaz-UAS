<?php
namespace Config;

class Csrf
{
/**
 * Esta función genera un token CSRF en PHP y lo almacena en la sesión.
 * 
 * Returns:
 *   La función `generarToken` devuelve un valor de tipo string, el cual es el token CSRF almacenado
 * en la variable `['csrf_token']`.
 */
    public static function generarToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

/**
 * La función `validarToken` verifica si un token dado coincide con el token CSRF almacenado en la sesión.
 * 
 * Args:
 *   token: La función `validarToken` se utiliza para validar un token CSRF. Los tokens CSRF se usan para
 * prevenir ataques de falsificación de solicitudes entre sitios (Cross-Site Request Forgery), asegurando
 * que la solicitud proviene de la fuente esperada.
 * 
 * Returns:
 *   La función `validarToken` devuelve un valor booleano. Verifica si la sesión contiene una clave
 * 'csrf_token' y si el valor de 'csrf_token' en la sesión es igual al token proporcionado después de
 * compararlo de forma segura. Si ambas condiciones se cumplen, devuelve true, indicando que el token es
 * válido. En caso contrario, devuelve false.
 */
    public static function validarToken(?string $token): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], (string)$token);
    }
}