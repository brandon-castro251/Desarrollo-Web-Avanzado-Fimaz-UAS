<?php
namespace Models;

use Config\Database;
use PDO;
use PDOException;
/* La clase `ProductoModel` en PHP proporciona métodos para interactuar con una tabla de productos en
la base de datos, incluyendo funciones para obtener, buscar, crear, actualizar y eliminar registros
de productos. */
class ProductoModel
{
    private PDO $conexion;

    public function __construct()
    {
        $db = new Database();
        $this->conexion = $db->connect();
    }
/**
 * La función `obtenerTodos` obtiene una cantidad específica de productos desde una tabla de base de datos,
 * comenzando desde un índice determinado.
 * 
 * Args:
 *   limite (int): El parámetro `limite` en la función `obtenerTodos` representa la cantidad máxima
 * de registros que se recuperarán de la base de datos. Se utiliza en la consulta SQL para limitar
 * el número de resultados devueltos por la instrucción SELECT.
 * 
 *   inicio (int): El parámetro `inicio` en la función `obtenerTodos` representa el punto de partida
 * desde donde se desean recuperar los registros de la tabla de la base de datos. Se utiliza en la
 * consulta SQL para especificar el desplazamiento (offset) desde el cual se obtendrán los registros.
 * 
 * Returns:
 *   Se devuelve un arreglo de productos. El método recupera todos los productos de la tabla
 * `productos`, ordenados por `id` de forma ascendente, con un límite y punto de inicio especificados.
 * Los datos se obtienen como un arreglo asociativo utilizando PDO y son devueltos por el método.
 * Si ocurre una excepción durante la operación en la base de datos, se devuelve un arreglo vacío.
 */
    public function obtenerTodos(int $limite, int $inicio): array
    {
        try {
            $sql = 'SELECT * FROM productos ORDER BY id ASC LIMIT :inicio, :limite';
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }
/**
 * La función `buscarPublico` busca productos en una base de datos utilizando un término de búsqueda
 * y devuelve los resultados en forma de arreglo.
 * 
 * Args:
 *   termino (string): La función `buscarPublico` es un método en PHP que busca productos en una
 * base de datos según un término de búsqueda proporcionado mediante el parámetro `termino`.
 * Si el parámetro `termino` está vacío, obtiene todos los productos ordenados por ID de forma
 * descendente. Si se proporciona un término de búsqueda, se realiza una búsqueda con dicho valor.
 * 
 * Returns:
 *   Se devuelve un arreglo con los resultados de la búsqueda. Si el término de búsqueda está vacío,
 * se obtienen y devuelven todos los productos de la base de datos. Si se proporciona un término,
 * se obtienen y devuelven los productos cuyos nombres o descripciones coinciden con dicho término.
 * Si ocurre un error durante la consulta a la base de datos, se devuelve un arreglo vacío.
 */
    public function buscarPublico(string $termino = ''): array
    {
        try {
            if (trim($termino) === '') {
                $sql = 'SELECT * FROM productos ORDER BY id DESC';
                $stmt = $this->conexion->query($sql);
                return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            }

            $sql = 'SELECT * FROM productos WHERE nombre LIKE :termino OR descripcion LIKE :termino ORDER BY id DESC';
            $stmt = $this->conexion->prepare($sql);
            $busqueda = '%' . $termino . '%';
            $stmt->bindValue(':termino', $busqueda);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }
/**
 * La función `obtenerPorId` obtiene un producto por su ID desde una base de datos utilizando PDO en PHP.
 * 
 * Args:
 *   id (int): La función `obtenerPorId` es un método en PHP que recupera un producto de una tabla
 * de base de datos según el `id` proporcionado. La función recibe un parámetro entero `id`,
 * que representa el identificador único del producto que se desea obtener.
 * 
 * Returns:
 *   La función `obtenerPorId` devuelve un arreglo con los datos del producto que coincide con el
 * ID proporcionado, o `null` si no se encuentra ningún producto o si ocurre una excepción durante
 * la consulta a la base de datos.
 */
    public function obtenerPorId(int $id): ?array
    {
        try {
            $sql = 'SELECT * FROM productos WHERE id = :id LIMIT 1';
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            return $producto ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
/**
 * La función `crear` inserta los datos de un producto en una tabla de base de datos y devuelve `true`
 * si la operación se realiza correctamente; de lo contrario, devuelve `false`.
 * 
 * Args:
 *   data (array): La función `crear` es un método en PHP que inserta un nuevo registro en una tabla
 * de base de datos llamada `productos`. Recibe como parámetro un arreglo `data`, el cual debe contener
 * las claves necesarias con la información del producto.
 * 
 * Returns:
 *   La función `crear` devuelve un valor booleano. Si la inserción del producto en la base de datos
 * se realiza correctamente, devolverá `true`. Si ocurre un error durante el proceso (como una
 * `PDOException`), la excepción será capturada, la transacción se revertirá si aún está en progreso
 * y la función devolverá `false`.
 */
    public function crear(array $data): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sql = "INSERT INTO productos 
            (sku, nombre, descripcion, precio_compra, precio_venta, existencia, imagen)
            VALUES (:sku, :nombre, :descripcion, :precio_compra, :precio_venta, :existencia, :imagen)";

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':sku', $data['sku']);
            $stmt->bindValue(':nombre', $data['nombre']);
            $stmt->bindValue(':descripcion', $data['descripcion']);
            $stmt->bindValue(':precio_compra', $data['precio_compra']);
            $stmt->bindValue(':precio_venta', $data['precio_venta']);
            $stmt->bindValue(':existencia', $data['existencia'], PDO::PARAM_INT);
            $stmt->bindValue(':imagen', $data['imagen']);

            $stmt->execute();
            $this->conexion->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }
/**
 * La función `actualizar` actualiza un registro de producto en la base de datos utilizando una
 * transacción en PHP.
 * 
 * Args:
 *   id (int): El parámetro `id` en la función `actualizar` representa el identificador único del
 * producto que se desea actualizar en la base de datos. Se utiliza en la consulta SQL para
 * especificar qué producto debe actualizarse según su ID.
 * 
 *   data (array): La función `actualizar` es responsable de actualizar un producto en la base de
 * datos utilizando el `id` proporcionado y el arreglo `data`. El arreglo `data` contiene los valores
 * actualizados de los campos del producto, como `sku`, `nombre`, `descripcion`,
 * `precio_compra`, entre otros.
 * 
 * Returns:
 *   La función `actualizar` devuelve un valor booleano. Si la operación de actualización se realiza
 * correctamente, devuelve `true`. Si ocurre una excepción (específicamente una `PDOException`),
 * la excepción es capturada, se revierte la transacción si aún está en progreso y la función
 * devuelve `false`.
 */
    public function actualizar(int $id, array $data): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sql = 'UPDATE productos SET
                        sku = :sku,
                        nombre = :nombre,
                        descripcion = :descripcion,
                        precio_compra = :precio_compra,
                        precio_venta = :precio_venta,
                        existencia = :existencia,
                        imagen = :imagen
                    WHERE id = :id';

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':sku', $data['sku']);
            $stmt->bindValue(':nombre', $data['nombre']);
            $stmt->bindValue(':descripcion', $data['descripcion']);
            $stmt->bindValue(':precio_compra', $data['precio_compra']);
            $stmt->bindValue(':precio_venta', $data['precio_venta']);
            $stmt->bindValue(':existencia', $data['existencia'], PDO::PARAM_INT);
            $stmt->bindValue(':imagen', $data['imagen']);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->conexion->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }
/**
 * La función `eliminar` en PHP intenta eliminar un registro de la tabla `productos` utilizando un
 * ID especificado, gestionando transacciones y devolviendo un valor booleano según el resultado.
 * 
 * Args:
 *   id (int): La función `eliminar` es un método que recibe un parámetro entero `id`, el cual
 * representa el identificador del producto que debe ser eliminado de la tabla `productos`
 * en la base de datos. La función intenta eliminar el producto con el ID especificado.
 * 
 * Returns:
 *   La función `eliminar` devuelve un valor booleano. Si la eliminación del producto con el ID
 * especificado se realiza correctamente, devuelve `true`. Si ocurre una excepción (`PDOException`)
 * durante el proceso de eliminación, la excepción es capturada, se revierte la transacción si estaba
 * en curso y la función devuelve `false`.
 */
    public function eliminar(int $id): bool
    {
        try {
            $this->conexion->beginTransaction();

            $sql = 'DELETE FROM productos WHERE id = :id';
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $this->conexion->commit();
            return true;

        } catch (PDOException $e) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            return false;
        }
    }
