# Especificación de Requisitos de Software (SRS)

**Proyecto:** Sistema Web para Gestión Clínica, Odontograma y Cartera Odontológica  
**Estándar:** ISO/IEC/IEEE 29148:2018  
**Arquitectura:** MVC Nativo (Modelo-Vista-Controlador)  
**Tecnologías:** PHP 8+, MySQL WORKBEANCH, Bootstrap 5, JavaScript Vanilla (Sin Frameworks)

---

## 1. Estructura de Organización del Proyecto y Carpetas

Siguiendo la estructura del estándar MVC y las directrices del proyecto final, el repositorio se organiza de la siguiente manera:

Dental_Care/
├── .opencode/ # Configuración y cerebro de la Inteligencia Artificial
│ └── skills/ # Tus 5 carpetas de habilidades configuradas con tus nombres exactos
│ ├── aprobar-tareas/ # Automatiza el cierre del backlog y genera tus comandos Git con
│ │ └── SKILL.md
│ ├── codijo-simple/ # Escribe código PHP en MVC nativo, corto, seguro y te da las 3 viñetas para la sustentación
│ │ └── SKILL.md
│ ├── corregir-tareas/ # Diagnostica errores de PHP/XAMPP y realiza correcciones quirúrgicas en el archivo exacto
│ │ └── SKILL.md
│ ├── mirar-tareas/ # Escanea tus documentos en docs/ para decirte qué subtarea sigue pendiente por programar
│ │ └── SKILL.md
│ └── verificar-criterios/ # Audita tu código y genera el checklist [OK] o [FALLA] con tus reglas de negocio (RN-01 a RN-03)
│ └── SKILL.md
├── app/ # El núcleo de la aplicación (Lógica del patrón de arquitectura MVC)
│ ├── config/ # Configuración global del sistema (ej. conexión segura a la base de datos vía PDO)
│ ├── controllers/ # Controladores: Procesan los datos que envía el usuario y coordinan las respuestas
│ ├── models/ # Modelos: Contienen las clases encargadas de ejecutar las consultas SQL mediante PDO
│ └── views/ # Vistas: Pantallas de la interfaz del usuario maquetadas en HTML5 y Bootstrap 5
├── database/ # Persistencia y diseño relacional de la base de datos
│ ├── modelo_eer.mwb # Diagrama entidad-relación gráfico diseñado en MySQL Workbench
│ ├── schema.sql # Código SQL con las instrucciones CREATE TABLE para montar la base de datos en XAMPP
│ ├── datos_prueba.sql # Registros iniciales simulados para que puedas iniciar sesión y hacer pruebas de inmediato
│ └── consultas.sql # Repositorio de consultas SQL clave para los reportes clínicos del negocio
├── docs/ # Planos técnicos de desarrollo y documentación de soporte del proyecto
│ ├── investigacion/ # Almacena las evidencias de campo iniciales, encuestas y entrevistas con la clínica
│ ├── requisitos.md # Requisitos funcionales y reglas de negocio del software (ej. restricciones de horarios)
│ ├── backlog_jira.md # Listado organizado en Markdown con tus Historias de Usuario (HU) y subtareas (TASK)
│ ├── trazabilidad.md # Matriz que vincula tus requisitos con el código y sus respectivos casos de prueba
│ ├── diseno_ui_ux.md # Guía de interfaz con la paleta de colores, componentes aprobados y tipografías
│ └── figma.pdf # Prototipos gráficos de alta fidelidad de las interfaces para pantallas móviles y escritorio
├── public/ # Única carpeta expuesta al servidor web para archivos estáticos por seguridad
│ ├── css/ # Archivos de estilos personalizados para las variaciones visuales de la clínica dental
│ ├── js/ # Archivos de JavaScript nativo para interacciones en tiempo real y validaciones del lado del cliente
│ └── img/ # Repositorio de imágenes, logotipos de la clínica, avatares del sistema e iconos
├── index.php # Enrutador principal de la aplicación: Captura todas las peticiones web y las distribuye
├── AGENTS.MD # Configuración global que define el rol y comportamiento de tu asistente de OpenCode
├── MEMORY.MD # Historial a largo plazo para mantener al agente al tanto de las decisiones tomadas en el proyecto
└── README.md # Documento explicativo paso a paso para que cualquier instructor instale el proyecto localmente

## 2. Módulos del Sistema

El sistema se divide en siete módulos principales que cubren las necesidades operativas del consultorio:

