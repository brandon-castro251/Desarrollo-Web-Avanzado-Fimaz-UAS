<?php
/* Este fragmento de código PHP está creando un formulario para registrar un producto.
A continuación, se muestra un desglose de lo que hace el código: */
require_once __DIR__ . '/../layouts/header.php';
use Config\Csrf;
?>

<h2>Registrar producto</h2>
<form action="/TIENDA/productos/store" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

    <div class="mb-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Precio compra</label>
        <input type="number" step="0.01" name="precio_compra" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Precio venta</label>
        <input type="number" step="0.01" name="precio_venta" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Existencia</label>
        <input type="number" name="existencia" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Imagen del producto</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="/TIENDA/productos" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>