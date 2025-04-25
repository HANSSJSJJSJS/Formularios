CREATE DATABASE IF NOT EXISTS formulario_db;
USE formulario_db;

-- Crear la tabla propietarios
CREATE TABLE IF NOT EXISTS propietarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_documento ENUM('CC', 'CE', 'PP') NOT NULL,
    numero_id VARCHAR(20) NOT NULL,
    genero ENUM('Mujer', 'Hombre', 'No identificado') NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    ciudad VARCHAR(50) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (tipo_documento, numero_id)
);


-- Crear la tabla administradores
CREATE TABLE IF NOT EXISTS administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_rol ENUM('A', 'V', 'P') NOT NULL COMMENT 'A=Administrador, V=Veterinario, P=Propietario',
    tipo_documento ENUM('CC', 'CE', 'PP') NOT NULL,
    numero_id VARCHAR(20) NOT NULL,
    genero ENUM('Mujer', 'Hombre', 'No identificado') NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    imagen VARCHAR(255) DEFAULT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (tipo_documento, numero_id)
);