/**
 * La función verifica si un SKU dado existe en la tabla "productos" de la base de datos,
 * excluyendo un ID específico si se proporciona.
 * 
 * Args:
 *   sku (string): El parámetro `sku` en la función `existeSKU` es una cadena que representa la
 * Unidad de Mantenimiento de Inventario (SKU) de un producto. Se utiliza para identificar
 * de forma única un producto en la base de datos.
 * 
 *   idExcluir (int): El parámetro `idExcluir` en la función `existeSKU` es un parámetro opcional
 * con un valor predeterminado de 0. Se utiliza para excluir un ID de producto específico de la
 * consulta al verificar la existencia de un SKU en la base de datos. Si se proporciona un valor
 * mayor que 0, dicho producto será excluido de la búsqueda.
 * 
 * Returns:
 *   La función `existeSKU` devuelve un valor booleano. Retorna `true` si existe un producto con el
 * SKU especificado en la base de datos y `false` si no existe o si ocurre una excepción durante
 * la ejecución de la consulta.
 */
    public function existeSKU(string $sku, int $idExcluir = 0): bool
    {
        try {
            $sql = 'SELECT id FROM productos WHERE sku = :sku';

            if ($idExcluir > 0) {
                $sql .= ' AND id != :id';
            }

            $stmt = $this->conexion->prepare($sql);
            $stmt->bindValue(':sku', $sku);

            if ($idExcluir > 0) {
                $stmt->bindValue(':id', $idExcluir, PDO::PARAM_INT);
            }
            
            $stmt->execute();

            return $stmt->fetch() ? true : false;

        } catch (PDOException $e) {
            return false;
        }
    }
/**
 * La función `totalProductos` obtiene la cantidad total de productos de una tabla de base de datos
 * mediante una consulta SQL.
 * 
 * Returns:
 *   La función `totalProductos()` devuelve el número total de productos en la base de datos como un
 * valor entero. Si ocurre una excepción durante la ejecución de la consulta a la base de datos,
 * devolverá 0.
 */
    public function totalProductos(): int
    {
        try {
            $sql = 'SELECT COUNT(*) as total FROM productos';
            $stmt = $this->conexion->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$resultado['total'];

        } catch (PDOException $e) {
            return 0;
        }
    }
}
?>