# Product Backlog y Estructura de Épicas (Jira / Open Code)

**Proyecto:** Sistema Web Consultorio Odontológico (MVC PHP 8 + MySQL + JS)  
**Total Épicas:** 6  
**Total Historias de Usuario (Stories):** 12  
**Total Tareas Técnicas (Tasks):** 36  
**Total Story Points:** 46  

---

## Resumen de Épicas del Sistema

- [x] **EP-01:** Seguridad y Gestión de Accesos
- [x] **EP-02:** Gestión de Pacientes y Expedientes Clínicos
- [ ] **EP-03:** Agenda y Control de Citas Médicas
- [ ] **EP-04:** Odontograma Clínico Interactivo (SVG)
- [ ] **EP-05:** Presupuestos y Control de Cartera
- [ ] **EP-06:** Retención (Recall WhatsApp) y Dashboard Gerencial

---

## ÉPICA 1: EP-01 - Seguridad y Gestión de Accesos

### HU-01
- **Tipo de Issue:** Story
- **Épica:** EP-01: Seguridad y Gestión de Accesos
- **Título:** [x] HU-01: Autenticación de Usuarios y Manejo de Sesiones
- **Story Points:** 3
- **Descripción:** Como recepcionista u odontólogo, quiero ingresar mis credenciales de acceso (correo y contraseña) para acceder al sistema de forma segura según los permisos de mi rol.
- **Criterios de Aceptación:**
  1. [x] Si las credenciales coinciden con el hash encriptado (`password_verify`), el sistema inicia sesión y redirige al Dashboard en menos de 2 segundos.
  2. [x] Si el correo o la contraseña son incorrectos, el sistema permanece en el login y muestra un mensaje de alerta: "Credenciales no válidas".
  3. [x] La contraseña viaja por método POST y se almacena con `password_hash()`.
- **Subtareas Técnicas (Tasks):**
  - [x] `TASK-01`: Crear tabla `usuario` en MySQL (`id`, `nombre`, `correo`, `password_hash`, `rol`, `estado`).
  - [x] `TASK-02`: Crear modelo `app/models/UsuarioModel.php` con método PDO `autenticar($correo)`.
  - [x] `TASK-03`: Crear controlador `app/controllers/AuthController.php` para gestionar `$_SESSION` y cierre de sesión.
  - [x] `TASK-04`: Maquetar vista `app/views/auth/login.php` con diseño responsivo en Bootstrap 5.

### HU-02
- **Tipo de Issue:** Story
- **Épica:** EP-01: Seguridad y Gestión de Accesos
- **Título:** [x] HU-02: Protección de Rutas y Control de Sesión Activa
- **Story Points:** 2
- **Descripción:** Como administrador del sistema, quiero restringir las rutas internas a usuarios no autenticados para evitar accesos no autorizados a la información médica de los pacientes.
- **Criterios de Aceptación:**
  1. [x] Si un usuario sin sesión activa intenta abrir cualquier controlador por URL (`index.php?c=cita`), el sistema redirige inmediatamente a `index.php?c=auth&a=login`.
  2. [x] Al cerrar sesión, se destruye la sesión por completo impidiendo regresar con el botón atrás del navegador.
- **Subtareas Técnicas (Tasks):**
  - [x] `TASK-05`: Implementar validación de sesión activa en el enrutador `index.php` o controlador base.
  - [x] `TASK-06`: Configurar redirecciones de seguridad cuando `!isset($_SESSION['usuario_id'])`.

---

## ÉPICA 2: EP-02 - Gestión de Pacientes y Expedientes Clínicos

### HU-03
- **Tipo de Issue:** Story
- **Épica:** EP-02: Gestión de Pacientes y Expedientes Clínicos
- **Título:** [x] HU-03: Registro y Edición de Expedientes con Cédula Única
- **Story Points:** 3
- **Descripción:** Como recepcionista, quiero registrar y actualizar los datos personales y antecedentes médicos de un paciente para crear su ficha clínica antes de asignarle una cita.
- **Criterios de Aceptación:**
  1. [x] El sistema impide guardar un paciente si su número de documento ya está registrado en la base de datos.
  2. [x] Campos obligatorios validados: documento, nombres, apellidos, teléfono y antecedentes médicos.
  3. [x] Permite actualizar teléfono, correo y antecedentes médicos desde el listado.
