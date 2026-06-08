PROYECTO: TIENDA MVC

Descripción:
Este proyecto es una tienda básica desarrollada en PHP utilizando el patrón MVC.
Permite administrar productos desde un panel de administrador y mostrar un catálogo público.

Requisitos:
    - PHP 8 o superior
    - MySQL/MariaDB
    - Apache(XAMPP recomendado)
    - PDO
    - Patrón MVC
    - Namespaces
    - Autoload
    - Try/Catch
    - Transacciones
    - Bootstrap

Pasos de instalación:
    1. Copiar la carpeta "TIENDA" dentro de htdocs
    2. Abrir phpMyAdmin
    3. Importar el archivo database.sql
    4. Verificar en config/Database.php que usuario y contraseña sean correctos
    5. Abrir en navegador:
        http://localhost/TIENDA

Acceso administrador:
    - Usuario: admin
    - Contraseña: admin123

Estructura del proyecto:
    TIENDA
        config/
            Autoload.php
            Crsf.php
            Database.php
        controllers/
            ApiController.php
            AuthController.php
            ProductoController.php
            PublicController.php
        models/
            BitacoraModel.php
            ProductoModel.php
            UsuarioModel.php
        views/
            auth/
                login.php
            img/
            layouts/
                footer.php
                header.php
            productos/
                create.php
                edit.php
                index.php
            public/
                catalogo.php
    .htaccess
    database.sql
    index.php
    README.txt

Rutas principales:
    - Catálogo público: http://localhost/TIENDA/catalogo
    - Login admin: http://localhost/TIENDA/login