<?php
namespace Models;
use Config\Database;
use PDO;
use PDOException;
/* La clase `BitacoraModel` en PHP proporciona métodos para insertar y recuperar registros de una tabla
llamada "bitacora" en la base de datos, manejando las excepciones de manera adecuada. */
class BitacoraModel
{
    private PDO $conexion;
    public function __construct()
    {
        $db = new Database();
        $this->conexion = $db->connect();
    }

/**
 * La función `registrar` inserta un registro en la tabla "bitacora" con la acción proporcionada y la
 * fecha actual.
 * 
 * Args:
 *   accion (string): La función `registrar` es un método en PHP que inserta un registro en una tabla de base de datos
 * llamada `bitacora`. El registro contiene dos campos: `accion` y `fecha` (fecha/hora). El campo
 * `accion` se pasa como parámetro a la función.
 * 
 * Returns:
 *   La función `registrar` devuelve un valor booleano. Si la inserción en la tabla `bitacora`
 * se realiza correctamente, devolverá `true`. Si ocurre una excepción (como una `PDOException`),
 * la excepción será capturada y la función devolverá `false`.
 */
    public function registrar(string $accion): bool
    {
        try {
            $sql = "INSERT INTO bitacora (accion, fecha) VALUES (:accion, NOW())";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':accion', $accion);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

/**
 * Esta función en PHP obtiene todos los registros de una tabla llamada "bitacora" en orden descendente por su
 * ID utilizando PDO y los devuelve como un arreglo asociativo.
 * 
 * Returns:
 *   Un arreglo de arreglos asociativos que contiene los datos de la tabla "bitacora", ordenados por la columna "id"
 * en orden descendente. Si ocurre una excepción durante la consulta a la base de datos, se devuelve un arreglo vacío.
 */
    public function obtenerTodos(): array
    {
        try {
            $sql = "SELECT * FROM bitacora ORDER BY id DESC";
            $stmt = $this->conexion->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>