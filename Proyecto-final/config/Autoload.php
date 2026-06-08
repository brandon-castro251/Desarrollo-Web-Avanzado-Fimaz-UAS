<?php
/* Este fragmento de código PHP está configurando un autoloader usando la función `spl_autoload_register`.
El propósito de un autoloader es cargar automáticamente los archivos de clases de PHP cuando se necesitan,
sin la necesidad de incluirlos o requerirlos explícitamente en el código.*/
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../';

    $class = str_replace('\\', '/', $class);
    $parts = explode('/', $class);

    if (!empty($parts)) {
        $parts[0] = strtolower($parts[0]);
    }

    $file = $baseDir . implode('/', $parts) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});