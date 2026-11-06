<?php
// =============================================================
// Clase PacienteController (TAREA-09 / HU-03 / RF-03 y RF-04)
// Recibe las peticiones ($_POST, $_GET), valida los datos,
// invoca al PacienteModel y carga las vistas correspondientes.
// =============================================================

require_once __DIR__ . '/../models/PacienteModel.php';

class PacienteController
{
    // Referencia al modelo con las consultas de la tabla paciente.
    private $modelo;

    public function __construct()
    {
        // Nos aseguramos de tener la sesión activa (las alertas
        // viajan por la sesión entre una página y otra).
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->modelo = new PacienteModel();
    }

    // Pantalla principal del módulo: muestra el listado de pacientes.
    public function index()
    {
        // Consultamos a todos los pacientes y los dejamos listos
        // para que la vista (TASK-10) los pinte en la tabla.
        $pacientes = $this->modelo->listar();

        require_once __DIR__ . '/../views/paciente/index.php';
    }

    // Endpoint de búsqueda asíncrona (HU-04 / RF-05 / CP-05).
    // Recibe ?termino=..., responde JSON puro y sin HTML.
    public function buscar()
    {
        // 1. Leemos y limpiamos el término enviado por JavaScript.
        $termino = trim($_GET['termino'] ?? '');

        // 2. Regla del criterio: solo filtramos con 3 o más caracteres.
        if (mb_strlen($termino) < 3) {
            $resultados = [];
        } else {
            $resultados = $this->modelo->buscar($termino);
        }

        // 3. Enviamos la cabecera JSON y codificamos los datos.
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['datos' => $resultados], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Guarda un paciente nuevo (recibe el formulario por POST).
    public function guardar()
    {
        // 1. Tomamos todos los datos enviados por el formulario.
        $datos = [
            'numero_documento'    => trim($_POST['numero_documento'] ?? ''),
            'nombres'             => trim($_POST['nombres'] ?? ''),
            'apellidos'           => trim($_POST['apellidos'] ?? ''),
            'telefono'            => trim($_POST['telefono'] ?? ''),
            'correo'              => trim($_POST['correo'] ?? ''),
            'fecha_nacimiento'    => $_POST['fecha_nacimiento'] ?? '',
            'antecedentes_medicos'=> trim($_POST['antecedentes_medicos'] ?? ''),
        ];

        // 2. Validamos que los campos obligatorios NO estén vacíos.
        $faltantes = $this->validarCamposObligatorios($datos);
        if ($faltantes !== []) {
            $this->alertar('peligro', 'Completar campos obligatorios: ' . implode(', ', $faltantes));
            header('Location: index.php?c=paciente&a=index');
            exit;
        }

        // 3. Validamos que el documento NO esté ya registrado (RF-04).
        if ($this->modelo->obtenerPorDocumento($datos['numero_documento'])) {
            $this->alertar('peligro', 'El documento ' . htmlspecialchars($datos['numero_documento']) . ' ya está registrado en el sistema.');
            header('Location: index.php?c=paciente&a=index');
            exit;
        }

        // 4. Todo válido: guardamos y avisamos el resultado.
        $guardado = $this->modelo->crear($datos);
        if ($guardado) {
            $this->alertar('exito', 'Paciente ' . htmlspecialchars($datos['nombres']) . ' registrado con éxito.');
        } else {
            $this->alertar('peligro', 'No fue posible registrar al paciente. Intente de nuevo.');
        }

        header('Location: index.php?c=paciente&a=index');
        exit;
    }

    // Actualiza teléfono, correo y antecedentes de un paciente (PUT/POST).
    public function actualizar()
    {
        // 1. Tomamos el id y los campos editables del formulario.
        $id = (int)($_POST['id'] ?? 0);
        $datos = [
            'telefono'            => trim($_POST['telefono'] ?? ''),
            'correo'              => trim($_POST['correo'] ?? ''),
            'antecedentes_medicos'=> trim($_POST['antecedentes_medicos'] ?? ''),
        ];

        // 2. Validamos que el id sea real y el teléfono no venga vacío.
        if ($id <= 0 || $datos['telefono'] === '') {
            $this->alertar('peligro', 'Datos incompletos para actualizar el expediente.');
            header('Location: index.php?c=paciente&a=index');
            exit;
        }

        // 3. Actualizamos y avisamos el resultado al usuario.
        $actualizo = $this->modelo->actualizar($id, $datos);
        $this->alertar(
            $actualizo ? 'exito' : 'peligro',
            $actualizo ? 'Expediente actualizado con éxito.' : 'No fue posible actualizar el expediente.'
        );

        header('Location: index.php?c=paciente&a=index');
        exit;
    }

    // Validación privada: devuelve el arreglo con los nombres de
    // los campos obligatorios que llegaron vacíos (criterio 2 HU-03).
    private function validarCamposObligatorios($datos)
    {
        $obligatorios = ['numero_documento', 'nombres', 'apellidos', 'telefono', 'antecedentes_medicos'];
        $faltantes = [];

        foreach ($obligatorios as $campo) {
            if ($datos[$campo] === '') {
                // Traducimos el nombre técnico a uno amable para el usuario.
                $nombres = [
                    'numero_documento'     => 'documento',
                    'nombres'              => 'nombres',
                    'apellidos'            => 'apellidos',
                    'telefono'             => 'teléfono',
                    'antecedentes_medicos' => 'antecedentes médicos',
                ];
                $faltantes[] = $nombres[$campo];
            }
        }
        return $faltantes;
    }

    // Guarda una alerta en la sesión para mostrarla en la vista destino.
    private function alertar($tipo, $mensaje)
    {
        $_SESSION['tipo_alerta']    = $tipo;
        $_SESSION['mensaje_alerta'] = $mensaje;
    }
}
