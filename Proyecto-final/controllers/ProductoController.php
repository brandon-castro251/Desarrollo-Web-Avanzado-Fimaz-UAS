<?php
namespace Controllers;
use Config\Csrf;
use Models\BitacoraModel;
use Models\ProductoModel;

/* La clase `ProductoController` en PHP gestiona operaciones CRUD para productos, incluyendo verificación de sesión,
validación de tokens CSRF, manejo de datos, subida de imágenes, manejo de errores y redirección. */
class ProductoController
{
    private ProductoModel $productoModel;

    public function __construct()
    {
        $this->productoModel = new ProductoModel();
    }

/**
 * La función `verificarSesion` verifica si hay una sesión activa y si el usuario ha iniciado sesión como administrador;
 * si no, redirige a la página de inicio de sesión.
 */
    private function verificarSesion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin'])) {
            header('Location: /TIENDA/login');
            exit;
        }
    }

/**
 * La función `verificarCsrf` verifica si el token CSRF en la solicitud POST es válido y redirige a una
 * ubicación específica si no lo es.
 */
    private function verificarCsrf(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!Csrf::validarToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['error'] = 'Token CSRF inválido o expirado.';
            header('Location: /TIENDA/productos');
            exit;
        }
    }

/**
 * La función `index` obtiene una lista de productos desde un modelo, calcula los parámetros de paginación y luego
 * carga una vista para mostrar los productos.
 */
    public function index(): void
    {
        $this->verificarSesion();
        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($pagina < 1) { $pagina = 1;}
        $limite = 5;
        $inicio = ($pagina - 1) * $limite;
        $productos = $this->productoModel->obtenerTodos($limite, $inicio);
        $totalProductos = $this->productoModel->totalProductos();
        $totalPaginas = ceil($totalProductos / $limite);

        require_once __DIR__ . '/../views/productos/index.php';
    }

/**
 * La función `create` en PHP verifica la sesión y requiere un archivo de vista específico para la
 * creación de productos.
 */
    public function create(): void
    {
        $this->verificarSesion();
        require_once __DIR__ . '/../views/productos/create.php';
    }

/**
 * La función `store` en PHP maneja la creación de un nuevo producto, incluyendo la validación de los datos del formulario,
 * la subida del archivo de imagen del producto y la inserción en la base de datos, con manejo de errores y redirección según sea necesario.
 */
    public function store(): void
    {
        $this->verificarSesion();
        $this->verificarCsrf();
        $nombreImagen = null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $archivo = $_FILES['imagen'];
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (!in_array($extension, $permitidas)) {
                $_SESSION['error'] = 'Solo se permiten JPG, JPEG, PNG o WEBP.';
                header('Location: /TIENDA/productos/create');
                exit;
            }

            $nombreImagen = uniqid() . '.' . $extension;
            move_uploaded_file($archivo['tmp_name'], __DIR__ . '/../views/img/imgProductos/' . $nombreImagen);
        }

        $data = [
            'sku' => trim($_POST['sku'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio_compra' => trim($_POST['precio_compra'] ?? ''),
            'precio_venta' => trim($_POST['precio_venta'] ?? ''),
            'existencia' => trim($_POST['existencia'] ?? ''),
            'imagen' => $nombreImagen
        ];

        foreach ($data as $campo => $valor) {
            if ($valor === '' && $campo !== 'imagen') {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                header('Location: /TIENDA/productos/create');
                exit;
            }
        }

        if (!is_numeric($data['precio_compra']) || !is_numeric($data['precio_venta'])
             || !is_numeric($data['existencia'])) {
            $_SESSION['error'] = 'Precio de compra, precio de venta y existencia deben ser numéricos.';
            header('Location: /TIENDA/productos/create');
            exit;
        }

        if ((float)$data['precio_venta'] < (float)$data['precio_compra']) {
            $_SESSION['error'] = 'El precio de venta debe ser mayor o igual al precio de compra.';
            header('Location: /TIENDA/productos/create');
            exit;
        }

        if ((float)$data['precio_compra'] < 0) {
            $_SESSION['error'] = 'El precio de compra no puede ser menor a 0.';
            header('Location: /TIENDA/productos/create');
            exit;
        }

        if ((float)$data['precio_venta'] < 0) {
            $_SESSION['error'] = 'El precio de venta no puede ser menor a 0.';
            header('Location: /TIENDA/productos/create');
            exit;
        }

        if ((int)$data['existencia'] < 0) {
            $_SESSION['error'] = 'La existencia no puede ser menor a 0.';
            header('Location: /TIENDA/productos/create');
            exit;
        }

        if ($this->productoModel->existeSKU($data['sku'])) {
            $_SESSION['error'] = 'El SKU ya existe.';
            header('Location: /TIENDA/productos/create');
            exit;
        }

        $this->productoModel->crear($data);

        $bitacora = new BitacoraModel();
        $bitacora->registrar('Se creó el producto: ' . $data['nombre']);

        $_SESSION['success'] = 'Producto registrado correctamente.';
        header('Location: /TIENDA/productos');
        exit;
    }

/**
 * La función `edit` en PHP verifica la sesión del usuario, obtiene un producto por su ID y redirige a la
 * página de edición si el producto es encontrado; de lo contrario, muestra un mensaje de error.
 */
    public function edit(): void
    {
        $this->verificarSesion();

        $id = (int)($_GET['id'] ?? 0);
        $producto = $this->productoModel->obtenerPorId($id);

        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado.';
            header('Location: /TIENDA/productos');
            exit;
        }

        require_once __DIR__ . '/../views/productos/edit.php';
    }

