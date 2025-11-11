# 🏔️ Sistema de Turismo en Coroico

Sistema web de gestión turística para Coroico, La Paz - Bolivia. Desarrollado con arquitectura MVC nativa en PHP y MySQL.

## 📋 Descripción

Sistema que permite a turistas explorar lugares turísticos, reservar tours y compartir opiniones, mientras que los administradores gestionan el contenido y visualizan estadísticas.

## 🎯 10 Requerimientos CRUD Implementados
1. Gestión de Usuarios 👤
2. Gestión de Lugares Turísticos 🏔️
3. Gestión de Tours 🎒
4. Gestión de Reservas 📅
5. Gestión de Comentarios 💬
6. Búsqueda y Filtrado de Lugares 🔍
7. Búsqueda y Filtrado de Tours 🔎
8. Dashboard de Administrador 📊
9. Historial de Reservas del Usuario 📜
10. Sistema de Calificaciones por Lugar ⭐

### 1. Gestión de Usuarios 👤
**Operaciones CRUD completas sobre la tabla `usuarios`**

- **CREATE**: Registro de nuevos turistas
  - Ruta: `/usuario/registro`
  - Formulario con nombre, email, contraseña
  
- **READ**: Visualización de perfil
  - Ruta: `/usuario/perfil`
  - Muestra datos del usuario autenticado
  
- **UPDATE**: Edición de datos personales
  - Ruta: `/usuario/perfil` (formulario)
  - Actualiza nombre, email y opcionalmente contraseña
  
- **DELETE**: Eliminación de cuenta
  - Botón en perfil de usuario
  - Verifica que no tenga reservas activas antes de eliminar

**Archivos**: `UsuarioController.php`, `Usuario.php`, `views/usuario/*`

---

### 2. Gestión de Lugares Turísticos 🏔️
**Operaciones CRUD completas sobre la tabla `lugares_turisticos` (Solo Admin)**

- **CREATE**: Agregar nuevos lugares
  - Ruta: `/lugares/crear`
  - Formulario: nombre, descripción, categoría, precio, horarios, dirección
  
- **READ**: Listar y ver detalles
  - Ruta: `/lugares` (listado con filtros)
  - Ruta: `/lugares/detalle?id=X` (detalle completo)
  
- **UPDATE**: Editar lugares existentes
  - Ruta: `/lugares/editar?id=X`
  - Modifica todos los campos del lugar
  
- **DELETE**: Eliminar lugares
  - Botón en listado y detalle (admin)
  - Verifica que no tenga comentarios antes de eliminar

**Archivos**: `LugarController.php`, `LugarTuristico.php`, `views/lugares/*`

---

### 3. Gestión de Tours 🎒
**Operaciones CRUD completas sobre la tabla `tours` (Solo Admin)**

- **CREATE**: Crear nuevos tours
  - Ruta: `/tours/crear`
  - Formulario: nombre, descripción, precio, cupo máximo
  
- **READ**: Listar y ver detalles
  - Ruta: `/tours` (listado con filtros)
  - Ruta: `/tours/detalle?id=X` (detalle + disponibilidad)
  
- **UPDATE**: Editar tours
  - Ruta: `/tours/editar?id=X`
  - Modifica información del tour
  
- **DELETE**: Eliminar tours
  - Botón en listado y detalle (admin)
  - Verifica que no tenga reservas antes de eliminar

**Archivos**: `TourController.php`, `Tour.php`, `views/tours/*`

---

### 4. Gestión de Reservas 📅
**Operaciones CRUD completas sobre la tabla `reservas`**

- **CREATE**: Realizar nueva reserva
  - Ruta: `/reservas/crear?tour=X`
  - Formulario: fecha, cantidad de personas
  - Calcula precio total automáticamente
  - Verifica disponibilidad de cupos
  
- **READ**: Ver mis reservas
  - Ruta: `/usuario/mis-reservas` (turistas)
  - Ruta: `/reservas/detalle?id=X` (detalle completo)
  - Separa reservas activas y pasadas
  
- **UPDATE**: Modificar reserva
  - Ruta: `/reservas/editar?id=X`
  - Permite cambiar fecha y cantidad de personas
  
- **DELETE**: Cancelar reserva
  - Botón en "Mis Reservas" y detalle
  - Solo disponible para reservas futuras

**Archivos**: `ReservaController.php`, `Reserva.php`, `views/reservas/*`

