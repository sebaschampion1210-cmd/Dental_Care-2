<?php
// =============================================================
// Clase PacienteModel (TAREA-08 / HU-03 / RF-03 y RF-04)
// Modelo encargado de TODAS las consultas SQL sobre la tabla
// 'paciente'. Solo lógica de datos, nunca pantallas (MVC).
// =============================================================

require_once __DIR__ . '/../config/Conexion.php';

class PacienteModel
{
    // Guarda el objeto PDO que usará toda la clase.
    private $conexion;

    // Al crear el modelo, obtenemos la conexión lista para usar.
    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    // Devuelve todos los pacientes ordenados alfabéticamente,
    // para pintar la tabla del directorio clínico.
    public function listar()
    {
        $sql = 'SELECT id, numero_documento, nombres, apellidos,
                       telefono, correo, fecha_nacimiento, antecedentes_medicos
                FROM paciente
                ORDER BY apellidos, nombres';

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        // Devuelve el arreglo completo de filas encontradas.
        return $consulta->fetchAll();
    }

    // Busca pacientes por parte del documento, nombres o apellidos.
    // El término viaja como parámetro nombrado (anti-inyección) y
    // el comodín '%...' se arma en PHP, NO en la string SQL.
    public function buscar($termino)
    {
        $patron = '%' . $termino . '%';

        $sql = 'SELECT id, numero_documento, nombres, apellidos,
                       telefono, correo, fecha_nacimiento, antecedentes_medicos
                FROM paciente
                WHERE numero_documento LIKE :patron
                   OR nombres    LIKE :patron
                   OR apellidos  LIKE :patron
                ORDER BY apellidos, nombres';

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':patron' => $patron]);

        return $consulta->fetchAll();
    }

    // Consulta si un número de documento ya está registrado.
    // Devuelve 'true' si existe (sirve para bloquear duplicados,
    // criterio RF-04) o 'false' si el documento está libre.
    public function obtenerPorDocumento($documento)
    {
        // Consulta preparada con parámetro nombrado (anti-inyección).
        $sql = 'SELECT id, nombres, apellidos, telefono, correo,
                       antecedentes_medicos
                FROM paciente
                WHERE numero_documento = :documento
                LIMIT 1';

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':documento' => $documento]);

        // Si fetch devuelve una fila, el documento YA existe.
        return $consulta->fetch();
    }

    // Inserta un paciente nuevo con sentencia preparada.
    // Devuelve el id generado o 'false' si no pudo guardar.
    public function crear($datos)
    {
        $sql = 'INSERT INTO paciente
                   (numero_documento, nombres, apellidos, telefono,
                    correo, fecha_nacimiento, antecedentes_medicos)
                VALUES
                   (:documento, :nombres, :apellidos, :telefono,
                    :correo, :fecha_nacimiento, :antecedentes)';

        $consulta = $this->conexion->prepare($sql);

        // Si MySQL rechaza el INSERT (por ejemplo, documento
        // duplicado con la clave UNIQUE), devolvemos 'false'
        // en lugar de romper la página con un fatal error.
        try {
            $resultado = $consulta->execute([
                ':documento'     => $datos['numero_documento'],
                ':nombres'       => $datos['nombres'],
                ':apellidos'     => $datos['apellidos'],
                ':telefono'      => $datos['telefono'],
                ':correo'        => $datos['correo'],
                ':fecha_nacimiento' => $datos['fecha_nacimiento'],
                ':antecedentes'  => $datos['antecedentes_medicos'],
            ]);
        } catch (PDOException $error) {
            return false;
        }

        // Si guardó bien, devolvemos el id autonumérico del nuevo paciente.
        return $resultado ? (int)$this->conexion->lastInsertId() : false;
    }

    // Actualiza SOLO los campos editables desde el listado:
    // teléfono, correo y antecedentes (criterio 3 de HU-03).
    public function actualizar($id, $datos)
    {
        $sql = 'UPDATE paciente
                SET telefono      = :telefono,
                    correo        = :correo,
                    antecedentes_medicos = :antecedentes
                WHERE id = :id';

        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([
            ':id'           => $id,
            ':telefono'     => $datos['telefono'],
            ':correo'       => $datos['correo'],
            ':antecedentes' => $datos['antecedentes_medicos'],
        ]);
    }
}
