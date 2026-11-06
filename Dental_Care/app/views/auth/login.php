<?php


// Tomamos el error guardado en la sesión (si existe) y luego lo
// eliminamos para que la alerta no reaparezca al recargar.
$errorLogin = $_SESSION['error_login'] ?? '';
unset($_SESSION['error_login']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Care | Iniciar Sesión</title>
    <!-- Bootstrap 5 desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Panel visual institucional (columna izquierda) */
        .panel-visual { background-color: #0284C7; }
        h1, h2, .btn { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <!-- ============ COLUMNA IZQUIERDA: PANEL INSTITUCIONAL ============ -->
        <!-- En móvil (d-md-none) se oculta, tal como pide el diseño. -->
        <div class="col-md-6 panel-visual d-none d-md-flex flex-column justify-content-center align-items-center text-white text-center p-5">
            <i class="bi bi-heart-pulse-fill" style="font-size: 5rem;"></i>
            <h2 class="mt-4 fw-bold">Dental Care</h2>
            <p class="mt-3 mb-0 fs-5">Consultorio Dental<br>
               Gestión de Citas, Odontograma y Caja</p>
        </div>

        <!-- ============ COLUMNA DERECHA: TARJETA DEL FORMULARIO ============ -->
        <div class="col-12 col-md-6 d-flex justify-content-center align-items-center"
             style="background-color: #F8FAFC;">
            <div class="card shadow-sm border-0" style="width: 100%; max-width: 420px; border-radius: 12px;">

                <div class="card-body p-4 p-md-5">
                    <!-- En móvil mostramos el logo centrado arriba -->
                    <div class="text-center mb-4">
                        <i class="bi bi-heart-pulse-fill text-primary" style="font-size: 2.5rem;"></i>
                        <h1 class="h3 fw-bold mt-2 mb-1">Dental Care</h1>
                        <p class="text-secondary mb-0">Bienvenido al Sistema Clínico</p>
                    </div>

                    <!-- Alerta roja: solo aparece si hubo credenciales no válidas -->
                    <?php if ($errorLogin !== ''): ?>
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div><?= htmlspecialchars($errorLogin) ?></div>
                        </div>
                    <?php endif; ?>

                    <!-- Enviamos los datos por POST al controlador Auth -->
                    <form method="POST" action="index.php?c=auth&a=iniciarSesion">

                        <!-- Campo: Correo electrónico (con ícono de usuario) -->
                        <div class="mb-3">
                            <label for="correo" class="form-label fw-semibold">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                                <input type="email" class="form-control" id="correo" name="correo"
                                       placeholder="usuario@consultorio.com" required>
                            </div>
                        </div>

                        <!-- Campo: Contraseña (con botón mostrar/ocultar) -->
                        <div class="mb-4">
                            <label for="clave" class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="clave" name="clave" required>
                                <button class="btn btn-outline-secondary" type="button" id="btnVerClave"
                                        title="Mostrar / ocultar clave">
                                    <i class="bi bi-eye" id="iconoClave"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Botón de ancho completo: Iniciar Sesión -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Bootstrap 5 JS (necesario para componentes interactivos) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script nativo (vanilla JS): muestra u oculta la contraseña.
    const btnVerClave = document.getElementById('btnVerClave');
    const campoClave  = document.getElementById('clave');
    const iconoClave  = document.getElementById('iconoClave');

    btnVerClave.addEventListener('click', function () {
        const esVisible = campoClave.type === 'text';
        // Alternamos entre 'text' y 'password' y cambiamos el ícono del ojo.
        campoClave.type = esVisible ? 'password' : 'text';
        iconoClave.className = esVisible ? 'bi bi-eye' : 'bi bi-eye-slash';
    });
</script>

</body>
</html>
