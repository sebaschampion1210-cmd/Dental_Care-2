# Especificación de Diseño UX/UI y Pantallas del Sistema

**Proyecto:** Sistema Web para Gestión Clínica, Odontograma y Cartera Odontológica  
**Herramienta de Diseño:** Figma  
**Framework Frontend:** Bootstrap 5 + CSS3 Personalizado  
**Dimensiones de Lienzo (Frames):** Escritorio (`1440 px` de ancho) y Móvil (`390 px` de ancho)  

---

## 1. Guía de Estilos (Design System)

### 1.1 Paleta de Colores
El diseño utiliza tonos clínicos limpios que transmiten higiene, orden médico y legibilidad durante largas jornadas frente a la pantalla:

* **Primario (Azul Clínico):** `#0284C7` (Botones principales, enlaces activos, encabezados destacados).
* **Secundario (Azul Oscuro / Pizarra):** `#0F172A` (Barra lateral de navegación, títulos principales y textos de alto contraste).
* **Fondo General:** `#F8FAFC` (Gris muy claro para reducir la fatiga visual).
* **Fondo de Tarjetas y Contenedores:** `#FFFFFF` (Blanco puro con bordes `#E2E8F0`).
* **Colores Semánticos (Estados de Cita y Alertas):**
  * **Éxito / Atendida / WhatsApp / Abono:** `#10B981` (Verde esmeralda).
  * **Advertencia / Pendiente:** `#F59E0B` (Ámbar).
  * **Peligro / Inasistencia / Caries / Error de Cruce:** `#EF4444` (Rojo coral).
  * **Información / Confirmada / Resina Dental:** `#3B82F6` (Azul brillante).

### 1.2 Tipografía (Máximo 2 Fuentes)
1. **Fuente Principal (Títulos y Botones):** *Inter* (Pesos: `600 SemiBold` y `700 Bold`).
2. **Fuente Secundaria (Cuerpo de texto, tablas e inputs):** *Roboto* (Pesos: `400 Regular` y `500 Medium`).

### 1.3 Componentes Reutilizables
* **Botones:** Altura de `40 px`, bordes redondeados (`border-radius: 6px`), variante primaria sólida (`#0284C7`), secundaria con borde (`outline`) y botón especial de WhatsApp (`#10B981`).
* **Inputs y Selectores:** Altura de `42 px`, borde `#CBD5E1`, foco azul (`#0284C7`) y texto de ayuda o error justo debajo del campo.
* **Etiquetas de Estado (Badges):** Píldoras redondeadas con texto en negrita para identificar rápido el estado de la cita (`Pendiente`, `Confirmada`, `Atendida`, `Cancelada`, `Inasistencia`).

---

## 2. Estructura de las Pantallas Mínimas

Cada una de las siguientes pantallas debe diseñarse en dos versiones dentro de Figma: **Escritorio (`1440 px`)** y **Móvil (`390 px`)**.

---

### Pantalla 1: Login (Inicio de Sesión)

* **Objetivo:** Permitir el ingreso seguro de Administradores, Odontólogos y Recepcionistas.
* **Distribución en Escritorio (`1440 px`):**
  * Diseño dividido en dos columnas iguales (50% / 50%).
  * **Columna Izquierda:** Fondo azul clínico (`#0284C7`) con el logotipo del consultorio odontológico, ilustración médica limpia y frase institucional.
  * **Columna Derecha:** Tarjeta centrada sobre fondo `#F8FAFC` con el formulario de acceso.
* **Elementos del Formulario:**
  * Campo de **Correo electrónico** con ícono de usuario.
  * Campo de **Contraseña** con botón de "mostrar/ocultar clave" (ícono de ojo).
  * Caja de alerta roja (oculta por defecto) para mostrar el mensaje: *"Correo o contraseña incorrectos"*.
  * Botón de ancho completo (`100%`): **"Iniciar Sesión"**.
* **Adaptación Móvil (`390 px`):**
  * Se oculta la columna ilustrativa izquierda y se muestra únicamente el logotipo superior centrado seguido de la tarjeta del formulario ocupando el `90%` del ancho de pantalla.