1. **Módulo de Seguridad y Usuarios:** Gestiona el inicio de sesión seguro y define los permisos de acceso según el tipo de usuario (Administrador y Odontólogo).
2. **Módulo de Pacientes y Expedientes:** Administra los datos personales, números de contacto y antecedentes médicos de cada paciente mediante un buscador rápido por cédula o nombre.
3. **Módulo de Agenda y Citas:** Organiza los turnos del consultorio, impide que se programen dos citas a la misma hora con el mismo especialista y actualiza el estado de cada consulta (Pendiente, Confirmada, Atendida, Cancelada, Inasistencia).
4. **Módulo de Odontograma Digital Interactivo:** Permite ver en pantalla un gráfico de los 32 dientes permanentes en formato SVG para registrar patologías (como caries, resinas o ausencias) haciendo clic en cada una de sus caras, guardando el historial por fecha.
5. **Módulo de Presupuestos y Control de Abonos:** Registra los tratamientos a realizar con sus costos totales, gestiona los abonos parciales que hace el paciente y calcula automáticamente cuánto dinero queda debiendo.
6. **Módulo de Retención y Reactivación (Recall):** Identifica a los pacientes que llevan más de 6 meses sin asistir o con tratamientos incompletos, ofreciendo un enlace directo para enviarles un mensaje recordatorio por WhatsApp en un solo clic.
7. **Módulo de Dashboard y Reportes:** Muestra en la pantalla principal tarjetas y gráficos con el dinero recaudado en el mes, el saldo pendiente por cobrar en tratamientos activos y el porcentaje de inasistencias de la semana.

---

## 3. Descripción del Problema

Actualmente, el consultorio odontológico lleva el control de sus citas en libretas de papel y mensajes informales de WhatsApp, lo que provoca confusiones en los horarios y una pérdida frecuente de citas cuando los pacientes no asisten sin avisar.

Por otro lado, las historias clínicas y los odontogramas impresos en hojas de papel se deterioran con el tiempo, son difíciles de archivar y no permiten comparar con rapidez los cambios en los dientes entre una consulta y otra.

Además, en tratamientos costosos y de varias sesiones como la ortodoncia o las endodoncias, los cobros se anotan a mano en talonarios de recibos físicos; esto ocasiona discusiones con los clientes sobre cuánto dinero han abonado, cuánto deben exactamente y deja en el olvido a los pacientes que suspenden sus tratamientos a mitad de camino.

---

## 4. Pregunta Problema

¿De qué manera un sistema web desarrollado bajo arquitectura MVC con PHP 8 y MySQL permite automatizar el agendamiento de turnos, el registro visual de diagnósticos dentales y el control de pagos y cobros en un consultorio odontológico?

---

## 5. Justificación

La implementación de este sistema web resuelve de raíz el desorden administrativo del consultorio al centralizar toda su información en una base de datos segura y accesible desde cualquier navegador.

La solución ahorra tiempo al personal eliminando el cruce involuntario de turnos médicos en la agenda, protege la historia dental del paciente a través de un odontograma interactivo fácil de consultar y da total transparencia financiera al calcular de forma exacta los abonos y deudas pendientes.

Asimismo, reactiva las ventas del negocio al alertar oportunamente sobre pacientes inactivos, mejorando la atención sin incurrir en costos de software complejos.

---

## 6. Objetivos del Proyecto

### 6.1 Objetivo General

Desarrollar una aplicación web bajo el patrón arquitectónico MVC utilizando PHP 8+, MySQL, Bootstrap 5 y JavaScript nativo, que centralice la gestión de citas médicas, el registro interactivo del odontograma y el control de cartera de tratamientos en un consultorio odontológico.

### 6.2 Objetivos Específicos

1. Diseñar una base de datos relacional normalizada en MySQL Workbench que garantice el almacenamiento seguro de pacientes, citas, caras dentales, presupuestos y abonos de dinero.
2. Construir un módulo de agenda que valide automáticamente los horarios para evitar citas superpuestas sobre el mismo especialista.
3. Desarrollar un odontograma visual interactivo con SVG y JavaScript para registrar diagnósticos por cara dental y consultar su historial cronológico.
4. Programar un módulo de cartera que calcule automáticamente los saldos adeudados y presente un panel de indicadores con el dinero recaudado y las citas atendidas.

---

## 7. Alcance y Limitaciones

### 7.1 Alcance del Sistema

