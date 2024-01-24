<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Competiciones extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->base = 'gestion/template';
    }

    public function gestion($competicion_torneo_id)
    {
        adminPage();
        $data = [];
        // carga la ficha completa del torneo, con fechas de los torneos
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        if ($competicion->torneo_id > 0) {
            $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
            if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
                show_404();
            }
            $data['torneo'] = $torneo;
        }
        $inscripciones = $this->database->inscritosCompeticion($competicion_torneo_id);
        $data['view'] = ['gestion/competiciones/tablerogeneral'];
        $data['deportistas'] = $this->database->getDeportistas();
        $data['competicion'] = $competicion;
        $data['inscripciones'] = $inscripciones;
        $data['ordenparticipacion'] = $this->database->inscritosOrdenCompeticion($competicion_torneo_id);
        $data['page_header']    =   (isset($torneo)) ? $torneo->titulo . ': Competición' : $competicion->categoria . ' ' . $competicion->modalidad;
        $data['css_files']       = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            assets_url() . 'plugins/jquery.gracket/style.css',
        ];
        $data['js_files']       = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            assets_url() . 'plugins/jquery.gracket/jquery.gracket.js',
            assets_url() . 'admin/js/vistas/competicionestorneosj3.js',
        ];
        show($data);
    }

    // KATA Y KUMITE
    public function mesa($competicion_torneo_id = '', $tipo = 'grupos')
    {
        asistentePage();
        $data = [];
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        if ($competicion->estado > 1 && $this->user->group->id > 3) {
            show_error('La competicion ha finalizado');
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['competicion'] = $competicion;
        //$data['view'] = ['gestion/competiciones/mesa'];

        $data['ordenparticipacion'] = $this->database->inscritosOrdenCompeticion($competicion_torneo_id);
        if($competicion->tipo == 'puntos'){
            $data['view'] = ['gestion/competiciones/mesakata'];
            $data['rondaspuntos'] = $this->database->getrondaskata($competicion_torneo_id);
            $data['finalistas'] = $this->database->finalKata($competicion_torneo_id);
            $data['js_files']       = [
                assets_url() . 'admin/js/vistas/mesakata.js',
            ];
        }
        if($competicion->tipo == 'liguilla'){
            $data['view'] = ['gestion/competiciones/mesaliguilla'];
            $data['tipo'] = $tipo;
            $matches = $this->database->getMatchesTree($competicion_torneo_id);
            $eliminatorias = $this->database->getEliminatoriasTree($competicion_torneo_id);
            $data['matches'] = $matches;
            $data['eliminatorias'] = $eliminatorias;
            $data['js_files']       = [
                assets_url() . 'plugins/moment.min.js',
                assets_url() . 'plugins/ezcountimer/ez.countimer.js',
                assets_url() . 'plugins/jquery.gracket/jquery.gracket.js',
                assets_url() . 'admin/js/vistas/mesacompeticionkumite.js',
            ];
        }

        if($competicion->tipo == 'eliminatoria'){
            $data['view'] = ['gestion/competiciones/mesaeliminatoria'];
            $matches = $this->database->getMatchesTree($competicion_torneo_id);
            $eliminatorias = $this->database->getEliminatoriasTree($competicion_torneo_id);
            $data['matches'] = $matches;
            $data['eliminatorias'] = $eliminatorias;
            $data['js_files']       = [
                assets_url() . 'plugins/moment.min.js',
                assets_url() . 'plugins/ezcountimer/ez.countimer.js',
                assets_url() . 'plugins/jquery.gracket/jquery.gracket.js',
                assets_url() . 'admin/js/vistas/mesacompeticionkumite.js',
            ];
        }

        //CREATE TABLE `playoff`.`puntosrey` (`puntos_id` INT(11) NOT NULL , `competicion_torneo_id` INT(11) NOT NULL , `user_id` INT(11) NOT NULL , `victorias` INT(3) NOT NULL , `empates` INT(3) NOT NULL , `derrotas` INT(3) NOT NULL , `puntos_favor` INT(5) NOT NULL , `total_combates` INT(3) NOT NULL , `puntos_total` INT(5) NOT NULL , `penalizaciones` INT(3) NOT NULL ) ENGINE = InnoDB;

        if($competicion->tipo == 'rey'){
            $grupos = [];
            foreach ($data['ordenparticipacion']['ordenados'] as $key => $part) {
                $grupos[$part->grupo][] = $part;
            }
            $data['grupos'] = $grupos;
            
            $data['view'] = ['gestion/competiciones/mesarey'];
            $data['tipo'] = $tipo;
            $matches = $this->database->getMatchesTree($competicion_torneo_id);
            $eliminatorias = $this->database->getEliminatoriasTree($competicion_torneo_id);
            $data['matches'] = $matches;
            $data['eliminatorias'] = $eliminatorias;
            $data['js_files']       = [
                assets_url() . 'plugins/moment.min.js',
                assets_url() . 'plugins/ezcountimer/ez.countimer.js',
                assets_url() . 'plugins/jquery.gracket/jquery.gracket.js',
                assets_url() . 'admin/js/vistas/mesacompeticionrey.js',
            ];
        }

        //printr($eliminatorias);
        $data['page_header']    =   $torneo->titulo . ': ' . $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;
        show($data);
    }

    public function clasificacionCompeticion($competicion_torneo_id = '')
    {
        asistenteCoachPage();
        $data = [];
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['competicion'] = $competicion;
        $data['tabactive'] = 'competiciones-tab';
        if($competicion->tipo == 'puntos'){
            $data['general'] = $this->database->clasificacionFinalKata($competicion_torneo_id, [1, 2, 3]);
            $data['view'] = ['gestion/competiciones/clasificacionkata'];
        }
        if($competicion->tipo == 'liguilla'){
            $data['clasificacion'] = $this->database->clasificacionGlobalKumite($competicion_torneo_id);
            $data['view'] = ['gestion/competiciones/clasificacionkumite'];
        }
        if($competicion->tipo == 'eliminatoria'){
            $data['clasificacion'] = $this->database->clasificacionGlobalKumite($competicion_torneo_id);
            $data['view'] = ['gestion/competiciones/clasificacionkumite'];
        }

        if($competicion->tipo == 'rey'){
            $data['clasificacion'] = $this->database->clasificacionGlobalRey($competicion_torneo_id);
            $data['view'] = ['gestion/competiciones/clasificacionrey'];
        }

        $data['page_header']    =   $torneo->titulo . ': ' . 'Clasificación' . $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;
        $data['js_files']       = [
            assets_url() . 'admin/js/vistas/clasificacioncompeticion.js',
        ];
        show($data);
    }

    public function eliminatoriaMatches($competicion_torneo_id){
        $matches = $this->database->getMatches($competicion_torneo_id);
        $rondas = [];
        foreach ($matches as $key => $match) {
            $ronda = $match->ronda;
            $array = [];
            if($ronda > 1){
                $prev = explode('|', $match->parent_rojo)[1];
                $name = ((substr($match->parent_rojo, -1) == '-') ? 'Perdedor '.$prev : 'Ganador '.$prev);
            }else{
                if($match->user_rojo > 0){
                    $user = $this->ion_auth->user($match->user_rojo)->row();
                    $name = $user->first_name.' '.$user->last_name;
                }else{
                    $name = '';
                }
            }
            $rojo = [
                'name' => $name,
                'id' => ($ronda > 1) ? $match->parent_rojo : $match->user_rojo,
                'inscripcion_id' => $match->inscripcion_rojo
            ];
            $array[]=$rojo;
            if($ronda > 1){
                $prev = explode('|', $match->parent_azul)[1];
                $name = ((substr($match->parent_azul, -1) == '-') ? 'Perdedor '.$prev : 'Ganador '.$prev);
            }else{
                if($match->user_azul > 0){
                    $user = $this->ion_auth->user($match->user_azul)->row();
                    $name = $user->first_name.' '.$user->last_name;
                }else{
                    $name = '';
                }
            }
           
            $azul = [
                'name' => $name,
                'id' => ($ronda > 1) ? $match->parent_azul : $match->user_azul,
                'inscripcion_id' => $match->inscripcion_azul
            ];
            $array[]=$azul;
           
            $rondas[$ronda][] = $array;
        }
        $return = [];
        foreach ($rondas as $key => $value) {
            $return[] = $value;
        }
       echo json_encode($return);
       
    }

    public function generar_tablero_competicion_tipo()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        $this->form_validation->set_rules('competicion_tipo', 'Tipo de competición', 'trim|required');
        validForm();

        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id',  $competicion->torneo_id);
        if (!isset($torneo) || $torneo == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Torneo no encontrado',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        // sería posible retornar por equipo cambiando la query
        $inscripciones = $this->database->inscritosCompeticion(input('competicion_torneo_id'));

        // valorar si es kata o kumite
        if(input('competicion_tipo') == 'puntos'){
            if ($competicion->modalidad == 'KATA' || $competicion->modalidad == 'kata') {
                // es kata. Se ordenan de forma aleatoria las inscripciones y se retornan los valores de ronda y jueces, por si los quiere variar
                shuffle($inscripciones);
                $response = [
                    'error'     => 0,
                    'tipo' => 'KATA',
                    'competicion_tipo' => input('competicion_tipo'),
                    'inscritos' => $inscripciones,
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                returnAjax($response);
            }else{
                $response = [
                    'error'     => 1,
                    'error_msn' => 'La competicion por puntos solo esta disponible para la modalidad de KATA',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                returnAjax($response);
            }
        }
        

        if(input('competicion_tipo') == 'liguilla'){
            // es kata. Se ordenan de forma aleatoria las inscripciones y se retornan los valores de ronda y jueces, por si los quiere variar
            shuffle($inscripciones);
            $response = [
                'error'     => 0,
                'tipo' => 'KUMITE',
                'competicion_tipo' => input('competicion_tipo'),
                'inscritos' => $inscripciones, //array_slice($inscripciones, 0, 24), //$inscripciones,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        if(input('competicion_tipo') == 'eliminatoria'){
            // es kata. Se ordenan de forma aleatoria las inscripciones y se retornan los valores de ronda y jueces, por si los quiere variar
            shuffle($inscripciones);
            $response = [
                'error'     => 0,
                'tipo' => 'KUMITE',
                'competicion_tipo' => input('competicion_tipo'),
                'inscritos' => $inscripciones, //array_slice($inscripciones, 0, 24), //$inscripciones,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        if(input('competicion_tipo') == 'liga'){
            // es kata. Se ordenan de forma aleatoria las inscripciones y se retornan los valores de ronda y jueces, por si los quiere variar
            shuffle($inscripciones);
            $response = [
                'error'     => 0,
                'tipo' => 'KUMITE',
                'competicion_tipo' => input('competicion_tipo'),
                'inscritos' => $inscripciones, //array_slice($inscripciones, 0, 24), //$inscripciones,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        if(input('competicion_tipo') == 'rey'){
            if(input('competicion_grupos') != '' && input('competicion_grupos') > 0){
                shuffle($inscripciones);
                $response = [
                    'error'     => 0,
                    'tipo' => 'KUMITE',
                    'competicion_tipo' => input('competicion_tipo'),
                    'competicion_grupos' => input('competicion_grupos'),
                    'inscritos' => $inscripciones,
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                returnAjax($response);

            }else{
                $response = [
                    'error'     => 1,
                    'error_msn' => 'El número mínimo de grupos es 1',
                    'csrf'      => $this->security->get_csrf_hash(),
                ];
                returnAjax($response);
            }
        }
    }

    public function pdfdoc($competicion_torneo_id)
    {
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }
        if ($competicion->tipo == 'puntos') {
            $data['torneo'] = $torneo;
            $data['competicion'] = $competicion;
            $data['ordenparticipacion'] = $this->database->inscritosOrdenCompeticion($competicion_torneo_id);
            $data['finalistas'] = $this->database->finalKata($competicion_torneo_id);
            $plantilla = 'pdfcompeticionkata';
        } elseif ($competicion->tipo == 'rey') {
            $plantilla = 'pdfcompeticionrey';
            $data['ordenparticipacion'] = $this->database->inscritosOrdenCompeticion($competicion_torneo_id);
            $grupos = [];
            foreach ($data['ordenparticipacion']['ordenados'] as $key => $part) {
                $grupos[$part->grupo][] = $part;
            }
            $data['grupos'] = $grupos;
            $matches = $this->database->getMatchesTree($competicion_torneo_id);
            $eliminatorias = $this->database->getEliminatoriasTree($competicion_torneo_id);
            $data['matches'] = $matches;
            $data['eliminatorias'] = $eliminatorias;
            $data['torneo'] = $torneo;
            $data['competicion'] = $competicion;
            $data['page_header']    =   $torneo->titulo . ': ' . $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;
        }else{
     
            $plantilla = 'pdfcompeticionkumite';
            $matches = $this->database->getMatchesTreePdf($competicion_torneo_id);
            $grupos = [];
            foreach ($matches as $key => $match) {
                $grupos[$match->grupo][] = $match;
            }
            $eliminatorias = $this->database->getEliminatoriasTree($competicion_torneo_id);
            $data['torneo'] = $torneo;
            $data['competicion'] = $competicion;
            $data['matches'] = $grupos;
            $data['eliminatorias'] = $eliminatorias;
            $data['page_header']    =   $torneo->titulo . ': ' . $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;
        }

        $this->load->library('pdf');
        $html     = $this->load->view($plantilla, $data, true);
        $filename = $competicion->modalidad . '_' . $competicion->categoria . '_' . $competicion->nivel;
        $genero = ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto');
        $filename = $filename . '_' . $genero;
        $this->pdf->generate($html, $filename, true, 0);
    }

    public function pdfdoccombates()
    {
       
            $plantilla = 'pdfcompeticionreycombates';
            $data['page_header']    =   'Registro de combates';
        

        $this->load->library('pdf');
        $html     = $this->load->view($plantilla, $data, true);
        $filename = 'hoja_combates_rey_de_la_pista';
        $this->pdf->generate($html, $filename, true, 0);
    }

    public function FinalizarCompeticion($competicion_torneo_id)
    {
        asistentePage();
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
        
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }
       
        if($competicion_torneo_id == 128){
            // copiar en competiciones 121 y 119
            // buscar las inscripciones y las copia

            // busca los matches cd la 128 y les copia 
            $this->db->where('competicion_torneo_id', 128);
            $inscripciones = $this->db->get('torneos_inscripciones')->result();
            foreach ($inscripciones as $key => $inscripcion) {
                unset($inscripcion->inscripcion_id);
                $inscripcion->competicion_torneo_id = 121;
                $this->db->insert('torneos_inscripciones', $inscripcion);
                $inscripcion->competicion_torneo_id = 119;
                $this->db->insert('torneos_inscripciones', $inscripcion);
            }

            $this->db->where('competicion_torneo_id', 128);
            $matches = $this->db->get('matches')->result();
            foreach ($matches as $key => $match) {
                unset($match->match_id);
                $match->competicion_torneo_id = 121;
                $this->db->insert('matches', $match);
                $match->competicion_torneo_id = 119;
                $this->db->insert('matches', $match);
            }

            $params = [
                'estado' => 2,
                'updatedAt' => date('Y-m-d H:i:s')
            ];
            $this->database->actualizar('torneos_competiciones', $params, 'competicion_torneo_id', 121);
            $params = [
                'estado' => 2,
                'updatedAt' => date('Y-m-d H:i:s')
            ];
            $this->database->actualizar('torneos_competiciones', $params, 'competicion_torneo_id', 119);

            $this->guardarClasificacionLM(121);
            $this->guardarClasificacionLM(119);
        }
        
        $this->guardarClasificacionLM($competicion_torneo_id);
        $params = [
            'estado' => 2,
            'updatedAt' => date('Y-m-d H:i:s')
        ];
        $this->database->actualizar('torneos_competiciones', $params, 'competicion_torneo_id', $competicion_torneo_id);
        
        redirect('torneos/competiciones/' . $torneo->slug, 'refresh');
    }

    public function guardarClasificacionLM($competicion_torneo_id)
    {
        asistentePage();
        $data = [];
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        if ($competicion->tipo == 'puntos') {
            $orden = $this->database->clasificacionFinalKata($competicion_torneo_id, [1, 2, 3]);
        } elseif ($competicion->tipo == 'liguilla' || $competicion->tipo == 'eliminatoria'|| $competicion->tipo == 'rey') {
            $orden = $this->database->clasificacionGlobalKumite($competicion_torneo_id);
        } else {
            show_404();
        }
        $i = 1;
        foreach ($orden as $key => $value) {
            $puntos =  ($i < 9) ? $this->config->item('puntoskata')[$i] : $this->config->item('puntoskata')[0];
            if ($competicion->tipo != 'puntos') {
                $puntos = $puntos + (5 * $value->ganados) + $value->puntos;
            }
            if($competicion->torneo_id == 4 || $competicion->torneo_id == 5){
                $puntos = $puntos * 2;
            }

            if($competicion->torneo_id == 5){
                $puntos = ($i == 0) ? 500 : $puntos;
            }
            $datos = [
                'lm' => date('Y'),
                'user_id' => $value->user_id,
                'nombre' => $value->first_name,
                'apellidos' => $value->last_name,
                'torneo_id' => $competicion->torneo_id,
                'competicion_torneo_id' => $competicion_torneo_id,
                'categoria' => $competicion->categoria,
                'modalidad' => $competicion->modalidad,
                'genero' => $competicion->genero,
                'nivel' => $competicion->nivel,
                'posicion' => $i,
                'puntos ' => $puntos,
            ];

            $this->db->where('user_id', $value->user_id);
            $this->db->where('competicion_torneo_id', $competicion_torneo_id);
            $ya_esta = $this->db->get('puntos_liga_municipal')->result();
            //printr($ya_esta);
            if (count($ya_esta) > 0) {
                $esta = $ya_esta[0];
                $this->database->actualizar('puntos_liga_municipal', $datos, 'lm_id', $esta->lm_id);
            } else {
                $this->database->insert('puntos_liga_municipal',  $datos);
            }
            $i++;
        }
    }
    
    // sorteo competicion tipo puntos
    public function guardar_orden_competicion()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        $this->form_validation->set_rules('orden_inscripciones', 'Orden inscripciones', 'trim|required');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        if ($competicion->modalidad != 'kata') {
            $response = [
                'error'     => 1,
                'error_msn' => 'Sorteo de competicion por puntos exclusivo para modalidad KATA',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id',  $competicion->torneo_id);
        if (!isset($torneo) || $torneo == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Torneo no encontrado',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $orden_array = explode(',', input('orden_inscripciones'));
        $orden = 1;
        foreach ($orden_array as $key => $inscripcion_id) {
            $this->database->actualizarorden(input('competicion_torneo_id'), $inscripcion_id, $orden);
            $orden++;
        }
        $params = [
            'tipo' => 'puntos',
            'estado' => 1,
            'updatedAt' => date('Y-m-d H:i:s')
        ];
        $this->database->actualizar('torneos_competiciones', $params, 'competicion_torneo_id', input('competicion_torneo_id'));
        $this->database->limpiar_tipo_competicion(input('competicion_torneo_id'));
        $response = [
            'error'     => 0,
            'msn' => 'Orden de participantes guardado',
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // sorteo competicion tipo liguilla y eliminatoria
    public function guardar_grupos_competicion()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        $this->form_validation->set_rules('inscripciones_id', 'Orden inscripciones', 'trim|required');
        $this->form_validation->set_rules('matches', 'Combates', 'trim|required');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id',  $competicion->torneo_id);
        if (!isset($torneo) || $torneo == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Torneo no encontrado',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $inscripciones_id = json_decode(input('inscripciones_id'));
        $orden = 1;
        foreach ($inscripciones_id as $key => $value) {
            if(!is_object($value)){
                $grupo = 0;
                $inscripcion_id = $value;
            }else{
                $grupo = $value->grupo;
                $inscripcion_id = $value->inscripcion_id;
            }   
            $this->database->actualizarorden(input('competicion_torneo_id'), $inscripcion_id, $orden, $grupo);
            $orden++;
        }
        $matches = json_decode(input('matches'));
        $this->database->actualizarmatches(input('competicion_torneo_id'), $matches);
        $params = [
            'estado' => 1,
            'updatedAt' => date('Y-m-d H:i:s'),
            'tipo' => input('tipo')
        ];
        // printr($params);
        $this->database->actualizar('torneos_competiciones', $params, 'competicion_torneo_id', input('competicion_torneo_id'));
        $response = [
            'error'     => 0,
            'msn' => 'Grupos y orden de participantes guardado',
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

    // competicion puntos
    public function guardar_puntos_kata()
    {
        isAjax();
        asistentePage();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Deportista', 'trim|required');
        $this->form_validation->set_rules('ronda', 'Ronda', 'trim|required');
        $this->form_validation->set_rules('juez', 'Juez', 'trim|required');
        $this->form_validation->set_rules('puntos', 'Puntos', 'trim|required');
        validForm();

        $this->utilidades->competicionEditable(input('competicion_torneo_id'));

        $guardado = $this->database->actualizarPuntosKata(input('competicion_torneo_id'), input('user_id'), input('ronda'), input('juez'), input('puntos'));
        if (is_numeric($guardado)) {
            $response = [
                'error'     => 0,
                'msn' => 'Guardado',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }
    // competicion puntos
    public function obtener_puntos_kata_competicion()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        validForm();
        $params = [
            'tabla' => 'puntoskata',
            'where' => [
                'competicion_torneo_id' => input('competicion_torneo_id')
            ]
        ];
        $puntos = $this->database->getWhere($params);
        $response = [
            'error'     => 0,
            'puntos' => $puntos,
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // competicion puntos
    public function clasificacionKata()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        validForm();
        $competicion_torneo_id = input('competicion_torneo_id');
        $rondas_normal = $this->database->getrondaskata($competicion_torneo_id);
        $rondas = range(1, $rondas_normal);
        $clasificacion = $this->database->clasificacionKata(input('competicion_torneo_id'), $rondas);
        $response = [
            'error'     => 0,
            'clasificacion' => $clasificacion,
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // competicion puntos
    public function clasificadoskata()
    {
        isAjax();
        asistentePage();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        $this->form_validation->set_rules('users_id', 'Deportistas', 'trim|required');
        validForm();
        $users_id = explode(',', input('users_id'));
        $marcados = $this->database->actualizarFinalistasKata(input('competicion_torneo_id'), $users_id);
        if ($marcados) {
            $response = [
                'error'     => 0,
                'msn' => 'Finalistas guardados',
                'redirect' => 'refresh',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }
    // competicion puntos
    public function clasificacionFinalKata()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        validForm();

       

        $clasificacion = $this->database->clasificacionFinalKata(input('competicion_torneo_id'));
        $response = [
            'error'     => 0,
            'clasificacion' => $clasificacion,
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // competicion puntos
    public function verronda($competicion_torneo_id, $ronda)
    {
        $this->base = 'gestion/template_pantallacompleta';
        $data['competicion_torneo_id'] = $competicion_torneo_id;
        $data['js_files']       = [
            assets_url() . 'admin/js/vistas/verrondakata.js',
        ];
        show($data);
    }

    // competicion REY
    public function guardar_puntos_rey()
    {
        isAjax();
        asistentePage();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Deportista', 'trim|required');
        $this->form_validation->set_rules('valor', 'Valor', 'trim|required');
        $this->form_validation->set_rules('field', 'Campo', 'trim|required');
        validForm();
        $this->utilidades->competicionEditable(input('competicion_torneo_id'));

        $guardado = $this->database->actualizarPuntosRey(input('competicion_torneo_id'), input('user_id'), input('field'), input('valor'));
        if (is_numeric($guardado)) {
            $response = [
                'error'     => 0,
                'msn' => 'Guardado',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }
    // competicion puntos
    public function obtener_puntos_rey_competicion()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        validForm();
        $params = [
            'tabla' => 'puntosrey',
            'where' => [
                'competicion_torneo_id' => input('competicion_torneo_id')
            ]
        ];
        $deportistas = $this->database->getWhere($params);
        $response = [
            'error'     => 0,
            'deportistas' => $deportistas,
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

    public function clasificacionGrupoRey()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competicion ID', 'trim');
        $this->form_validation->set_rules('grupo', 'Grupo', 'trim');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $players = $this->database->clasificacionGrupoRey(input('competicion_torneo_id'), input('grupo'));
        //printr($players);
        $response = [
            'error' => 0,
            'users' => $players,
            'csrf'  => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    
    // matches
    public function getMatch()
    {
        asistentePage();
        isAjax();
        $this->form_validation->set_rules('match_id', 'Combate', 'trim|required');
        validForm();
        $match = $this->database->getMatch(input('match_id'));
        if ($match != FALSE) {
            $response = [
                'error'     => 0,
                'match' => $match,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'El combate no existe',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }
    // matches
    public function updateMatch()
    {
        asistentePage();
        isAjax();
        $this->form_validation->set_rules('match_id', 'Combate', 'trim|required');
        $this->form_validation->set_rules('estado', 'Estado', 'trim');
        $this->form_validation->set_rules('puntos_rojo', 'Puntos rojo', 'trim');
        $this->form_validation->set_rules('puntos_azul', 'Puntos azul', 'trim');
        $this->form_validation->set_rules('senshu', 'Senshu', 'trim');
        $this->form_validation->set_rules('hantei', 'Hantei', 'trim');
        $this->form_validation->set_rules('winner', 'Ganador', 'trim');
        validForm();
        $match = $this->database->getMatch(input('match_id'));
        if ($match == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'El combate no existe',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        $params = [];
        if ($this->input->post('estado') != '') {
            $params['estado'] = input('estado');
        }
        if ($this->input->post('puntos_rojo') != '') {
            $params['puntos_rojo'] = input('puntos_rojo');
        }
        if ($this->input->post('puntos_azul') != '') {
            $params['puntos_azul'] = input('puntos_azul');
        }
        if ($this->input->post('senshu') != '') {
            $params['senshu'] = input('senshu');
        }
        if ($this->input->post('hantei') != '') {
            $params['hantei'] = input('hantei');
        }
        if ($this->input->post('winner') != '') {
            $params['winner'] = input('winner');
        }
        if (count($params) < 1) {
            $response = [
                'error'     => 1,
                'error_msn' => 'No se han recibido datos.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $accion = $this->database->actualizar('matches', $params, 'match_id', input('match_id'));
        if ($accion == input('match_id')) {
            if ($match->grupo == 0) {
                $siguienteeliminatoria = $this->database->getEliminatoriaSiguiente($match);
                if ($siguienteeliminatoria != FALSE) {
                    $match = $this->database->getMatch(input('match_id'));
                    $posicionEliminatoria = $this->database->posicionEliminatoria($match);
                    if ($match->winner == $match->user_rojo) {
                        $inscripcion_winner = $match->inscripcion_rojo;
                        $user_lost = $match->user_azul;
                        $inscripcion_lost = $match->inscripcion_azul;
                    } else {
                        $inscripcion_winner = $match->inscripcion_azul;
                        $user_lost = $match->user_rojo;
                        $inscripcion_lost = $match->inscripcion_rojo;
                    }
                    $parent_text = 'r' . $match->ronda . '|' . $posicionEliminatoria;
                    $parent_lost = 'r' . $match->ronda . '|' . $posicionEliminatoria . '-';
                    foreach ($siguienteeliminatoria as $key => $siguienteelimin) {
                        if ($siguienteelimin->parent_rojo == $parent_text) {
                            $data = [
                                'user_rojo' => $match->winner,
                                'inscripcion_rojo' => $inscripcion_winner,
                                'updatedAt' => date('Y-m-d H:i:s')
                            ];
                            $this->database->actualizar('matches', $data, 'match_id', $siguienteelimin->match_id);
                        }
                        if ($siguienteelimin->parent_azul == $parent_text) {
                            $data = [
                                'user_azul' => $match->winner,
                                'inscripcion_azul' => $inscripcion_winner,
                                'updatedAt' => date('Y-m-d H:i:s')
                            ];
                            $this->database->actualizar('matches', $data, 'match_id', $siguienteelimin->match_id);
                        }

                        // perdedor
                        if ($siguienteelimin->parent_rojo == $parent_lost) {
                            $data = [
                                'user_rojo' => $user_lost,
                                'inscripcion_rojo' => $inscripcion_lost,
                                'updatedAt' => date('Y-m-d H:i:s')
                            ];
                            $this->database->actualizar('matches', $data, 'match_id', $siguienteelimin->match_id);
                        }
                        if ($siguienteelimin->parent_azul == $parent_lost) {
                            $data = [
                                'user_azul' => $user_lost,
                                'inscripcion_azul' => $inscripcion_lost,
                                'updatedAt' => date('Y-m-d H:i:s')
                            ];
                            $this->database->actualizar('matches', $data, 'match_id', $siguienteelimin->match_id);
                        }
                    }

                    $refresh = 1;
                }
            }

            $response = [
                'error'     => 0,
                'match' => $this->database->getMatch(input('match_id')),
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            if (isset($refresh) && $refresh == 1) {
                $response['redirect'] = 'refresh';
            }
            returnAjax($response);
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'No se ha actulalizado el combate. Los datos recibidos no cambian los datos existentes.',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }
    // competicion liguilla
    public function clasificacionGrupo()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competicion ID', 'trim');
        $this->form_validation->set_rules('grupo', 'Grupo', 'trim');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $players = $this->database->clasificacionGrupoKumite(input('competicion_torneo_id'), input('grupo'));
        //printr($players);
        $response = [
            'error' => 0,
            'users' => $players,
            'csrf'  => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // competicion liguilla
    public function guardarClasificacionGrupo()
    {
        asistentePage();
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competicion ID', 'trim');
        $this->form_validation->set_rules('grupo', 'Grupo', 'trim|required');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $players = [];
        if($competicion->tipo == 'liguilla'){
            $players = $this->database->clasificacionGrupoKumite(input('competicion_torneo_id'), input('grupo'));
        }

        if($competicion->tipo == 'rey'){
            $players = $this->database->clasificacionGrupoRey(input('competicion_torneo_id'), input('grupo'));
        }
        
        $matches = $this->database->getEliminatoriasGrupo(input('competicion_torneo_id'), input('grupo'));
        //printr($this->db->last_query());
        $cambios = 0;
        foreach ($matches as $key => $value) {
            if (strpos($value->parent_rojo, 'g' . input('grupo') . '|') !== FALSE) {
                $explode = explode('|', $value->parent_rojo);
                $posicion = end($explode);
                $player = $players[$posicion - 1];
                if (isset($player)) {
                    $params = [
                        'tabla' => 'torneos_inscripciones',
                        'where' => [
                            'deletedAt' => '0000-00-00 00:00:00',
                            'competicion_torneo_id' => input('competicion_torneo_id'),
                            'user_id' => $player->user_id
                        ]
                    ];
                    $inscripcion = $this->database->getWhere($params, 'nopage');
                    $inscripcion = $inscripcion[0];
                    $data = [
                        'user_rojo' => $player->user_id,
                        'inscripcion_rojo' => $inscripcion->inscripcion_id,
                        'updatedAt' => date('Y-m-d H:i:s')
                    ];
                    if ($this->database->actualizar('matches', $data, 'match_id', $value->match_id) == $value->match_id) {
                        $cambios++;
                    };
                }
            }
            if (strpos($value->parent_rojo, 'g|' . input('grupo')) !== FALSE) {
                $explode = explode('|', $value->parent_rojo);
                $posicion = end($explode);
                if (isset($players[$posicion - 1])) {
                    $player = $players[$posicion - 1];
                    if (isset($player)) {
                        $params = [
                            'tabla' => 'torneos_inscripciones',
                            'where' => [
                                'deletedAt' => '0000-00-00 00:00:00',
                                'competicion_torneo_id' => input('competicion_torneo_id'),
                                'user_id' => $player->user_id
                            ]
                        ];
                        $inscripcion = $this->database->getWhere($params, 'nopage');
                        $inscripcion = $inscripcion[0];
                        $data = [
                            'user_rojo' => $player->user_id,
                            'inscripcion_rojo' => $inscripcion->inscripcion_id,
                            'updatedAt' => date('Y-m-d H:i:s')
                        ];
                        if ($this->database->actualizar('matches', $data, 'match_id', $value->match_id) == $value->match_id) {
                            $cambios++;
                        };
                    }
                }
            }
            if (strpos($value->parent_azul, 'g' . input('grupo') . '|') !== FALSE) {
                $explode = explode('|', $value->parent_azul);
                $posicion = end($explode);
                $player = $players[$posicion - 1];
                if (isset($player)) {
                    $params = [
                        'tabla' => 'torneos_inscripciones',
                        'where' => [
                            'competicion_torneo_id' => input('competicion_torneo_id'),
                            'user_id' => $player->user_id
                        ]
                    ];
                    $inscripcion = $this->database->getWhere($params, 'nopage');
                    $inscripcion = $inscripcion[0];
                    $data = [
                        'user_azul' => $player->user_id,
                        'inscripcion_azul' => $inscripcion->inscripcion_id,
                        'updatedAt' => date('Y-m-d H:i:s')
                    ];
                    if ($this->database->actualizar('matches', $data, 'match_id', $value->match_id) == $value->match_id) {
                        $cambios++;
                    };
                }
            }
            if (strpos($value->parent_azul, 'g|' . input('grupo')) !== FALSE) {
                $explode = explode('|', $value->parent_azul);
                $posicion = end($explode);
                $player = $players[$posicion - 1];
                if (isset($player)) {
                    $params = [
                        'tabla' => 'torneos_inscripciones',
                        'where' => [
                            'competicion_torneo_id' => input('competicion_torneo_id'),
                            'user_id' => $player->user_id
                        ]
                    ];
                    $inscripcion = $this->database->getWhere($params, 'nopage');
                    $inscripcion = $inscripcion[0];
                    $data = [
                        'user_azul' => $player->user_id,
                        'inscripcion_azul' => $inscripcion->inscripcion_id,
                        'updatedAt' => date('Y-m-d H:i:s')
                    ];
                    if ($this->database->actualizar('matches', $data, 'match_id', $value->match_id) == $value->match_id) {
                        $cambios++;
                    };
                }
            }
        }
        // se buscan las eliminatorias que contengan m2 en algun parent
        $matchesconsegundos = $this->database->getEliminatoriasConSegundos(input('competicion_torneo_id'));
        // si existe, se buscan las clasificaciones, se buscan lso segundos, y se retornan sus ids.

        if (count($matchesconsegundos) > 0) {
            $segundosgrupos = $this->database->getSegundosClasificados(input('competicion_torneo_id'));
            usort($segundosgrupos, function ($a, $b) {
                $retval = $b->ganados <=> $a->ganados;
                if ($retval == 0) {
                    $retval = $b->puntos <=> $a->puntos;
                    if ($retval == 0) {
                        $retval = $b->senshu <=> $a->senshu;
                        if ($retval == 0) {
                            $retval = $b->hantei <=> $a->hantei;
                        }
                    }
                }
                return $retval;
            });
            foreach ($matchesconsegundos as $key => $match) {
                $params = [];
                $m2   = 'm2';
                $parent = $match->parent_rojo;
                $esrojo = strpos($parent, $m2);
                if ($esrojo !== false) {
                    $posicion = explode('|', $match->parent_rojo);
                    $posicion = $posicion[1] - 1;
                    $user = $segundosgrupos[$posicion];
                    $params['user_rojo'] = $user->user_id;
                    $params['inscripcion_rojo'] = $user->inscripcion_id;
                }
                $parent = $match->parent_azul;
                $esazul = strpos($parent, $m2);
                if ($esazul !== false) {
                    $posicion = explode('|', $match->parent_azul);
                    $posicion = $posicion[1] - 1;
                    $user = $segundosgrupos[$posicion];
                    $params['user_azul'] = $user->user_id;
                    $params['inscripcion_azul'] = $user->inscripcion_id;
                }
                if (count($params) > 0) {
                    $params['updatedAt'] = date('Y-m-d H:i:s');
                    $this->database->actualizar('matches', $params, 'match_id', $match->match_id);
                }
            }
        }
        // $players = $this->database->clasificacionGrupoKumite(input('competicion_torneo_id'), input('grupo'));
        if ($cambios > 0) {
            $response = [
                'error'     => 0,
                'msn' => 'Guardado',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        } else {
            $response = [
                'error'     => 1,
                'error_msn' => 'No se han realizado cambios en las eliinatorias dependientes',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }
    // competicion liguilla y eliminatoria
    public function eliminatoriasCompeticion()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competicion ID', 'trim');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $data = $this->database->eliminatoriasCompeticionKumite(input('competicion_torneo_id'));
        $response = [
            'error' => 0,
            'data' => $data,
            'csrf'  => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // competicion liguilla y eliminatoria
    public function getMatchesCompeticion()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competicion ID', 'trim');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        $matches = $this->database->getMatches(input('competicion_torneo_id'));
        foreach ($matches as $key => $match) {
            if ($match->user_rojo > 0) {
                $rojo = $this->ion_auth->user($match->user_rojo)->row();
                $club = $this->database->getUserClub($rojo->club_id)->nombre;
                $match->rojo = (object)[];
                $match->rojo->nombre = $rojo->first_name . ' ' . $rojo->last_name . '<br><small>' . $club . '</small>';
            }
            if ($match->user_azul > 0) {
                $azul = $this->ion_auth->user($match->user_azul)->row();
                $club = $this->database->getUserClub($azul->club_id)->nombre;
                $match->azul = (object)[];
                $match->azul->nombre = $azul->first_name . ' ' . $azul->last_name . '<br><small>' . $club . '</small>';
            }
        }
        $response = [
            'error' => 0,
            'data' => $matches,
            'csrf'  => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }
    // competicion liguilla y eliminatoria
    public function eliminatoriasCompeticionKumite($competicion_torneo_id = '')
    {
        asistenteCoachPage();
        $data = [];
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['competicion'] = $competicion;
        $data['tabactive'] = 'competiciones-tab';
        $data['matches'] = $this->database->getMatchesTree($competicion_torneo_id);
        $data['eliminatorias'] = $this->database->getEliminatoriasTree($competicion_torneo_id);
        $data['view'] = ['gestion/competiciones/cuadrokumite'];
        $data['page_header']    =   $torneo->titulo . ': ' . 'Clasificación' . $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;
        $data['css_files']       = [
            assets_url() . 'plugins/jquery.gracket/style.css',
        ];
        $data['js_files']       = [
            assets_url() . 'plugins/jquery.gracket/jquery.gracket.js',
            base_url() . 'assets/public/js/vistacompeticionkumite.js',
            assets_url() . 'admin/js/vistas/clasificacioncompeticion.js',
        ];
        show($data);
    }
    // funcion auxiliar
    public function reiniciar_competiciones($torneo_id)
    {
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $torneo_id);
        if (!isset($torneo) || $torneo == false) {
            show_404();
        }
        $data['page_header']    =  $torneo->titulo;
        $data['torneo'] = $torneo;
        if ($torneo->tipo != 2) {
            $competicioneskata = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KATA');
        }
        if ($torneo->tipo != 1) {
            $competicioneskumite = $this->database->getCompeticionesTorneo($torneo->torneo_id, 'KUMITE');
        }

        $data = [
            'grupo' => 0,
            'orden' => 0,
            'updatedAt' => date('Y-m-d H:i:s')
        ];
        $this->db->where('torneo_id', $torneo_id);
        $this->db->update('torneos_inscripciones', $data);
        if ($torneo->tipo != 2) {
            foreach ($competicioneskata as $key => $value) {
                $this->db->where('competicion_torneo_id', $value->competicion_torneo_id);
                $this->db->delete('puntoskata');
            }
        }
        if ($torneo->tipo != 1) {
            foreach ($competicioneskumite as $key => $value) {
                $this->db->where('competicion_torneo_id', $value->competicion_torneo_id);
                $this->db->delete('matches');
            }
        }
    }

    

    

    public function guardar_todas($torneo_id)
    {
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $torneo_id);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }

        $competiciones = $this->database->buscarDato('torneos_competiciones', 'torneo_id', $torneo_id);
        foreach ($competiciones as $key => $value) {
            if ($value->estado == 2 && $value->deletedAt == '0000-00-00 00:00:00') {
                $this->guardarClasificacionLM($value->competicion_torneo_id);
            }
        }
    }

    // ELIMINADAS
    /*

    // KATA
    public function mesakata($competicion_torneo_id = '')
    {
        asistentePage();
        $data = [];
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        if ($competicion->estado > 1 && $this->user->group->id > 3) {
            show_error('La competicion ha finalizado');
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }
        $data['torneo'] = $torneo;
        $data['competicion'] = $competicion;
        $data['ordenparticipacion'] = $this->database->inscritosOrdenCompeticion($competicion_torneo_id);
        $data['finalistas'] = $this->database->finalKata($competicion_torneo_id);
        $data['view'] = ['gestion/competiciones/mesakata'];
        $data['page_header']    =   $torneo->titulo . ': ' . $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;

        $data['js_files']       = [
            assets_url() . 'admin/js/vistas/mesakata.js',
        ];
        show($data);
    }

    public function generar_tablero_competicion()
    {
        adminPage();
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id',  $competicion->torneo_id);
        if (!isset($torneo) || $torneo == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Torneo no encontrado',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        // sería posible retornar por equipo cambiando la query
        $inscripciones = $this->database->inscritosCompeticion(input('competicion_torneo_id'));
        // valorar si es kata o kumite
        if ($competicion->modalidad == 'KATA' || $competicion->modalidad == 'kata') {
            // es kata. Se ordenan de forma aleatoria las inscripciones y se retornan los valores de ronda y jueces, por si los quiere variar
            shuffle($inscripciones);
            $response = [
                'error'     => 0,
                'tipo' => 'KATA',
                'inscritos' => $inscripciones,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }

        if ($competicion->modalidad == 'KUMITE' || $competicion->modalidad == 'kumite') {
            // es kata. Se ordenan de forma aleatoria las inscripciones y se retornan los valores de ronda y jueces, por si los quiere variar
            shuffle($inscripciones);
            $response = [
                'error'     => 0,
                'tipo' => 'KUMITE',
                'inscritos' => $inscripciones, //array_slice($inscripciones, 0, 24), //$inscripciones,
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }

    public function mesakumite($competicion_torneo_id, $tipo = 'grupos')
    {
        asistentePage();
        $data = [];
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }
        // combates
        $matches = $this->database->getMatchesTree($competicion_torneo_id);
        $eliminatorias = $this->database->getEliminatoriasTree($competicion_torneo_id);
        // printr($eliminatorias);
        $data['torneo'] = $torneo;
        $data['competicion'] = $competicion;
        $data['matches'] = $matches;
        $data['eliminatorias'] = $eliminatorias;
        $data['view'] = ['gestion/competiciones/mesakumite'];
        $data['tipo'] = $tipo;

        $data['page_header']    =   $torneo->titulo . ': ' . $competicion->modalidad . ' ' . $competicion->categoria . ' ' . $competicion->genero . ' ' . $competicion->nivel;

        $data['js_files']       = [
            assets_url() . 'plugins/moment.min.js',
            assets_url() . 'plugins/ezcountimer/ez.countimer.js',
            assets_url() . 'plugins/jquery.gracket/jquery.gracket.js',
            assets_url() . 'admin/js/vistas/mesacompeticionkumite.js',
        ];
        show($data);
    }

    public function printr($competicion_torneo_id)
    {
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if (!isset($competicion) || $competicion == false) {
            show_404();
        }
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }
        $inscripciones = $this->database->inscritosCompeticion($competicion_torneo_id);
        $data['torneo'] = $torneo;
        $data['competicion'] = $competicion;
        $data['inscripciones'] = $inscripciones;
        $data['ordenparticipacion'] = $this->database->inscritosOrdenCompeticion($competicion_torneo_id);
        $matches = $this->database->getMatchesTree($competicion_torneo_id);
        $data['matches'] = $matches;
        // valorar si es kata o kumite
        //printr($ordenparticipacion);
        $this->load->library('pdf');
        // buscar los datos del pedido para ver si se usa plantilla
        $plantilla = 'pdfcompeticion';
        $html     = $this->load->view($plantilla, $data, true);
        $filename = $competicion->modalidad . '_' . $competicion->categoria . '_' . $competicion->nivel;
        $genero = ($competicion->genero == 'M') ? 'Masculino' : (($competicion->genero == 'F') ? 'Femenino' : 'Mixto');
        $filename = $filename . '_' . $genero;

        $this->pdf->generate($html, $filename, true, 0, [0, 0, 600, 744]);
    }

    */
}
