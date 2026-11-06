<?php

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
                //LIMIT 1: En cuanto encuentres el primer resultado que coincida con este correo, detén la búsqueda", ya que el correo es único.
                LIMIT 1';

                // prepare sirve para evitar la inyection sql y el usuario pueda entrar de forma segura
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':correo' => $correo]);

        // fetch() devuelve la fila encontrada o FALSE si no existe.
        return $consulta->fetch();
    }
}
