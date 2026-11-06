<?php


// Activamos la sesión PHP (necesaria para validar el login).
session_start();

// ------------------------------------------------------------------
// 1. Leemos el controlador y la acción solicitados por la URL.
//    Si no llegan, por defecto enviamos al login.
// ------------------------------------------------------------------
$controlador = $_GET['c'] ?? 'auth';
$accion      = $_GET['a'] ?? 'login';

// ------------------------------------------------------------------
// 2. PROTECCIÓN DE RUTAS (TAREA-06):
//    Si NO hay sesión activa y la petición NO es del login,
//    redirigimos inmediatamente al formulario de acceso.
// ------------------------------------------------------------------
if ($controlador !== 'auth' && !isset($_SESSION['usuario_id'])) {
    header('Location: index.php?c=auth&a=login');
    exit;
}

// ------------------------------------------------------------------
// 3. Si SÍ hay sesión activa y abren el login a mano, lo enviamos
//    al panel (no tiene sentido re-loguearse). El cierre de sesión
//    siempre está permitido.
// ------------------------------------------------------------------
if ($controlador === 'auth' && isset($_SESSION['usuario_id'])
    && $accion !== 'cerrarSesion') {
    header('Location: index.php?c=dashboard&a=index');
    exit;
}

// ------------------------------------------------------------------
// 4. Traducimos el nombre de la URL a la clase del controlador.
//    Ej: c=auth  ->  AuthController.php
// ------------------------------------------------------------------
$claseControlador = ucfirst(strtolower($controlador)) . 'Controller';
$archivo          = __DIR__ . '/app/controllers/' . $claseControlador . '.php';

// Si el módulo no existe todavía, mostramos un aviso claro.
if (!is_file($archivo)) {
    die('Módulo "' . htmlspecialchars($controlador) . '" aún no está disponible en el sistema.');
}
require_once $archivo;

// ------------------------------------------------------------------
// 5. Traducimos la acción de la URL al método de la clase.
//    Ej: a=login -> método mostrarLogin() para mayor claridad.
// ------------------------------------------------------------------
$metodos = [
    'login'         => 'mostrarLogin',
    'iniciarSesion' => 'iniciarSesion',
    'cerrarSesion'  => 'cerrarSesion',
];

// Si la acción enviada es válida, preparamos el método a llamar.
if (isset($metodos[$accion])) {
    $metodo = $metodos[$accion];
} elseif (method_exists($claseControlador, $accion)) { // método directo
    $metodo = $accion;
} else {
    die('Acción "' . htmlspecialchars($accion) . '" no reconocida.');
}

// ------------------------------------------------------------------
// 6. Ejecutamos: creamos el controlador y llamamos su método.
// ------------------------------------------------------------------
(new $claseControlador())->$metodo();
