<?php
require_once 'Usuario.php';
require_once 'Admin.php';
require_once 'Alumno.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Examen Parcial 1 - Mini Sistema de Usuarios</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; max-width: 900px; }
        th, td { border: 1px solid #333; padding: 12px; text-align: left; }
        th { background-color: #4a90e2; color: white; }
        h1 { color: #333; }
        .error { color: red; font-weight: bold; background: #ffe6e6; padding: 15px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🏛️ Mini-Sistema de Usuarios - POO PHP (Examen Parcial 1)</h1>";

$usuarios = [];

// 1. Admin válido
$admin = new Admin("Juan Pérez", "admin@uas.edu.mx");
$usuarios[] = $admin;

// 2. Alumno válido
$alumno = new Alumno("María López", "alumno@uas.edu.mx", "20230001");
$usuarios[] = $alumno;

// 3. Intento con correo inválido (prueba de excepción)
try {
    $invalido = new Alumno("Carlos Ruiz", "correo-invalido-sin-arroba", "20230002");
    $usuarios[] = $invalido;
} catch (Exception $e) {
    echo "<div class='error'>❌ Error controlado (try/catch): " . $e->getMessage() . "</div>";
}

// Tabla de resultados
echo "<h2>Usuarios registrados correctamente</h2>";
echo "<table>
        <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Matrícula</th>
        </tr>";

foreach ($usuarios as $usuario) {
    $matricula = ($usuario instanceof Alumno) ? $usuario->getMatricula() : "N/A";
    echo "<tr>
            <td>" . $usuario->getNombre() . "</td>
            <td>" . $usuario->getCorreo() . "</td>
            <td>" . $usuario->getRol() . "</td>
            <td>" . $matricula . "</td>
          </tr>";
}

echo "</table></body></html>";