---

### 5. Gestión de Comentarios 💬
**Operaciones CRUD completas sobre la tabla `comentarios`**

- **CREATE**: Agregar opinión
  - Formulario en `/lugares/detalle?id=X`
  - Calificación de 1-5 estrellas + comentario
  - Validación: un comentario por usuario por lugar
  
- **READ**: Ver comentarios
  - Mostrados en detalle de cada lugar
  - Incluye nombre del usuario, fecha y calificación
  
- **UPDATE**: Editar mi comentario
  - Ruta: `/comentarios/editar?id=X`
  - Solo el autor o admin puede editar
  
- **DELETE**: Eliminar comentario
  - Botón en cada comentario
  - Solo el autor o admin puede eliminar

**Archivos**: `ComentarioController.php`, `Comentario.php`, `views/comentarios/*`

---

### 6. Búsqueda y Filtrado de Lugares 🔍
**Operación READ con filtros múltiples sobre `lugares_turisticos`**

Filtros disponibles en `/lugares`:
- **Por nombre**: Búsqueda de texto en nombre y descripción
- **Por categoría**: Mirador, Cascada, Aventura, Cultural
- **Por precio mínimo**: Mayor o igual a X
- **Por precio máximo**: Menor o igual a X

**SQL**: `SELECT * FROM lugares_turisticos WHERE nombre LIKE ? AND categoria = ? AND precio_entrada BETWEEN ? AND ?`

**Archivos**: `LugarTuristico.php::buscar()`, `views/lugares/index.php`

---

### 7. Búsqueda y Filtrado de Tours 🔎
**Operación READ con filtros y ordenamiento sobre `tours`**

Filtros disponibles en `/tours`:
- **Por nombre**: Búsqueda de texto
- **Por precio mínimo/máximo**: Rango de precios
- **Ordenamiento**: 
  - Nombre (A-Z / Z-A)
  - Precio (menor a mayor / mayor a menor)

**SQL**: `SELECT * FROM tours WHERE nombre LIKE ? AND precio BETWEEN ? AND ? ORDER BY precio ASC`

**Archivos**: `Tour.php::buscar()`, `views/tours/index.php`

---

### 8. Dashboard de Administrador 📊
**Operaciones READ con agregaciones y estadísticas**

Estadísticas mostradas en `/admin/dashboard`:

- **COUNT**: Total de usuarios, lugares, tours, reservas, comentarios
- **SUM**: Ingresos totales de todas las reservas
- **AVG**: Promedio general de calificaciones
- **GROUP BY**: Tours más reservados, lugares más comentados
- **JOIN**: Próximas reservas con datos de usuario y tour

**SQL Ejemplo**:
```sql
-- Tours más reservados
SELECT t.*, COUNT(r.id_reserva) as total_reservas
FROM tours t
LEFT JOIN reservas r ON t.id_tour = r.id_tour
GROUP BY t.id_tour
ORDER BY total_reservas DESC

-- Ingresos totales
SELECT SUM(precio_total) as ingresos_totales FROM reservas
```

**Archivos**: `AdminController.php`, modelos varios, `views/admin/dashboard.php`

---

### 9. Historial de Reservas del Usuario 📜
**Operación READ con JOINs y filtros de fecha**

Funcionalidades en `/usuario/mis-reservas`:

- **Reservas activas**: `WHERE fecha_tour >= CURDATE()`
- **Reservas pasadas**: `WHERE fecha_tour < CURDATE()`
- **JOIN**: Combina datos de reservas con información del tour

**SQL**:
```sql
SELECT r.*, t.nombre as tour_nombre, t.descripcion as tour_descripcion
FROM reservas r
INNER JOIN tours t ON r.id_tour = t.id_tour
WHERE r.id_usuario = ? AND r.fecha_tour >= CURDATE()
ORDER BY r.fecha_tour ASC
```

**Archivos**: `Reserva.php::getActivasPorUsuario()`, `views/usuario/mis_reservas.php`

---

### 10. Sistema de Calificaciones por Lugar ⭐
**Operación READ con agregación AVG y COUNT**

Funcionalidades:
- **Promedio de calificación**: `AVG(calificacion)` por lugar
- **Total de opiniones**: `COUNT(*)` de comentarios
- **Distribución**: Cantidad de comentarios por cada estrella (1-5)

