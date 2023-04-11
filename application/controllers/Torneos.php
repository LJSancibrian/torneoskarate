<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Torneos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->in_group([1, 2, 3, 4, 5, 6])) {
            $this->session->set_flashdata('info', 'Inicie sesión');
            redirect('login', 'refresh');
        }

        $this->base = 'gestion/template';
    }

    public function index()
    {
        asistentePage();
        validUrl();
        $data['page_header']    = 'Torneos';
        $data['table_title'] = 'Lista de torneos registrados en la plataforma';
        if ($this->user->group->id < 4) {
            $data['table_button']   = [
                'name' => 'crear_torneo',
                'content' => '<i class="fa fa-plus mr-3"></i> Nuevo torneo',
                'extra' => [
                    'data-tooltip' => TRUE,
                    'data-editar-torneo' => 'nuevo',
                    'title' => 'Crear nuevo torneo',
                    'data-original-title' => 'Crear nuevo torneo',
                    'class' => 'btn btn-sm btn-primary ml-auto'
                ]
            ];
            $data['view']           = ['gestion/common/tabla_datatable', 'gestion/torneos/torneos_form_modal'];
            $data['js_files']       = [assets_url() . 'admin/js/vistas/torneos.js'];
        } else {
            $data['view']           = ['gestion/common/tabla_datatable'];
            $data['js_files']       = [assets_url() . 'admin/js/vistas/torneos_aux.js'];
        }
        show($data);
    }

    /***
     * DATATABLES GET
     */
    // datatable torneos
    public function getTorneos($columna = null, $valor = null)
    {
        logged();
        $this->load->library('Datatable');
        $tabla  = 'torneos';
        $campos = [
            $tabla . '.slug',
            $tabla . '.titulo',
            $tabla . '.tipo',
            $tabla . '.fecha',
            $tabla . '.limite',
            $tabla . '.estado',
            $tabla . '.*'
        ];
        $join     = [];
        $add_rule = [
            "group_by" => $tabla . ".torneo_id",
        ];
        $where = [];
        if ($this->input->get('estado') != '') {
            $where['estado'] = $this->input->get('estado');
        }
        if ($this->input->get('proximos') == 1) {
            $where['fecha >='] = date('Y-m-d');
        }
        if ($this->input->get('proximos') == 2) {
            $where['fecha <'] = date('Y-m-d');
        }
        if ($columna != "" && $valor != "") {
            $where  = [$tabla . '.' . $columna => $valor];
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        } else {
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        }
        $result->query = $this->db->last_query();
        $res = json_encode($result);
        echo $res;
    }
    // procesamiento del formulario de nuevo usuario: crea tambien un equipo para ese usuario
    public function nuevo_torneo_form()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('titulo', 'Título', 'trim|required');
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|required');
        $this->form_validation->set_rules('organizador', 'Organizador', 'trim|required');
        $this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('direccion', 'Direccion', 'required');
        $this->form_validation->set_rules('tipo', 'Modalidad', 'required');
        $this->form_validation->set_rules('fecha', 'Fecha del torneo', 'required');
        $this->form_validation->set_rules('limite', 'Fecha limite de inscripcion', 'required');
        validForm();
        $data = [
            'titulo' => $this->input->post('titulo'),
            'descripcion'  => $this->input->post('descripcion'),
            'organizador'        => $this->input->post('organizador'),
            'telefono'      => $this->input->post('telefono'),
            'email'      => $this->input->post('email'),
            'slug'   => $this->utilidades->rand_unique('torneos', 'slug', 10, TRUE),
            'direccion' => $this->input->post('direccion'),
            'tipo'  => $this->input->post('tipo'),
            'fecha'        => $this->input->post('fecha'),
            'limite'      => $this->input->post('limite'),
            'estado' => ($this->input->post('estado') == 'OK') ? 1 : 0,
        ];
        $torneo_id = $this->database->insert('torneos', $data);
        if ($torneo_id) {
            $redirect = base_url() . 'torneos/gestion/' . $data['slug'];
            $response = [
                'error'    => 0,
                'msn'      => 'Nuevo torneo creado correctamente',
                'redirect' => $redirect,
                'csrf'     => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => $this->ion_auth->errors(),
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
    }

    // obtiene los datos del equipo
    public function ver_torneo_fetch()
    {
        logged();
        isAjax();
        $this->form_validation->set_rules('torneo_id', 'Torneo', 'trim|required');
        validForm();
        $torneo_id = $this->input->post('torneo_id');

        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $torneo_id);

        if (!$torneo) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Torneo no encontrado.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $params = [
            'tabla' => 'documentos',
            'where' => [
                'item_rel' => 'torneo',
                'item_id' => $torneo_id,
                'estado' => 'disponible'
            ]
        ];
        $torneo_archivos  = $this->database->getWhere($params);

        $torneo->archivos = $torneo_archivos;
        $response = [
            'error'    => 0,
            'data'      => ['torneo' => $torneo],
            'csrf'     => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

    // procesamiento del formulario de los datos del equipo
    public function editar_torneo_form()
    {
        adminPage();
        isAjax();
        if ($this->input->post('torneo_id') == '') {
            $response = [
                'error'     => 1,
                'error_msn' => 'Identificador de torneo no válido.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $this->input->post('torneo_id'));
        if (!$torneo) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El equipo no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $this->form_validation->set_rules('titulo', 'Título', 'trim|required');
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|required');
        $this->form_validation->set_rules('organizador', 'Organizador', 'trim|required');
        $this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('direccion', 'Direccion', 'required');
        $this->form_validation->set_rules('tipo', 'Modalidad', 'required');
        $this->form_validation->set_rules('fecha', 'Fecha del torneo', 'required');
        $this->form_validation->set_rules('limite', 'Fecha limite de inscripcion', 'required');
        validForm();
        if ($torneo->titulo != $this->input->post('titulo')) {
            $datos['titulo'] = $this->input->post('titulo');
        }
        if ($torneo->descripcion != $this->input->post('descripcion')) {
            $datos['descripcion'] = $this->input->post('descripcion');
        }
        if ($torneo->organizador != $this->input->post('organizador')) {
            $datos['organizador'] = $this->input->post('organizador');
        }
        if ($torneo->telefono != $this->input->post('telefono')) {
            $datos['telefono'] = $this->input->post('telefono');
        }
        if ($torneo->email != $this->input->post('email')) {
            $datos['email'] = $this->input->post('email');
        }
        if ($torneo->direccion != $this->input->post('direccion')) {
            $datos['direccion'] = $this->input->post('direccion');
        }
        if ($torneo->tipo != $this->input->post('tipo')) {
            $datos['tipo'] = $this->input->post('tipo');
        }
        if ($torneo->fecha != $this->input->post('fecha')) {
            $datos['fecha'] = $this->input->post('fecha');
        }
        if ($torneo->limite != $this->input->post('limite')) {
            $datos['limite'] = $this->input->post('limite');
        }
        if ($this->input->post('estado') == 'OK') {
            if ($torneo->estado == 0) {
                $datos['estado'] = 1;
            }
        } else {
            if ($torneo->estado == 1) {
                $datos['estado'] = 0;
            }
        }
        if (empty($datos)) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Los datos indicados no cambian los datos del torneo.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $cambiar = $this->database->actualizar('torneos', $datos, 'torneo_id', $this->input->post('torneo_id'));
            if ($cambiar != $this->input->post('torneo_id')) {
                $response = [
                    'error'     => 1,
                    'error_msn' => 'Ha ocurrido un error y no se han realizado los cambios.',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            } else {
                $response = [
                    'error' => 0,
                    'msn'   => 'Actualización de datos correcta',
                    'csrf'  => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            }
        }
    }

    // FICHA Y GESTION DE TORNEOS
    public function gestion($slug)
    {
        asistenteCoachPage();
        validUrl();
        $data = [];
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }

        $data['torneo'] = $torneo;
        $data['view'] = ['gestion/torneos/base'];
        $data['page_header']    =   $torneo->titulo;
        $data['tabactive'] = 'info-tab';
        $data['clubs'] = $this->database->torneoClubs($torneo->torneo_id);
        $data['entrenadores'] = $this->database->torneoEntrenadores($torneo->torneo_id);
        $data['inscripciones'] = $this->database->torneoInscripciones($torneo->torneo_id);
        $data['archivos'] = $this->database->torneoArchivos($torneo->torneo_id);
        show($data);
    }

    public function datos($slug)
    {
        adminPage();
        $data = [];
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['view'] = ['gestion/torneos/base'];
        $data['page_header']    =   $torneo->titulo . ': Datos del torneo';
        $data['tabactive'] = 'datos-tab';
        $data['js_files'][] = assets_url() . 'admin/js/vistas/torneo_datos.js';
        show($data);
    }

    public function archivos($slug)
    {
        adminPage();
        $data = [];
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['view'] = ['gestion/torneos/base'];
        $data['page_header']    =   $torneo->titulo . ': Archivos y documentos';
        $data['tabactive'] = 'archivos-tab';
        $data['js_files'][] = assets_url() . 'admin/js/vistas/torneo_archivos.js';
        show($data);
    }

    public function inscripciones($slug)
    {
        //asistentePage();
        $data = [];
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['page_header']    =   $torneo->titulo . ': Inscripciones';
        if ($this->user->group->id < 4) {
            $data['tabactive'] = 'inscripciones-tab';
            $data['clubs'] = $this->database->torneoClubs($torneo->torneo_id);
            $data['deportistas'] = $this->database->getDeportistas();
            //printr( $data['deportistas']);
            $data['competicioneskata'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KATA');
            // printr($data['competicioneskata']);
            $data['competicioneskumite'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KUMITE');
            if ($torneo->tipo != 2) {
                $data['m_kata'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KATA');
            }
            if ($torneo->tipo != 1) {
                $data['m_kumite'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KUMITE');
            }
            $data['view'] = ['gestion/torneos/base'];
            $data['css_files'] = [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ];
            $data['js_files'] = [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                assets_url() . 'admin/js/vistas/torneo_inscripciones.js'
            ];
        } else {
            $club = $this->database->userClub();
            $data['competicioneskata'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KATA');
            $data['competicioneskumite'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KUMITE');
            $data['club'] = $club;
            $data['deportistas'] = $this->database->clubUsers($club->club_id, [6]);
            $data['entrenadores'] = $this->database->clubUsers($club->club_id, [5]);
            $data['inscripciones'] = $this->database->torneoIncripcionesClubDeportistas($club->club_id, $torneo->torneo_id);
            $data['tabactive'] = 'inscripcion_deportistas-tab';
            $data['view']           = ['gestion/torneos/base'];
            $data['js_files']       = [assets_url() . 'admin/js/vistas/torneos_inscripcion.js'];
        }
        show($data);
    }

    public function categorias($slug)
    {
        // adminPage();
        asistenteCoachPage();
        $data = [];
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['view'] = ['gestion/torneos/base'];
        $data['page_header']    =   $torneo->titulo . ': Categorias';
        if ($torneo->tipo != 2) {
            $data['competicioneskata'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KATA');
        }
        if ($torneo->tipo != 1) {
            $data['competicioneskumite'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KUMITE');
        }
        $data['tabactive'] = 'categorias-tab';
        $data['js_files'][] = assets_url() . 'admin/js/vistas/torneo_categorias.js';
        show($data);
    }

    public function competiciones($slug)
    {
        asistentePage();
        $data = [];
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['page_header']    =   $torneo->titulo . ': Competición';
        $data['categorias'] = $this->database->get('torneos_categorias');
        if ($torneo->tipo != 2) {
            $data['c_kata'] = $this->database->torneoCategoriasInscripciones($torneo->torneo_id, 'KATA');
        }
        if ($torneo->tipo != 1) {
            $data['c_kumite'] = $this->database->torneoCategoriasInscripciones($torneo->torneo_id, 'KUMITE');
        }
        $data['tabactive'] = 'competiciones-tab';
        if ($this->user->group->id < 4) {
            $data['view'] = ['gestion/torneos/base'];
            $data['js_files'][] = assets_url() . 'admin/js/vistas/torneo_competiciones.js';
        } else {
            $data['view'] = ['gestion/torneos/base_aux'];
            $data['js_files'][] = assets_url() . 'admin/js/vistas/torneo_competiciones_aux.js';
        }

        show($data);
    }

    public function clasificaciones($slug)
    {
        asistentePage();
        $data = [];
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['torneo'] = $torneo;
        if ($torneo->tipo != 2) {
            $data['competicioneskata'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KATA');
            foreach ($data['competicioneskata'] as $k => $competicion) {
                $clasifica = $this->database->clasificacionFinalKata($competicion->competicion_torneo_id, [1, 2, 3]);
                $competicion->clasificacion = $clasifica;
            }
        }
        if ($torneo->tipo != 1) {
            $data['competicioneskumite'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KUMITE');
            foreach ($data['competicioneskumite'] as $k => $competicion) {
                $clasifica = $this->database->clasificacionGlobalKumite($competicion->competicion_torneo_id);
                $competicion->clasificacion = $clasifica;
            }
        }
        $data['page_header']    =   $torneo->titulo . ': Clasificaciones';
        $data['tabactive'] = 'clasificaciones-tab';
        if ($this->user->group->id < 4) {
            $data['view'] = ['gestion/torneos/base'];
            $data['js_files'][] = assets_url() . 'admin/js/vistas/torneo_categorias.js';
        } else {
            $data['view'] = ['gestion/torneos/base_aux'];
            $data['js_files'][] = assets_url() . 'admin/js/vistas/torneo_categorias_aux.js';
        }
        show($data);
    }

    public function ver($slug)
    {
        validUrl();
        $data = [];
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['page_header']    =  $torneo->titulo;
        $data['torneo'] = $torneo;
        if ($torneo->tipo != 2) {
            $data['competicioneskata'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KATA');
            foreach ($data['competicioneskata'] as $key => $competicionkata) {
                $data['competicioneskata'][$key]->clasificacionfinal = $this->database->clasificacionFinalKata($competicionkata->competicion_torneo_id, [1, 2, 3]);
            }
        }
        if ($torneo->tipo != 1) {
            $data['competicioneskumite'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KUMITE');
            foreach ($data['competicioneskumite'] as $key => $competicionkata) {
                $clasificacion = $this->database->clasificacionKumite($competicionkata->competicion_torneo_id);
                //var_dump($clasificacion);
                if (!empty($clasificacion) && count($clasificacion) > 0) {
                    $data['competicioneskumite'][$key]->clasificacionfinal = $clasificacion;
                } else {
                    //$data['competicioneskumite'][$key]->clasificacionfinal = [];
                }
            }
        }
        // clubes participantes
        $data['clubs'] = $this->database->torneoClubs($torneo->torneo_id);
        $data['view']           = 'public/vistatorneo';
        show($data);
    }














    public function competicionesTorneo($slug)
    {
        adminPage();
        //validUrl();
        $data = [];
        // carga la ficha completa del torneo, con fechas de los torneos
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['view'] = ['gestion/competiciones/base'];
        $data['page_header']    =   $torneo->titulo . ': Competición';
        if ($torneo->tipo != 2) {
            $data['c_kata'] = $this->database->torneoCategoriasInscripciones($torneo->torneo_id, 'KATA');
        }
        if ($torneo->tipo != 1) {
            $data['c_kumite'] = $this->database->torneoCategoriasInscripciones($torneo->torneo_id, 'KUMITE');
        }

        $data['js_files']       = [
            assets_url() . 'admin/js/vistas/competicionestorneos.js',
        ];
        show($data);
    }

    public function get_competiciones_fetch()
    {
        logged();
        isAjax();
        $this->form_validation->set_rules('torneo_id', 'Torneo', 'trim|required');
        $this->form_validation->set_rules('modalidad', 'Modalidad', 'trim|required');
        $this->form_validation->set_rules('genero', 'Genero', 'trim|required');
        validForm();
        $torneo_id = $this->input->post('torneo_id');
        $modalidad = $this->input->post('modalidad');
        $genero = ($this->input->post('genero') == 'M') ? 1 : 2;
        $competiciones = $this->database->getCompeticionesTorneo($torneo_id, $modalidad, $genero);
        $response = [
            'error'     => 0,
            'competiciones' => $competiciones,
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        echo json_encode($response);
        exit();
    }

    public function add_competiciones()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('torneo_id', 'Torneo', 'trim|required');
        $this->form_validation->set_rules('competiciones', 'Descripcion', 'trim|required');
        validForm();
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $this->input->post('torneo_id'));
        if (!$torneo || $torneo == false) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El equipo no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $categorias_nuevas = json_decode($this->input->post('competiciones'));
        foreach ($categorias_nuevas as $key => $value) {
            $this->database->insert('torneos_competiciones', $value);
        }
        $response = [
            'error'     => 0,
            'msn' => 'Datos añadidas',
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        echo json_encode($response);
        exit();
    }

    public function del_competiciones()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        validForm();
        $deleted = $this->database->actualizar('torneos_competiciones', ['deletedAt' => date('Y-m-d H:i:s')], 'competicion_torneo_id', $this->input->post('competicion_torneo_id'));
        if ($deleted != $this->input->post('competicion_torneo_id')) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Acción no realizada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 0,
                'msn' => 'Datos eliminados',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }

    /**** */
    public function add_categoria()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('torneo_id', 'Torneo', 'trim|required');
        $this->form_validation->set_rules('categoria', 'Categoría', 'trim|required');
        $this->form_validation->set_rules('genero', 'Género', 'trim|required');
        $this->form_validation->set_rules('nivel', 'Peso / Nivel', 'trim|required');
        $this->form_validation->set_rules('modalidad', 'Modalidad', 'trim|required');
        validForm();
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $this->input->post('torneo_id'));
        if (!$torneo || $torneo == false) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El equipo no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        $data = [
            'torneo_id' => input('torneo_id'),
            'modalidad' => input('modalidad'),
            'categoria' => input('categoria'),
            'genero' => input('genero'),
            'nivel' => input('nivel'),
        ];

        $competicion_torneo_id = $this->database->insert('torneos_competiciones', $data);

        if ($competicion_torneo_id != FALSE) {
            // formar el tr
            $mod = strtolower($data['modalidad']);
            $tr = '<tr data-competicion_torneo_id="' . $competicion_torneo_id . '" data-modalidad="' . $data['modalidad'] . '">
            <td><input type="text" name="categoria_' . $mod . '" class="form-control" placeholder="Texto. Ej: Alevín" value="' . $data['categoria'] . '" disabled></td>
            <td>
                <select name="genero_' . $mod . '" class="form-control" disabled>';
            $selected = ($data['genero'] == 'M') ? "selected" : "";
            $tr .= '<option value="M" ' . $selected . '>Masculino</option>';
            $selected = ($data['genero'] == 'F') ? "selected" : "";
            $tr .= '<option value="F" ' . $selected . '>Femenino</option>';
            $selected = ($data['genero'] == 'X') ? "selected" : "";
            $tr .= '<option value="X" ' . $selected . '>Mixto</option>';
            $tr .= '</td>
             <td><input type="text" name="nivel_' . $mod . '" class="form-control" placeholder="Texto. Ej: < 40 Kg, ej: inicial" value="' . $data['nivel'] . '" disabled></td>
             <td class="text-truncate">
             <button type="button" class="btn btn-sm btn-icon btn-round btn-warning" data-edit="row' . $mod . '" data-accion="editar"><i class="fas fa-edit"></i>
             <button type="button" class="btn btn-sm btn-icon btn-round btn-danger" data-del="row' . $mod . '"><i class="fas fa-trash"></i>
             </td>
            </tr>';
            $response = [
                'error'     => 0,
                'html' => $tr,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'No se ha podido registrar la categoría.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }

    public function edit_categoria()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición ID', 'trim|required');
        $this->form_validation->set_rules('torneo_id', 'Torneo', 'trim|required');
        $this->form_validation->set_rules('categoria', 'Categoría', 'trim|required');
        $this->form_validation->set_rules('genero', 'Género', 'trim|required');
        $this->form_validation->set_rules('nivel', 'Peso / Nivel', 'trim|required');
        $this->form_validation->set_rules('modalidad', 'Modalidad', 'trim|required');
        validForm();
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $this->input->post('torneo_id'));
        if (!$torneo || $torneo == false) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El equipo no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $competicion_torneo = $this->database->buscarDato('torneos_competiciones', 'competicion_torneo_id', $this->input->post('competicion_torneo_id'));
        if (!$competicion_torneo || $competicion_torneo == false) {
            $response = [
                'error'     => 1,
                'error_msn' => 'La competición no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        $data = [
            'torneo_id' => input('torneo_id'),
            'modalidad' => input('modalidad'),
            'categoria' => input('categoria'),
            'genero' => input('genero'),
            'nivel' => input('nivel'),
            'updatedAt' => date('Y-m-d H:i:s')
        ];

        $actualizar = $this->database->actualizar('torneos_competiciones', $data, 'competicion_torneo_id', input('competicion_torneo_id'));

        if ($actualizar == input('competicion_torneo_id')) {
            $response = [
                'error'     => 0,
                'msn' =>  'correcto',
                'csrf'   => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'No se ha podido actualizar la competición.',
                'csrf'      => $this->security->get_csrf_hash(),

            ];
            returnAjax($response);
        }
    }

    public function delete_categoria()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición ID', 'trim|required');
        $this->form_validation->set_rules('torneo_id', 'Torneo', 'trim|required');
        validForm();
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $this->input->post('torneo_id'));
        if (!$torneo || $torneo == false) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El equipo no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $competicion_torneo = $this->database->buscarDato('torneos_competiciones', 'competicion_torneo_id', $this->input->post('competicion_torneo_id'));
        if (!$competicion_torneo || $competicion_torneo == false) {
            $response = [
                'error'     => 1,
                'error_msn' => 'La competición no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        $data = [
            'deletedAt' => date('Y-m-d H:i:s')
        ];

        $actualizar = $this->database->actualizar('torneos_competiciones', $data, 'competicion_torneo_id', input('competicion_torneo_id'));

        if ($actualizar == input('competicion_torneo_id')) {
            $response = [
                'error'     => 0,
                'msn' =>  'correcto',
                'csrf'   => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'No se ha podido eliminar la competición.',
                'csrf'      => $this->security->get_csrf_hash(),

            ];
            returnAjax($response);
        }
    }


    /**** */
    public function getInscripciones($columna = null, $valor = null)
    {
        logged();
        $this->load->library('Datatable');
        $tabla  = 'torneos_inscripciones';
        $campos = [
            'users.usercode',
            'users.first_name',
            'users.last_name',
            'torneos_competiciones.genero',
            'torneos_competiciones.modalidad',
            'CONCAT(torneos_competiciones.categoria, " ", torneos_competiciones.nivel) AS categoria_text',
            'clubs.nombre',
            $tabla . '.estado',
            $tabla . '.*'
        ];
        $join     = [
            'users' => 'users.id = torneos_inscripciones.user_id',
            'clubs' => 'clubs.club_id = users.club_id',
            'torneos_competiciones' => 'torneos_competiciones.competicion_torneo_id = torneos_inscripciones.competicion_torneo_id'
        ];
        $add_rule = [
            "group_by" => $tabla . ".inscripcion_id",
        ];
        //printr($this->input->get());
        $where = [];
        if ($this->input->get('torneo_id') != '') {
            $where['torneos_competiciones.torneo_id'] = $this->input->get('torneo_id');
        }
        if ($this->input->get('estado') != '') {
            $where['torneos_inscripciones.estado'] = $this->input->get('estado');
        }
        if ($this->input->get('equipo') != '') {
            $where['clubs.club_id'] = $this->input->get('equipo');
        }
        if ($this->input->get('modalidad') != '') {
            $where['torneos_competiciones.modalidad'] = $this->input->get('modalidad');
        }
        if ($this->input->get('t_categoria_id') != '') {
            $where['torneos_competiciones.competicion_torneo_id'] = $this->input->get('t_categoria_id');
        }
        $where['torneos_inscripciones.deletedAt'] = '0000-00-00 00:00:00';
        if ($columna != "" && $valor != "") {
            $where  = [$tabla . '.' . $columna => $valor];
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        } else {
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        }
        $res = json_encode($result);
        echo $res;
    }

    public function getCompeticiones($columna = null, $valor = null)
    {
        logged();
        $this->load->library('Datatable');
        $tabla  = 'torneos_competiciones';
        $campos = [
            'torneos_competiciones.modalidad',
            'torneos_competiciones.genero',
            'torneos_competiciones.categoria',
            'torneos_competiciones.nivel',
            $tabla . '.estado',
            '(SELECT COUNT(*) FROM `torneos_inscripciones` WHERE competicion_torneo_id = torneos_competiciones.competicion_torneo_id) AS n_inscripciones',
            $tabla . '.*'
        ];
        $join     = [
            //'torneos_categorias' => 'torneos_categorias.t_categoria_id = torneos_competiciones.t_categoria_id'
        ];
        $add_rule = [
            "group_by" => $tabla . ".competicion_torneo_id",
            "order_by" => [
                ['torneos_competiciones.modalidad', 'desc'],
                ['torneos_competiciones.genero', 'asc'],
                //['torneos_categorias.year', 'asc'],
                ['torneos_competiciones.nivel', 'asc']
            ]
        ];
        $where = [];
        if ($this->input->get('torneo_id') != '') {
            $where['torneos_competiciones.torneo_id'] = $this->input->get('torneo_id');
        }
        if ($this->input->get('estado') != '') {
            $where['torneos_competiciones.estado'] = $this->input->get('estado');
        }
        if ($this->input->get('modalidad') != '') {
            $where['torneos_competiciones.modalidad'] = $this->input->get('modalidad');
        }
        if ($this->input->get('t_categoria_id') != '') {
            $where['torneos_competiciones.t_categoria_id'] = $this->input->get('t_categoria_id');
        }
        $where['torneos_competiciones.deletedAt'] = '0000-00-00 00:00:00';
        if ($columna != "" && $valor != "") {
            $where  = [$tabla . '.' . $columna => $valor];
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        } else {
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        }
        $res = json_encode($result);
        echo $res;
    }

    public function proximos()
    {
        logged();
        validUrl();
        $data['page_header']    = 'Próximos Torneos';
        $data['table_title'] = 'Próximos torneos';
        $data['default_filter'] = '2';
        $data['view']           = ['gestion/common/tabla_datatable'];
        $data['js_files']       = [assets_url() . 'admin/js/vistas/torneos_proximos.js'];
        show($data);
    }

    public function getDeportistas($columna = null, $valor = null)
    {
        $this->load->library('Datatable');
        /**
         * PARA ver que categoria corrsponde segun edad
         * '(SELECT categoria FROM torneos_categorias WHERE (year * -1) > (SUM(YEAR(CURDATE()) - DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(users.dob)), "%Y"))) AND `modalidad` = "KATA" ORDER BY year desc LIMIT 1) AS categoria_kata',
         *   '(SELECT categoria FROM torneos_categorias WHERE (year * -1) > (SUM(YEAR(CURDATE()) - DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(users.dob)), "%Y"))) AND `modalidad` = "KUMITE" ORDER BY year desc LIMIT 1) AS categoria_kumite',
         */
        $tabla  = 'users';
        $campos = [
            $tabla . '.first_name',
            $tabla . '.last_name',
            'YEAR(' . $tabla . '.dob) AS yob',
            $tabla . '.genero',
            $tabla . '.peso',
            json_encode('(SELECT * FROM torneos_categorias WHERE `modalidad` = "KATA") AS categorias_kata'),
            '(SELECT * FROM torneos_categorias WHERE `modalidad` = "KUMITE") AS categoria_kumite',
            $tabla . '.*',
        ];
        $join     = [
            'users_groups' => $tabla . '.id = users_groups.user_id',
            'clubs' => $tabla . '.club_id = clubs.club_id',
            'torneos_inscripciones' => $tabla . '.club_id = clubs.club_id'
        ];
        $add_rule = [
            "group_by" => $tabla . ".id",
        ];
        $where = [
            'users_groups.group_id' => 6
        ];
        if ($this->user->group->id > 3) {
            $where['clubs.club_id'] = $this->user->club_id;
        }
        if ($this->input->get('club_id') != '') {
            $where['users.club_id'] = $this->input->get('club_id');
        }
        if ($columna != "" && $valor != "") {
            $where  = [$tabla . '.' . $columna => $valor];
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        } else {
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        }

        $res = json_encode($result);
        echo $res;
    }

    public function inscripcion($slug)
    {
        logged();
        $torneo = $this->database->buscarDato('torneos', 'slug', $slug);
        if (!$torneo || $torneo == false) {
            show_404();
        }
        $data['page_header']    = 'Inscripcion de participantes';
        $club = $this->database->userClub();
        $data['torneo'] = $torneo;
        $data['competicioneskata'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KATA');
        //printr($data['competicioneskata']);
        $data['competicioneskumite'] = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KUMITE');
        $data['club'] = $club;
        $data['deportistas'] = $this->database->clubUsers($club->club_id, [6]);
        $data['entrenadores'] = $this->database->clubUsers($club->club_id, [5]);
        $data['inscripciones'] = $this->database->torneoIncripcionesClubDeportistas($club->club_id, $torneo->torneo_id);
        //printr($data['inscripciones']);
        $data['view']           = ['gestion/torneos/inscripcion_deportistas'];
        $data['js_files']       = [assets_url() . 'admin/js/vistas/torneos_inscripcion.js'];
        show($data);
    }

    public function manage_inscripciones()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_previa_torneo_id', 'Competición previa', 'trim|required');
        $this->form_validation->set_rules('competicion_nueva_torneo_id', 'Competición nueva', 'trim|required');
        $this->form_validation->set_rules('torneo_id', 'Torneo', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Deportista', 'trim|required');
        validForm();

        // es nueva inscripcion, se crea un nuevo registro con la actual
        if (input('competicion_previa_torneo_id') == 0) {
            $params = [
                'torneo_id' => input('torneo_id'),
                'user_id' => input('user_id'),
                'estado' => (input('estado') != '') ? input('estado') : 0,
                'competicion_torneo_id' => input('competicion_nueva_torneo_id')
            ];
            $inscripcion = $this->database->insert('torneos_inscripciones', $params);
            if ($inscripcion) {
                $response = [
                    'error'     => 0,
                    'msn' => 'Correcto',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                returnAjax($response);
            } else {
                $response = [
                    'error'     => 1,
                    'error_msn' => 'Inscripción no realizada',
                    'redirect' => 'refresh',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                returnAjax($response);
            }
        } else {
            $params = [
                'tabla' => 'torneos_inscripciones',
                'where' => [
                    'torneo_id' => input('torneo_id'),
                    'user_id' => input('user_id'),
                    'deletedAt' => '0000-00-00 00:00:00',
                    'competicion_torneo_id' => input('competicion_previa_torneo_id')
                ]
            ];
            $inscripcion_previa = $this->database->getWhere($params);
            if (!isset($inscripcion_previa) || $inscripcion_previa == false || count($inscripcion_previa) == 0) {
                $response = [
                    'error'     => 1,
                    'error_msn' => 'Inscripción no encontrada',
                    'redirect' => 'refresh',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                returnAjax($response);
            }
            // nuevos datos
            $params = [
                'torneo_id' => input('torneo_id'),
                'user_id' => input('user_id'),
                'competicion_torneo_id' => (input('competicion_nueva_torneo_id') == 0) ? input('competicion_previa_torneo_id') : input('competicion_nueva_torneo_id'),
                'estado' => (input('estado') != '') ? input('estado') : 0,
                'deletedAt' => (input('competicion_nueva_torneo_id') == 0) ? date('Y-m-d H:i:s') : $inscripcion_previa[0]->deletedAt,
            ];
            $actualizar = $this->database->actualizar('torneos_inscripciones', $params, 'inscripcion_id', $inscripcion_previa[0]->inscripcion_id);
            if ($actualizar != $inscripcion_previa[0]->inscripcion_id) {
                $response = [
                    'error'     => 1,
                    'error_msn' => 'No se ha podido eliminar la inscripción. Intentelo de nuevo mas tarde o contacte con un administrador.',
                    'redirect' => 'refresh',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                returnAjax($response);
            } else {
                $response = [
                    'error'     => 0,
                    'msn' => 'Correcto',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                returnAjax($response);
            }
        }
    }

    public function manage_estado_inscripciones()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('inscripcion_id', 'Inscripcion', 'trim|required');
        $this->form_validation->set_rules('estado', 'Estado', 'trim|required');
        validForm();
        $inscripcion = $this->database->buscarDato('torneos_inscripciones', 'inscripcion_id', input('inscripcion_id'));
        if (!isset($inscripcion) || $inscripcion == false) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Inscripción no encontrada',
                'redirect' => 'refresh',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $actualizardatos = [
            'estado' => input('estado'),
            'updatedAt' => date('Y-m-d H:i:s')
        ];
        if (input('estado') == 0) {
            $actualizardatos['grupo'] = 0;
            $actualizardatos['orden'] = 0;
        }
        $actualizar = $this->database->actualizar('torneos_inscripciones', $actualizardatos, 'inscripcion_id', input('inscripcion_id'));
        if ($actualizar != input('inscripcion_id')) {
            $response = [
                'error'     => 1,
                'error_msn' => 'No se ha podido cambiar el estado de la inscripción. Intentelo de nuevo mas tarde o contacte con un administrador.',
                'redirect' => 'refresh',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 0,
                'msn' => 'Correcto',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }

    public function get_inscripciones_competicion()
    {
        asistentePage();
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        validForm();
        $competicion = $this->database->buscarDato('torneos_competiciones', 'competicion_torneo_id', input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competcición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $inscripciones = $this->database->inscritosCompeticion(input('competicion_torneo_id'));
        $response = [
            'error'     => 0,
            'inscritos' =>  $inscripciones,
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

    public function finalizados()
    {
        logged();
        validUrl();
        $data['page_header']    = 'Torneos finalizados';
        $data['table_title'] = 'Torneos finalizados del club';
        $data['default_filter'] = '2';
        $data['view']           = ['gestion/common/tabla_datatable'];
        $data['js_files']       = [assets_url() . 'admin/js/vistas/torneos_finalizados.js'];
        show($data);
    }

    public function copiar_categorias($torneo_origen, $torneo_destino)
    {
        adminPage();
        $this->db->where('torneo_id', $torneo_origen);
        $this->db->where('deletedAt', '0000-00-00 00:00:00');
        $categorias = $this->db->get('torneos_competiciones')->result();

        foreach ($categorias as $key => $value) {
            $data = [
                'sibling_id' => ($value->sibling_id == 0) ? $value->competicion_torneo_id : $value->sibling_id,
                'torneo_id' => $torneo_destino,
                'modalidad' =>  $value->modalidad,
                'categoria' =>  $value->categoria,
                'genero' =>  $value->genero,
                'nivel' =>  $value->nivel,
            ];
            $competicion_torneo_id = $this->database->insert('torneos_competiciones', $data);
        }
    }

    public function copiar_inscripciones($torneo_origen, $torneo_destino)
    {
        adminPage();
        $this->db->where('torneo_id', $torneo_origen);
        $this->db->where('deletedAt', '0000-00-00 00:00:00');
        $inscripciones = $this->db->get('torneos_inscripciones')->result();

        foreach ($inscripciones as $key => $inscripcion) {
            // busca la copeticion
            $this->db->where('competicion_torneo_id', $inscripcion->competicion_torneo_id);
            $competicion = $this->db->get('torneos_competiciones')->row();
            $sibling_id = $competicion->sibling_id;

            $this->db->where('sibling_id', $sibling_id);
            $this->db->where('torneo_id', $torneo_destino);
            $competicion_destino = $this->db->get('torneos_competiciones')->row();

            $params = [
                'torneo_id' => $torneo_destino,
                'user_id' => $inscripcion->user_id,
                'competicion_torneo_id' => $competicion_destino->competicion_torneo_id,
                'estado' => 1
            ];
            $inscripcion_final = $this->database->insert('torneos_inscripciones', $params);
        }
    }

    public function copy_inscripciones()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_origen', 'Competición origen', 'trim|required');
        $this->form_validation->set_rules('competicion_destino', 'Competición destino', 'trim|required');
        validForm();

        // busca la copeticion
        $inscripciones = $this->database->inscritosCompeticion(input('competicion_origen'));

        foreach ($inscripciones as $key => $i) {
            $params = [
                'torneo_id' => input('torneo_id'),
                'user_id' => $i->user_id,
                'competicion_torneo_id' => input('competicion_destino'),
                'estado' => 1
            ];
            $inscripcion_final = $this->database->insert('torneos_inscripciones', $params);
        }
        $response = [
            'error'     => 0,
            'msn' => 'Inscripciones copiadas',
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }


    public function grupos()
    {

        adminPage();
        validUrl();
        $data['page_header']    = 'Grupos de torneos';
        $data['table_title'] = 'Lista de grupos de torneos';

        $data['table_button']   = [
            'name' => 'crear_grupo',
            'content' => '<i class="fa fa-plus mr-3"></i> Nuevo grupo',
            'extra' => [
                'data-tooltip' => TRUE,
                'data-editar-grupo' => 'nuevo',
                'title' => 'Crear nuevo grupo',
                'data-original-title' => 'Crear nuevo grupo',
                'class' => 'btn btn-sm btn-primary ml-auto'
            ]
        ];
        $params = [
            'tabla' => 'torneos_grupos',
            'where' => [
                'estado' => 1,
                'deletedAt' => '	0000-00-00 00:00:00'
            ]
        ];
        $data['grupos'] = $this->database->getWhere($params);
        $params = [
            'tabla' => 'torneos',
            'where' => ['deletedAt' => '	0000-00-00 00:00:00']
        ];
        $data['torneos'] = $this->database->getWhere($params);
        $data['view']           = ['gestion/common/tabla_datatable', 'gestion/torneos/torneos_grupo_form_modal'];
        $data['js_files']       = [assets_url() . 'admin/js/vistas/torneos_grupos.js'];

        show($data);
    }
     /***
     * DATATABLES GET
     */
    // datatable torneos
    public function getTorneosGrupos($columna = null, $valor = null)
    {
        logged();
        $this->load->library('Datatable');
        $tabla  = 'torneos_grupos';
        $campos = [
            $tabla . '.grupo_id',
            $tabla . '.titulo',
            $tabla . '.descripcion',
            "GROUP_CONCAT(torneos.titulo SEPARATOR ', ') AS titulos_torneos",
            $tabla.'.estado'
        ];
        $join     = [
            'grupos_torneos' => 'torneos_grupos.grupo_id = grupos_torneos.grupo_id',
            'torneos' => "grupos_torneos.torneo_id = torneos.torneo_id"
        ];
        $add_rule = [
            "group_by" => "torneos_grupos.grupo_id",
        ];
        $where = [];
        /*if ($this->input->get('estado') != '') {
            $where['estado'] = $this->input->get('estado');
        }
        if ($this->input->get('proximos') == 1) {
            $where['fecha >='] = date('Y-m-d');
        }
        if ($this->input->get('proximos') == 2) {
            $where['fecha <'] = date('Y-m-d');
        }*/
        if ($columna != "" && $valor != "") {
            $where  = [$tabla . '.' . $columna => $valor];
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        } else {
            $result = json_decode($this->datatable->get_datatable($this->input->get(), $tabla, $join, $campos, $where, $add_rule));
        }
        $result->query = $this->db->last_query();
        $res = json_encode($result);
        echo $res;

    }
    // procesamiento del formulario de nuevo usuario: crea tambien un equipo para ese usuario
    public function nuevo_grupo_form()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('titulo', 'Título', 'trim|required');
        $this->form_validation->set_rules('descripcion', 'Texto promocional', 'trim|required');
        $this->form_validation->set_rules('torneo_id[]', 'Torneos', 'trim|required');
        validForm();
        $data = [
            'titulo' => $this->input->post('titulo'),
            'descripcion'  => $this->input->post('descripcion'),
            'torneo_ids'        => implode('|', $this->input->post('torneo_id[]')),
            'estado'        => ($this->input->post('estado') == 'OK') ? 1 : 0,
        ];
        $grupo_id = $this->database->insert('torneos_grupos', $data);

        foreach ($this->input->post('torneo_id[]') as $t => $torneo_id) {
           $data = [
                'grupo_id' => $grupo_id,
                'torneo_id' => $torneo_id
           ];
           $this->database->insert('grupos_torneos', $data);
        }
        if ($grupo_id) {
            $redirect = base_url() . 'torneos/grupo/' . $grupo_id;
            $response = [
                'error'    => 0,
                'msn'      => 'Nuevo grupo de torneos creado correctamente',
                'redirect' => $redirect,
                'csrf'     => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'No se ha podido crear el grupo de torneos',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
    }

     // obtiene los datos del equipo
     public function ver_grupo_fetch()
     {
         logged();
         isAjax();
         $this->form_validation->set_rules('grupo_id', 'Grupo', 'trim|required');
         validForm();
         $grupo_id = $this->input->post('grupo_id');
         $grupo = $this->database->buscarDato('torneos_grupos', 'grupo_id', $grupo_id);
 
         if (!$grupo) {
             $response = [
                 'error'     => 1,
                 'error_msn' => 'Grupo no encontrado.',
                 'csrf'      => $this->security->get_csrf_hash(),
             ];
             echo json_encode($response);
             exit();
         }
         $response = [
             'error'    => 0,
             'data'      => ['grupo' => $grupo],
             'csrf'     => $this->security->get_csrf_hash(),
         ];
         returnAjax($response);
     }

      // procesamiento del formulario de los datos del equipo
    public function editar_grupo_form()
    {
        adminPage();
        isAjax();
        if ($this->input->post('grupo_id') == '') {
            $response = [
                'error'     => 1,
                'error_msn' => 'Identificador de grupo no válido.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $grupo = $this->database->buscarDato('torneos_grupos', 'grupo_id', $this->input->post('grupo_id'));
        if (!$grupo) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El grupo no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $this->form_validation->set_rules('titulo', 'Título', 'trim|required');
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|required');
        validForm();
        $torneo_ids_post = implode('|', $this->input->post('torneo_id[]'));
        if ($grupo->titulo != $this->input->post('titulo')) {
            $datos['titulo'] = $this->input->post('titulo');
        }
        if ($grupo->descripcion != $this->input->post('descripcion')) {
            $datos['descripcion'] = $this->input->post('descripcion');
        }
        if ($grupo->torneo_ids != $torneo_ids_post) {
            $datos['torneo_ids'] = $torneo_ids_post;
        }
        if ($this->input->post('estado') == 'OK') {
            if ($grupo->estado == 0) {
                $datos['estado'] = 1;
            }
        } else {
            if ($grupo->estado == 1) {
                $datos['estado'] = 0;
            }
        }
        if (empty($datos)) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Los datos indicados no cambian los datos del grupo.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $cambiar = $this->database->actualizar('torneos_grupos', $datos, 'grupo_id', $this->input->post('grupo_id'));
            if ($cambiar != $this->input->post('grupo_id')) {
                $response = [
                    'error'     => 1,
                    'error_msn' => 'Ha ocurrido un error y no se han realizado los cambios.',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            } else {
                if(isset( $datos['torneo_ids']) ) {
                    $this->db->query('DELETE FROM grupos_torneos WHERE grupo_id = '.$this->input->post('grupo_id').';');
                    foreach ($this->input->post('torneo_id[]') as $t => $torneo_id) {
                        $data = [
                             'grupo_id' => $this->input->post('grupo_id'),
                             'torneo_id' => $torneo_id
                        ];
                        $this->database->insert('grupos_torneos', $data);
                     }
                }
                $response = [
                    'error' => 0,
                    'msn'   => 'Actualización de datos correcta',
                    'csrf'  => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            }
        }
    }
}
