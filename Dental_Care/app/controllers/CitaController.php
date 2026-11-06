<?php


require_once __DIR__ . '/../models/CitaModel.php';
require_once __DIR__ . '/../models/PacienteModel.php';

class CitaController
{
    // Referencias a los modelos de la agenda y del directorio.
    private $modelo;
    private $pacienteModelo;

    public function __construct()
    {
        // Garantiza la sesión para mover alertas entre páginas.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        //Para manejar todo lo relacionado con las citas y los cruces de horarios.
        $this->modelo         = new CitaModel();
        // Para poder traer el listado de pacientes y mostrarlos en los menús desplegables (<select>) del formulario
        $this->pacienteModelo = new PacienteModel();
    }

    // Pantalla principal: formulario de reserva + agenda del día.
    public function index()
    {
        // Dato del <select> de odontólogos y de pacientes.
        $odontologos = $this->modelo->listarOdontologos();
        $pacientes   = $this->pacienteModelo->listar();

        // Fecha de la agenda a revisar (por defecto, HOY en PHP).
        $fechaAgenda = $_GET['fecha'] ?? date('Y-m-d');

        // Turnos ya ocupados en esa fecha.
        $citas = $this->modelo->listarDelDia($fechaAgenda);

        require_once __DIR__ . '/../views/cita/index.php';
    }

    // Guarda la cita del formulario comprobando que el horario esté libre.
    public function guardar()
    {
        // 1. Tomamos los datos enviados por el formulario de reserva.
        $datos = [
            'paciente_id'   => (int)($_POST['paciente_id']   ?? 0),
            'odontologo_id' => (int)($_POST['odontologo_id'] ?? 0),
            'fecha_cita'    => trim($_POST['fecha_cita']    ?? ''),
            'hora_inicio'   => trim($_POST['hora_inicio']   ?? ''),
            'hora_fin'      => trim($_POST['hora_fin']      ?? ''),
            'motivo'        => trim($_POST['motivo']        ?? ''),
        ];

        // 2. Reglas básicas: campos llenos y franja coherente.
        if (!$this->datosValidos($datos)) {
            header('Location: index.php?c=cita&a=index');
            exit;
        }

        // 3. RN-01: si hay cruce de horario, avisamos con el detalle.
        $citaQueEstorba = $this->modelo->cruceHorario($datos);
        if ($citaQueEstorba !== null && $citaQueEstorba !== false) {
            $inicio = substr($citaQueEstorba['hora_inicio'], 0, 5);
            $fin    = substr($citaQueEstorba['hora_fin'], 0, 5);
            $this->alertar('peligro', 'Error: El doctor '
                . htmlspecialchars($citaQueEstorba['odontologo'])
                . ' ya tiene una cita programada entre las ' . $inicio
                . ' y las ' . $fin . '. No se guardó el turno.');
            header('Location: index.php?c=cita&a=index&fecha=' . $datos['fecha_cita']);
            exit;
        }

        // 4. Horario libre: guardamos y avisamos el resultado.
        $guardada = $this->modelo->crear($datos);
        $this->alertar(
            $guardada ? 'exito' : 'peligro',
            $guardada
                ? 'Cita agendada con éxito para el ' . $datos['fecha_cita'] . ' a las ' . $datos['hora_inicio'] . '.'
                : 'No fue posible guardar la cita. Intente de nuevo.'
        );

        header('Location: index.php?c=cita&a=index&fecha=' . $datos['fecha_cita']);
        exit;
    }

    // Cambia el estado de una cita con los botones rápidos de la agenda
    // (HU-06 / TASK-18 / RF-08). El modelo aplica el bloqueo RN-03.
    public function cambiarEstado()
    {
        // 1. Leemos el turno y el nuevo estado elegido en el botón.
        $id     = (int)($_GET['id'] ?? 0);
        $estado = trim($_GET['estado'] ?? '');
        $fecha  = trim($_GET['fecha'] ?? date('Y-m-d'));

        // 2. El estado enviado debe ser uno de los 5 válidos del ENUM.
        $estadosValidos = ['Pendiente', 'Confirmada', 'Atendida', 'Cancelada', 'Inasistencia'];
        if ($estado === '' || !in_array($estado, $estadosValidos, true)) {
            $this->alertar('peligro', 'Estado no reconocido por el sistema.');
        } else {
            // 3. Delegamos al modelo (RN-03 protege las citas cerradas).
            $actualizo = $this->modelo->actualizarEstado($id, $estado);

            // 4. Avisamos el resultado: éxito o el bloqueo de la regla.
            if ($actualizo) {
                $this->alertar('exito', 'El turno #(' . $id . ') pasó a estado (' . $estado . ')');
            } else {
                $this->alertar('peligro', 'El turno #(' . $id . ') está en estado Atendida o Cancelada y YA NO puede modificarse.');
            }
        }

        // 5. Volvemos a la misma agenda recién actualizada.
        header('Location: index.php?c=cita&a=index&fecha=' . $fecha);
        exit;
    }

    // Valida obligatorios, que las horas no vengan invertidas y
    // que la fecha sea real (devuelve true y alerta si algo falla).
    private function datosValidos($datos)
    {
        $vacios = $datos['paciente_id'] <= 0 || $datos['odontologo_id'] <= 0
               || $datos['fecha_cita'] === '' || $datos['hora_inicio'] === ''
               || $datos['hora_fin'] === ''   || $datos['motivo'] === '';

        if ($vacios) {
            $this->alertar('peligro', 'Datos incompletos: revisa los campos del formulario de reserva.');
            return false;
        }

        // La hora de inicio debe ser ANTES que la de cierre del turno.
        if ($datos['hora_inicio'] >= $datos['hora_fin']) {
            $this->alertar('peligro', 'La hora de inicio debe ser anterior a la hora de fin.');
            return false;
        }

        return true;
    }

    // Guarda una alerta en la sesión para mostrarla en la vista destino.
    private function alertar($tipo, $mensaje)
    {
        $_SESSION['tipo_alerta']    = $tipo;
        $_SESSION['mensaje_alerta'] = $mensaje;
    }
}
