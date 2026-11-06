# Matriz de Trazabilidad e Hitos de Épicas

**Proyecto:** Sistema Web para Gestión Clínica, Odontograma y Cartera Odontológica  
**Instrucciones para Open Code:** Actualizar la casilla `[ ]` a `[x]` en la sección de Épicas y cambiar la columna **Estado** de `[ ] Pendiente` a `[x] Completado` a medida que se implemente y verifique cada funcionalidad.

---

## 1. Control de Épicas del Sistema

| ID Épica | Nombre de la Épica / Módulo | Historias Asociadas | Story Points | Estado de Épica |
| :--- | :--- | :--- | :---: | :--- |
| **EP-01** | Seguridad y Gestión de Accesos | HU-01, HU-02 | 5 | [x] Completado |
| **EP-02** | Gestión de Pacientes y Expedientes Clínicos | HU-03, HU-04 | 5 | [x] Completado |
| **EP-03** | Agenda y Control de Citas Médicas | HU-05, HU-06 | 7 | [ ] Pendiente |
| **EP-04** | Odontograma Clínico Interactivo (SVG) | HU-07, HU-08 | 13 | [ ] Pendiente |
| **EP-05** | Presupuestos y Control de Cartera | HU-09, HU-10 | 8 | [ ] Pendiente |
| **EP-06** | Retención (Recall WhatsApp) y Dashboard Gerencial | HU-11, HU-12 | 8 | [ ] Pendiente |

---

## 2. Matriz de Trazabilidad Completa

| Requisito | Historia | Módulo / Épica | Funcionalidad Clave | Caso de Prueba | Estado |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **RF-01** | HU-01 | EP-01: Seguridad y Gestión de Accesos | Autenticación de usuarios con `password_verify` y variables de sesión por rol | CP-01: Login con credenciales válidas e inválidas | [x] Completado |
| **RF-02** | HU-02 | EP-01: Seguridad y Gestión de Accesos | Bloqueo de rutas internas por URL si no existe sesión activa | CP-02: Intento de acceso directo a URL protegida sin login | [x] Completado |
| **RF-03** | HU-03 | EP-02: Gestión de Pacientes y Expedientes | Creación y edición de ficha de paciente con datos y antecedentes | CP-03: Registro exitoso de paciente nuevo en base de datos | [x] Completado |
| **RF-04** | HU-03 | EP-02: Gestión de Pacientes y Expedientes | Validación de número de documento único en la tabla `paciente` | CP-04: Rechazo de registro al ingresar una cédula duplicada | [x] Completado |
| **RF-05** | HU-04 | EP-02: Gestión de Pacientes y Expedientes | Buscador asíncrono de pacientes por nombre o cédula con `fetch()` | CP-05: Filtrado en tiempo real al escribir 3 o más caracteres | [x] Completado |
| **RF-06** | HU-05 | EP-03: Agenda y Control de Citas Médicas | Programación de cita asociando paciente, odontólogo, fecha y hora | CP-06: Creación de cita nueva con estado inicial Pendiente | [ ] Pendiente |
| **RF-07** | HU-05 | EP-03: Agenda y Control de Citas Médicas | Algoritmo anti-solapamiento horario por odontólogo en MySQL | CP-07: Bloqueo y alerta al intentar agendar dos citas a la misma hora | [ ] Pendiente |
| **RF-08** | HU-06 | EP-03: Agenda y Control de Citas Médicas | Cambio de estado de cita (`Confirmada`, `Atendida`, `Cancelada`, `Inasistencia`) y bloqueo de concluidas | CP-08: Cambio a estado Inasistencia y bloqueo de edición en cita Atendida | [ ] Pendiente |
| **RF-09** | HU-07 | EP-04: Odontograma Clínico Interactivo | Selección y coloreado de las 5 caras en los 32 dientes del gráfico SVG | CP-09: Clic en cara dental, cambio de color y guardado en MySQL | [ ] Pendiente |
| **RF-10** | HU-08 | EP-04: Odontograma Clínico Interactivo | Filtro cronológico para visualizar odontogramas de fechas pasadas | CP-10: Selección de fecha anterior y reconstrucción visual del SVG | [ ] Pendiente |
| **RF-11** | HU-09 | EP-05: Presupuestos y Control de Cartera | Creación de plan de tratamiento con cálculo de costo total y saldo inicial | CP-11: Guardado de presupuesto con `saldo_pendiente` igual a `costo_total` | [ ] Pendiente |
| **RF-12** | HU-10 | EP-05: Presupuestos y Control de Cartera | Registro de abonos parciales y descuento automático del saldo pendiente | CP-12: Registro de abono en caja y descuento exacto en el saldo del plan | [ ] Pendiente |
| **RF-13** | HU-10 | EP-05: Presupuestos y Control de Cartera | Regla de negocio que impide registrar un abono mayor a la deuda actual | CP-13: Rechazo de transacción al digitar un abono superior al saldo pendiente | [ ] Pendiente |
| **RF-14** | HU-10 | EP-05: Presupuestos y Control de Cartera | Cambio automático del tratamiento a estado `Pagado` cuando el saldo es $0 | CP-14: Abono del saldo restante total y transición automática a Pagado | [ ] Pendiente |
| **RF-15** | HU-11 | EP-06: Retención (Recall) y Dashboard | Consulta de pacientes con más de 180 días sin cita o con tratamientos inactivos | CP-15: Listado correcto de pacientes que superan 6 meses sin atención | [ ] Pendiente |
| **RF-16** | HU-11 | EP-06: Retención (Recall) y Dashboard | Generación de botón con enlace `https://wa.me/` y mensaje personalizado | CP-16: Apertura de enlace de WhatsApp con teléfono y texto del paciente | [ ] Pendiente |
| **RF-17** | HU-12 | EP-06: Retención (Recall) y Dashboard | Panel gerencial con recaudo mensual, cartera por cobrar y tasa de inasistencia | CP-17: Verificación de sumatorias y conteos SQL en las tarjetas del Dashboard | [ ] Pendiente |