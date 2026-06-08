<?php
/* Este fragmento de código PHP es responsable de mostrar una tabla de productos con diversos detalles,
como ID, SKU, Nombre, Precio de Compra, Precio de Venta, Stock, Imagen y Acciones (Editar y Eliminar).
A continuación, se muestra un desglose de lo que hace cada parte del código: */
use Config\Csrf;
require_once __DIR__ . '/../layouts/header.php';
$paginaActual = $pagina ?? 1;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Administración de productos</h2>
    <div>
        <a href="index.php?route=productos/create" class="btn btn-success">Nuevo producto</a>
        <a href="index.php?route=logout" class="btn btn-danger">Cerrar sesión</a>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>SKU</th>
            <th>Nombre</th>
            <th>Precio compra</th>
            <th>Precio venta</th>
            <th>Existencia</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>

    <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= (int)$producto['id']; ?></td>
                <td><?= htmlspecialchars($producto['sku']); ?></td>
                <td><?= htmlspecialchars($producto['nombre']); ?></td>
                <td><?= number_format((float)$producto['precio_compra'], 2); ?></td>
                <td><?= number_format((float)$producto['precio_venta'], 2); ?></td>
                <td><?= (int)$producto['existencia']; ?></td>

                <td>
                    <?php if (!empty($producto['imagen'])): ?>
                        <img
                            src="/TIENDA/views/img/imgProductos/<?= htmlspecialchars($producto['imagen']); ?>"
                            width="80"
                            height="80"
                            style="object-fit:contain;border-radius:8px;background:#f8f9fa;">
                        <?php else: ?> Sin imagen <?php endif; ?>
                </td>

                <td>
                    <a
                        href="/TIENDA/productos/edit/<?= (int)$producto['id']; ?>"
                    class="btn btn-primary btn-sm">
                        Editar
                    </a>

                    <form action="/TIENDA/productos/delete" method="POST" class="d-inline">
                        
                        <input type="hidden" name="id" value="<?= (int)$producto['id']; ?>">

                        <input type="hidden" name="csrf_token" value="<?= Csrf::generarToken(); ?>">

                        <button
                            type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Deseas eliminar este producto?');">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="8" class="text-center">
                    No hay productos registrados
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="mt-3">
    <?php if ($paginaActual > 1): ?>
        <a href="/TIENDA/productos?pagina=<?= $paginaActual - 1 ?>"
            class="btn btn-secondary">
            Anterior
        </a>

    <?php endif; ?>

    <?php if (!empty($totalPaginas)): ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

            <a href="/TIENDA/productos?pagina=<?= $i ?>"
                class="btn btn-sm <?= ($i == $paginaActual) ? 'btn-primary' : 'btn-outline-primary' ?>">
                <?= $i ?>
            </a>

        <?php endfor; ?>

    <?php endif; ?>

    <?php if (!empty($totalPaginas) && $paginaActual < $totalPaginas): ?>
        <a href="/TIENDA/productos?pagina=<?= $paginaActual + 1 ?>" class="btn btn-secondary">
            Siguiente
        </a>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>