- Inicio de sesión con contraseñas encriptadas y permisos para Administrador y Odontólogo.
- Creación, edición y búsqueda inmediata de expedientes de pacientes con sus antecedentes médicos.
- Calendario y agenda médica con estados de turno: Pendiente, Confirmada, Atendida, Cancelada e Inasistencia.
- Odontograma digital para la dentición permanente de 32 piezas dentales, permitiendo marcar patologías en sus 5 caras.
- Registro de presupuestos con lista de procedimientos, control de abonos recibidos en caja y cálculo de saldos.
- Bandeja de pacientes inactivos con botón de contacto directo a WhatsApp (`wa.me`).
- Panel principal con métricas de ingresos mensuales y tasa de ausentismo.

### 7.2 Limitaciones del Sistema

- No procesa pagos en línea con tarjetas de crédito o pasarelas bancarias (los pagos se registran como cobros presenciales en efectivo o transferencias manuales).
- No realiza envíos masivos mediante la API oficial de pago de WhatsApp Cloud; utiliza enlaces directos del navegador hacia WhatsApp Web o móvil.
- No incluye facturación electrónica bajo validaciones de la DIAN.

---

## 8. Tabla de Actores del Sistema

| Actor             | Responsabilidad Principal                                                                                                          | Acceso en el Sistema                                                                                                                   |
| :---------------- | :--------------------------------------------------------------------------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------- |
| **Administrador** | Supervisa la administración del negocio, revisa reportes de caja, audita la cartera pendiente y gestiona el personal.              | Acceso total a todos los módulos: usuarios, agenda completa, expedientes, abonos, reportes gerenciales y recall.                       |
| **Odontólogo**    | Realiza la atención médica, actualiza el odontograma digital, consulta su agenda diaria y formula presupuestos clínicos.           | Acceso a su propia agenda de citas, consulta de expedientes de pacientes, odontograma interactivo y creación de planes de tratamiento. |
| **Recepcionista** | Atiende al público, programa citas en la agenda, actualiza la asistencia de los pacientes y registra los abonos de dinero en caja. | Acceso al módulo de pacientes, programación de citas, cambio de estados de turno, registro de pagos y módulo de recall.                |

---

## 9. Requisitos Funcionales (RF)