```text
+-------------------------------------------------------------------+
|                         |                                         |
|                         |           [ LOGO DENTAL CARE ]          |
|      PANEL VISUAL       |      Bienvenido al Sistema Clínico      |
|      INSTITUCIONAL      |                                         |
|     (Fondo #0284C7)     |      Correo Electrónico:                |
|                         |      [ usuario@consultorio.com       ]  |
|    Consultorio Dental   |      Contraseña:                        |
|    Gestión de Citas,    |      [ *********************     (O) ]  |
|    Odontograma y Caja   |                                         |
|                         |      [        INICIAR SESIÓN         ]  |
+-------------------------------------------------------------------+
```

---

### Pantalla 2: Menú de Navegación (Layout Principal)

* **Objetivo:** Permitir moverse por todos los módulos del sistema en un solo clic y ver qué usuario tiene la sesión iniciada.
* **Distribución en Escritorio (`1440 px`):**
  * **Barra Lateral Fija (Sidebar izquierdo - `260 px` de ancho, fondo `#0F172A`):**
    1. Logotipo del consultorio en la parte superior.
    2. Ítem: **Dashboard** (Ícono de gráfica).
    3. Ítem: **Agenda de Citas** (Ícono de calendario).
    4. Ítem: **Pacientes y Expedientes** (Ícono de usuarios).
    5. Ítem: **Presupuestos y Caja** (Ícono de billete/cartera).
    6. Ítem: **Recall / Pacientes Inactivos** (Ícono de WhatsApp / campana).
    7. Ítem (Solo Admin): **Usuarios del Sistema** (Ícono de configuración).
  * **Barra Superior (Top Navbar - `64 px` de alto, fondo `#FFFFFF`):**
    * Muestra el título de la pantalla actual a la izquierda.
    * A la derecha muestra el nombre del usuario, su etiqueta de rol (`Odontólogo` / `Recepcionista` / `Administrador`) y el botón **"Cerrar Sesión"**.
* **Adaptación Móvil (`390 px`):**
  * El menú lateral se esconde y se transforma en una barra superior compacta con un **botón de menú hamburguesa (`☰`)** que despliega el menú lateral de forma flotante (*Offcanvas* de Bootstrap).

---

### Pantalla 3: Dashboard (Panel de Control y Métricas)

* **Objetivo:** Mostrar el resumen del consultorio en tiempo real al iniciar sesión.
* **Distribución de Bloques:**
  1. **Fila Superior de 4 Tarjetas KPI (Indicadores Clave):**
     * **Tarjeta 1 - Citas de Hoy:** Número grande (ej. `8 Citas`) + subtexto (`2 pendientes por atender`).
     * **Tarjeta 2 - Ingresos del Mes:** Total recaudado en abonos (ej. `$4.250.000`).
     * **Tarjeta 3 - Cartera por Cobrar:** Saldo total pendiente en tratamientos activos (ej. `$2.150.000`).
     * **Tarjeta 4 - Tasa de Inasistencia:** Porcentaje del mes en color rojo si supera el 15% (ej. `12.5% - 3 faltas`).
  2. **Sección Central (Dividida en 2 columnas: 65% izquierda / 35% derecha):**
     * **Columna Izquierda (Agenda del Día):** Tabla rápida con las citas de hoy (Hora, Paciente, Odontólogo, Motivo, Estado y botón rápido para abrir expediente).
     * **Columna Derecha (Pacientes por Llamar - Recall):** Lista resumida de pacientes con más de 6 meses sin control, cada uno con un botón verde directo: **`[WhatsApp]`**.
* **Adaptación Móvil (`390 px`):**
  * Las 4 tarjetas KPI se apilan una debajo de otra (1 columna) y las tablas permiten desplazamiento horizontal suave (`table-responsive`).

---

### Pantalla 4: Lista de Pacientes (Directorio Clínico)