**SQL**:
```sql
-- Promedio y total
SELECT 
    COALESCE(AVG(calificacion), 0) as promedio,
    COUNT(*) as total_comentarios
FROM comentarios
WHERE id_lugar = ?

-- Conteo por calificación
SELECT calificacion, COUNT(*) as total
FROM comentarios
WHERE id_lugar = ?
GROUP BY calificacion
ORDER BY calificacion DESC
```

Mostrado en: `/lugares/detalle?id=X`

**Archivos**: `Comentario.php::getPromedioCalificacion()`, `views/lugares/detalle.php`

---

## 🗄️ Uso de Almacenamiento Web

### LocalStorage (Persistente)
- **Favoritos**: Lugares marcados como favoritos
- **Búsquedas recientes**: Últimas 5 búsquedas
- **Carrito de reserva**: Datos temporales antes de confirmar
- **Preferencias**: Configuraciones del usuario

### SessionStorage (Sesión actual)
- **Filtros de búsqueda**: Mantiene filtros mientras navega
- **Historial de navegación**: Páginas visitadas en la sesión

### Cookies (Seguras)
- **Sesión de usuario**: HttpOnly, Secure, SameSite
- **Token "Recordar sesión"**: Duración 30 días
- Configuradas en `helpers/Session.php`

**Archivos**: `public/js/storage.js`

---

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 7.4+ (Nativo, sin frameworks)
- **Base de Datos**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Arquitectura**: MVC (Modelo-Vista-Controlador)
- **Servidor**: Apache con mod_rewrite

---

## 📁 Estructura del Proyecto

```
turismo-coroico/
├── config/                 # Configuraciones
│   ├── database.php
│   └── config.php
├── models/                 # Modelos (lógica de datos)
│   ├── Model.php
│   ├── Usuario.php
│   ├── LugarTuristico.php
│   ├── Tour.php
│   ├── Reserva.php
│   └── Comentario.php
├── controllers/            # Controladores (coordinación)
│   ├── InicioController.php
│   ├── UsuarioController.php
│   ├── LugarController.php
│   ├── TourController.php
│   ├── ReservaController.php
│   ├── ComentarioController.php
│   └── AdminController.php
├── views/                  # Vistas (presentación)
│   ├── layouts/
│   ├── home/
│   ├── usuario/
│   ├── lugares/
│   ├── tours/
│   ├── reservas/
│   ├── comentarios/
│   └── admin/
├── helpers/                # Utilidades
│   ├── Session.php
│   ├── Validator.php
│   └── Utils.php
├── public/                 # Archivos públicos
│   ├── css/
│   ├── js/
│   └── uploads/
├── index.php               # Front Controller
├── .htaccess              # Configuración Apache
└── database.sql           # Script de BD
```

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

## 🎨 Características Destacadas

✅ **MVC Puro**: Separación estricta de responsabilidades  
✅ **Sin Frameworks**: Código PHP nativo  
✅ **Responsive**: Diseño adaptable a móviles  
✅ **Seguridad**: Protección XSS, CSRF, SQL Injection  
✅ **Validaciones**: Cliente y servidor  
✅ **LocalStorage**: Datos persistentes del navegador  
✅ **SessionStorage**: Datos temporales de sesión  
✅ **Cookies Seguras**: HttpOnly y SameSite  

---

## 📊 Resumen de Operaciones CRUD

| Requerimiento | CREATE | READ | UPDATE | DELETE |
|---------------|--------|------|--------|--------|
| 1. Usuarios | ✅ | ✅ | ✅ | ✅ |
| 2. Lugares | ✅ | ✅ | ✅ | ✅ |
| 3. Tours | ✅ | ✅ | ✅ | ✅ |
| 4. Reservas | ✅ | ✅ | ✅ | ✅ |
| 5. Comentarios | ✅ | ✅ | ✅ | ✅ |
| 6. Búsqueda Lugares | - | ✅ | - | - |
| 7. Búsqueda Tours | - | ✅ | - | - |
| 8. Dashboard Admin | - | ✅ | - | - |
| 9. Historial Reservas | - | ✅ | - | - |
| 10. Calificaciones | - | ✅ | - | - |

**Total**: 35+ operaciones implementadas

---

## 📝 Licencia

Proyecto educativo - Sistema de Turismo en Coroico, La Paz, Bolivia

---

## 👨‍💻 Autor

Desarrollado como proyecto académico para demostrar implementación de arquitectura MVC con PHP nativo y MySQL.