| ID        | Módulo      | Requisito Funcional                                                                                                                | Prioridad | Criterio de Aceptación                                                                                                                      |
| :-------- | :---------- | :--------------------------------------------------------------------------------------------------------------------------------- | :-------- | :------------------------------------------------------------------------------------------------------------------------------------------ |
| **RF-01** | Seguridad   | El sistema debe validar las credenciales del usuario al iniciar sesión mediante contraseñas encriptadas.                           | Alta      | Si los datos son válidos, permite ingresar al sistema en menos de 2 segundos; si son erróneos, muestra un mensaje de alerta.                |
| **RF-02** | Seguridad   | El sistema debe bloquear el acceso a cualquier página interna si el usuario no ha iniciado sesión previamente.                     | Alta      | Escribir la URL de una sección protegida sin haber iniciado sesión redirige automáticamente a la pantalla de login.                         |
| **RF-03** | Pacientes   | El sistema debe permitir registrar nuevos pacientes con nombres, cédula, teléfono, correo y antecedentes médicos.                  | Alta      | Al guardar los datos obligatorios, el paciente queda almacenado en la base de datos y disponible para agendar citas.                        |
| **RF-04** | Pacientes   | El sistema debe impedir el registro de dos pacientes con el mismo número de documento de identidad.                                | Alta      | Si se digita un número de cédula que ya existe en el sistema, la operación se cancela y se notifica que el documento ya está registrado.    |
| **RF-05** | Pacientes   | El sistema debe permitir buscar pacientes ingresando parte de su nombre, apellido o número de documento.                           | Media     | Al escribir 3 o más letras en la barra de búsqueda, la tabla muestra únicamente los pacientes coincidentes al instante.                     |
| **RF-06** | Agenda      | El sistema debe permitir agendar citas indicando el paciente, el odontólogo, la fecha, la hora de inicio y el motivo.              | Alta      | Al confirmar los datos, el turno queda registrado con estado inicial Pendiente y aparece en el calendario del especialista.                 |
| **RF-07** | Agenda      | El sistema debe bloquear la asignación de una cita si el horario elegido choca con otra cita previa del mismo odontólogo.          | Alta      | Si el horario solicitado se cruza con otra cita activa, el sistema detiene el registro y avisa que el odontólogo ya se encuentra ocupado.   |
| **RF-08** | Agenda      | El sistema debe permitir cambiar el estado de una cita a Confirmada, Atendida, Cancelada o Inasistencia.                           | Alta      | Cambiar el estado a Inasistencia libera la ocupación de la agenda y actualiza el contador de faltas en los reportes.                        |
| **RF-09** | Odontograma | El sistema debe presentar un gráfico interactivo de los 32 dientes para marcar patologías haciendo clic en cada cara dental.       | Alta      | Al seleccionar una cara del diente y elegir un diagnóstico (como caries), la pieza se colorea en pantalla y el registro se guarda.          |
| **RF-10** | Odontograma | El sistema debe permitir consultar el odontograma de visitas anteriores de un paciente según la fecha seleccionada.                | Media     | Al elegir una fecha previa en la lista, el dibujo dental se actualiza para mostrar exactamente cómo estaban los dientes en esa fecha.       |
| **RF-11** | Cartera     | El sistema debe permitir crear presupuestos clínicos sumando los procedimientos a realizar para calcular el costo total.           | Alta      | Al agregar los tratamientos acordados con el paciente, el sistema guarda el costo total e inicializa el saldo pendiente por el mismo valor. |
| **RF-12** | Cartera     | El sistema debe permitir registrar abonos de dinero a un tratamiento activo y descontar automáticamente la deuda del paciente.     | Alta      | Si un paciente debe $200.000 y abona $50.000, el sistema guarda el pago y actualiza el saldo pendiente a $150.000 de forma inmediata.       |
| **RF-13** | Cartera     | El sistema debe rechazar cualquier abono de dinero que sea mayor al saldo adeudado por el paciente.                                | Alta      | Si se intenta registrar un pago de $100.000 sobre una deuda de $60.000, el sistema bloquea el cobro e impide saldos negativos.              |
| **RF-14** | Cartera     | El sistema debe cambiar automáticamente el estado de un tratamiento a Liquidado cuando el saldo adeudado llegue a cero pesos ($0). | Media     | Al ingresar el pago final que salda la totalidad del tratamiento, el estado pasa a Pagado sin necesidad de cambiarlo a mano.                |
| **RF-15** | Recall      | El sistema debe listar a los pacientes que no han tenido citas médicas en los últimos 6 meses o que tienen tratamientos a medias.  | Media     | La pantalla de retención muestra el listado ordenado de pacientes que llevan más de 180 días sin asistir al consultorio.                    |
| **RF-16** | Recall      | El sistema debe proporcionar un botón que abra WhatsApp con un mensaje redactado para invitar al paciente a su control preventivo. | Media     | Al hacer clic en el botón verde, se abre WhatsApp con el teléfono del paciente y el texto del recordatorio listo para enviar.               |
| **RF-17** | Dashboard   | El sistema debe mostrar en el panel inicial el total de dinero recaudado en el mes y el porcentaje de inasistencias registradas.   | Media     | La pantalla principal presenta tarjetas con los totales del mes calculados directamente con las operaciones guardadas en la base de datos.  |

---

## 10. Requisitos No Funcionales (RNF)

| ID         | Categoría      | Requisito No Funcional                                                                    | Criterio de Medición                                                                                                                    |
| :--------- | :------------- | :---------------------------------------------------------------------------------------- | :-------------------------------------------------------------------------------------------------------------------------------------- |
| **RNF-01** | Seguridad      | Las contraseñas de los usuarios deben guardarse encriptadas en la base de datos.          | Uso de la función nativa de encriptación de PHP (Bcrypt). Queda prohibido guardar claves en texto legible.                              |
| **RNF-02** | Seguridad      | El sistema debe prevenir ataques informáticos de inyección SQL en todos sus formularios.  | El 100% de las consultas a MySQL deben utilizar sentencias preparadas mediante PDO.                                                     |
| **RNF-03** | Rendimiento    | El tiempo de respuesta del sistema debe ser rápido al navegar entre pantallas.            | Las páginas y consultas deben cargar en menos de 2 segundos en el entorno de desarrollo local (XAMPP).                                  |
| **RNF-04** | Usabilidad     | La interfaz gráfica debe adaptarse a pantallas de computadores y teléfonos celulares.     | El diseño debe verse ordenado y ser completamente funcional tanto en monitores de escritorio como en pantallas móviles con Bootstrap 5. |
| **RNF-05** | Arquitectura   | El código debe organizarse de forma limpia bajo el patrón Modelo-Vista-Controlador (MVC). | Separación estricta de carpetas para modelos, vistas y controladores en PHP puro, sin depender de frameworks como Laravel.              |
| **RNF-06** | Compatibilidad | El sistema debe funcionar de manera uniforme en los navegadores web actuales.             | Funcionamiento verificado en las versiones más recientes de Google Chrome, Mozilla Firefox y Microsoft Edge.                            |