- **Subtareas Técnicas (Tasks):**
  - [x] `TASK-07`: Crear tabla `paciente` en MySQL con restricción `UNIQUE` en `numero_documento`.
  - [x] `TASK-08`: Programar en `app/models/PacienteModel.php` los métodos CRUD (`listar`, `crear`, `actualizar`, `obtenerPorDocumento`).
  - [x] `TASK-09`: Crear `app/controllers/PacienteController.php` con validaciones de campos obligatorios y duplicados.
  - [x] `TASK-10`: Diseñar vista y modal de formulario en `app/views/paciente/index.php` con Bootstrap 5.

### HU-04
- **Tipo de Issue:** Story
- **Épica:** EP-02: Gestión de Pacientes y Expedientes Clínicos
- **Título:** [x] HU-04: Búsqueda Asíncrona de Pacientes en Tiempo Real
- **Story Points:** 2
- **Descripción:** Como recepcionista, quiero buscar pacientes escribiendo parte de su nombre, apellido o documento para encontrar su expediente rápidamente sin recargar la página.
- **Criterios de Aceptación:**
  1. [x] Al escribir 3 o más caracteres en el buscador, la tabla filtra los pacientes coincidentes en menos de 1 segundo.
  2. [x] Al limpiar el campo de búsqueda, se vuelve a mostrar el listado general de pacientes.
- **Subtareas Técnicas (Tasks):**
  - [x] `TASK-11`: Agregar método de búsqueda con `LIKE` parametrizado en `PacienteModel.php` y endpoint JSON en `PacienteController.php`.
  - [x] `TASK-12`: Crear script `public/js/pacientes.js` consumiendo el endpoint mediante `fetch()` en el evento `input`.

---

## ÉPICA 3: EP-03 - Agenda y Control de Citas Médicas

### HU-05
- **Tipo de Issue:** Story
- **Épica:** EP-03: Agenda y Control de Citas Médicas
- **Título:** HU-05: Agendamiento de Turnos con Algoritmo Anti-Solapamiento
- **Story Points:** 5
- **Descripción:** Como recepcionista, quiero agendar citas eligiendo paciente, odontólogo, fecha, hora de inicio y motivo, comprobando que el horario esté libre para evitar cruces de turnos en el sillón dental.
- **Criterios de Aceptación:**
  1. El sistema bloquea el registro si el horario seleccionado se cruza con otra cita activa del mismo odontólogo.
  2. Si existe cruce horario, muestra una alerta indicando que el especialista ya tiene ese turno ocupado.
  3. Toda nueva cita se registra con estado inicial `Pendiente`.
- **Subtareas Técnicas (Tasks):**
  - [ ] `TASK-13`: Crear tabla `cita` en MySQL con llaves foráneas hacia `paciente` y `usuario` (odontólogo).
  - [ ] `TASK-14`: Programar validación de cruces de horario en `app/models/CitaModel.php`.
  - [ ] `TASK-15`: Programar `app/controllers/CitaController.php` procesando la reserva y retornando alertas de colisión.
  - [ ] `TASK-16`: Maquetar vista de agenda diaria y formulario de reserva en `app/views/cita/index.php`.

### HU-06
- **Tipo de Issue:** Story
- **Épica:** EP-03: Agenda y Control de Citas Médicas
- **Título:** HU-06: Gestión y Transición de Estados de Cita (Inasistencia)
- **Story Points:** 2
- **Descripción:** Como recepcionista, quiero cambiar el estado de una cita a Confirmada, Atendida, Cancelada o Inasistencia para llevar el control real de asistencia en el consultorio.
- **Criterios de Aceptación:**
  1. Al cambiar una cita a `Inasistencia`, se contabiliza inmediatamente en las métricas de ausentismo del mes.
  2. Si una cita está en estado `Atendida` o `Cancelada`, el sistema bloquea la edición de su fecha, hora y doctor asignado.
- **Subtareas Técnicas (Tasks):**
  - [ ] `TASK-17`: Crear método `actualizarEstado($id, $estado)` con bloqueo de edición en `CitaModel.php`.
  - [ ] `TASK-18`: Añadir botones de cambio rápido de estado con distintivos de color (badges) en `app/views/cita/index.php`.

