-- ============================================
-- Sistema de Turismo en Coroico
-- Base de Datos: turismo_coroico
-- ============================================

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS turismo_coroico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE turismo_coroico;

-- ============================================
-- TABLA: usuarios
-- ============================================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    rol ENUM('admin', 'turista') NOT NULL DEFAULT 'turista',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos de usuarios
INSERT INTO usuarios (nombre_completo, email, password, rol) VALUES
('Juan Pérez Administrador', 'admin@coroico.com', 'admin123', 'admin'),
('María López', 'maria.lopez@gmail.com', 'maria123', 'turista'),
('Carlos Mendoza', 'carlos.mendoza@hotmail.com', 'carlos123', 'turista'),
('Ana Quispe', 'ana.quispe@yahoo.com', 'ana123', 'turista'),
('Roberto Mamani', 'roberto.mamani@gmail.com', 'roberto123', 'turista');

-- ============================================
-- TABLA: lugares_turisticos
-- ============================================
CREATE TABLE lugares_turisticos (
    id_lugar INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    categoria ENUM('mirador', 'cascada', 'aventura', 'cultural') NOT NULL,
    direccion VARCHAR(200),
    precio_entrada DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    horario_apertura TIME,
    horario_cierre TIME,
    imagen_lugar VARCHAR(255) DEFAULT 'default.jpg',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos de lugares turísticos
INSERT INTO lugares_turisticos (nombre, descripcion, categoria, direccion, precio_entrada, horario_apertura, horario_cierre, imagen_lugar) VALUES
('Mirador El Calvario', 'Vista panorámica espectacular de Coroico y los valles circundantes. Ideal para fotografía al amanecer y atardecer.', 'mirador', 'Calle Principal, subida al cerro', 10.00, '06:00:00', '18:00:00', 'calvario.jpg'),
('Cascadas de Vagantes', 'Hermosas cascadas naturales rodeadas de vegetación tropical. Incluye senderos y áreas para picnic.', 'cascada', 'Comunidad de Vagantes, 8 km de Coroico', 15.00, '08:00:00', '17:00:00', 'vagantes.jpg'),
('Sendero de las Yungas', 'Caminata de aventura por la selva con flora y fauna variada. Nivel de dificultad moderado.', 'aventura', 'Carretera antigua a Caranavi', 20.00, '07:00:00', '16:00:00', 'yungas.jpg'),
('Museo Afroboliviano', 'Museo dedicado a la historia y cultura de la comunidad afroboliviana en la región de los Yungas.', 'cultural', 'Plaza Principal de Coroico', 8.00, '09:00:00', '17:00:00', 'museo.jpg'),
('Mirador de Uchumachi', 'Punto de observación con vista a las montañas nevadas del Illimani y Mururata en días despejados.', 'mirador', 'Comunidad Uchumachi, 5 km de Coroico', 12.00, '06:00:00', '18:00:00', 'uchumachi.jpg');

-- ============================================
-- TABLA: tours
-- ============================================
CREATE TABLE tours (
    id_tour INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    cupo_maximo INT NOT NULL DEFAULT 10,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos de tours
INSERT INTO tours (nombre, descripcion, precio, cupo_maximo) VALUES
('Tour Completo Coroico', 'Recorrido de día completo visitando los principales atractivos: Mirador El Calvario, Cascadas de Vagantes y Museo Afroboliviano. Incluye transporte, guía y almuerzo.', 180.00, 15),
('Aventura en las Yungas', 'Experiencia de trekking por senderos de selva tropical, visita a cascadas ocultas y observación de aves. Incluye equipo, guía especializado y snacks.', 250.00, 10),
('Tour Cultural y Gastronómico', 'Descubre la cultura afroboliviana, visita al museo, prueba de comida tradicional y taller de danza. Incluye transporte y almuerzo típico.', 150.00, 20),
('Amanecer en los Miradores', 'Salida temprano para observar el amanecer desde los mejores miradores de Coroico. Incluye desayuno campestre y fotografía profesional.', 120.00, 12),
('Cascadas y Naturaleza', 'Tour especializado en las cascadas de la región. Visita a 3 cascadas diferentes con tiempo para nadar. Incluye transporte, guía y box lunch.', 200.00, 15);

-- ============================================
-- TABLA: reservas
-- ============================================
CREATE TABLE reservas (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_tour INT NOT NULL,
    fecha_tour DATE NOT NULL,
    cantidad_personas INT NOT NULL DEFAULT 1,
    precio_total DECIMAL(10,2) NOT NULL,
    fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_tour) REFERENCES tours(id_tour) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos de reservas
INSERT INTO reservas (id_usuario, id_tour, fecha_tour, cantidad_personas, precio_total, fecha_reserva) VALUES
(2, 1, '2025-11-10', 2, 360.00, '2025-11-01 10:30:00'),
(3, 2, '2025-11-12', 1, 250.00, '2025-11-02 14:20:00'),
(4, 4, '2025-11-08', 3, 360.00, '2025-11-03 09:15:00'),
(5, 3, '2025-11-15', 2, 300.00, '2025-11-03 16:45:00'),
(2, 5, '2025-11-20', 4, 800.00, '2025-11-04 11:00:00');

-- ============================================
-- TABLA: comentarios
-- ============================================
CREATE TABLE comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_lugar INT NOT NULL,
    calificacion INT NOT NULL CHECK (calificacion BETWEEN 1 AND 5),
    comentario TEXT NOT NULL,
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_lugar) REFERENCES lugares_turisticos(id_lugar) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar datos de comentarios
INSERT INTO comentarios (id_usuario, id_lugar, calificacion, comentario, fecha_comentario) VALUES
(2, 1, 5, 'Excelente vista panorámica! El amanecer desde este mirador es espectacular. Totalmente recomendado para fotógrafos.', '2025-10-25 08:30:00'),
(3, 2, 4, 'Las cascadas son hermosas y el sendero está bien mantenido. Solo le falta un poco más de señalización en algunos tramos.', '2025-10-26 15:20:00'),
(4, 3, 5, 'Una aventura increíble! La caminata es desafiante pero vale totalmente la pena. La naturaleza es impresionante.', '2025-10-28 12:10:00'),
(5, 4, 4, 'Muy interesante conocer la historia afroboliviana. El museo tiene buena información aunque podría tener más piezas en exhibición.', '2025-10-29 10:45:00'),
(2, 5, 5, 'El mejor lugar para ver el Illimani en días despejados. Llegamos temprano y pudimos ver las montañas nevadas perfectamente.', '2025-11-01 07:15:00');

-- ============================================
-- ÍNDICES para mejor rendimiento
-- ============================================
CREATE INDEX idx_usuario_email ON usuarios(email);
CREATE INDEX idx_lugar_categoria ON lugares_turisticos(categoria);
CREATE INDEX idx_reserva_fecha ON reservas(fecha_tour);
CREATE INDEX idx_comentario_lugar ON comentarios(id_lugar);
CREATE INDEX idx_comentario_calificacion ON comentarios(calificacion);

-- ============================================
-- FIN DEL SCRIPT
-- ============================================