<?php


class Conexion
{
    // Datos de conexión de XAMPP (sin contraseña por defecto).
    private const SERVIDOR    = 'localhost';
    private const BASE_DATOS  = 'dental_care';
    private const USUARIO_BD  = 'root';
    private const CLAVE_BD    = '';

    // Método estático: devuelve el PDO  listo para usarse en cualquier parte de nustr codijo si lo necesitamos.
    public static function conectar(): PDO
    {
        try {
            // DSN es la cadena que puedo usar para conectarme a mysql con charset utf8mb4 para soportar tildes y eñes.
            $dsn = 'mysql:host=' . self::SERVIDOR . ';dbname='
                 . self::BASE_DATOS . ';charset=utf8mb4';

            //Le ordena a PHP que si hay un error en una consulta SQL (por ejemplo, escribiste mal el nombre de una tabla), lance una excepción (un error detallado).
            $opciones = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,

                //Configura para que, por defecto, cuando consultes datos, te devuelva un arreglo asociativo (un array donde las llaves son los nombres de las columnas de tu base de datos, facilitando muchísimo mostrarlos en HTML o JSON
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            //: Si todo sale bien, crea la conexión usando las credenciales y las opciones, y la devuelve lista para usar en tus consultas (SELECT, INSERT, etc.).
            return new PDO($dsn, self::USUARIO_BD, self::CLAVE_BD, $opciones);
        } catch (PDOException $error) {
            // Si falla la conexión, mostramos un mensaje claro Y DIE ES PARA MAS QUE TODO CASOS DE ERROR O EXEPCIONES.
            die('Error de conexión: ' . $error->getMessage());
        }
    }
}

// RECORDEMOS QUE... 
// Es una extensión oficial de PHP que actúa como una capa intermedia (un puente) entre tu código de programación y la base de datos (como MySQL).