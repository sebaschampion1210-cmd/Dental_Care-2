<?php
// =============================================================
// Vista: paciente/index.php (TAREA-10 / HU-03 / RF-03 y RF-04)
// Directorio clínico: tabla de pacientes, paginación, buscador y
// modal de registro, según la "Pantalla 4" de docs/diseno_ui_ux.md.
// Variables que recibe del controlador: $pacientes (arreglo).
// =============================================================

// Tomamos la alerta enviada por el controlador (si existe) y la
// eliminamos para que no reaparezca al recargar la página.
$tipoAlerta    = $_SESSION['tipo_alerta'] ?? '';
$mensajeAlerta = $_SESSION['mensaje_alerta'] ?? '';
unset($_SESSION['tipo_alerta'], $_SESSION['mensaje_alerta']);

// Paginación simple: dividimos el listado en grupos de 10.
$gruposDePacientes = array_chunk($pacientes, 10);
$paginaActual      = max(1, (int)($_GET['pagina'] ?? 1));
$pacientesPagina   = $gruposDePacientes[$paginaActual - 1] ?? [];
$totalPaginas      = max(1, count($gruposDePacientes));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Care | Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #F8FAFC; }
        .alerta-medica { color: #EF4444; font-weight: 600; }
        h1, h2, .btn { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>

<!-- ============ BARRA SUPERIOR: usuario activo + cerrar sesión ============ -->
<nav class="navbar navbar-light bg-white border-bottom py-2">
    <div class="container-fluid">
        <span class="navbar-brand fw-bold text-primary"><i class="bi bi-heart-pulse-fill me-1"></i> Dental Care</span>
        <div class="d-flex align-items-center gap-2">
            <span class="fw-semibold"><?= htmlspecialchars($_SESSION['nombre'] ?? '') ?></span>
            <span class="badge bg-secondary"><?= htmlspecialchars($_SESSION['rol'] ?? '') ?></span>
            <a href="index.php?c=auth&a=cerrarSesion" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </div>
    </div>
</nav>

<div class="container py-4">

    <h1 class="h4 fw-bold mb-3">Pacientes y Expedientes Clínicos</h1>

    <!-- ============ ALERTA DE RESULTADO (éxito o peligro) ============ -->
    <?php if ($mensajeAlerta !== ''): ?>
        <div class="alert alert-<?= $tipoAlerta === 'exito' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <i class="bi <?= $tipoAlerta === 'exito' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i>
            <?= htmlspecialchars($mensajeAlerta) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ============ CABECERA DE ACCIONES: buscador + botón nuevo ============ -->
    <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
        <div class="input-group" style="max-width: 420px;">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="search" id="buscadorPacientes" class="form-control"
                   placeholder="Buscar por cédula, nombre o apellido...">
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevo">
            <i class="bi bi-plus-lg"></i> Nuevo Paciente
        </button>
    </div>

    <!-- ============ TABLA DE PACIENTES ============ -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaPacientes">
                <thead class="table-light">
                    <tr>
                        <th>Documento</th>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Edad</th>
                        <th>Antecedentes Médicos</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="cuerpoTabla">
                    <?php foreach ($pacientesPagina as $paciente): ?>
                        <?php
                            // Calculamos la edad a partir de la fecha de nacimiento.
                            $llegada  = new DateTime($paciente['fecha_nacimiento']);
                            $hoy      = new DateTime();
                            $edad     = $llegada->diff($hoy)->y;
                            // Si el texto contiene la palabra "alérgic", lo resaltamos en rojo.
                            $esAlergico = stristr($paciente['antecedentes_medicos'], 'alérg') !== false;
                        ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($paciente['numero_documento']) ?></td>
                            <td><?= htmlspecialchars($paciente['nombres'] . ' ' . $paciente['apellidos']) ?></td>
                            <td><?= htmlspecialchars($paciente['telefono']) ?></td>
                            <td><?= $edad ?> años</td>
                            <td class="<?= $esAlergico ? 'alerta-medica' : '' ?>">
                                <?= htmlspecialchars($paciente['antecedentes_medicos']) ?>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEditar"
                                        data-id="<?= $paciente['id'] ?>"
                                        data-telefono="<?= htmlspecialchars($paciente['telefono']) ?>"
                                        data-correo="<?= htmlspecialchars($paciente['correo'] ?? '') ?>"
                                        data-antecedentes="<?= htmlspecialchars($paciente['antecedentes_medicos']) ?>"
                                        title="Editar teléfono, correo y antecedentes">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" disabled
                                        title="Expediente y odontograma (disponible en Épica 4)">
                                    <i class="bi bi-file-medical"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" disabled
                                        title="Agendar cita (disponible en Épica 3)">
                                    <i class="bi bi-calendar-plus"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($pacientesPagina === []): ?>
                        <tr><td colspan="6" class="text-center text-secondary py-4">No hay pacientes para mostrar.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============ PAGINACIÓN INFERIOR ============ -->
    <?php if ($totalPaginas > 1): ?>
        <nav class="mt-3"><ul class="pagination pagination-sm">
            <li class="page-item <?= $paginaActual <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?c=paciente&a=index&pagina=<?= $paginaActual - 1 ?>">Anterior</a>
            </li>
            <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
                <li class="page-item <?= $p === $paginaActual ? 'active' : '' ?>">
                    <a class="page-link" href="index.php?c=paciente&a=index&pagina=<?= $p ?>"><?= $p ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $paginaActual >= $totalPaginas ? 'disabled' : '' ?>">
                <a class="page-link" href="index.php?c=paciente&a=index&pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
            </li>
        </ul></nav>
    <?php endif; ?>
</div>

<!-- ============ MODAL: REGISTRAR NUEVO PACIENTE ============ -->
<div class="modal fade" id="modalNuevo" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="index.php?c=paciente&a=guardar">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-plus me-2"></i>Registrar Nuevo Paciente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Número de Documento *</label>
                    <input type="text" name="numero_documento" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Nombres *</label>
                        <input type="text" name="nombres" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Apellidos *</label>
                        <input type="text" name="apellidos" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Teléfono *</label>
                        <input type="tel" name="telefono" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Fecha de Nacimiento *</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" required>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" class="form-control">
                </div>
                <div class="mb-2">
                    <label class="form-label">Antecedentes Médicos *</label>
                    <textarea name="antecedentes_medicos" class="form-control" rows="2"
                              placeholder="Ej: Alérgico a la penicilina. Hipertenso." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Guardar Paciente</button>
            </div>
        </form>
    </div></div>
</div>

<!-- ============ MODAL: EDITAR DATOS BÁSICOS (criterio 3 HU-03) ============ -->
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="POST" action="index.php?c=paciente&a=actualizar">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil me-2"></i>Editar Expediente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="editarId">
                <div class="mb-2">
                    <label class="form-label">Teléfono *</label>
                    <input type="tel" name="telefono" id="editarTelefono" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Correo</label>
                    <input type="email" name="correo" id="editarCorreo" class="form-control">
                </div>
                <div class="mb-2">
                    <label class="form-label">Antecedentes Médicos *</label>
                    <textarea name="antecedentes_medicos" id="editarAntecedentes" class="form-control" rows="2" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Actualizar</button>
            </div>
        </form>
    </div></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Script del buscador asíncrono de pacientes (TASK-12) -->
<script src="public/js/pacientes.js"></script>
<script>
    // Script nativo: al abrir el modal de edición, copiamos los datos
    // del botón presionado (data-*) hacia los campos del formulario.
    const modalEditar = document.getElementById('modalEditar');
    modalEditar.addEventListener('show.bs.modal', function (evento) {
        const boton = evento.relatedTarget;
        document.getElementById('editarId').value = boton.getAttribute('data-id');
        document.getElementById('editarTelefono').value = boton.getAttribute('data-telefono');
        document.getElementById('editarCorreo').value = boton.getAttribute('data-correo');
        document.getElementById('editarAntecedentes').value = boton.getAttribute('data-antecedentes');
    });
</script>

</body>
</html>
