<?php
require_once "controllers/ProductoController.php";
require_once "models/Producto.php";

$controller = new ProductoController();

// 🔍 BUSCAR
$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";

// ✏️ EDITAR (OBTENER DATOS)
$productoEditar = null;
if (isset($_GET['editar'])) {
    $productoEditar = $controller->obtener($_GET['editar']);
}

// INSERTAR / ACTUALIZAR
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST["id"] ?? null;
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $existencia = $_POST["existencia"];
    $precio = $_POST["precio"];

    $producto = new Producto($id, $nombre, $descripcion, $existencia, $precio);

    if ($id) {
        $controller->actualizar($producto);
    } else {
        $controller->crear($producto);
    }
}

// ELIMINAR
if (isset($_GET["eliminar"])) {
    $controller->eliminar($_GET["eliminar"]);
}

// LISTAR O BUSCAR
if ($buscar != "") {
    $productos = $controller->buscar($buscar);
} else {
    $productos = $controller->listar();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>CRUD Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f5f5f5;">

<div class="container mt-4">

    <!-- TITULO -->
    <h2 class="text-center mb-4">CRUD de Productos con PHP, PDO y POO</h2>

    <!-- CARD AGREGAR -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Agregar producto
        </div>

        <div class="card-body">
            <form method="POST" class="row g-3">
                <input type="hidden" name="id" value="<?= $productoEditar['id'] ?? '' ?>">

                <div class="col-md-3">
                    <label>Nombre</label>
                    <input type="text" name="nombre"
                    value="<?= $productoEditar['nombre'] ?? '' ?>"
                    class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label>Descripción</label>
                    <input type="text" name="descripcion"
                    value="<?= $productoEditar['descripcion'] ?? '' ?>"
                    class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label>Existencia</label>
                    <input type="number" name="existencia"
                    value="<?= $productoEditar['existencia'] ?? '' ?>"
                    class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label>Precio</label>
                    <input type="number" step="0.01" name="precio"
                    value="<?= $productoEditar['precio'] ?? '' ?>"
                    class="form-control" required>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-success w-100">
                        <?= $productoEditar ? "Actualizar" : "Guardar" ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- CARD LISTA -->
    <div class="card">
        <div class="card-header bg-dark text-white">
            Lista de productos
        </div>

        <div class="card-body">

            <!-- BUSCAR -->
            <form method="GET" class="row mb-3">
                <div class="col-md-10">
                    <input type="text" name="buscar"
                    value="<?= $buscar ?>"
                    class="form-control"
                    placeholder="Buscar por nombre o descripción">
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Buscar</button>
                </div>
            </form>

            <!-- TABLA -->
            <table class="table table-bordered table-striped">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Existencia</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= $p['nombre'] ?></td>
                        <td><?= $p['descripcion'] ?></td>
                        <td><?= $p['existencia'] ?></td>
                        <td>$<?= number_format($p['precio'], 2) ?></td>
                        <td>
                            <a href="?editar=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="?eliminar=<?= $p['id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('¿Eliminar este producto?')">
                               Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>