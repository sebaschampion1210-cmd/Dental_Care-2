<?php
// =============================================================
// Clase Conexion (TAREA-02 / HU-01)
// Encargada ÚNICAMENTE de crear el objeto PDO que conecta a la
// base de datos 'dental_care' en el servidor local XAMPP.
// =============================================================

class Conexion
{
    // Datos de conexión de XAMPP (sin contraseña por defecto).
    private const SERVIDOR    = 'localhost';
    private const BASE_DATOS  = 'dental_care';
    private const USUARIO_BD  = 'root';
    private const CLAVE_BD    = '';

    // Método estático: devuelve el PDO listo para usarse.
    public static function conectar(): PDO
    {
        try {
            // DSN con charset utf8mb4 para soportar tildes y eñes.
            $dsn = 'mysql:host=' . self::SERVIDOR . ';dbname='
                 . self::BASE_DATOS . ';charset=utf8mb4';

            // Opciones: lanzar excepciones si hay error SQL
            // y devolver los resultados como arreglos asociativos.
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            return new PDO($dsn, self::USUARIO_BD, self::CLAVE_BD, $opciones);
        } catch (PDOException $error) {
            // Si falla la conexión, mostramos un mensaje claro.
            die('Error de conexión: ' . $error->getMessage());
        }
    }
}