/**
 * Esta función en PHP actualiza la información de un producto, incluyendo la gestión de la subida de imágenes, la
 * validación de datos de entrada y el manejo de errores.
 */
    public function update(): void
    {
        $this->verificarSesion();
        $this->verificarCsrf();

        $id = (int)($_POST['id'] ?? 0);

        $productoActual = $this->productoModel->obtenerPorId($id);
        if (!$productoActual) {
            $_SESSION['error'] = 'Producto no encontrado.';
            header('Location: /TIENDA/productos');
            exit;
        }

        $nombreImagen = $productoActual['imagen'] ?? null;

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
            $archivo = $_FILES['imagen'];
            $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
            $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($extension, $permitidas)) {
                $nombreImagen = uniqid() . '.' . $extension;
                move_uploaded_file($archivo['tmp_name'],__DIR__ . '/../views/img/imgProductos/' . $nombreImagen);
            }
        }

        $data = [
            'sku' => trim($_POST['sku'] ?? ''),
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio_compra' => trim($_POST['precio_compra'] ?? ''),
            'precio_venta' => trim($_POST['precio_venta'] ?? ''),
            'existencia' => trim($_POST['existencia'] ?? ''),
            'imagen' => $nombreImagen
        ];

        foreach ($data as $campo => $valor) {
            if ($valor === '' && $campo !== 'imagen') {
                $_SESSION['error'] = 'Todos los campos son obligatorios.';
                header("Location: /TIENDA/productos/edit?id=$id");
                exit;
            }
        }

        if ((float)$data['precio_venta'] < (float)$data['precio_compra']) {
            $_SESSION['error'] = 'Precio inválido.';
            header("Location: /TIENDA/productos/edit?id=$id");
            exit;
        }

        if ((int)$data['existencia'] < 0) {
            $_SESSION['error'] = 'Existencia inválida.';
            header("Location: /TIENDA/productos/edit?id=$id");
            exit;
        }

        if ($this->productoModel->existeSKU($data['sku'], $id)) {
            $_SESSION['error'] = 'El SKU ya existe.';
            header("Location: /TIENDA/productos/edit?id=$id");
            exit;
        }

        $this->productoModel->actualizar($id, $data);

        $bitacora = new BitacoraModel();
        $bitacora->registrar('Se actualizó el producto: ' . $data['nombre']);

        $_SESSION['success'] = 'Producto actualizado correctamente.';
        header('Location: /TIENDA/productos');
        exit;
    }


/**
 * La función PHP `delete` verifica la sesión y el token CSRF, elimina un producto por su ID, registra la acción en una
 * Bitácora y redirige con mensajes de éxito o error.
 */
    public function delete(): void
    {
        $this->verificarSesion();
        $this->verificarCsrf();

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            header('Location: /TIENDA/productos');
            exit;
        }

        $producto = $this->productoModel->obtenerPorId($id);

        if (!$producto) {
            $_SESSION['error'] = 'Producto no encontrado.';
            header('Location: /TIENDA/productos');
            exit;
        }

        $this->productoModel->eliminar($id);

        $bitacora = new BitacoraModel();
        $bitacora->registrar('Se eliminó el producto: ' . $producto['nombre']);

        $_SESSION['success'] = 'Producto eliminado correctamente.';
        header('Location: /TIENDA/productos');
        exit;
    }
}