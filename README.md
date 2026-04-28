# LC Quiromasajes — Proyecto Final DAW

Aplicación web desarrollada en **PHP + MySQL** para la gestión de un centro de quiromasaje. Permite mostrar tratamientos, registrar/iniciar sesión de usuarios, consultar perfil, visualizar citas y disponer de un panel básico de administración.

## 1) Objetivo del proyecto

Este proyecto implementa una base funcional para un sistema de reservas y gestión de clientes orientado a un centro de bienestar.

Funcionalidades principales:

- Catálogo público de servicios (tratamientos activos).
- Registro e inicio de sesión con roles (`usuario`, `admin`, `trabajador`).
- Zona privada de cliente con perfil y listado de citas.
- Panel de administración con tabla de citas y datos cruzados.
- Endpoint para consultar horas ocupadas por fecha (JSON).
- Esquema SQL completo para levantar la base de datos.

## 2) Stack tecnológico

- **Backend:** PHP (PDO para acceso a datos).
- **Base de datos:** MySQL / MariaDB.
- **Frontend:** HTML, CSS, JavaScript vanilla.
- **Servidor recomendado en local:** XAMPP, WAMP o LAMP.

## 3) Estructura del proyecto

```text
.
├── index.php                    # Home + listado de servicios activos
├── formulario.php               # Formulario de contacto
├── procesar_contacto.php        # Procesamiento del formulario de contacto
├── mis_citas.php                # Área privada con citas del usuario logueado
├── perfil.php                   # Área privada con datos del perfil
├── admin.php                    # Panel de administración (solo admin)
├── obtener_horas.php            # Endpoint JSON de horas ocupadas
├── includes/
│   ├── db.php                   # Conexión PDO a MySQL
│   ├── header.php               # Cabecera/navegación común
│   └── footer.php               # Pie común + carga de JS
├── Login/
│   ├── index.html               # Formulario login/registro
│   ├── procesar_auth.php        # Lógica de autenticación y alta de cuenta
│   └── logout.php               # Cierre de sesión
├── assets/
│   ├── css/style.css            # Estilos globales
│   └── js/script.js             # Interacciones front (navbar, menú, etc.)
└── bbdd/LcQuiromasajes.sql      # Esquema + datos semilla
```

## 4) Requisitos previos

1. PHP 8.x (recomendado, compatible con PDO MySQL).
2. MySQL 8.x o MariaDB equivalente.
3. Servidor web (Apache/Nginx) o entorno integrado (XAMPP).

## 5) Instalación y puesta en marcha

### Paso 1 — Clonar o copiar el proyecto

Ubica esta carpeta dentro del directorio servido por tu servidor web (por ejemplo, `htdocs` en XAMPP).

### Paso 2 — Crear la base de datos

Importa el script SQL:

- Archivo: `bbdd/LcQuiromasajes.sql`
- Crea la BD `LcQuiromasajes`, tablas y datos iniciales.

Ejemplo por CLI:

```bash
mysql -u root -p < bbdd/LcQuiromasajes.sql
```

### Paso 3 — Configurar conexión

Edita `includes/db.php` si tus credenciales no son las de desarrollo:

- Host: `localhost`
- Base de datos: `LcQuiromasajes`
- Usuario: `root`
- Password: `''` (vacío en local por defecto del proyecto)

### Paso 4 — Ejecutar en navegador

Abre la URL del proyecto, por ejemplo:

```text
http://localhost/Trabajo-Final-De-Curso/index.php
```

## 6) Modelo de datos (resumen)

El script SQL define entidades de autenticación, personas, servicios, operaciones y feedback.

Tablas clave:

- **`Perfil`**: credenciales, email único y rol del usuario.
- **`Usuario`**: datos de cliente vinculados a `Perfil`.
- **`Trabajadores`** y **`Administrador`**: especializaciones por rol.
- **`Servicios`**: catálogo de tratamientos con duración/precio/estado activo.
- **`Citas`**: reservas con fecha/hora, estado y precio final.
- **`Producto`** + **`Opera`**: módulo de venta (estructura preparada).
- **`Opiniones`**: reseñas/comentarios.

## 7) Flujo funcional por pantallas

### Inicio (`index.php`)

- Carga servicios activos desde la tabla `Servicios`.
- Muestra CTA para explorar tratamientos e iniciar reserva.

### Login/Registro (`Login/index.html` + `Login/procesar_auth.php`)

- Registro: crea entrada en `Perfil` y automáticamente un `Usuario` básico.
- Login: valida hash con `password_verify` y crea sesión.
- Redirección por rol:
  - `admin` → `admin.php`
  - resto → `index.php`

### Perfil y citas (`perfil.php`, `mis_citas.php`)

- Requieren sesión activa.
- `perfil.php` muestra email y tipo de cuenta.
- `mis_citas.php` lista citas del usuario con JOIN a servicio y especialista.

### Administración (`admin.php`)

- Restringido a sesiones con `permiso = admin`.
- Lista citas con cliente, servicio, trabajador, estado y total.

### Horas ocupadas (`obtener_horas.php`)

- Endpoint GET por fecha (`?fecha=YYYY-MM-DD`).
- Devuelve JSON con horas reservadas no canceladas.

## 8) Roles y control de acceso

- **Invitado:** puede ver home y formulario de contacto.
- **Usuario autenticado:** acceso a perfil y sus citas.
- **Administrador:** acceso adicional a panel admin.

El control se realiza con `$_SESSION` en cada página protegida.

## 9) Observaciones actuales del código

- Existe un enlace de reserva desde `index.php` hacia `reservar.php`, pero ese archivo no está presente en este repositorio.
- `procesar_contacto.php` contiene una implementación de ejemplo (mensaje de éxito simulado) y comentarios indicando que convendría una tabla específica para consultas.
- El SQL incluye datos de ejemplo y algunas inserciones adicionales de prueba para poblar tablas relacionadas.

## 10) Mejoras recomendadas

1. Implementar `reservar.php` y flujo completo de creación/edición/cancelación de citas.
2. Endurecer validaciones server-side (campos, longitudes, formato teléfono, etc.).
3. Añadir protección CSRF en formularios críticos.
4. Añadir migraciones versionadas y seeds separados.
5. Externalizar configuración sensible (`.env`) en lugar de credenciales hardcodeadas.
6. Crear tests funcionales y de integración para autenticación y citas.
7. Añadir logging estructurado de errores en producción.

## 11) Credenciales de ejemplo (según script SQL)

El archivo `bbdd/LcQuiromasajes.sql` documenta usuarios de ejemplo y sus contraseñas en comentarios. Se recomienda cambiar estas credenciales al desplegar cualquier entorno real.

## 12) Licencia

Proyecto académico (TFG/Proyecto final DAW). Ajusta este apartado con la licencia que quieras aplicar (`MIT`, `Apache-2.0`, etc.) si planeas publicarlo.
