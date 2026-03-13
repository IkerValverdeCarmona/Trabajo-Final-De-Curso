CREATE DATABASE IF NOT EXISTS LcQuiromasajes;
USE LcQuiromasajes;

-- ==========================================================
-- SEGURIDAD Y PERFILES
-- ==========================================================
CREATE TABLE Perfil (
    id_perfil INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(200) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    permiso ENUM('admin', 'trabajador', 'usuario') NOT NULL
);

-- ==========================================================
--ENTIDADES DE PERSONAS
-- ==========================================================
CREATE TABLE Administrador (
    id_perfil INT PRIMARY KEY,
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Usuario (
    id_perfil INT PRIMARY KEY,
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Trabajadores (
    id_trabajador INT PRIMARY KEY AUTO_INCREMENT,
    id_perfil INT UNIQUE,
    email VARCHAR(200) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(150) NOT NULL,
    especialidad VARCHAR(100),
    telefono VARCHAR(9),
    dni VARCHAR(9) UNIQUE,
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE SET NULL ON UPDATE CASCADE
);

-- ==========================================================
-- CATÁLOGO Y NEGOCIO
-- ==========================================================
CREATE TABLE Producto (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(300),
    precio DECIMAL(10,2) NOT NULL CHECK (precio >= 0),
    stock INT DEFAULT 0 CHECK (stock >= 0)
);

CREATE TABLE Citas (
    id_cita INT PRIMARY KEY AUTO_INCREMENT,
    fecha_hora DATETIME NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('Pendiente','Completado','Cancelado') DEFAULT 'Pendiente',
    descuento DECIMAL(5,2) DEFAULT 0 CHECK (descuento >= 0),
    nombre_servicio VARCHAR(200) NOT NULL, 
    precio_final DECIMAL(10,2) NOT NULL DEFAULT 0,  
    id_perfil INT NOT NULL, 
    id_trabajador INT,
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_trabajador) REFERENCES Trabajadores(id_trabajador) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE Opera (
    id_opera INT PRIMARY KEY AUTO_INCREMENT,
    id_perfil INT NOT NULL,
    id_producto INT NOT NULL,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    cantidad INT NOT NULL DEFAULT 1 CHECK (cantidad > 0),
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto) ON DELETE CASCADE ON UPDATE CASCADE
);

-- ==========================================================
-- INSERCIÓN DE DATOS (CON LOS NUEVOS ADMINS)
-- ==========================================================

-- Perfiles de Administradores (Lidia e Iker)
INSERT INTO Perfil (email, contraseña, permiso) VALUES
('lidia@lcquiromasajes.com', 'adminLidia123', 'admin'), -- ID 1
('iker@lcquiromasajes.com', 'adminIker123', 'admin');   -- ID 2

-- Registramos sus IDs en la tabla específica de Administrador
INSERT INTO Administrador (id_perfil) VALUES (1), (2);

-- Resto de perfiles de prueba
INSERT INTO Perfil (email, contraseña, permiso) VALUES
('ana@gmail.com', 'ana123', 'usuario'),                       -- ID 3
('carlos@gmail.com', 'carlos123', 'usuario'),                 -- ID 4
('laura_staff@lcquiromasajes.com', 'laura123', 'trabajador'); -- ID 5

-- Trabajadores
INSERT INTO Trabajadores (id_perfil, email, nombre, apellido, especialidad, telefono, dni) VALUES
(5, 'laura@lcquiromasajes.com', 'Laura', 'García', 'Masaje relajante', '600111222', '12345678A');

-- Productos de ejemplo
INSERT INTO Producto (nombre, descripcion, precio, stock) VALUES
('Vela aromática lavanda', 'Vela relajante hecha a mano', 8.50, 15),
('Aceite esencial de eucalipto', 'Aceite para masajes y aromaterapia', 9.90, 20);

-- Citas de ejemplo (Usando el ID de perfil de Ana y el ID de trabajador de Laura)
INSERT INTO Citas (fecha_hora, nombre_servicio, precio_final, id_perfil, id_trabajador) VALUES
('2026-03-20 10:00:00', 'Masaje relajante 60 min', 35.00, 3,  1);