* **Objetivo:** Consultar, buscar en tiempo real y registrar pacientes sin recargar la página.
* **Elementos de la Pantalla:**
  1. **Cabecera de Acciones:**
     * Barra de búsqueda amplia a la izquierda con ícono de lupa: *"Buscar por número de cédula, nombre o apellido..."* (Búsqueda asíncrona con `fetch()`).
     * Botón primario a la derecha: **`+ Nuevo Paciente`**.
  2. **Tabla de Pacientes:**
     * Columnas: `Documento`, `Nombre Completo`, `Teléfono`, `Edad`, `Antecedentes Médicos` (resaltado en rojo si tiene alergias), y columna `Acciones`.
     * Botones en `Acciones`:
       * **Ver Expediente / Odontograma** (Ícono de diente / carpeta azul).
       * **Editar Datos** (Ícono de lápiz gris).
       * **Agendar Cita** (Ícono de calendario).
  3. **Paginación inferior:** Controles de página (`Anterior`, `1`, `2`, `3`, `Siguiente`).

---

### Pantalla 5: Formulario de Agendamiento de Citas

* **Objetivo:** Programar turnos médicos verificando que el horario no se cruce con otra cita del mismo odontólogo.
* **Distribución de la Pantalla:**
  * **Columna Izquierda (Formulario de Reserva - 40% del ancho):**
    * Selector de **Paciente** (con buscador por cédula).
    * Selector de **Odontólogo** asignado.
    * Campo de **Fecha de la Cita** (`date picker`).
    * Campo de **Hora de Inicio** y **Hora de Fin** (ej. `09:00 AM` a `09:45 AM`).
    * Campo de **Motivo de Consulta** (ej. *Valoración, Control de ortodoncia, Urgencia*).
    * **Alerta de Validación (Mensaje de Error):** Recuadro rojo destacado que aparece cuando hay choque de horario: *"Error: El Dr. Alejandro Martínez ya tiene una cita programada entre las 09:00 y las 09:45"*.
    * Botón primario: **"Guardar Cita"**.
  * **Columna Derecha (Disponibilidad del Día - 60% del ancho):**
    * Listado de los turnos ya ocupados en la fecha seleccionada con botones rápidos para cambiar el estado de la cita (`Confirmar`, `Marcar Atendida`, `Marcar Inasistencia`, `Cancelar`).

---

### Pantalla 6: Vista de Detalle del Paciente (Expediente + Odontograma SVG)

* **Objetivo:** Pantalla principal de trabajo del odontólogo en el sillón dental. Reúne los datos médicos, el odontograma interactivo y el estado de cuenta del paciente.
* **Distribución por Bloques:**
  1. **Encabezado del Expediente (Ficha Superior):**
     * Nombre completo del paciente, número de cédula, edad, teléfono con enlace a WhatsApp y un recuadro amarillo/rojo visible con sus **Antecedentes Médicos** (ej. *"Alerta: Alérgico a la Penicilina / Hipertenso"*).
  2. **Panel Central: Odontograma Interactivo (SVG):**
     * **Barra de Herramientas del Odontograma:**
       * Selector de fechas anteriores (*"Ver odontograma del: 17/11/2026 - Actual"* / *"10/03/2026"*).
       * Leyenda de colores clínicos: `Rojo = Caries`, `Azul = Resina/Obturación`, `Amarillo = Corona`, `Verde = Endodoncia`, `Gris/Cruz = Diente Ausente`.
     * **Lienzo Vectorial SVG (Los 32 dientes permanentes):**
       * Fila superior (Dientes `18` al `11` y `21` al `28`).
       * Fila inferior (Dientes `48` al `41` y `31` al `38`).
       * Cada diente está dividido en 5 polígonos clicables (Vestibular, Lingual, Mesial, Distal y Oclusal en el centro).
     * Botón inferior: **"Guardar Cambios del Odontograma"**.
  3. **Panel Inferior: Planes de Tratamiento y Estado de Cartera:**
     * Tabla que muestra los tratamientos del paciente: `Tratamiento`, `Costo Total`, `Total Abonado`, `Saldo Pendiente` (en negrita) y `Estado` (`En Proceso` o `Liquidado`).
     * Al lado de cada tratamiento activo, el botón verde: **`+ Registrar Abono`**.

