<?php
namespace Controllers;
use Models\ProductoModel;

class PublicController
{
/**
 * La función `catalogo` obtiene productos basados en un término de búsqueda y los muestra en una
 * vista de catálogo público.
 */
    public function catalogo(): void
    {
        $termino = trim($_GET['buscar'] ?? "");
        $productoModel = new ProductoModel();
        $productos = $productoModel->buscarPublico($termino);
        require_once __DIR__ . '/../views/public/catalogo.php';
    }
}