---

## 11. Historias de Usuario (HU)

- **HU-01:** Como **recepcionista**, quiero registrar los datos de un nuevo paciente para crear su ficha médica antes de programarle una cita.
  - _Criterio de Aceptación:_ El sistema guarda los datos obligatorios y deja al paciente habilitado inmediatamente para asignarle una cita.
- **HU-02:** Como **recepcionista**, quiero agendar una cita comprobando que el horario esté libre para evitar que dos pacientes lleguen a la misma hora con el mismo doctor.
  - _Criterio de Aceptación:_ El sistema no permite agendar turnos en horas ocupadas y muestra una alerta clara en pantalla.
- **HU-03:** Como **recepcionista**, quiero marcar las citas de los pacientes que no asistieron como 'Inasistencia' para llevar el control de turnos perdidos.
  - _Criterio de Aceptación:_ Al cambiar el estado a inasistencia, el turno se registra como perdido y alimenta las métricas de ausentismo del mes.
- **HU-04:** Como **odontólogo**, quiero marcar los diagnósticos directamente sobre un dibujo digital de los dientes para ver gráficamente el estado de la boca del paciente.
  - _Criterio de Aceptación:_ Al hacer clic sobre cualquier cara del diente, el dibujo cambia de color y la información queda almacenada.
- **HU-05:** Como **odontólogo**, quiero consultar los odontogramas de citas pasadas para comparar la evolución de los tratamientos en el tiempo.
  - _Criterio de Aceptación:_ Al seleccionar una fecha previa, el dibujo muestra exactamente el estado en que estaban los dientes en esa fecha.
- **HU-06:** Como **odontólogo**, quiero elaborar un presupuesto con los procedimientos a realizar para acordar el costo total del tratamiento con el paciente.
  - _Criterio de Aceptación:_ El sistema calcula automáticamente la suma de los valores y fija la deuda inicial del paciente.
- **HU-07:** Como **recepcionista**, quiero registrar los abonos de dinero que hace el paciente para mantener su cuenta al día y entregarle soporte del saldo que le queda.
  - _Criterio de Aceptación:_ Cada abono descuenta de forma inmediata el dinero adeudado y queda asentado con fecha y hora.
- **HU-08:** Como **administrador**, quiero que el sistema rechace abonos por valores superiores a la deuda actual para evitar descuadres de caja y saldos negativos.
  - _Criterio de Aceptación:_ Si se digita un valor mayor al saldo adeudado, el sistema no procesa el cobro y muestra un mensaje de corrección.
- **HU-09:** Como **administrador**, quiero un listado de los pacientes que llevan más de 6 meses sin consulta para enviarles un mensaje de control por WhatsApp en un solo clic.
  - _Criterio de Aceptación:_ La tabla muestra a los pacientes inactivos con un botón que abre WhatsApp con el mensaje preventivo ya redactado.
- **HU-10:** Como **administrador**, quiero ver en el panel principal el dinero cobrado en el mes y las citas atendidas para evaluar el rendimiento general del consultorio.
  - _Criterio de Aceptación:_ Los números del panel principal coinciden exactamente con los pagos y las citas registradas en la base de datos.

---

## 12. Reglas de Negocio (RN)

- **RN-01: Prohibición de Citas Cruzadas en Agenda**  
  El sistema no permite programar dos citas para el mismo odontólogo en horarios que se superpongan total o parcialmente. Antes de guardar cualquier turno, el sistema verifica que la hora de inicio y de fin solicitadas se encuentren en un espacio completamente libre, evitando esperas excesivas y cruces de pacientes en el sillón dental.

- **RN-02: Control de Saldos y Cobros Exactos**  
  El saldo pendiente de cualquier tratamiento se calcula de forma automática restando el total de los abonos registrados al costo total del presupuesto acordado. El sistema prohíbe terminantemente registrar abonos que superen la deuda actual, garantizando que el saldo nunca sea negativo y evitando confusiones contables al momento de cobrar.

- **RN-03: Bloqueo de Modificación en Citas Concluidas**  
  Una vez que una cita pasa al estado de Atendida o Cancelada, sus datos de fecha, hora, odontólogo y paciente no pueden ser editados ni eliminados. Esta regla garantiza la validez legal del historial médico del consultorio y asegura que los reportes de auditoría no sean alterados.
