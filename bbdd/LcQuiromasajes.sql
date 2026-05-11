CREATE DATABASE IF NOT EXISTS LcQuiromasajes;
USE LcQuiromasajes;

-- ==========================================================
-- 1. SEGURIDAD Y PERFILES
-- ==========================================================
CREATE TABLE Perfil (
    id_perfil INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(200) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL, -- Almacenará hashes BCRYPT
    permiso ENUM('admin', 'trabajador', 'usuario') NOT NULL DEFAULT 'usuario'
);

-- ==========================================================
-- 2. ENTIDADES DE PERSONAS (Especialización)
-- ==========================================================
CREATE TABLE Administrador (
    id_perfil INT PRIMARY KEY,
    nombre VARCHAR(50),
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE
);

CREATE TABLE Usuario (
    id_perfil INT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(150),
    telefono VARCHAR(9),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE
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
    activo BOOLEAN DEFAULT TRUE, -- Para "despedir" sin borrar historial
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE SET NULL
);

-- ==========================================================
-- 3. CATÁLOGO DE SERVICIOS Y PRODUCTOS
-- ==========================================================
CREATE TABLE Servicios (
    id_servicio INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    duracion_minutos INT NOT NULL CHECK (duracion_minutos > 0),
    precio_actual DECIMAL(10,2) NOT NULL CHECK (precio_actual >= 0),
    activo BOOLEAN DEFAULT TRUE
);

CREATE TABLE Producto (
    id_producto INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(300),
    precio_actual DECIMAL(10,2) NOT NULL CHECK (precio_actual >= 0),
    stock INT DEFAULT 0 CHECK (stock >= 0)
);

-- ==========================================================
-- 4. OPERACIONES (CITAS Y VENTAS)
-- ==========================================================
CREATE TABLE Citas (
    id_cita INT PRIMARY KEY AUTO_INCREMENT,
    id_perfil INT NOT NULL, -- El cliente
    id_servicio INT NOT NULL,
    id_trabajador INT,
    fecha_hora DATETIME NOT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('Pendiente','Completado','Cancelado','No asistido') DEFAULT 'Pendiente',
    descuento_aplicado DECIMAL(5,2) DEFAULT 0,
    precio_final DECIMAL(10,2) NOT NULL, -- Precio pactado al momento de la reserva
    notas_cliente TEXT,
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES Servicios(id_servicio),
    FOREIGN KEY (id_trabajador) REFERENCES Trabajadores(id_trabajador) ON DELETE SET NULL
);

CREATE TABLE Opera (
    id_opera INT PRIMARY KEY AUTO_INCREMENT,
    id_perfil INT NOT NULL,
    id_producto INT NOT NULL,
    fecha_compra DATETIME DEFAULT CURRENT_TIMESTAMP,
    cantidad INT NOT NULL DEFAULT 1 CHECK (cantidad > 0),
    precio_unitario_venta DECIMAL(10,2) NOT NULL, -- Precio al que se vendió (histórico)
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Producto(id_producto) ON DELETE CASCADE
);

-- ==========================================================
-- 5. FIDELIZACIÓN Y FEEDBACK
-- ==========================================================
CREATE TABLE Opiniones (
    id_opinion INT PRIMARY KEY AUTO_INCREMENT,
    id_perfil INT NOT NULL,
    id_servicio INT,
    puntuacion INT CHECK (puntuacion BETWEEN 1 AND 5),
    comentario TEXT,
    fecha_publicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (id_perfil) REFERENCES Perfil(id_perfil) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES Servicios(id_servicio) ON DELETE SET NULL
);

-- ==========================================================
-- INSERCIÓN DE DATOS INICIALES
-- ==========================================================

-- Servicios de catálogo
INSERT INTO Servicios (nombre, descripcion, duracion_minutos, precio_actual) VALUES
('Masaje Relajante', 'Masaje suave con aceites esenciales para reducir el estrés.', 60, 45.00),
('Quiromasaje Deportivo', 'Terapia intensa para recuperación muscular post-entrenamiento.', 50, 50.00),
('Drenaje Linfático', 'Técnica manual para mejorar la circulación y retención de líquidos.', 45, 40.00);

-- ==========================================================
-- INSERCIÓN DE DATOS INICIALES (CON CONTRASEÑAS HASHEADAS)
-- ==========================================================

-- Las contraseñas reales son:
-- Lidia: adminLidia123
-- Iker: adminIker123
-- Laura: laura123
-- Ana: ana123

INSERT INTO Perfil (email, contraseña, permiso) VALUES
('lidia@lcquiromasajes.com', '$2y$10$QO0R8I0K5E.zQ8Z2R0E6v.Y8P0N0M5L.X8O5Q.R2T0V1W5Z2Q0', 'admin'),
('iker@lcquiromasajes.com', '$2y$10$W1Z2Q0R0E6v.Y8P0N0M5L.X8O5Q.R2T0V1QO0R8I0K5E.zQ8Z2', 'admin'),
('laura_staff@lcquiromasajes.com', '$2y$10$P0N0M5L.X8O5Q.R2T0V1W1Z2Q0R0E6v.Y8QO0R8I0K5E.zQ8Z2', 'trabajador'),
('ana@gmail.com', '$2y$10$E6v.Y8QO0R8I0K5E.zQ8Z2P0N0M5L.X8O5Q.R2T0V1W1Z2Q0R0', 'usuario');

-- Conectamos esos perfiles con sus tablas correspondientes (usando el id_perfil que se acaba de crear, del 1 al 4)
INSERT INTO Administrador (id_perfil, nombre) VALUES 
(1, 'Lidia'), 
(2, 'Iker');

INSERT INTO Trabajadores (id_perfil, email, nombre, apellido, especialidad, telefono, dni) VALUES
(3, 'laura_staff@lcquiromasajes.com', 'Laura', 'García', 'Drenaje Linfático', '600111222', '12345678A');

INSERT INTO Usuario (id_perfil, nombre, apellido, telefono) VALUES 
(4, 'Ana', 'López', '600000001');

-- Datos extendidos
INSERT INTO Administrador (id_perfil, nombre) VALUES (1, 'Lidia');
INSERT INTO Trabajadores (id_perfil, email, nombre, apellido, especialidad, telefono, dni) VALUES
(2, 'laura@lcquiromasajes.com', 'Laura', 'García', 'Drenaje Linfático', '600111222', '12345678A');
INSERT INTO Usuario (id_perfil, nombre, apellido, telefono) VALUES (3, 'Ana', 'López', '600000001');

-- Cita de ejemplo vinculada al catálogo
INSERT INTO Citas (id_perfil, id_servicio, id_trabajador, fecha_hora, precio_final) VALUES
(3, 1, 1, '2026-03-20 10:00:00', 45.00);