---

## ÉPICA 4: EP-04 - Odontograma Clínico Interactivo (SVG)

### HU-07
- **Tipo de Issue:** Story
- **Épica:** EP-04: Odontograma Clínico Interactivo (SVG)
- **Título:** HU-07: Registro Visual de Diagnóstico en SVG por Caras Dentales
- **Story Points:** 8
- **Descripción:** Como odontólogo, quiero hacer clic sobre las caras de los 32 dientes en un dibujo interactivo SVG para marcar caries, resinas, endodoncias o ausencias y guardarlas en el expediente del paciente.
- **Criterios de Aceptación:**
  1. El gráfico SVG muestra las 32 piezas dentales adultas divididas en sus 5 caras interactivas.
  2. Al seleccionar una patología y hacer clic en una cara dental, la zona cambia de color según la convención clínica (ej. Rojo = Caries, Azul = Resina/Obturación).
  3. Al guardar, los registros se almacenan en MySQL vinculados al paciente y a la fecha de atención.
- **Subtareas Técnicas (Tasks):**
  - [ ] `TASK-19`: Crear tabla `odontograma` en MySQL (`id`, `paciente_id`, `diente`, `cara`, `diagnostico`, `observacion`, `fecha_registro`).
  - [ ] `TASK-20`: Construir el componente vectorial SVG de 32 dientes en `app/views/odontograma/ver.php` con atributos `data-diente` y `data-cara`.
  - [ ] `TASK-21`: Programar `public/js/odontograma.js` para pintar caras del SVG y enviar el arreglo de hallazgos por `fetch()`.
  - [ ] `TASK-22`: Crear `app/controllers/OdontogramaController.php` y `OdontogramaModel.php` para guardar el diagnóstico mediante transacción PDO.

### HU-08
- **Tipo de Issue:** Story
- **Épica:** EP-04: Odontograma Clínico Interactivo (SVG)
- **Título:** HU-08: Consulta Histórica de Odontogramas Anteriores
- **Story Points:** 5
- **Descripción:** Como odontólogo, quiero seleccionar fechas de consultas pasadas dentro del expediente del paciente para comparar cómo evolucionaron sus dientes con el tiempo.
- **Criterios de Aceptación:**
  1. La vista presenta un selector con las fechas históricas en que se guardaron odontogramas del paciente.
  2. Al elegir una fecha anterior, el dibujo SVG se actualiza mostrando exactamente los colores y diagnósticos de esa sesión.
- **Subtareas Técnicas (Tasks):**
  - [ ] `TASK-23`: Agregar método en `OdontogramaModel.php` para listar fechas de atención y consultar hallazgos por fecha.
  - [ ] `TASK-24`: Implementar función JavaScript en `public/js/odontograma.js` que limpie y repinte el SVG al cambiar la fecha seleccionada.

---

## ÉPICA 5: EP-05 - Presupuestos y Control de Cartera

### HU-09
- **Tipo de Issue:** Story
- **Épica:** EP-05: Presupuestos y Control de Cartera
- **Título:** HU-09: Creación de Plan de Tratamiento y Presupuesto Inicial
- **Story Points:** 3
- **Descripción:** Como odontólogo, quiero elaborar un presupuesto registrando los procedimientos a realizar y su costo total para acordar formalmente el plan de pago con el paciente.
- **Criterios de Aceptación:**
  1. Al guardar el plan de tratamiento, el sistema define el `costo_total` e inicializa el `saldo_pendiente` por ese mismo valor.
  2. El plan de tratamiento se registra con estado inicial `Activo`.
- **Subtareas Técnicas (Tasks):**
  - [ ] `TASK-25`: Crear tabla `tratamiento` en MySQL (`id`, `paciente_id`, `odontologo_id`, `descripcion`, `costo_total`, `saldo_pendiente`, `estado`, `fecha_creacion`).
  - [ ] `TASK-26`: Crear `app/models/TratamientoModel.php` y `app/controllers/TratamientoController.php`.
  - [ ] `TASK-27`: Maquetar vista de creación y listado de presupuestos en `app/views/tratamiento/index.php`.

