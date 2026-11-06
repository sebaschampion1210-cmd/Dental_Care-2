<?php

// Controlador encargado de gestionar el inicio de sesión y el
// cierre de sesión del personal del consultorio.

// ESTA PARTE SIRVE PARA QUE PHP PUEDA BUSCAR LA RUTA EN MODELS MAS RAPIDO Y SIN PERDERSE
require_once __DIR__ . '/../models/UsuarioModel.php';

class AuthController
{
    // Referencia al modelo de usuarios para consultas SQL.
    private $modelo;

    public function __construct()
    {
        //  SESSION_START Activa el sistema de sesiones de PHP para poder recordar quién inició sesión.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->modelo = new UsuarioModel();
    }

    // Muestra la vista del formulario de login .
    public function mostrarLogin()
    {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Recibe los datos del formulario (POST), valida las credenciales
    // e inicia la sesión si todo es correcto.
    public function iniciarSesion()
    {
        // 1. Tomamos los datos enviados por el formulario con POST.
        $correo = $_POST['correo'] ?? '';
        $clave  = $_POST['clave']  ?? '';

        // 2. Buscamos al usuario en la BD por su correo (modelo PDO).
        $usuario = $this->modelo->autenticar($correo);

        // 3. Validamos: debe existir, estar activo y su contraseña
        // debe coincidir con el hash guardado en la tabla.
        if ($usuario && $usuario['estado'] === 'activo'
            && password_verify($clave, $usuario['password_hash'])) {

            // 4. Credenciales válidas: guardamos sus datos en la sesión.
            // Primero regeneramos el ID de la sesión por seguridad
            // (evita el ataque llamado "fijación de sesión").
            // 
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre']     = $usuario['nombre'];
            $_SESSION['rol']        = $usuario['rol'];

            // 5. Redirigimos al panel principal (criterio 1 de HU-01).
            header('Location: index.php?c=dashboard&a=index');
            exit;
        }

        // 6. Credenciales no válidas: regresamos al login con la
        // alerta exigida en el criterio 2 de HU-01.
        $_SESSION['error_login'] = 'Credenciales no válidas';
        header('Location: index.php?c=auth&a=login');
        exit;
    }

    // Cierra la sesión por completo e impide volver atrás (HU-02).
    public function cerrarSesion()
    {
        // 1. Vaciamos todas las variables de sesión.
        $_SESSION = [];

        // 2. Pedimos al navegador que elimine la cookie de sesión.
        if (ini_get('session.use_cookies')) {
            $parametros = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $parametros['path'], $parametros['domain'],
                $parametros['secure'], $parametros['httponly']);
        }

        // 3. Destruimos la sesión por completo en el servidor.
        session_destroy();

        // 4. Devolvemos al usuario al formulario de login.
        header('Location: index.php?c=auth&a=login');
        exit;
    }
}
