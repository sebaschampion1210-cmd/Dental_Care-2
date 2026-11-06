# Memoria del Proyecto: Dental Care

## Perfil del Desarrollador

- **Nombre:** Sebastian Vega
- **Rol:** Analista y Desarrollador de Software (ADSO)
- **Contexto:** Estudiante/Aprendiz enfocado en la sustentación del proyecto ante instructoras técnicas joan castillo.

## Estado de la Infraestructura Técnica

- **Stack Tecnológico:** PHP 8+ (POO), PDO para persistencia, MySQL/MariaDB (XAMPP), HTML5, Bootstrap 5 y JavaScript nativo (Vanilla JS).
- **Arquitectura:** Patrón Modelo-Vista-Controlador (MVC) nativo y ligero con enrutamiento centralizado en `index.php`.
- **Ruta del Proyecto Local:** `C:\xampp\htdocs\Dental_Care`

## Configuración del Ecosistema IA (OpenCode)

Las habilidades locales están mapeadas bajo los siguientes alias de carpetas dentro de `.opencode/skills/`:

1. `mirar-tareas` -> Planificación y lectura del backlog.
2. `codijo-simple` -> Programación limpia, en español, con 3 viñetas de sustentación.
3. `verificar-criterios` -> Auditoría técnica de reglas de negocio (`RN-01`, `RN-02`, `RN-03`).
4. `corregir-tareas` -> Solución quirúrgica de errores en PHP/XAMPP.
5. `aprobar-tareas` -> Cierre de documentación en cascada y comandos Git.

## Decisiones Técnicas Clave Aprobadas

- **Nombres en Código:** Todo el código fuente (variables, métodos, clases y tablas) se escribe estrictamente en **español** para facilitar la sustentación técnica didáctica.
- **Seguridad:** Uso obligatorio de sentencias preparadas en PDO (`prepare` y `execute`) con parámetros nombrados para mitigar riesgos de inyección SQL.
- **Límite de Complejidad:** Métodos y funciones con un tope estricto de 25 a 30 líneas para asegurar la legibilidad del código.

## Últimos Avances y Estado Actual (Bitácora)

- **Última actualización:** 06 de noviembre de 2026.
- **Estado:** ✅ ÉPICA 01 (Seguridad y Accesos) COMPLETA: TASK-01 a TASK-06 con `[x]`, RF-01/RF-02 y CP-01/CP-02 validados en auditoría. Archivos: `database/dental_care.sql`, `app/config/Conexion.php`, `app/models/UsuarioModel.php`, `app/controllers/AuthController.php`, `app/views/auth/login.php`, `index.php`.
- **Estado:** ✅ ÉPICA 02 (Gestión de Pacientes) COMPLETA Y CERRADA: HU-03 y HU-04 auditadas con `verificar-criterios`, marcadas TASK-07 a TASK-12, RF-03/RF-04/RF-05 y CP-03/CP-04/CP-05 en backlog + matriz; EP-02 marcada `[x]` en ambos documentos.
- **Avance ÉPICA 02 (Gestión de Pacientes):**
  - ✅ HU-03 COMPLETA (TASK-07 a TASK-10 con `[x]`, criterios `[x]`, auditada y cerrada): tabla `paciente` con UNIQUE en `numero_documento` (script re-ejecutable con `INSERT IGNORE`), modelo `app/models/PacienteModel.php` (listar/obtenerPorDocumento/crear/actualizar + try-catch para duplicados), controlador `app/controllers/PacienteController.php` (valida obligatorios, rechaza duplicados, alertas por `$_SESSION`), vista `app/views/paciente/index.php` (tabla, modales nuevo/editar, paginación, edad calculada, barra con Cerrar Sesión). Sebastian probó CP-03 y CP-04 en su navegador: funcionan.
  - ✅ TASK-11 y TASK-12 COMPLETAS (HU-04 búsqueda asíncrona): método `buscar()` con `LIKE :patron` en el modelo, endpoint JSON `buscar()` en el controlador (responde con `json_encode` + `JSON_UNESCAPED_UNICODE`), script `public/js/pacientes.js` con `fetch()` y evento `input`. Probado en vivo contra Apache: "Torres" → JSON del paciente, "To" → vacío, "109876" → por cédula.
  - 🔧 Corrección de auditoría (06/11): se agregó la columna `correo` al `SELECT` del método `listar()` en `PacienteModel.php`. Motivo: el modal de edición llegaba siempre sin correo desde la tabla normal y al guardar se borraba el correo real. Verificado por Sebastian.
  - 🔧 Reparación de datos (06/11): las filas 1 y 2 de `paciente` (Andrés Castro, María Torres) tenían tildes corruptas por double-encoding al importar los datos (mostraban "Andr├®s"). Se repararon con UPDATE vía conexión `utf8mb4`; el texto de `dental_care.sql` original estaba correcto (bytes `C3 A9`). Aprendizaje: si al importar en phpMyAdmin aparecen "palitos y ©", la sesión tenía charset equivocado; verificar que la conexión sea utf8mb4.
