<?php
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../models/Producto.php";

class ProductoController {
    private $conn;
    private $table_name = "productos";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function crear(Producto $producto) {
        $query = "INSERT INTO " . $this->table_name . "
                  (nombre, descripcion, existencia, precio)
                  VALUES (:nombre, :descripcion, :existencia, :precio)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":nombre", $producto->nombre);
        $stmt->bindParam(":descripcion", $producto->descripcion);
        $stmt->bindParam(":existencia", $producto->existencia);
        $stmt->bindParam(":precio", $producto->precio);

        return $stmt->execute();
    }

    public function listar() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eliminar($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function obtener($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar(Producto $producto) {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre,
                      descripcion = :descripcion,
                      existencia = :existencia,
                      precio = :precio
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $producto->id);
        $stmt->bindParam(":nombre", $producto->nombre);
        $stmt->bindParam(":descripcion", $producto->descripcion);
        $stmt->bindParam(":existencia", $producto->existencia);
        $stmt->bindParam(":precio", $producto->precio);

        return $stmt->execute();
    }

    // 🔍 MÉTODO BUSCAR (PDF 4.1)
    public function buscar($termino) {
        $query = "SELECT * FROM " . $this->table_name . "
                  WHERE nombre LIKE :termino
                  OR descripcion LIKE :termino";

        $stmt = $this->conn->prepare($query);
        $termino = "%$termino%";
        $stmt->bindParam(":termino", $termino);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>