```text
+-----------------------------------------------------------------------+
| PACIENTE: Andrés Felipe Castro | CC: 1020304051 | Tel: 3102223344     |
| [!] ALERTA MÉDICA: Alergia a la penicilina                            |
+-----------------------------------------------------------------------+
| ODONTOGRAMA INTERACTIVO         [ Histórico: 05/11/2026 (Actual) v ]  |
| Leyenda: (o) Caries [Rojo]  (o) Resina [Azul]  (X) Ausente [Gris]     |
|                                                                       |
|   [18] [17] [16] [15] [14] [13] [12] [11] | [21] [22] [23] [24] ...   |
|   [48] [47] [46] [45] [44] [43] [42] [41] | [31] [32] [33] [34] ...   |
+-----------------------------------------------------------------------+
| PLANES DE TRATAMIENTO Y SALDOS               [+ Nuevo Presupuesto]    |
| Tratamiento              | Costo Total | Abonado  | Saldo    | Acción |
| Endodoncia + Corona M36  | $950.000    | $500.000 | $450.000 | [Abonar|
+-----------------------------------------------------------------------+
```

---

### Pantalla 7: Ventanas Modales (Emergentes)

Se diseñan dos ventanas modales superpuestas con fondo oscurecido al 50% (`backdrop`):

#### Modal A: Registrar Abono de Dinero (Control de Cartera)
* **Encabezado:** *"Registrar Abono a Tratamiento"* + Botón de cierre (`X`).
* **Resumen Informativo Superior:**
  * Tratamiento: *Ortodoncia Correctiva Metálica*.
  * Costo Total: `$1.800.000` | **Saldo Pendiente Actual: `$450.000`**.
* **Campos del Formulario:**
  * **Monto a Abonar ($):** Input numérico (Si el usuario digita un valor mayor a `$450.000`, aparece un borde rojo con el mensaje: *"El abono no puede superar el saldo pendiente de $450.000"*).
  * **Método de Pago:** Selector (`Efectivo`, `Transferencia`, `Tarjeta`).
  * **Observación / Recibo:** Texto corto (ej. *"Abono cuota mensual noviembre"*).
* **Pie del Modal:** Botón gris `"Cancelar"` y botón verde `"Confirmar Pago y Generar Recibo"`.

#### Modal B: Asignar Diagnóstico a Cara Dental (Al hacer clic en un diente del SVG)
* **Encabezado:** *"Diagnóstico Pieza Dental N.° 16 - Cara Oclusal"*.
* **Campos del Formulario:**
  * Selector de **Hallazgo / Procedimiento:** (`Caries`, `Resina / Obturación`, `Endodoncia`, `Corona`, `Pieza Ausente`, `Diente Sano / Limpiar`).
  * Campo de **Nota Clínica:** Breve observación de la pieza (ej. *"Caries profunda, requiere radiografía periapical"*).
* **Pie del Modal:** Botón `"Cancelar"` y botón primario `"Aplicar al Diente"`.

---

## 3. Conexiones para el Prototipo Navegable en Figma

Para cumplir con la entrega del prototipo interactivo de la Sesión 4, conecta las pantallas en la pestaña **Prototype** de Figma siguiendo estas flechas:

1. **Login** $\rightarrow$ Clic en botón *"Iniciar Sesión"* $\rightarrow$ Navega al **Dashboard**.
2. **Menú Lateral (Sidebar)** $\rightarrow$ Clic en *"Pacientes"* $\rightarrow$ Navega a **Lista de Pacientes**.
3. **Lista de Pacientes** $\rightarrow$ Clic en botón *"Ver Expediente"* $\rightarrow$ Navega a **Vista de Detalle (Expediente + Odontograma)**.
4. **Vista de Detalle** $\rightarrow$ Clic sobre cualquier diente del **Odontograma SVG** $\rightarrow$ Abre como *Overlay* el **Modal B (Diagnóstico Dental)**.
5. **Vista de Detalle** $\rightarrow$ Clic en botón *"+ Registrar Abono"* $\rightarrow$ Abre como *Overlay* el **Modal A (Registrar Abono)**.
6. **Menú Lateral (Sidebar)** $\rightarrow$ Clic en *"Agenda de Citas"* $\rightarrow$ Navega al **Formulario de Agendamiento de Citas**.