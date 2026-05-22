# LC Quiromasajes — Proyecto Final DAW

Aplicación web desarrollada en **PHP + MySQL** para la gestión integral de un centro de quiromasajes y bienestar.

El sistema permite gestionar reservas, usuarios, productos, citas y administración interna mediante una plataforma web moderna, responsive y optimizada para producción.

---

# 📌 Objetivo del proyecto

El objetivo principal del proyecto es digitalizar la gestión de un centro de bienestar mediante una aplicación web accesible, intuitiva y escalable.

La plataforma permite:

- Mostrar tratamientos y servicios.
- Gestionar reservas online.
- Registro e inicio de sesión.
- Gestión de perfil de cliente.
- Gestión de citas.
- Tienda online de productos.
- Panel de administración.
- Gestión de trabajadores y clientes.
- Sistema de opiniones y reseñas.

---

# 🛠 Stack tecnológico

## Backend
- PHP 8
- PDO (PHP Data Objects)

## Base de datos
- MySQL / MariaDB

## Frontend
- HTML5
- CSS3
- JavaScript Vanilla
- Bootstrap 5

## Hosting
- InfinityFree

## Entorno de desarrollo
- XAMPP

---

# 📂 Estructura actual del proyecto

```text
.
├── public/
│  ├── index.php
│  ├── perfil.php
│  ├── mis_citas.php
│  ├── mis_pedidos.php
│  ├── resenas.php
│  ├── formulario.php
│  ├── obtener_horas.php
│  ├── cancelar_cita.php
│  ├── procesar_contacto.php
│  ├── procesar_resena.php
│  └──reservar.php
├── admin/
│   ├── index.php
│   ├── admin_citas.php
│   ├── admin_productos.php
│   ├── admin_servicios.php
│   ├── actualizar_estado.php
│   ├── admin_pedidos.php
│   ├── eliminar_cita.php
│   ├── gestionar_cita.php
│   ├── nuevo_producto.php
│   ├── nuevo_servicio.php    
│   └── admin_gestion_resenas.php
│
├── auth/
│   ├── login.php
│   ├── registro.php
│   ├── logout.php
│   ├── google_callback.php
│   ├── procesar_login.php
│   ├── procesar_registro.php
│   └── procesar_auth.php
│
├── tienda/
│   ├── index.php
│   ├── carrito.php
│   └── procesar_reserva.php
│
├── includes/
│   ├── db.php
│   ├── header.php
│   ├── footer.php
│   └── google_config.php
│
├── assets/
│   ├── css/
│   │   └── style.css
│   │
│   ├── js/
│   │   └── script.js
│   │
│   └── img/
│
├── bbdd/
│   ├──backup_lcquiromasajes.sql
│   └── LcQuiromasajes.sql
│
└── README.md
⚙️ Requisitos previos

Para ejecutar el proyecto en local se necesita:

PHP 8 o superior
MySQL 8 o MariaDB
Apache o Nginx
XAMPP (recomendado)
🚀 Instalación local
1. Clonar o copiar el proyecto

Colocar el proyecto dentro de:

htdocs/

si se utiliza XAMPP.

Ejemplo:

C:/xampp/htdocs/LcQuiromasajes
2. Crear la base de datos

Importar:

bbdd/LcQuiromasajes.sql -- sin datos
bbdd/backup_lcquiromasajes -- datos de ejemplo

Esto creará:

tablas,
relaciones,
datos de ejemplo,
usuarios iniciales.

3. Configurar conexión MySQL

Editar:

includes/db.php

Configurar:

$host = "localhost";
$dbname = "LcQuiromasajes";
$user = "root";
$password = "1234";
4. Ejecutar el proyecto

Abrir:

http://localhost/LcQuiromasajes/

🌐 Despliegue online (InfinityFree)

El proyecto está preparado para desplegarse en:

InfinityFree

Configuración utilizada
Hosting
InfinityFree Free Hosting
Base de datos
MySQL proporcionado por InfinityFree
Carpeta pública
htdocs/
Configuración de conexión en producción

Ejemplo:

$host = "sqlXXX.infinityfree.com";
$dbname = "if0_XXXXXXXX_LcQuiromasajes";
$user = "if0_XXXXXXXX";
$password = "********";
👥 Roles del sistema
Invitado
Ver tratamientos
Ver productos
Registrarse
Iniciar sesión
Usuario
Reservar citas
Ver perfil
Gestionar pedidos
Ver historial
Trabajador
Gestión de citas asignadas
Administrador
Gestión completa del sistema
Gestión de usuarios
Gestión de productos
Gestión de servicios
Gestión de trabajadores
🔒 Seguridad implementada
Hash de contraseñas con password_hash
Validación de sesiones
Control de acceso por roles
Consultas preparadas PDO
Escape de salida con htmlspecialchars
📱 Responsive Design

La aplicación está optimizada para:

móviles,
tablets,
escritorio.

Incluye:

menú hamburguesa,
diseño adaptable,
navegación responsive,
optimización visual móvil.
📌 Funcionalidades principales
Sistema de autenticación
Registro
Login
Logout
Roles
Gestión de citas
Visualización
Reserva
Horas ocupadas dinámicas
Tienda online
Productos
Carrito
Checkout
Administración
CRUD de servicios
CRUD de productos
Gestión de usuarios
Gestión de trabajadores
🔮 Mejoras futuras
Pasarela de pago Stripe/PayPal
Sistema de emails automáticos
Calendario interactivo
Notificaciones
Panel estadístico
API REST
Sistema de cupones