- **Avance ÉPICA 03 (Agenda y Citas):**
  - ✅ HU-05 COMPLETA EN CÓDIGO (TASK-13 a TASK-16, pendiente prueba de Sebastian y auditoría): tabla `cita` en `database/dental_care.sql` (FK a `paciente` y `usuario`, ENUM de 5 estados con default `Pendiente`, datos de prueba re-ejecutables del día con `WHERE NOT EXISTS`), modelo `app/models/CitaModel.php` (`listarOdontologos`, `listarDelDia`, `cruceHorario` con la condición de solape `inicio < fin_existente Y fin > inicio_existente` + filtro `estado NOT IN ("Cancelada","Inasistencia")`, `crear` con try-catch), controlador `app/controllers/CitaController.php` (`index` con fecha GET, `guardar` valida campos/franja y alerta colisión con nombre del doctor y horas, método `datosValidos`), vista `app/views/cita/index.php` (form 40% + agenda 60% con badges de estado, navegación día ◀ Hoy ▶).
  - ✅ HU-06 (TASK-17/18) COMPLETA EN CÓDIGO (pendiente prueba y auditoría): `CitaModel::actualizarEstado($id,$estado)` con doble bloqueo RN-03 (revisa estado actual + condiciona el UPDATE con `estado NOT IN ("Atendida","Cancelada")` y usa `rowCount`), acción `cambiarEstado()` en el controlador (valida estado contra los 5 válidos del ENUM, avisa éxito o candado), columna Acciones en la agenda con menú desplegable Confirmar/Atendida/Inasistencia/Cancelar y candado 🔒 "Cerrada" para las concluidas.
  - ✏️ Ajuste solicitado por Sebastian: la "Hora de Fin" del formulario de reserva muestra un hint en Bootstrap (`form-text`): "Esta hora es un aproximado de cuándo termina la cita."
  - 🔧 Reparación de datos (06/11): `usuario` id 3 "Carlos González" llegó mojibake de la importación anterior; se corrigió por UPDATE v ía PDO `utf8mb4` (los literales con tilde en la consola de Windows se envían como `?`, reparar siempre por script PHP).
- **Nota de estructura:** Los documentos de backlog y trazabilidad se encuentran en `docs/investigacion/backlog/`.
- **Credenciales de prueba:** usuarios `admin@dentalcare.com` / `recepcion@dentalcare.com` / `odontologo@dentalcare.com`, contraseña: `Dental2025*`. Pacientes: 1020304051 (Andrés Castro, alérgico), 1098765432 (María Torres).
- **Siguiente Paso Inmediato (retomar aquí en la próxima sesión):** 1) Sebastian prueba HU-05 y HU-06 en el navegador: CP-06 (cita nueva nace Pendiente) y CP-07 (colisión 08:00 con Dr. Carlos → alerta roja), y CP-08 (cambio a Inasistencia + intentar modificar una cita Atendida/Cancelada → candado). 2) Auditar HU-05/HU-06 con `verificar-criterios` (RF-06, RF-07, RF-08 + RN-01 y RN-03). 3) Cierre de ÉPICA 03 con `aprobar-tareas` (TASK-13 a TASK-18, HU-05/HU-06, EP-03 en backlog + matriz) + actualizar esta bitácora + comandos Git. 4) Iniciar ÉPICA 04 (Odontograma SVG) desde la TASK-19.