### HU-10
- **Tipo de Issue:** Story
- **Épica:** EP-05: Presupuestos y Control de Cartera
- **Título:** HU-10: Registro de Abonos Parciales y Recálculo Automático de Saldo
- **Story Points:** 5
- **Descripción:** Como recepcionista, quiero registrar los abonos de dinero que realiza el paciente sobre su tratamiento para descontar automáticamente su deuda y emitir un soporte de pago claro.
- **Criterios de Aceptación:**
  1. Al guardar un abono, el sistema resta el monto abonado del `saldo_pendiente` del tratamiento de forma automática.
  2. Si el monto digitado supera el `saldo_pendiente`, el sistema rechaza el cobro e impide que queden saldos negativos.
  3. Cuando el `saldo_pendiente` llega a cero ($0), el estado del tratamiento cambia automáticamente a `Pagado`.
- **Subtareas Técnicas (Tasks):**
  - [ ] `TASK-28`: Crear tabla `pago_abono` en MySQL (`id`, `tratamiento_id`, `monto`, `metodo_pago`, `fecha_pago`, `observacion`).
  - [ ] `TASK-29`: Programar transacción PDO en `app/models/PagoModel.php` que inserte el abono, valide que no supere la deuda y actualice `saldo_pendiente` y `estado` en `tratamiento`.
  - [ ] `TASK-30`: Diseñar modal de abono y vista de recibo/historial de pagos en `app/views/tratamiento/pagos.php`.

---

## ÉPICA 6: EP-06 - Retención (Recall WhatsApp) y Dashboard Gerencial

### HU-11
- **Tipo de Issue:** Story
- **Épica:** EP-06: Retención (Recall WhatsApp) y Dashboard Gerencial
- **Título:** HU-11: Lista de Pacientes Inactivos (Recall) con Enlace a WhatsApp
- **Story Points:** 3
- **Descripción:** Como administrador o recepcionista, quiero ver el listado de pacientes que llevan más de 6 meses sin cita o con tratamientos pendientes de pago para enviarles un recordatorio por WhatsApp en un clic.
- **Criterios de Aceptación:**
  1. La tabla lista automáticamente a los pacientes cuya última cita supere los 180 días o que tengan tratamientos activos sin movimiento reciente.
  2. Cada registro incluye un botón que abre `https://wa.me/57[telefono]?text=...` con el mensaje personalizado listo para enviar.
- **Subtareas Técnicas (Tasks):**
  - [ ] `TASK-31`: Escribir consulta SQL con `MAX(fecha)` y `DATEDIFF` en `app/models/RecallModel.php`.
  - [ ] `TASK-32`: Crear `app/controllers/RecallController.php` armando la URL codificada de WhatsApp para cada paciente.
  - [ ] `TASK-33`: Maquetar vista `app/views/recall/index.php` con botón directo a WhatsApp Web/Móvil.

### HU-12
- **Tipo de Issue:** Story
- **Épica:** EP-06: Retención (Recall WhatsApp) y Dashboard Gerencial
- **Título:** HU-12: Panel Principal (Dashboard) con Métricas Financieras y de Citas
- **Story Points:** 5
- **Descripción:** Como administrador, quiero ver en la pantalla de inicio el total de dinero recaudado en el mes, el saldo total por cobrar en cartera y la tasa de inasistencia para controlar el rendimiento del consultorio.
- **Criterios de Aceptación:**
  1. Muestra tarjetas con totales calculados en tiempo real desde MySQL: ingresos del mes (`SUM`), cartera pendiente (`SUM`), citas del día (`COUNT`) y porcentaje de inasistencias.
  2. Los datos coinciden exactamente con los registros guardados en las tablas `cita`, `tratamiento` y `pago_abono`.
- **Subtareas Técnicas (Tasks):**
  - [ ] `TASK-34`: Programar consultas SQL de agregación (`SUM`, `COUNT`, `GROUP BY`) en `app/models/DashboardModel.php`.
  - [ ] `TASK-35`: Crear `app/controllers/DashboardController.php` enviando los indicadores a la vista principal.
  - [ ] `TASK-36`: Maquetar las tarjetas de indicadores y tablas resumen en `app/views/dashboard/index.php` con Bootstrap 5.