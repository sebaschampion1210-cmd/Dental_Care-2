-- =============================================================
-- Script SQL: dental_care.sql (TASK-01 y TASK-07)
-- Propósito: Crear la base de datos con las tablas 'usuario' y
-- 'paciente' del Sistema Web Consultorio Odontológico
-- (MVC PHP 8 + MySQL).
-- Ejecutar desde phpMyAdmin (XAMPP) o consola MySQL.
-- =============================================================

-- Creamos la base de datos si no existe y la seleccionamos.
CREATE DATABASE IF NOT EXISTS dental_care
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_spanish_ci;

USE dental_care;

-- =============================================================
-- Tabla: usuario (TASK-01 / HU-01 / RF-01)
-- Guarda las credenciales de acceso del personal del consultorio.
-- La contraseña NUNCA se guarda en texto plano: se almacena su
-- hash generado con password_hash() en PHP (columna password_hash).
-- =============================================================
CREATE TABLE IF NOT EXISTS usuario (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(80)  NOT NULL,
    correo        VARCHAR(100) NOT NULL UNIQUE,
    -- El hash bcrypt ocupa unos 60 caracteres; 255 da holgura.
    password_hash VARCHAR(255) NOT NULL,
    -- Rol define los permisos de acceso según el perfil.
    rol           ENUM('recepcionista', 'odontologo', 'admin') NOT NULL,
    -- Estado permite desactivar un usuario sin borrarlo.
    estado        ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- =============================================================
-- Datos de prueba usuarios (PASSWORD: Dental2025*)
-- Insertamos 3 usuarios, uno por rol, para probar el login.
-- Cada hash fue generado en PHP con:
-- password_hash('Dental2025*', PASSWORD_DEFAULT)
-- =============================================================
-- Usamos INSERT IGNORE: si el correo ya existe en una segunda
-- ejecución del script, MySQL lo salta sin generar un error.
INSERT IGNORE INTO usuario (nombre, correo, password_hash, rol, estado) VALUES
('Sebastian Vega',  'admin@dentalcare.com',      '$2y$10$WM1qSi67xMqtrnVWmiuc4e0pMLgqqGgvIQZJQS2RZWmEKs/6YQUJe', 'admin',         'activo'),
('Laura Restrepo',  'recepcion@dentalcare.com',  '$2y$10$WM1qSi67xMqtrnVWmiuc4e0pMLgqqGgvIQZJQS2RZWmEKs/6YQUJe', 'recepcionista', 'activo'),
('Carlos González', 'odontologo@dentalcare.com', '$2y$10$WM1qSi67xMqtrnVWmiuc4e0pMLgqqGgvIQZJQS2RZWmEKs/6YQUJe', 'odontologo',    'activo');

-- =============================================================
-- Tabla: paciente (TASK-07 / HU-03 / RF-03 y RF-04)
-- Guarda la ficha clínica básica de cada paciente.
-- 'numero_documento' lleva restricción UNIQUE: la base de datos
-- rechaza por sí sola dos cédulas iguales (RF-04).
-- =============================================================
CREATE TABLE IF NOT EXISTS paciente (
    id                 INT AUTO_INCREMENT PRIMARY KEY,
    numero_documento   VARCHAR(20)  NOT NULL,
    nombres            VARCHAR(60)  NOT NULL,
    apellidos          VARCHAR(60)  NOT NULL,
    telefono           VARCHAR(15)  NOT NULL,
    correo             VARCHAR(100) NULL,
    -- Fecha de nacimiento para calcular la edad en la vista.
    fecha_nacimiento   DATE         NOT NULL,
    -- Antecedentes médicos: alergias, enfermedades, etc.
    antecedentes_medicos TEXT       NOT NULL,
    fecha_registro     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_documento (numero_documento)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- =============================================================
-- Datos de prueba: 2 pacientes simulados para la prueba CP-03.
-- =============================================================
-- También con INSERT IGNORE para poder re-ejecutar el script.
INSERT IGNORE INTO paciente (numero_documento, nombres, apellidos, telefono, correo, fecha_nacimiento, antecedentes_medicos) VALUES
('1020304051', 'Andrés Felipe', 'Castro Muñoz',  '3102223344', 'andres.castro@test.com', '1995-04-12', 'Alérgico a la penicilina.'),
('1098765432', 'María Camila',  'Torres Ortega', '3115558877', 'maria.torres@test.com',  '2001-09-25', 'Ninguno. Hipertensión controlada.');

-- =============================================================
-- Tabla: cita (TASK-13 / HU-05 / RF-06 y RF-07)
-- Guarda cada turno del sillón dental: quién atiende, a qué
-- paciente, en qué franja horaria y con qué motivo.
-- Llaves foráneas: paciente_id -> paciente, odontologo_id -> usuario.
-- La regla RN-01 (no cruzar horarios) se refuerza en PHP con la
-- consulta de CitaModel::validarCruceHorario().
-- =============================================================
CREATE TABLE IF NOT EXISTS cita (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    paciente_id     INT NOT NULL,
    -- Odontólogo: apunta a la tabla usuario (rol odontologo).
    odontologo_id   INT NOT NULL,
    fecha_cita      DATE NOT NULL,
    hora_inicio     TIME NOT NULL,
    hora_fin        TIME NOT NULL,
    motivo          VARCHAR(200) NOT NULL,
    -- Estados del ciclo del turno (RF-08 / HU-06).
    estado          ENUM('Pendiente','Confirmada','Atendida','Cancelada','Inasistencia')
                    NOT NULL DEFAULT 'Pendiente',
    fecha_registro  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paciente_id)   REFERENCES paciente(id),
    FOREIGN KEY (odontologo_id) REFERENCES usuario(id)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_spanish_ci;

-- =============================================================
-- Datos de prueba: 2 turnos de HOY con el odontólogo de la base.
-- Los id se toman con subconsultas (no dependen de números fijos)
-- y el WHERE NOT EXISTS evita duplicarlos si re-ejecutas el script.
-- Sirven para probar CP-06 y CP-07 (colisión de horarios).
-- =============================================================
INSERT INTO cita (paciente_id, odontologo_id, fecha_cita, hora_inicio, hora_fin, motivo, estado)
SELECT (SELECT id FROM paciente  WHERE numero_documento = '1020304051' LIMIT 1),
       (SELECT id FROM usuario   WHERE rol = 'odontologo' LIMIT 1),
       CURDATE(), '08:00:00', '08:30:00', 'Valoración inicial y limpieza.', 'Pendiente'
WHERE NOT EXISTS (SELECT 1 FROM cita WHERE fecha_cita = CURDATE() AND hora_inicio = '08:00:00');

INSERT INTO cita (paciente_id, odontologo_id, fecha_cita, hora_inicio, hora_fin, motivo, estado)
SELECT (SELECT id FROM paciente  WHERE numero_documento = '1098765432' LIMIT 1),
       (SELECT id FROM usuario   WHERE rol = 'odontologo' LIMIT 1),
       CURDATE(), '10:00:00', '10:45:00', 'Control de ortodoncia.', 'Confirmada'
WHERE NOT EXISTS (SELECT 1 FROM cita WHERE fecha_cita = CURDATE() AND hora_inicio = '10:00:00');
