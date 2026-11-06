<?php
// =============================================================
// Clase UsuarioModel (TAREA-02 / HU-01 / RF-01)
// Modelo encargado de TODAS las consultas SQL sobre la tabla
// 'usuario'. Solo contiene lógica de datos, nunca HTML.
// =============================================================

require_once __DIR__ . '/../config/Conexion.php';

class UsuarioModel
{
    // Guarda el objeto PDO que usará toda la clase.
    private $conexion;

    // Al crear el modelo, obtenemos la conexión lista para usar.
    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    // Busca un usuario por su correo y devuelve sus datos en un
    // arreglo (incluye el 'password_hash'). Devuelve 'false' si
    // el correo no existe, para que el controlador decida qué hacer.
    public function autenticar($correo)
    {
        // Consulta preparada con el parámetro nombrado ':correo'
        // (evita la inyección SQL de forma segura).
        $sql = 'SELECT id, nombre, correo, password_hash, rol, estado
                FROM usuario
                WHERE correo = :correo
                LIMIT 1';

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':correo' => $correo]);

        // fetch() devuelve la fila encontrada o FALSE si no existe.
        return $consulta->fetch();
    }
}
