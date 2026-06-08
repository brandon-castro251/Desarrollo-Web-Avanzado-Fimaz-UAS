<?php
namespace Models;

use Config\Database;
use PDO;
use PDOException;
/* La clase `UsuarioModel` en PHP contiene un método llamado `buscarPorUsername` que busca un usuario
por su nombre de usuario en una tabla de base de datos y devuelve los datos del usuario si es encontrado. */
class UsuarioModel
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Database();
        $this->conexion = $db->connect();
    }
/**
 * Esta función en PHP busca un usuario por su nombre de usuario en una base de datos y devuelve los
 * datos del usuario si es encontrado.
 * 
 * Args:
 *   username (string): La función `buscarPorUsername` es un método en PHP que busca un usuario en una
 * tabla de base de datos llamada `usuarios` utilizando el nombre de usuario proporcionado mediante el
 * parámetro `username`. Utiliza una consulta SQL preparada para seleccionar al usuario con el nombre
 * de usuario especificado y limita el resultado a una sola fila. Si se encuentra un usuario, se
 * devuelven sus datos.
 * 
 * Returns:
 *   La función `buscarPorUsername` devuelve un arreglo con la información del usuario si se encuentra
 * un usuario con el nombre de usuario especificado en la base de datos. Si no se encuentra ningún
 * usuario, devuelve `null`. Si ocurre una excepción (`PDOException`) durante la consulta a la base de
 * datos, también devuelve `null`.
 */
    public function buscarPorUsername(string $username): ?array
    {
        try {
            $sql = 'SELECT * FROM usuarios WHERE username = :username LIMIT 1';
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $usuario = $stmt->fetch();
            return $usuario ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
}
?>