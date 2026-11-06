<?php
// =============================================================
// Vista: cita/index.php (TAREA-16 / HU-05 / RF-06 y RF-07)
// Pantalla 5 del diseño: formulario de reserva (izquierda) y
// disponibilidad del día (derecha) con badges de estado.
// Variables que recibe: $odontologos, $pacientes, $citas, $fechaAgenda.
// =============================================================

// Alerta de resultado (éxito de guardado o error de colisión de horario).
$tipoAlerta    = $_SESSION['tipo_alerta'] ?? '';
$mensajeAlerta = $_SESSION['mensaje_alerta'] ?? '';
unset($_SESSION['tipo_alerta'], $_SESSION['mensaje_alerta']);

// Colores Bootstrap de cada estado del turno (RF-08 / TASK-18).
$coloresEstado = [
    'Pendiente'    => 'warning text-dark',
    'Confirmada'   => 'primary',
    'Atendida'     => 'success',
    'Cancelada'    => 'secondary',
    'Inasistencia' => 'danger',
];

// Fechas vecinas para navegar la agenda día por día.
$diaAnterior = date('Y-m-d', strtotime($fechaAgenda . ' -1 day'));
$diaSiguiente = date('Y-m-d', strtotime($fechaAgenda . ' +1 day'));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Care | Agenda de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #F8FAFC; }
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
            <a href="index.php?c=paciente&a=index" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-people"></i> Pacientes
            </a>
            <a href="index.php?c=auth&a=cerrarSesion" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
            </a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <h1 class="h4 fw-bold mb-3">Agenda y Control de Citas</h1>

    <!-- ============ ALERTA DE RESULTADO (colisión de horario va aquí) ============ -->
    <?php if ($mensajeAlerta !== ''): ?>
        <div class="alert alert-<?= $tipoAlerta === 'exito' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <i class="bi <?= $tipoAlerta === 'exito' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> me-2"></i>
            <?= htmlspecialchars($mensajeAlerta) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3">

        <!-- ============ COLUMNA IZQUIERDA (40%): FORMULARIO DE RESERVA ============ -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-calendar-plus me-1 text-primary"></i> Reservar Nueva Cita
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?c=cita&a=guardar">
                        <div class="mb-2">
                            <label class="form-label">Paciente *</label>
                            <select name="paciente_id" class="form-select" required>
                                <option value="">— Seleccione el paciente —</option>
                                <?php foreach ($pacientes as $paciente): ?>
                                    <option value="<?= $paciente['id'] ?>">
                                        <?= htmlspecialchars($paciente['numero_documento'] . ' — ' . $paciente['nombres'] . ' ' . $paciente['apellidos']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Odontólogo Asignado *</label>
                            <select name="odontologo_id" class="form-select" required>
                                <option value="">— Seleccione el odontólogo —</option>
                                <?php foreach ($odontologos as $odontologo): ?>
                                    <option value="<?= $odontologo['id'] ?>">
                                        <?= htmlspecialchars('Dr. ' . $odontologo['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Fecha de la Cita *</label>
                            <input type="date" name="fecha_cita" class="form-control"
                                   value="<?= htmlspecialchars($fechaAgenda) ?>" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <label class="form-label">Hora de Inicio *</label>
                                <input type="time" name="hora_inicio" class="form-control" required>
                            </div>
                            <div class="col-6 mb-2">
                                <label class="form-label">Hora de Fin *</label>
                                <input type="time" name="hora_fin" class="form-control" required>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Esta hora es un aproximado de cu&aacute;ndo termina la cita.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Motivo de Consulta *</label>
                            <textarea name="motivo" class="form-control" rows="2" required
                                      placeholder="Ej: Valoración, Control de ortodoncia, Urgencia..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-1"></i> Guardar Cita
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ============ COLUMNA DERECHA (60%): AGENDA DEL DÍA ============ -->
        <div class="col-md-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">
                            <i class="bi bi-calendar-event me-1 text-primary"></i>
                            Turnos del <?= date('d/m/Y', strtotime($fechaAgenda)) ?>
                        </span>
                        <span class="d-flex gap-1">
                            <a class="btn btn-sm btn-outline-secondary" href="index.php?c=cita&a=index&fecha=<?= $diaAnterior ?>" title="Día anterior">◀</a>
                            <a class="btn btn-sm btn-outline-secondary" href="index.php?c=cita&a=index&fecha=<?= date('Y-m-d') ?>">Hoy</a>
                            <a class="btn btn-sm btn-outline-secondary" href="index.php?c=cita&a=index&fecha=<?= $diaSiguiente ?>" title="Día siguiente">▶</a>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Horario</th>
                                    <th>Paciente</th>
                                    <th>Odontólogo</th>
                                    <th>Motivo</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas as $cita): ?>
                                    <tr>
                                        <td class="fw-semibold">
                                            <?= substr($cita['hora_inicio'], 0, 5) ?> – <?= substr($cita['hora_fin'], 0, 5) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($cita['nombres'] . ' ' . $cita['apellidos']) ?>
                                            <br><small class="text-secondary">C.C. <?= htmlspecialchars($cita['numero_documento']) ?></small>
                                        </td>
                                        <td><?= htmlspecialchars($cita['odontologo']) ?></td>
                                        <td><small><?= htmlspecialchars($cita['motivo']) ?></small></td>
                                        <td>
                                            <span class="badge bg-<?= $coloresEstado[$cita['estado']] ?>">
                                                <?= $cita['estado'] ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <?php if ($cita['estado'] === 'Atendida' || $cita['estado'] === 'Cancelada'): ?>
                                                <!-- RN-03: cita CERRADA, no admite más cambios -->
                                                <span class="text-secondary" title="Cita cerrada: no admite cambios">
                                                    <i class="bi bi-lock-fill"></i> Cerrada
                                                </span>
                                            <?php else: ?>
                                                <!-- Botones rápidos de estado (TASK-18) -->
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                            data-bs-toggle="dropdown">Cambiar estado</button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a class="dropdown-item" href="index.php?c=cita&a=cambiarEstado&id=<?= $cita['id'] ?>&estado=Confirmada&fecha=<?= $fechaAgenda ?>">
                                                            <i class="bi bi-check2-circle me-1"></i> Confirmar</a></li>
                                                        <li><a class="dropdown-item" href="index.php?c=cita&a=cambiarEstado&id=<?= $cita['id'] ?>&estado=Atendida&fecha=<?= $fechaAgenda ?>">
                                                            <i class="bi bi-clipboard2-check me-1"></i> Marcar Atendida</a></li>
                                                        <li><a class="dropdown-item" href="index.php?c=cita&a=cambiarEstado&id=<?= $cita['id'] ?>&estado=Inasistencia&fecha=<?= $fechaAgenda ?>">
                                                            <i class="bi bi-person-x me-1"></i> Marcar Inasistencia</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger" href="index.php?c=cita&a=cambiarEstado&id=<?= $cita['id'] ?>&estado=Cancelada&fecha=<?= $fechaAgenda ?>">
                                                            <i class="bi bi-x-circle me-1"></i> Cancelar</a></li>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if ($citas === []): ?>
                                    <tr><td colspan="6" class="text-center text-secondary py-4">
                                        <i class="bi bi-calendar-x me-1"></i> No hay turnos reservados para este día.
                                    </td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <small class="text-secondary d-block mt-3">
                        <i class="bi bi-info-circle me-1"></i>
                        RN-01: el sistema rechaza automáticamente horarios cruzados del mismo odontólogo.
                        RN-03: una cita Atendida o Cancelada queda cerrada 🔒 sin cambios.
                    </small>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
