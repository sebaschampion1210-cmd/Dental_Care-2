<?php


require_once __DIR__ . '/../config/Conexion.php';

class CitaModel
{
    // Guarda el objeto PDO que usará toda la clase.
    private $conexion;

    // Al crear el modelo, obtenemos la conexión lista para usar.
    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    // Lista el personal con rol odontólogo para el <select> del form.
    public function listarOdontologos()
    {
        $sql = 'SELECT id, nombre
                FROM usuario
                WHERE rol = "odontologo" AND estado = "activo"
                ORDER BY nombre';

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    // Devuelve los turnos de UNA fecha (la agenda del día).
    // Une cita con paciente y usuario para mostrar nombres claros.
    public function listarDelDia($fecha)
    {
        $sql = 'SELECT c.id, c.hora_inicio, c.hora_fin, c.motivo, c.estado,
                       p.numero_documento, p.nombres, p.apellidos,
                       u.nombre AS odontologo
                FROM cita c
                JOIN paciente   p ON p.id = c.paciente_id
                JOIN usuario    u ON u.id = c.odontologo_id
                WHERE c.fecha_cita = :fecha
                ORDER BY c.hora_inicio';

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':fecha' => $fecha]);

        return $consulta->fetchAll();
    }

    public function cruceHorario($datos)
    {
        $sql = 'SELECT u.nombre AS odontologo, c.hora_inicio, c.hora_fin
                FROM cita c
                JOIN usuario u ON u.id = c.odontologo_id
                WHERE c.odontologo_id = :odontologo
                  AND c.fecha_cita = :fecha
                  AND c.estado NOT IN ("Cancelada", "Inasistencia")
                  AND :inicio < c.hora_fin
                  AND :fin > c.hora_inicio
                LIMIT 1';

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([
            ':odontologo' => $datos['odontologo_id'],
            ':fecha'      => $datos['fecha_cita'],
            ':inicio'     => $datos['hora_inicio'],
            ':fin'        => $datos['hora_fin'],
        ]);

        return $consulta->fetch();
    }

    // Guarda la cita nueva con estado inicial 'Pendiente' (RF-06).
    // Devuelve 'true' si MySQL la aceptó o 'false' si falló.
    public function crear($datos)
    {
        $sql = 'INSERT INTO cita
                    (paciente_id, odontologo_id, fecha_cita,
                     hora_inicio, hora_fin, motivo, estado)
                VALUES
                    (:paciente, :odontologo, :fecha,
                     :inicio, :fin, :motivo, "Pendiente")';

        try {
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([
                ':paciente'   => $datos['paciente_id'],
                ':odontologo' => $datos['odontologo_id'],
                ':fecha'      => $datos['fecha_cita'],
                ':inicio'     => $datos['hora_inicio'],
                ':fin'        => $datos['hora_fin'],
                ':motivo'     => $datos['motivo'],
            ]);
        } catch (PDOException $error) {
            return false;
        }

        // Si todo salió bien, la cita quedó guardada con éxito.
        return true;
    }

    // Consulta UNA cita por su id (sirve para revisar su estado actual).
    public function obtenerPorId($id)
    {
        $sql = 'SELECT id, fecha_cita, estado
                FROM cita
                WHERE id = :id
                LIMIT 1';

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([':id' => $id]);

        return $consulta->fetch();
    }

    // Cambia el estado del turno (RF-08 / TASK-17 / RN-03).
    // Regla RN-03: una cita 'Atendida' o 'Cancelada' queda CERRADA
    // y ya no se puede modificar. Se bloquea en la misma consulta
    // con 'estado NOT IN (...)' y también revisando el dato antes.
    public function actualizarEstado($id, $estado)
    {
        // 1. Revisión didáctica: ¿existe la cita y sigue abierta?
        $cita = $this->obtenerPorId($id);
        if ($cita === false
            || $cita['estado'] === 'Atendida'
            || $cita['estado'] === 'Cancelada') {
            return false;
        }

        // 2. Doble bloqueo a nivel SQL (si otra página la cerró
        //    entre medio, el UPDATE no la toca).
        $sql = 'UPDATE cita
                SET estado = :estado
                WHERE id = :id
                  AND estado NOT IN ("Atendida", "Cancelada")';

        try {
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([':id' => $id, ':estado' => $estado]);
        } catch (PDOException $error) {
            return false;
        }

        // 'rowCount' cuenta filas cambiadas: 1 = actualizada bien.
        return $consulta->rowCount() === 1;
    }
}
