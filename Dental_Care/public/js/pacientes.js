// =============================================================
// Script: pacientes.js (TAREA-12 / HU-04 / RF-05)
// Búsqueda asíncrona de pacientes en tiempo real, en JS nativo.
// Al escribir 3+ caracteres consulta el endpoint con fetch()
// y pinta los resultados SIN recargar la página (criterio 1).
// =============================================================

// Referencias del DOM que vamos a usar en todas las funciones.
const campoBusqueda = document.getElementById('buscadorPacientes');
const cuerpoTabla   = document.getElementById('cuerpoTabla');

// Al cargar la página, recordamos el HTML original de la tabla
// para poder restaurarlo cuando el buscador se limpie (criterio 2).
const filasOriginales = cuerpoTabla.innerHTML;

// Cada vez que el usuario escribe algo en el buscador...
campoBusqueda.addEventListener('input', function () {
    const termino = campoBusqueda.value.trim();

    // Con menos de 3 caracteres mostramos el listado general.
    if (termino.length < 3) {
        cuerpoTabla.innerHTML = filasOriginales;
        return;
    }

    // Con 3 o más, consultamos el endpoint JSON del controlador.
    const url = 'index.php?c=paciente&a=buscar&termino=' +
                encodeURIComponent(termino);

    // fetch() pide los datos; primero convertimos a JSON y luego pintamos.
    fetch(url)
        .then(function (respuesta) { return respuesta.json(); })
        .then(function (json) { pintarResultados(json.datos); });
});

// Reconstruye el tbody en pantalla con las filas que llegan en JSON.
function pintarResultados(pacientes) {
    cuerpoTabla.innerHTML = '';

    if (pacientes.length === 0) {
        cuerpoTabla.innerHTML = '<tr><td colspan="6" class="text-center text-secondary py-4">No se encontraron pacientes.</td></tr>';
        return;
    }

    pacientes.forEach(function (paciente) {
        cuerpoTabla.appendChild(crearFila(paciente));
    });
}

// Construye <tr> de un paciente con createElement/textContent
// (así evitamos inyección de HTML desde los datos).
function crearFila(paciente) {
    const fila = document.createElement('tr');

    agregarCelda(fila, paciente.numero_documento, 'fw-semibold');
    agregarCelda(fila, paciente.nombres + ' ' + paciente.apellidos, '');
    agregarCelda(fila, paciente.telefono, '');

    // Calculamos la edad con la diferencia entre hoy y su nacimiento.
    const nacimiento  = new Date(paciente.fecha_nacimiento);
    const edad        = Math.abs(new Date().getUTCFullYear() - nacimiento.getUTCFullYear());
    agregarCelda(fila, edad + ' años', '');

    // Los antecedentes se resaltan en rojo si son de alergia.
    const esAlergico = paciente.antecedentes_medicos.toLowerCase().includes('alérg');
    agregarCelda(fila, paciente.antecedentes_medicos, esAlergico ? 'alerta-medica' : '');

    // Última celda: botones de acciones (marcados en azul).
    const celdaAcciones = document.createElement('td');
    celdaAcciones.className = 'text-end';
    celdaAcciones.appendChild(crearBotonEditar(paciente));
    fila.appendChild(celdaAcciones);

    return fila;
}

// Crea una celda <td> con texto seguro y clases CSS opcionales.
function agregarCelda(fila, texto, clases) {
    const celda = document.createElement('td');
    if (clases !== '') { celda.className = clases; }
    celda.textContent = texto;
    fila.appendChild(celda);
}

// Crea el botón "editar" compatible con el modal de la vista.
function crearBotonEditar(paciente) {
    const boton = document.createElement('button');
    boton.className = 'btn btn-outline-primary btn-sm';
    boton.title = 'Editar teléfono, correo y antecedentes';

    const icono = document.createElement('i');
    icono.className = 'bi bi-pencil';
    boton.appendChild(icono);

    // Copiamos los datos en atributos data-* para el modal de edición.
    boton.setAttribute('data-bs-toggle', 'modal');
    boton.setAttribute('data-bs-target', '#modalEditar');
    boton.setAttribute('data-id', paciente.id);
    boton.setAttribute('data-telefono', paciente.telefono);
    boton.setAttribute('data-correo', paciente.correo || '');
    boton.setAttribute('data-antecedentes', paciente.antecedentes_medicos);

    return boton;
}
