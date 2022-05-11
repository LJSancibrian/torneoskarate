<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Equipos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->in_group([1, 2, 3, 4, 5])) {
            $this->session->set_flashdata('info', 'Inicie sesión');
            redirect('login', 'refresh');
        }

        $this->base = 'gestion/template';
    }
    // lista de equipos 
    public function index()
    {
        validUrl();
        adminPage();
        $data['page_header']    = 'Equipos / Clubes / Escuelas';
        $data['table_title'] = 'Equipos registrados en la plataforma';
        $data['table_button']   = [
            'content' => '<i class="fa fa-plus mr-3"></i> Nuevo equipo',
            'href' => base_url() . 'equipos/nuevo-equipo',
            'extra' => [
                'data-tooltip' => true,
                'title' => 'Crear nuevo equipo',
                'data-original-title' => 'Crear nuevo equipo',
                'class' => 'btn btn-sm btn-primary ml-auto'
            ]
        ];
        $data['default_filter'] = '5';
        $data['view']           = 'gestion/common/tabla_datatable';
        $data['js_files']       = [assets_url() . 'admin/js/vistas/equipos.js'];
        show($data);
    }
    // lista de entrenadores 
    public function entrenadores()
    {
        validUrl();
        coachPage();
        $data['page_header']    = 'Entrenadores / Coaches';
        $data['table_title'] = 'Entrenadores registrados en la plataforma';
        $data['table_button']   = [
            'name' => 'anadir_deportistas',
            'content' => '<i class="fa fa-plus mr-3"></i> Añadir entrenador',
            'extra' => [
                'data-tooltip' => TRUE,
                'data-carga-entrenador' => "individual",
                'title' => '',
                'data-original-title' => 'Crear nuevo entrenador',
                'class' => 'btn btn-sm btn-primary ml-auto'
            ]
        ];
        if ($this->user->group->id == 5) {
            $data['club'] = $this->database->userClub();
        } else {
            $data['clubs'] = $this->database->getClubs();
        }
        $data['default_filter'] = '5';
        $data['view']           = ['gestion/common/tabla_datatable', 'gestion/equipos/equipos_entrenadores_add_modal'];
        $data['js_files']       = [assets_url() . 'admin/js/vistas/equipos_entrenadores.js'];
        show($data);
    }
    // lista de deportistas 
    public function deportistas()
    {
        validUrl();
        coachPage();
        $data['page_header']    = 'Deportistas';
        $data['table_title'] = 'Deportistas registrados en la plataforma';
        $data['table_button']   = [
            'name' => 'anadir_deportistas',
            'content' => '<i class="fa fa-plus mr-3"></i> Añadir deportista/s',
            'extra' => [
                'data-tooltip' => TRUE,
                'data-toggle' => 'dropdown',
                'aria-haspopup' => "true",
                'aria-expanded' => "false",
                'title' => '',
                'data-original-title' => 'Añadir deportista/s',
                'class' => 'btn btn-sm btn-primary ml-auto'
            ],
            'dropdown' => [
                [
                    //'href' => '',
                    'content' =>  'Individual',
                    'extra' => [
                        'class' => "dropdown-item",
                        'data-carga-deportista' => "individual"
                    ]
                ],
                [
                    'href' => '#',
                    'content' =>  'Carga masiva con archivo .csv',
                    'extra' => [
                        'class' => "dropdown-item",
                        'data-carga-deportista' => "archivo"
                    ]
                ],
            ]
        ];
        if ($this->user->group->id == 5) {
            $data['club'] = $this->database->userClub();
        } else {
            $data['clubs'] = $this->database->getClubs();
        }
        $data['default_filter'] = '6';
        $data['view']           = ['gestion/common/tabla_datatable', 'gestion/equipos/equipos_deportistas_add_modal'];
        $data['js_files']       = [assets_url() . 'admin/js/vistas/equipos_deportistas.js'];
        show($data);
    }

    /***
     * DATATABLES GET
     */
    // datatable equipos
    public function getEquipos($columna = null, $valor = null)
    {
        $this->load->library('Datatable');
        $tabla  = 'clubs';
        $campos = [
            $tabla . '.club_id',
            $tabla . '.slug',
            $tabla . '.nombre',
            'CONCAT(users.first_name, " " , users.last_name) AS responsable',
            $tabla . '.email',
            $tabla . '.telefono',
            $tabla . '.estado',
            $tabla . '.*'
        ];
        $join     = [
            'users' => $tabla . '.user_id = users.id',
        ];
        $add_rule = [
            "group_by" => $tabla . ".club_id",
        ];
        $where = [];
        if ($this->input->get('estado') != '') {
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
    // datatables deportistas
    public function getDeportistas($columna = null, $valor = null)
    {
        $this->load->library('Datatable');
        //$query = SELECT categoria FROM tornoes_categorias WEHRE SUM(year * -1) > () ORDER BY year ASC LIMIT 1
        //'SUM(YEAR(CURDATE()) - DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(users.dob)), "%Y")) AS edad_cat', 
        $tabla  = 'users';
        $campos = [
            $tabla . '.usercode',
            $tabla . '.first_name',
            $tabla . '.last_name',
            'YEAR(' . $tabla . '.dob) AS yob',
            $tabla . '.genero',
            'clubs.nombre',
            $tabla . '.*',
            /*'(SELECT categoria FROM torneos_categorias WHERE (year * -1) > (SUM(YEAR(CURDATE()) - DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(users.dob)), "%Y"))) AND `modalidad` = "KATA" ORDER BY year desc LIMIT 1) AS categoria_kata',
            '(SELECT categoria FROM torneos_categorias WHERE (year * -1) > (SUM(YEAR(CURDATE()) - DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(users.dob)), "%Y"))) AND `modalidad` = "KUMITE" ORDER BY year desc LIMIT 1) AS categoria_kumite',*/
        ];
        $join     = [
            'users_groups' => $tabla . '.id = users_groups.user_id',
            'clubs' => $tabla . '.club_id = clubs.club_id'
        ];
        $add_rule = [
            "group_by" => $tabla . ".id",
        ];
        $where = [
            'users_groups.group_id' => 6
        ];
        if ($this->user->group->id > 3) {
            $where['clubs.club_id'] = $this->database->userClub()->club_id;
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
    // datatables coaches
    public function getEntrenadores($columna = null, $valor = null)
    {
        $this->load->library('Datatable');
        $tabla  = 'users';
        $campos = [
            $tabla . '.usercode',
            $tabla . '.first_name',
            $tabla . '.last_name',
            $tabla . '.email',
            $tabla . '.phone',
            'clubs.nombre',
            $tabla . '.*',
            '(CASE WHEN clubs.user_id = users.id THEN 1 ELSE 0 END) AS responsable'
        ];
        $join     = [
            'users_groups' => $tabla . '.id = users_groups.user_id',
            'clubs' => $tabla . '.club_id = clubs.club_id'
        ];
        $add_rule = [
            "group_by" => $tabla . ".id",
        ];
        $where = [
            'users_groups.group_id' => 5
        ];
        if ($this->user->group->id > 3) {
            $where['clubs.club_id'] = $this->database->userClub()->club_id;
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

    /**
     * GESTIÓN DE EQUIPOS
     */
    // nuevo equipo: formulario nuevo usuario
    public function nuevo_equipo()
    {
        validUrl();
        adminPage();
        $data['page_header']    = 'Crear nuevo equipo';
        $data['view']     = 'gestion/equipos/equipos_nuevo';
        $data['js_files'] = [assets_url() . 'admin/js/vistas/equipos_usuario_nuevo.js'];
        show($data);
    }
    // procesamiento del formulario de nuevo usuario: crea tambien un equipo para ese usuario
    public function nuevo_usuario_form()
    {
        isAjax();
        $this->form_validation->set_rules('first_name', 'Nombre', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'trim|required');
        $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|required|validDni|is_unique[users.dni]', ['is_unique' => 'El dni ya está en uso']);
        $this->form_validation->set_rules('phone', 'Teléfono', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]', ['is_unique' => 'El email ya está en uso']);
        $this->form_validation->set_rules('newpassword', 'Contraseña', 'required|min_length[8]|matches[confirmpassword]');
        $this->form_validation->set_rules('confirmpassword', 'Confirmar contraseña', 'required');
        validForm();
        $email           = strtolower($this->input->post('email'));
        $identity        = $email;
        $password        = $this->input->post('newpassword');
        $additional_data = [
            'first_name' => $this->input->post('first_name'),
            'last_name'  => $this->input->post('last_name'),
            'dni'        => $this->input->post('dni'),
            'phone'      => $this->input->post('phone'),
            'usercode'   => $this->utilidades->rand_unique('users', 'usercode', 10, TRUE),
        ];
        $registro = $this->ion_auth->register($identity, $password, $email, $additional_data, [5]);
        if ($registro) {
            if ($this->input->post('active') == 'OK') {
                $this->ion_auth->activate($registro['id']);
            }
            // se crea el quipo
            $equipo = [
                'slug'   => $this->utilidades->rand_unique('clubs', 'slug', 10, TRUE),
                'user_id' => $registro['id'],
            ];
            $club_id = $this->database->insert('clubs', $equipo);
            $this->database->actualizar('users', ['club_id' => $club_id], 'id', $registro['id']);
            $redirect = base_url() . 'equipos/ver/' . $equipo['slug'];
            $response = [
                'error'    => 0,
                'msn'      => 'Usuario responsable de equipo creado correctamente',
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
    public function ver_equipo_fetch()
    {
        isAjax();
        $this->form_validation->set_rules('club_id', 'Club', 'trim|required');
        validForm();
        $club_id = $this->input->post('club_id');
        $club = $this->database->buscarDato('clubs', 'club_id', $club_id);
        if (!$club) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Equipo o club no encontrado.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $responsable = $this->database->buscarDato('users', 'id', $club->user_id);
        $response = [
            'error'    => 0,
            'data'      => ['club' => $club, 'responsable' => $responsable],
            'csrf'     => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // vista del equipo en paneles
    public function ver($slug, $tab = '')
    {
        validUrl();
        $equipo = $this->database->buscarDato('clubs', 'slug', $slug);
        if (!$equipo) {
            show_404();
            exit();
        }
        $data['page_header']    = $equipo->nombre;
        $data['tab']    = $tab;
        $data['club']     = $equipo;
        $data['user']     = $this->database->buscarDato('users', 'id', $equipo->user_id);
        $data['view']           = 'gestion/equipos/equipos_ver';
        $data['js_files']       = [assets_url() . 'admin/js/vistas/equipos_ver.js'];
        show($data);
    }

    public function vermiequipo()
    {
        validUrl();
        if (!in_array(5, $this->user->groups_ids)) {
            show_404();
        }
        $equipo = $this->database->userClub();
        if (!$equipo) {
            show_404();
            exit();
        }
        $data['page_header']    = 'Mi equipo';
        $data['entrenadores_num'] = count($this->database->clubUsers($equipo->club_id, [5]));
        $data['deportistas_num'] = count($this->database->clubUsers($equipo->club_id, [6]));
        $data['club']     = $equipo;
        $data['view']           = 'gestion/equipos/equipos_vermiequipo';
        $data['js_files']       = [assets_url() . 'admin/js/vistas/equipos_vermiequipo.js'];
        show($data);
    }
    // procesamiento del formulario de los datos del equipo
    public function editar_equipo_form()
    {
        isAjax();
        if ($this->input->post('club_id') == '') {
            $response = [
                'error'     => 1,
                'error_msn' => 'Identificador de equipo no válido.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $club = $this->database->buscarDato('clubs', 'club_id', $this->input->post('club_id'));
        if (!$club) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El equipo no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $this->form_validation->set_rules('nombre', 'Nombre del club', 'required');
        if ($club->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[clubs.email]', ['is_unique' => 'El email ya está en uso por otro equipo']);
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required');
        }
        $this->form_validation->set_rules('telefono', 'Teléfono', 'required');
        $this->form_validation->set_rules('direccion', 'Dirección', 'trim|required');
        $this->form_validation->set_rules('descripcion', 'Descripción', 'trim|required');
        if (empty($_FILES['archivo']['name'])) {
            $this->form_validation->set_rules('archivo', 'Logo', 'required', ['required' => 'No se ha recibido ningun archivo']);
        }
        validForm();

        if (!empty($_FILES['archivo']['name'])) {
            $upload_path = 'uploads/equipos/';
            $archivo_servidor = $this->utilidades->upload_file($_FILES['archivo'], $upload_path, '', '', 'archivo');
            if (!isset($archivo_servidor['display_errors'])) {
                $datos['img'] = base_url() . $upload_path . '/' . $archivo_servidor['file_name'];
            } else {
                $this->form_validation->set_rules('archivo', 'Logo no cargado', 'required', ['required' => $archivo_servidor['display_errors']]);
                validForm();
            }
        }

        if ($club->nombre != $this->input->post('nombre')) {
            $datos['nombre'] = $this->input->post('nombre');
        }
        if ($club->descripcion != $this->input->post('descripcion')) {
            $datos['descripcion'] = $this->input->post('descripcion');
        }
        if ($club->direccion != $this->input->post('direccion')) {
            $datos['direccion'] = $this->input->post('direccion');
        }
        if ($club->email != $this->input->post('email')) {
            $datos['email'] = $this->input->post('email');
        }
        if ($club->telefono != $this->input->post('telefono')) {
            $datos['telefono'] = $this->input->post('telefono');
        }
        if ($this->input->post('estado') == 'OK') {
            if ($club->estado == 0) {
                $datos['estado'] = 1;
            }
        } else {
            if ($club->estado == 1) {
                $datos['estado'] = 0;
            }
        }
        if (empty($datos)) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Los datos indicados no cambian los datos del equipo.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            $cambiar = $this->database->actualizar('clubs', $datos, 'club_id', $this->input->post('club_id'));
            if ($cambiar != $this->input->post('club_id')) {
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
    // procesamiento del formulario de los datos dfel usaurio del equipo
    public function editar_usuario_form()
    {
        isAjax();
        if ($this->input->post('user_id') == '') {
            $response = [
                'error'     => 1,
                'error_msn' => 'Identificador de usuario no válido.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $user = $this->utilidades->user($this->input->post('user_id'));
        if (!$user) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El usuario no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }

        $this->form_validation->set_rules('first_name', 'Nombre', 'required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'required');
        if ($user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[users.email]', ['is_unique' => 'El email ya está en uso']);
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required');
        }
        if ($user->dni != $this->input->post('dni')) {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|required|validDni|is_unique[users.dni]', ['is_unique' => 'El dni ya está en uso']);
        } else {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|required');
        }
        $this->form_validation->set_rules('phone', 'Teléfono', 'required');
        if ($this->input->post('newpassword') != '') {
            $this->form_validation->set_rules('newpassword', 'Nueva contraseña', 'matches[confirmpassword]', ['matches' => 'Las contraseñas no coinciden']);
            $this->form_validation->set_rules('confirmpassword', 'Confirmar contraseña', 'required');
        }
        validForm();
        if ($user->first_name != $this->input->post('first_name')) {
            $datos['first_name'] = $this->input->post('first_name');
        }
        if ($user->last_name != $this->input->post('last_name')) {
            $datos['last_name'] = $this->input->post('last_name');
        }
        if ($user->dni != $this->input->post('dni')) {
            $datos['dni'] = $this->input->post('dni');
        }
        if ($user->email != $this->input->post('email')) {
            $datos['email'] = $this->input->post('email');
        }
        if ($user->phone != $this->input->post('phone')) {
            $datos['phone'] = $this->input->post('phone');
        }

        if ($this->input->post('newpassword') != '') {
            $datos['password'] = $this->input->post('newpassword');
        }
        $cambio_rol = 0;
        $cambio_estado = 0;
        if ($this->input->post('active') == 'OK') {
            if ($user->active == 0) {
                $this->ion_auth->activate($user->id);
                $cambio_estado = 1;
            }
        } else {
            if ($user->active == 1) {
                $this->ion_auth->deactivate($user->id);
                $cambio_estado = 1;
            }
        }
        $cambio_datos = 0;
        
        if (!empty($datos)) {    
            $cambio = $this->ion_auth->update($this->input->post('user_id'), $datos);
            $cambio_datos = 1;
        }

        if ($cambio_datos == 1 || $cambio_rol == 1 || $cambio_estado == 1) {
            $response = [
                'error' => 0,
                'msn'   => $this->ion_auth->messages(),
                'csrf'  => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            if ($this->ion_auth->errors()) {
                $error_msn =  $this->ion_auth->errors();
            } else {
                $error_msn = 'No se han realizado cambios en los datos del usuario';
            }
            $response = [
                'error'     => 1,
                'error_msn' => $error_msn,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
    }

    /**
     * GEESTIÓN DE DEPORTISTAS EN EQUIPOS
     */
    // procesamiento del formulario de nuevo deportista
    public function nuevo_deportista_form()
    {
        isAjax();
        $this->form_validation->set_rules('club_id', 'ClubID', 'trim|required');
        $this->form_validation->set_rules('first_name', 'Nombre', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'trim|required');
        $this->form_validation->set_rules('dob', 'Fecha de nacimiento', 'trim|required');
        $this->form_validation->set_rules('genero', 'Género', 'trim|required');

        $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|validDni|is_unique[users.dni]', ['is_unique' => 'El dni ya está en uso']);
        $this->form_validation->set_rules('phone', 'Teléfono', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[users.email]', ['is_unique' => 'El email ya está en uso']);
        $this->form_validation->set_rules('peso', 'Peso', 'trim|numeric');
        $this->form_validation->set_rules('nivel', 'Nivel', 'trim');

        validForm();
        $usercode = $this->utilidades->rand_unique('users', 'usercode', 10, TRUE);
        $email           = ($this->input->post('email') != '') ? strtolower($this->input->post('email')) : strtolower($usercode . '@' . $this->input->post('club_id') . '.generado');
        $identity        = $email;
        $password        = rand_alphanumeric(8);
        $additional_data = [
            'first_name' => $this->input->post('first_name'),
            'last_name'  => $this->input->post('last_name'),
            'dni'        => $this->input->post('dni'),
            'phone'      => $this->input->post('phone'),
            'dob'      => $this->input->post('dob'),
            'nivel'      => $this->input->post('nivel'),
            'peso'      => $this->input->post('peso'),
            'genero'      => $this->input->post('genero'),
            'usercode'   => $usercode,
            'club_id'   => $this->input->post('club_id'),
        ];
        $registro = $this->ion_auth->register($identity, $password, $email, $additional_data, [6]);
        if ($registro) {
            if ($this->input->post('active') == 'OK') {
                $this->ion_auth->activate($registro['id']);
            }
            $response = [
                'error'    => 0,
                'msn'      => 'Deportista creado correctamente',
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
    public function deportistas_file_form()
    {
        $this->load->library('form_validation');
        if (empty($_FILES['archivo']['name'])) {
            $this->form_validation->set_rules('archivo', 'Archivo', 'required');
        }
        $this->form_validation->set_rules('club_id', 'Identificador equipo', 'required');
        validForm();
        $club_id = $this->input->post('club_id');
        $path = FCPATH . '/assets/admin/csvfiles/' . $club_id . '_' . $_FILES['archivo']['name'];
        move_uploaded_file($_FILES["archivo"]["tmp_name"], $path);
        $fp = fopen($path, 'r', '"') or die("can't open file");
        $loop = 1;
        while ($csv_line = fgetcsv($fp, 0, ";")) {
            if ($loop == 1) {
                $loop++;
                continue;
            }
            $datos = array_map("utf8_encode", $csv_line);
            $usercode = $this->utilidades->rand_unique('users', 'usercode', 10, TRUE);
            $email           = ($datos[7] != '') ? strtolower($datos[7]) : strtolower($usercode . '@' . $this->input->post('club_id') . '.generado');
            $identity        = $email;
            $password        = rand_alphanumeric(8);
            $dob = str_replace('/', '-', trim($datos[2]));
            $nombre = mb_convert_case(trim($datos[0]), MB_CASE_TITLE, 'UTF-8');
            $last_name = mb_convert_case(trim($datos[1]), MB_CASE_TITLE, 'UTF-8');
            $additional_data = [
                'first_name' => ucfirst($nombre),
                'last_name'  =>  $last_name,
                'dni'        => ($datos[8] != '') ? strtoupper(trim($datos[8])) : '',
                'phone'      => $datos[6],
                'dob'      =>  date('Y-m-d', strtotime($dob)),
                'nivel'      => $datos[5],
                'peso'      => $datos[4],
                'genero'      => ($datos[3] == 'M') ? 1 : 2,
                'usercode'   => $usercode,
                'club_id'   => $this->input->post('club_id'),
            ];
            $registro = $this->ion_auth->register($identity, $password, $email, $additional_data, [6]);
            if ($registro) {
                $this->ion_auth->activate($registro['id']);
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

        $response = [
            'error'    => 0,
            'msn'      => 'Deportistas creados desde archivo correctamente',
            'csrf'     => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

    public function deportistas_escuela($club_id, $file)
    {
        
        $path = FCPATH . '/assets/admin/csvfilesemp/' . $file.'.csv';

        $fp = fopen($path, 'r') or die("can't open file");
        utf8_encode(fgets($fp));
        $loop = 1;
        while ($csv_line = fgetcsv($fp, 0, ",")) {
            if ($loop == 1) {
                $loop++;
                continue;
            }
            $datos = array_map("utf8_encode", $csv_line);
            $email           = strtolower(trim($datos[0]) . '@' . $this->input->post('club_id') . '.generado');
            $identity        = $email;
            $password        = rand_alphanumeric(8);
            $nombrecompleto = strtolower(trim($datos[1]));
            $nombrearray = explode(', ', $nombrecompleto);
            $fechanac = trim($datos[3]);
            $fechaarray = explode('/', $fechanac);
            $dob = $fechaarray[2].'-'.$fechaarray[1].'-'.$fechaarray[0];
            $additional_data = [
                'first_name' => trim($nombrearray[1]),
                'last_name'  =>  trim($nombrearray[0]),
                'dni'        => ($datos[2] != '') ? strtoupper(trim($datos[2])) : '',
                'phone'      => '',
                'dob'      =>  $dob,
                'nivel'      => '',
                'peso'      => '',
                'genero'      => 1,
                'usercode'   => trim($datos[0]),
                'club_id'   => $club_id,
            ];
            var_dump($additional_data);
            $registro = $this->ion_auth->register($identity, $password, $email, $additional_data, [6]);
            if ($registro) {
                $this->ion_auth->activate($registro['id']);
            }
        }

        $response = [
            'error'    => 0,
            'msn'      => 'Deportistas creados desde archivo correctamente',
            'csrf'     => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // procesamiento del formulario de editar deportista
    public function editar_deportista_form()
    {
        isAjax();
        if ($this->input->post('user_id') == '') {
            $response = [
                'error'     => 1,
                'error_msn' => 'Identificador de usuario no válido.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $user = $this->utilidades->user($this->input->post('user_id'));
        if (!$user) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El usuario no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }

        $this->form_validation->set_rules('first_name', 'Nombre', 'required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'required');
        $this->form_validation->set_rules('dob', 'Fecha de nacimiento', 'required');
        $this->form_validation->set_rules('genero', 'Género', 'required');

        if ($user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', 'Email', 'is_unique[users.email]', ['is_unique' => 'El email ya está en uso']);
        } else {
            $this->form_validation->set_rules('email', 'Email', 'trim');
        }
        if ($user->dni != $this->input->post('dni')) {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|validDni|is_unique[users.dni]', ['is_unique' => 'El dni ya está en uso']);
        } else {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim');
        }
        $this->form_validation->set_rules('phone', 'Teléfono', 'trim');
        $this->form_validation->set_rules('peso', 'Peso', 'trim');
        $this->form_validation->set_rules('nivel', 'Nivel', 'trim');
        validForm();
        if ($user->first_name != $this->input->post('first_name')) {
            $datos['first_name'] = $this->input->post('first_name');
        }
        if ($user->last_name != $this->input->post('last_name')) {
            $datos['last_name'] = $this->input->post('last_name');
        }
        if ($user->dob != $this->input->post('dob')) {
            $datos['dob'] = $this->input->post('dob');
        }
        if ($user->genero != $this->input->post('genero')) {
            $datos['genero'] = $this->input->post('genero');
        }
        if ($user->dni != $this->input->post('dni')) {
            $datos['dni'] = $this->input->post('dni');
        }
        if ($user->email != $this->input->post('email')) {
            $datos['email'] = $this->input->post('email');
        }
        if ($user->phone != $this->input->post('phone')) {
            $datos['phone'] = $this->input->post('phone');
        }
        if ($user->peso != $this->input->post('peso')) {
            $datos['peso'] = $this->input->post('peso');
        }
        if ($user->nivel != $this->input->post('nivel')) {
            $datos['nivel'] = $this->input->post('nivel');
        }
        if ($this->user->group->id < 4) {
            if ($user->club_id != $this->input->post('club_id')) {
                $datos['club_id'] = $this->input->post('club_id');
            }
        }
        $cambio_estado = 0;
        if ($this->input->post('active') == 'OK') {
            if ($user->active == 0) {
                $this->ion_auth->activate($user->id);
                $cambio_estado = 1;
            }
        } else {
            if ($user->active == 1) {
                $this->ion_auth->deactivate($user->id);
                $cambio_estado = 1;
            }
        }
        $cambio_datos = 0;
        if (!empty($datos)) {
            $this->ion_auth->update($this->input->post('user_id'), $datos);
            $cambio_datos = 1;
        }

        if ($cambio_datos == 1 || $cambio_estado == 1) {
            $response = [
                'error' => 0,
                'msn'   => $this->ion_auth->messages(),
                'csrf'  => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            if ($this->ion_auth->errors()) {
                $error_msn =  $this->ion_auth->errors();
            } else {
                $error_msn = 'No se han realizado cambios en los datos del deportista';
            }
            $response = [
                'error'     => 1,
                'error_msn' => $error_msn,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
    }
    // obtiene los datos de un deportista
    public function ver_deportista_fetch()
    {
        isAjax();
        $this->form_validation->set_rules('deportista_id', 'ID deportista', 'trim|required');
        validForm();
        $user_id = $this->input->post('deportista_id');
        $user = $this->database->buscarDato('users', 'id', $user_id);
        if (!$user) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Deportista no encontrado',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $response = [
            'error'    => 0,
            'data'      => ['deportista' => $user],
            'csrf'     => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

    /**
     * GESTIÓN DE ENTRENADORES EN EQUIPOS
     */
    // procesamiento del formulario de nuevo entrenador
    public function nuevo_entrenador_form()
    {
        isAjax();
        $this->form_validation->set_rules('first_name', 'Nombre', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'trim|required');
        $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|validDni|is_unique[users.dni]', ['is_unique' => 'El dni ya está en uso']);
        $this->form_validation->set_rules('phone', 'Teléfono', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.email]', ['is_unique' => 'El email ya está en uso']);
        $this->form_validation->set_rules('newpassword', 'Contraseña', 'required|min_length[8]|matches[confirmpassword]');
        $this->form_validation->set_rules('confirmpassword', 'Confirmar contraseña', 'required');
        validForm();
        $email           = strtolower($this->input->post('email'));
        $identity        = $email;
        $password        = $this->input->post('newpassword');
        $additional_data = [
            'first_name' => $this->input->post('first_name'),
            'last_name'  => $this->input->post('last_name'),
            'dni'        => $this->input->post('dni'),
            'phone'      => $this->input->post('phone'),
            'usercode'   => $this->utilidades->rand_unique('users', 'usercode', 10, TRUE),
            'club_id'    => $this->input->post('club_id'),
        ];
        $registro = $this->ion_auth->register($identity, $password, $email, $additional_data, [5]);
        if ($registro) {
            if ($this->input->post('active') == 'OK') {
                $this->ion_auth->activate($registro['id']);
            }
            $response = [
                'error'    => 0,
                'msn'      => 'Entrenador creado correctamente',
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
    // obtiene los datos de un entrenador
    public function ver_entrenador_fetch()
    {
        isAjax();
        $this->form_validation->set_rules('entrenador_id', 'ID entrenador', 'trim|required');
        validForm();
        $user_id = $this->input->post('entrenador_id');
        $user = $this->database->buscarDato('users', 'id', $user_id);
        if (!$user) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Entrenador no encontrado',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $response = [
            'error'    => 0,
            'data'      => ['coach' => $user],
            'csrf'     => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // procesamiento del formulario de los datos del entrenador del equipo
    public function editar_entrenador_form()
    {
        isAjax();
        if ($this->input->post('user_id') == '') {
            $response = [
                'error'     => 1,
                'error_msn' => 'Identificador de entrenador no válido.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $user = $this->utilidades->user($this->input->post('user_id'));
        if (!$user) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El entrenador no existe.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }

        $this->form_validation->set_rules('first_name', 'Nombre', 'required');
        $this->form_validation->set_rules('last_name', 'Apellidos', 'required');
        if ($user->email != $this->input->post('email')) {
            $this->form_validation->set_rules('email', 'Email', 'required|is_unique[users.email]', ['is_unique' => 'El email ya está en uso']);
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required');
        }
        if ($user->dni != $this->input->post('dni')) {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|required|validDni|is_unique[users.dni]', ['is_unique' => 'El dni ya está en uso']);
        } else {
            $this->form_validation->set_rules('dni', 'DNI, NIF, NIE...', 'trim|required');
        }
        $this->form_validation->set_rules('phone', 'Teléfono', 'required');
        if ($this->input->post('newpassword') != '') {
            $this->form_validation->set_rules('newpassword', 'Nueva contraseña', 'matches[confirmpassword]', ['matches' => 'Las contraseñas no coinciden']);
            $this->form_validation->set_rules('confirmpassword', 'Confirmar contraseña', 'required');
        }
        validForm();
        if ($user->first_name != $this->input->post('first_name')) {
            $datos['first_name'] = $this->input->post('first_name');
        }
        if ($user->last_name != $this->input->post('last_name')) {
            $datos['last_name'] = $this->input->post('last_name');
        }
        if ($user->dni != $this->input->post('dni')) {
            $datos['dni'] = $this->input->post('dni');
        }
        if ($user->email != $this->input->post('email')) {
            $datos['email'] = $this->input->post('email');
        }
        if ($user->phone != $this->input->post('phone')) {
            $datos['phone'] = $this->input->post('phone');
        }
        if ($this->user->group->id < 4) {
            if ($user->club_id != $this->input->post('club_id')) {
                $datos['club_id'] = $this->input->post('club_id');
            }
        }
        if ($this->input->post('newpassword') != '') {
            $datos['password'] = $this->input->post('newpassword');
        }

        $cambio_rol = 0;
        $cambio_estado = 0;
        if ($this->input->post('active') == 'OK') {
            if ($user->active == 0) {
                $this->ion_auth->activate($user->id);
                $cambio_estado = 1;
            }
        } else {
            if ($user->active == 1) {
                $this->ion_auth->deactivate($user->id);
                $cambio_estado = 1;
            }
        }
        $cambio_datos = 0;
        if (!empty($datos)) {
            $this->ion_auth->update($this->input->post('user_id'), $datos);
            $cambio_datos = 1;
        }

        if ($cambio_datos == 1 || $cambio_rol == 1 || $cambio_estado == 1) {
            $response = [
                'error' => 0,
                'msn'   => $this->ion_auth->messages(),
                'csrf'  => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        } else {
            if ($this->ion_auth->errors()) {
                $error_msn =  $this->ion_auth->errors();
            } else {
                $error_msn = 'No se han realizado cambios en los datos del entrenador';
            }
            $response = [
                'error'     => 1,
                'error_msn' => $error_msn,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
    }




    public function ver_usuario($user_id)
    {
        $user = $this->utilidades->user($user_id);
        $data['page_header']    = 'Perfil de ' . $user->first_name . ' ' . $user->last_name;
        $data['view']     = 'gestion/entrenador_perfil';
        $data['user']     = $user;
        $data['clubs']     = $this->database->userClub();
        $data['js_files'] = [assets_url() . 'admin/js/vistas/entrenador_perfil.js'];
        show($data);
    }

    public function ver_usuario_fetch()
    {
        isAjax();
        $this->form_validation->set_rules('user_id', 'ID usuario', 'trim|required');
        validForm();
        $user_id = $this->input->post('user_id');

        $user = $this->database->buscarDato('users', 'id', $user_id);
        if (!$user) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Usuarios no encontrado.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            echo json_encode($response);
            exit();
        }
        $response = [
            'error'    => 0,
            'data'      => ['responsable' => $user],
            'csrf'     => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
}
/*
SIN USO

    public function usuarios()
    {
        validUrl();
        $data['page_header']    = 'Usuarios / Equipos';
        $data['table_button']   = [
            'content' => '<i class="fa fa-plus mr-3"></i> Nuevo usuario',
            'href' => base_url().'equipos/nuevo-usuario',
            'extra' => [
                'data-tooltip' => true, 
                'title' => 'Crear nuevo usuario',
                'data-original-title' => 'Crear nuevo usuario',
                'class' => 'btn btn-primary ml-auto'
            ]
        ];
        $data['default_filter'] = '5';
        $data['view']           = 'gestion/common/tabla_datatable';
        $data['js_files']       = [assets_url() . 'admin/js/vistas/equipos_usuarios.js'];
        show($data);
    }
    public function getUsuarios($columna = null, $valor = null)
    {
        $this->load->library('Datatable');
        $tabla  = 'users';
        $campos = [
            $tabla . '.id',
            $tabla . '.first_name',
            $tabla . '.last_name',
            $tabla . '.phone',
            $tabla . '.email',
            $tabla . '.active',
            'clubs.nombre',
            'clubs.club_id',
        ];
        $join     = [
            'users_groups' => $tabla .'.id = users_groups.user_id',
            'clubs' => $tabla .'.id = clubs.user_id',
        ];
        $add_rule = [
            "group_by" => $tabla . ".id",
        ];
        $where = [];
        if($this->input->get('rol') != ''){         
            $groups = explode('|', $this->input->get('rol'));
            if(count($groups) > 0){
                $add_rule['group_start'] = '';
                foreach ($groups as $key => $value) {
                    //echo $key.'-'.$value;
                    if($key === 0){
                        $add_rule['where'] = ['users_groups.group_id',$value];
                    }else{
                        $add_rule['or_where'][] = ['users_groups.group_id',$value];
                    }
                }
                $add_rule['group_end'] = '';
            }else{
                $where['users_groups.group_id'] = $this->input->get('rol');
            }
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

    public function nuevo_usuario()
    {
        validUrl();
        $data['page_header']    = 'Crear nuevo entrenador';
        $data['view']     = 'gestion/entrenador_nuevo';
        $data['js_files'] = [assets_url() . 'admin/js/vistas/entrenador_nuevo.js'];
        show($data);
    }
    public function nuevo_equipo_form()
    {
        isAjax();

        $this->form_validation->set_rules('nombre', 'Nombre', 'trim|required');
        $this->form_validation->set_rules('descripcion', 'Descripcion', 'trim|required');
        $this->form_validation->set_rules('direccion', 'Dirección', 'trim|required');
        $this->form_validation->set_rules('telefono', 'Teléfono', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Entrenador', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[clubs.email]', ['is_unique' => 'El email ya está en uso por otro club']);

        validForm(); else {
            $datos_club = [
                'nombre' => $this->input->post('nombre'),
                'slug' => $this->utilidades->rand_unique('clubs', 'slug', 8, TRUE),
                'user_id' => $this->input->post('user_id'),
                'descripcion'  => $this->input->post('descripcion'),
                'direccion'  => $this->input->post('direccion'),
                'email'        => $this->input->post('email'),
                'telefono'      => $this->input->post('telefono'),
                'estado'      => ($this->input->post('estado') == 'OK') ? 1 : 0,
            ];
            $club = $this->database->insert('clubs', $datos_club);
            if ($club) {
                $redirect = $_SERVER['HTTP_REFERER'];
                $response = [
                    'error'    => 0,
                    'msn'      => 'Club o escuela creado',
                    'redirect' => $redirect,
                    'csrf'     => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            } else {
                $response = [
                    'error'     => 1,
                    'error_msn' => $this->db->error_db(),
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                echo json_encode($response);
                exit();
            }
        }
    }
*/
