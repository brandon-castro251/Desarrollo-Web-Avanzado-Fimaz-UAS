CREATE DATABASE IF NOT EXISTS tienda_mvc;
USE tienda_mvc;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  nombre_completo VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS productos (
  id INT(11) NOT NULL AUTO_INCREMENT,
  sku VARCHAR(50) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT NOT NULL,
  precio_compra DECIMAL(10,2) NOT NULL,
  precio_venta DECIMAL(10,2) NOT NULL,
  existencia INT(11) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  imagen VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY sku (sku)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS bitacora (
  id INT(11) NOT NULL AUTO_INCREMENT,
  accion VARCHAR(255) NOT NULL,
  fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO usuarios (id, username, password, nombre_completo) VALUES
(5, 'admin', '$2y$10$qifff0eslJ0N2Y5GGq4KtOgFm0LH.wBLRGvD1ZdniIIKIXUz1kPpq', 'Administrador General');

INSERT INTO productos (id, sku, nombre, descripcion, precio_compra, precio_venta, existencia, created_at, updated_at, imagen) VALUES
(22, 'AJDH784', 'TECLADO', 'Conexión inalámbrica mediante Bluetooth o receptor USB', 3000.00, 4000.00, 3, '2026-06-06 22:24:48', '2026-06-07 18:38:19', '6a25ba9bc73f2.webp'),
(23, 'ABC456', 'Laptop ThinkPad', 'El icónico botón rojo en el centro del teclado', 7000.00, 8000.00, 15, '2026-06-06 23:08:58', '2026-06-07 18:37:51', '6a25ba7fda237.jpg'),
(24, 'ABC4JH5', 'Televisión', 'Resolución Full HD, 4K o 8K', 15000.00, 35000.00, 3, '2026-06-06 23:31:51', '2026-06-07 18:36:12', '6a25ba1cb73da.jpg'),
(25, 'ABC4567H78', 'Audífonos', 'Micrófono integrado y batería de larga duración', 1000.00, 2000.00, 8, '2026-06-07 00:16:43', '2026-06-07 18:38:05', '6a25ba8d76c06.jpg'),
(26, 'ABC45676', 'Celular Iphone 13', 'Pantalla Super Retina XDR OLED', 15000.00, 23000.00, 6, '2026-06-07 02:22:11', '2026-06-07 18:38:40', '6a25bab058e58.jpg'),
(28, 'ABC456MSD', 'AirPods', 'Volumen al 100%', 2000.00, 3000.00, 56, '2026-06-07 21:48:15', '2026-06-07 21:48:15', '6a25e71f62672.jpg');

INSERT INTO bitacora (id, accion, fecha) VALUES
(1, 'Se creó el producto: Laptop ThinkPad', '2026-06-06 23:08:58'),
(2, 'Se actualizó el producto: COCA COLA', '2026-06-07 00:43:11'),
(3, 'Se actualizó el producto: COCuuuu', '2026-06-07 00:43:25'),
(4, 'Se creó el producto: COCA COLA LING', '2026-06-07 02:22:11'),
(5, 'Se creó el producto: COCA COLA joaa', '2026-06-07 02:42:45'),
(6, 'Se actualizó el producto: TECLADO', '2026-06-07 06:29:52'),
(7, 'Se actualizó el producto: Laptop ThinkPad', '2026-06-07 06:31:34'),
(8, 'Se actualizó el producto: Televisión', '2026-06-07 06:35:24'),
(9, 'Se actualizó el producto: Audífonos', '2026-06-07 06:38:23'),
(10, 'Se actualizó el producto: Celular Iphone 13', '2026-06-07 06:40:52'),
(11, 'Se actualizó el producto: Celular Iphone 13', '2026-06-07 06:41:07'),
(12, 'Se eliminó el producto: COCA COLA joaa', '2026-06-07 07:03:22'),
(13, 'Se actualizó el producto: TECLADO', '2026-06-07 18:34:16'),
(14, 'Se actualizó el producto: Televisión', '2026-06-07 18:36:12'),
(15, 'Se actualizó el producto: Laptop ThinkPad', '2026-06-07 18:37:51'),
(16, 'Se actualizó el producto: Audífonos', '2026-06-07 18:38:05'),
(17, 'Se actualizó el producto: TECLADO', '2026-06-07 18:38:19'),
(18, 'Se actualizó el producto: Celular Iphone 13', '2026-06-07 18:38:40'),
(19, 'Se creó el producto: AirPods', '2026-06-07 21:48:15'),
(20, 'Se actualizó el producto: TECLADO', '2026-06-07 21:58:08');

COMMIT;