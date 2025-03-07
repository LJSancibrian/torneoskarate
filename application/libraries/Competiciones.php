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
        $torneo = $this->database->buscarDato('torneos', 'torneo_id', $competicion->torneo_id);
        if (!isset($torneo) || $torneo == false || $torneo->deletedAt != '0000-00-00 00:00:00') {
            show_404();
        }
        $inscripciones = $this->database->inscritosCompeticion($competicion_torneo_id);
        // valorar si es kata o kumite
        if ($competicion->modalidad == 'KATA' || $competicion->modalidad == 'kata') {
            $data['view'] = ['gestion/competiciones/tablerokata'];
        } else {
            $data['view'] = ['gestion/competiciones/tablerokumite'];
        }
        $data['torneo'] = $torneo;
        $data['competicion'] = $competicion;
        $data['inscripciones'] = $inscripciones;
        $data['ordenparticipacion'] = $this->database->inscritosOrdenCompeticion($competicion_torneo_id);
        $data['page_header']    =   $torneo->titulo . ': Competición';
        $data['css_files']       = [
            assets_url() . 'plugins/jquery.gracket/style.css',
        ];
        $data['js_files']       = [
            assets_url() . 'plugins/jquery.gracket/jquery.gracket.js',
            assets_url() . 'admin/js/vistas/competicionestorneos.js',
        ];
        show($data);
    }

    // KATA
    public function mesakata($competicion_torneo_id = '')
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
                'error_msn' => 'Competcición no encontrada',
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
            // es kata. Se ordenan de forma aleatoria las inscripcionbes y se retornan los valores de ronda y jueces, por si los quiere variar
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
            // es kata. Se ordenan de forma aleatoria las inscripcionbes y se retornan los valores de ronda y jueces, por si los quiere variar
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
                'error_msn' => 'Competcición no encontrada',
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
            'estado' => 1,
            'updatedAt' => date('Y-m-d H:i:s')
        ];
        $this->database->actualizar('torneos_competiciones', $params, 'competicion_torneo_id', input('competicion_torneo_id'));

        $response = [
            'error'     => 0,
            'msn' => 'Orden de participantes guardado',
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

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
                'error_msn' => 'Competcición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }

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

    public function clasificacionKata()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        validForm();
        $clasificacion = $this->database->clasificacionKata(input('competicion_torneo_id'),[1,2]);
        $response = [
            'error'     => 0,
            'clasificacion' => $clasificacion,
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

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
                'error_msn' => 'Competcición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
    }

    public function clasificacionFinalKata()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competición', 'trim|required');
        validForm();
        $clasificacion = $this->database->clasificacionKata(input('competicion_torneo_id'),[3]);
        $response = [
            'error'     => 0,
            'clasificacion' => $clasificacion,
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

    public function verronda($competicion_torneo_id, $ronda)
    {
        $this->base = 'gestion/template_pantallacompleta';
        $data['competicion_torneo_id'] = $competicion_torneo_id;
        $data['js_files']       = [
            assets_url() . 'admin/js/vistas/verrondakata.js',
        ];
        show($data);
    }

    // KUMITE
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
                'error_msn' => 'Competcición no encontrada',
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
            $grupo = $value->grupo;
            $inscripcion_id = $value->inscripcion_id;
            $this->database->actualizarorden(input('competicion_torneo_id'), $inscripcion_id, $orden, $grupo);
            $orden++;
        }
        $matches = json_decode(input('matches'));
        $this->database->actualizarmatches(input('competicion_torneo_id'), $matches);
        $params = [
            'estado' => 1,
            'updatedAt' => date('Y-m-d H:i:s')
        ];
        $this->database->actualizar('torneos_competiciones', $params, 'competicion_torneo_id', input('competicion_torneo_id'));
        $response = [
            'error'     => 0,
            'msn' => 'Grupos y orden de participantes guardado',
            'csrf'      => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
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
            $siguienteeliminatoria = $this->database->getEliminatoriaSiguiente($match);
            if($siguienteeliminatoria != FALSE){
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
                $parent_lost = 'r' . $match->ronda . '|' . $posicionEliminatoria.'-';
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
            $response = [
                'error'     => 0,
                'match' => $this->database->getMatch(input('match_id')),
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            if(isset($refresh) && $refresh == 1){
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
                'error_msn' => 'Competcición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $players = $this->database->clasificacionGrupoKumite(input('competicion_torneo_id'), input('grupo'));
        $response = [
            'error' => 0,
            'users' => $players,
            'csrf'  => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }

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
                'error_msn' => 'Competcición no encontrada',
                'csrf'      => $this->security->get_csrf_hash(),
            ];
            returnAjax($response);
        }
        $players = $this->database->clasificacionGrupoKumite(input('competicion_torneo_id'), input('grupo'));
        $matches = $this->database->getEliminatoriasGrupo(input('competicion_torneo_id'), input('grupo'));
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
        }
        // se buscan las eliminatorias que contengan m2 en algun parent
        $matchesconsegundos = $this->database->getEliminatoriasConSegundos(input('competicion_torneo_id'));
        // si existe, se buscan las clasificaciones, se buscan lso segundos, y se retornan sus ids.
        
        if(count($matchesconsegundos) > 0){
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
                    $posicion = explode('|', $match->parent_azul);
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

    public function eliminatoriasCompeticion()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competicion ID', 'trim');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competcición no encontrada',
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

    public function getMatchesCompeticion()
    {
        isAjax();
        $this->form_validation->set_rules('competicion_torneo_id', 'Competicion ID', 'trim');
        validForm();
        $competicion = $this->database->getCompeticion(input('competicion_torneo_id'));
        if (!isset($competicion) || $competicion == FALSE) {
            $response = [
                'error'     => 1,
                'error_msn' => 'Competcición no encontrada',
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
                $match->rojo->nombre = $rojo->first_name . ' ' . $rojo->last_name.'<br><small>'.$club.'</small>';
            }
            if ($match->user_azul > 0) {
                $azul = $this->ion_auth->user($match->user_azul)->row();
                $club = $this->database->getUserClub($azul->club_id)->nombre;
                $match->azul = (object)[];
                $match->azul->nombre = $azul->first_name . ' ' . $azul->last_name.'<br><small>'.$club.'</small>';
            }
        }
        $response = [
            'error' => 0,
            'data' => $matches,
            'csrf'  => $this->security->get_csrf_hash(),
        ];
        returnAjax($response);
    }


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

    public function liga()
    {
        echo "
         <script>let teams = [
            'Tigers',
            'Foofels',
            'Drampamdom',
            'Lakebaka',
            'llopies'
          ]
          
          const roundRobin = (teams) => {
            let schedule = []
            let league = teams.slice()
            
            if (league.length % 2) {
              league.push('None')
            }
            
            let rounds = league.length
            
            for (let j=0; j<(rounds-1)*2; j ++) {
              schedule[j] = []
              for (let i=0; i<rounds/2; i++) {
                if (league[i] !== 'None' && league[rounds-1-i] !== 'None') {
                  if (j % 2 == 1) {
                    schedule[j].push([league[i], league[rounds-1-i]])
                  } else {
                    schedule[j].push([league[rounds-1-i], league[i]])
                  }
                }
              }
              league.splice(1, 0, league.pop())
            }
            return schedule
          }
          
          let leagueSchedule = roundRobin(teams)
          
          for (let p=0; p<leagueSchedule.length; p++) {
            console.log(leagueSchedule[p])
          }</script>";
    }
}
