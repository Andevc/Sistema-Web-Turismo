# Sistema de Turismo en Coroico

Sistema web de **gestión turística** desarrollado para **Coroico, La Paz - Bolivia**, con arquitectura **MVC nativa en PHP y MySQL**.  
Permite a los turistas explorar lugares, reservar tours y dejar opiniones, mientras que los administradores gestionan contenido y consultan estadísticas en tiempo real.

---

## Descripción General

El sistema ofrece una experiencia completa tanto para turistas como para administradores:

- Los **turistas** pueden registrarse, explorar lugares turísticos, filtrar resultados, reservar tours, dejar calificaciones y consultar su historial de reservas.  
- Los **administradores** tienen acceso a la gestión de usuarios, lugares, tours, reservas y comentarios, además de un **panel de estadísticas** con métricas clave del sistema.

---

## Requerimientos CRUD Implementados

El sistema cumple con **10 requerimientos CRUD** principales, que en conjunto abarcan más de **35 operaciones completas** de creación, lectura, actualización y eliminación.

| # | Módulo | CREATE | READ | UPDATE | DELETE |
|---|---------|--------|------|--------|--------|
| 1 | Usuarios | ✅ | ✅ | ✅ | ✅ |
| 2 | Lugares Turísticos | ✅ | ✅ | ✅ | ✅ |
| 3 | Tours | ✅ | ✅ | ✅ | ✅ |
| 4 | Reservas | ✅ | ✅ | ✅ | ✅ |
| 5 | Comentarios | ✅ | ✅ | ✅ | ✅ |
| 6 | Búsqueda de Lugares | - | ✅ | - | - |
| 7 | Búsqueda de Tours | - | ✅ | - | - |
| 8 | Dashboard de Administrador | - | ✅ | - | - |
| 9 | Historial de Reservas | - | ✅ | - | - |
| 10 | Calificaciones por Lugar | - | ✅ | - | - |

---

## Descripción de los Módulos

### 1. Gestión de Usuarios
CRUD completo para registro, visualización, edición y eliminación de cuentas.  
Incluye validación de datos, autenticación y restricciones según rol (turista o administrador).

**Archivos:**  
`UsuarioController.php`, `Usuario.php`, `views/usuario/*`

---

### 2. Gestión de Lugares Turísticos
Permite al administrador crear, editar y eliminar lugares con información detallada.  
Los turistas pueden visualizar, filtrar y comentar sobre cada destino.

**Archivos:**  
`LugarController.php`, `LugarTuristico.php`, `views/lugares/*`

---

### 3. Gestión de Tours
CRUD completo para administrar tours, incluyendo nombre, descripción, precio, cupo y disponibilidad.  
Los usuarios pueden reservar tours disponibles.

**Archivos:**  
`TourController.php`, `Tour.php`, `views/tours/*`

---

### 4. Gestión de Reservas
Sistema de reservas funcional con validación de disponibilidad, cálculo automático de precios y control de historial.

**Archivos:**  
`ReservaController.php`, `Reserva.php`, `views/reservas/*`

---

### 5. Gestión de Comentarios
Permite a los usuarios dejar opiniones y calificaciones sobre los lugares turísticos.  
Incluye validación para evitar comentarios duplicados y control de permisos.

**Archivos:**  
`ComentarioController.php`, `Comentario.php`, `views/comentarios/*`

---

### 6. Búsqueda y Filtrado de Lugares
Búsqueda avanzada con múltiples filtros:
- Nombre o descripción  
- Categoría (Mirador, Cascada, Aventura, Cultural)  
- Precio mínimo y máximo  

**Archivos:**  
`LugarTuristico.php::buscar()`, `views/lugares/index.php`

---

### 7. Búsqueda y Filtrado de Tours
Filtros de búsqueda y ordenamiento por nombre y precio.

**Archivos:**  
`Tour.php::buscar()`, `views/tours/index.php`

---

### 8. Dashboard de Administrador
Panel de control con métricas clave:
- Cantidad total de usuarios, lugares, tours y reservas  
- Promedio de calificaciones  
- Ingresos generados  
- Tours más reservados y lugares más comentados  

**Archivos:**  
`AdminController.php`, `views/admin/dashboard.php`

---

### 9. Historial de Reservas del Usuario
Visualización de reservas activas y pasadas con detalle del tour reservado.

**Archivos:**  
`Reserva.php::getActivasPorUsuario()`, `views/usuario/mis_reservas.php`

---

### 10. Sistema de Calificaciones por Lugar
Promedio y distribución de calificaciones por destino turístico.  
Incluye conteo total de opiniones y desglose por estrellas.

**Archivos:**  
`Comentario.php::getPromedioCalificacion()`, `views/lugares/detalle.php`

---

## Uso de Almacenamiento Web

### LocalStorage (Persistente)
- Favoritos  
- Búsquedas recientes  
- Carrito de reserva  
- Preferencias del usuario  

### SessionStorage (Sesión actual)
- Filtros de búsqueda  
- Historial de navegación  

### Cookies (Seguras)
- Sesión del usuario (`HttpOnly`, `Secure`, `SameSite`)  
- Token “Recordar sesión” (30 días de duración)  

**Archivos:**  
`public/js/storage.js`

---

## Tecnologías Utilizadas

- **Backend:** PHP 7.4+ (Nativo)  
- **Base de Datos:** MySQL 5.7+  
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)  
- **Arquitectura:** MVC (Modelo - Vista - Controlador)  
- **Servidor:** Apache con mod_rewrite  

---

## ⚙️ Instalación

### 1. Requisitos
- PHP 7.4+
- MySQL 5.7+
- Apache con mod_rewrite

### 2. Configurar Base de Datos
```bash
# Crear base de datos
mysql -u root -p
CREATE DATABASE turismo_coroico;
exit;

# Importar estructura y datos
mysql -u root -p turismo_coroico < database.sql
```

### 3. Configurar Conexión
Editar `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'turismo_coroico');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Configurar URLs
Editar `config/config.php`:
```php
define('BASE_URL', 'http://localhost/turismo-coroico/');
```

Editar `.htaccess`:
```apache
RewriteBase /turismo-coroico/
```

### 5. Permisos
```bash
chmod 755 public/uploads/
```

---

## 👥 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@coroico.com | admin123 | Administrador |
| andev@gmail.com | 123456 | Turista |

---



