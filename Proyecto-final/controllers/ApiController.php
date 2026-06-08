<?php

namespace Controllers;

use Models\ProductoModel;

/* La clase `ApiController` en PHP es responsable de manejar las solicitudes relacionadas con productos.  
Interactúa con un objeto `ProductoModel` para obtener todos los productos y luego devuelve los datos de los productos  
en formato JSON con información adicional como el estado de la solicitud y el número total de productos. */
class ApiController
{
    private ProductoModel $productoModel;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
    }

    public function productos(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $productos = $this->productoModel->obtenerTodos();

        echo json_encode([
            'status' => 'success',
            'total' => count($productos),
            'data' => $productos
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
?>