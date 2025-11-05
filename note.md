turismo-coroico/
│
├── config/
│   ├── database.php              # Configuración de conexión a MySQL
│   └── config.php                # Configuraciones generales (URL base, nombre del sitio)
│
├── models/
│   ├── Model.php                 # Clase base con conexión PDO
│   ├── Usuario.php               # CRUD usuarios + login/logout
│   ├── LugarTuristico.php        # CRUD lugares + búsqueda/filtros
│   ├── Tour.php                  # CRUD tours + búsqueda/filtros
│   ├── Reserva.php               # CRUD reservas + historial + estadísticas
│   └── Comentario.php            # CRUD comentarios + calificaciones
│
├── controllers/
│   ├── InicioController.php      # Página principal (home)
│   ├── UsuarioController.php     # Login, registro, perfil, mis-reservas
│   ├── LugarController.php       # Listar, detalle, crear, editar, eliminar lugares
│   ├── TourController.php        # Listar, detalle, crear, editar, eliminar tours
│   ├── ReservaController.php     # Crear, ver, editar, cancelar reservas
│   ├── ComentarioController.php  # Crear, editar, eliminar comentarios
│   └── AdminController.php       # Dashboard de administrador
│
├── views/
│   ├── layouts/
│   │   ├── header.php            # <head>, meta tags, CSS
│   │   ├── navbar.php            # Menú de navegación (diferente para admin/turista)
│   │   └── footer.php            # Footer con info de contacto
│   │
│   ├── home/
│   │   └── index.php             # Página de inicio con destacados
│   │
│   ├── usuario/
│   │   ├── login.php             # Formulario de login
│   │   ├── registro.php          # Formulario de registro
│   │   ├── perfil.php            # Ver y editar perfil
│   │   └── mis_reservas.php      # Historial de reservas del usuario
│   │
│   ├── lugares/
│   │   ├── index.php             # Lista de lugares con filtros
│   │   ├── detalle.php           # Detalle de lugar + comentarios + mapa
│   │   ├── crear.php             # Formulario crear lugar (admin)
│   │   └── editar.php            # Formulario editar lugar (admin)
│   │
│   ├── tours/
│   │   ├── index.php             # Lista de tours con filtros
│   │   ├── detalle.php           # Detalle de tour + disponibilidad
│   │   ├── crear.php             # Formulario crear tour (admin)
│   │   └── editar.php            # Formulario editar tour (admin)
│   │
│   ├── reservas/
│   │   ├── crear.php             # Formulario de reserva
│   │   ├── confirmar.php         # Confirmación de reserva
│   │   ├── detalle.php           # Ver detalle de una reserva
│   │   └── editar.php            # Modificar reserva
│   │
│   ├── comentarios/
│   │   ├── crear.php             # Formulario agregar comentario
│   │   └── editar.php            # Formulario editar comentario
│   │
│   └── admin/
│       └── dashboard.php         # Panel de control con estadísticas
│
├── public/
│   ├── css/
│   │   └── styles.css            # Estilos personalizados
│   │
│   ├── js/
│   │   ├── scripts.js            # JavaScript general
│   │   ├── storage.js            # LocalStorage/SessionStorage
│   │   └── validaciones.js       # Validaciones del lado del cliente
│   │
│   └── uploads/
│       ├── lugares/              # Imágenes de lugares turísticos
│       └── .htaccess             # Protección de directorio
│
├── helpers/
│   ├── Session.php               # Manejo de sesiones y autenticación
│   ├── Validator.php             # Validaciones de formularios
│   └── Utils.php                 # Funciones auxiliares (formateo, sanitización)
│
├── index.php                     # Front Controller (enrutador principal)
├── .htaccess                     # Reescritura de URLs
└── database.sql                  # Script SQL que creamos
```

---

## 🔗 Mapeo de URLs a Controladores y Vistas

### **Rutas Públicas (sin autenticación)**
```
GET  /                                    → InicioController::index()           → views/home/index.php
GET  /usuario/login                       → UsuarioController::login()          → views/usuario/login.php
POST /usuario/autenticar                  → UsuarioController::autenticar()     → Redirección
GET  /usuario/registro                    → UsuarioController::registro()       → views/usuario/registro.php
POST /usuario/guardar                     → UsuarioController::guardar()        → Redirección
GET  /usuario/logout                      → UsuarioController::logout()         → Redirección

GET  /lugares                             → LugarController::index()            → views/lugares/index.php
GET  /lugares/detalle?id=1                → LugarController::detalle($id)       → views/lugares/detalle.php
GET  /lugares/buscar?categoria=mirador    → LugarController::buscar()           → views/lugares/index.php

GET  /tours                               → TourController::index()             → views/tours/index.php
GET  /tours/detalle?id=1                  → TourController::detalle($id)        → views/tours/detalle.php
GET  /tours/buscar?precio_max=200         → TourController::buscar()            → views/tours/index.php
```

### **Rutas Privadas (requieren login como turista)**
```
GET  /usuario/perfil                      → UsuarioController::perfil()         → views/usuario/perfil.php
POST /usuario/actualizar                  → UsuarioController::actualizar()     → Redirección
GET  /usuario/mis-reservas                → UsuarioController::misReservas()    → views/usuario/mis_reservas.php

GET  /reservas/crear?tour=1               → ReservaController::crear($tour_id)  → views/reservas/crear.php
POST /reservas/guardar                    → ReservaController::guardar()        → views/reservas/confirmar.php
GET  /reservas/detalle?id=1               → ReservaController::detalle($id)     → views/reservas/detalle.php
GET  /reservas/editar?id=1                → ReservaController::editar($id)      → views/reservas/editar.php
POST /reservas/actualizar                 → ReservaController::actualizar()     → Redirección
GET  /reservas/cancelar?id=1              → ReservaController::cancelar($id)    → Redirección

POST /comentarios/crear                   → ComentarioController::crear()       → Redirección
GET  /comentarios/editar?id=1             → ComentarioController::editar($id)   → views/comentarios/editar.php
POST /comentarios/actualizar              → ComentarioController::actualizar()  → Redirección
GET  /comentarios/eliminar?id=1           → ComentarioController::eliminar($id) → Redirección
```

### **Rutas Administrativas (requieren login como admin)**
```
GET  /admin/dashboard                     → AdminController::dashboard()        → views/admin/dashboard.php

GET  /lugares/crear                       → LugarController::crear()            → views/lugares/crear.php
POST /lugares/guardar                     → LugarController::guardar()          → Redirección
GET  /lugares/editar?id=1                 → LugarController::editar($id)        → views/lugares/editar.php
POST /lugares/actualizar                  → LugarController::actualizar()       → Redirección
GET  /lugares/eliminar?id=1               → LugarController::eliminar($id)      → Redirección

GET  /tours/crear                         → TourController::crear()             → views/tours/crear.php
POST /tours/guardar                       → TourController::guardar()           → Redirección
GET  /tours/editar?id=1                   → TourController::editar($id)         → views/tours/editar.php
POST /tours/actualizar                    → TourController::actualizar()        → Redirección
GET  /tours/eliminar?id=1                 → TourController::eliminar($id)       → Redirección