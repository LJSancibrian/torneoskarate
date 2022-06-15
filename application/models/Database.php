<?php defined('BASEPATH') or exit('No direct script access allowed');
class Database extends CI_Model
{

    public function __construct()
    {
    }

    private function paginate($param)
    {
        if (!isset($param['page'])) {
            $param['page'] = 1;
        }
        if (!isset($param['limit'])) {
            $param['limit'] = 5000;
        }

        if ($param['limit'] > 0) {
            $desde = $param['limit'] * ($param['page'] - 1);
            $this->db->limit($param['limit'], $desde);
        }
    }
    private function no_deleted($tablas = [])
    {
        if (count($tablas) < 1) {
            $this->db->where('deletedAt', '0000-00-00 00:00:00');
        } else {
            foreach ($tablas as $key => $tabla) {
                $this->db->where($tabla . '.deletedAt', '0000-00-00 00:00:00');
            }
        }
    }

    public function get($tabla)
    {
        return $this->db->get($tabla)->result();
    }

    public function getWhere($param, $nopage = '', $object = true)
    {
        // PAGINACIÓN
        if ($nopage == '') {
            $this->paginate($param);
        }

        if (isset($param['where']) && count($param['where']) > 0) {
            foreach ($param['where'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if (isset($param['order_by']) && count($param['order_by']) > 0) {
            foreach ($param['order_by'] as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($object == true) {
            return $this->db->get($param['tabla'])->result();
        } else {
            return $this->db->get($param['tabla'])->result_array();
        }
    }

    public function insert($tabla, $datos)
    {
        $id = $this->db->insert($tabla, $datos);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function actualizar($tabla, $datos, $id_field, $id)
    {
        $this->db->where($id_field, $id);
        $this->db->update($tabla, $datos);
        if ($this->db->affected_rows() > 0) {
            return $id;
        } else {
            return $this->db->error();
        }
    }

    public function buscarDato($tabla, $column, $value)
    {
        $this->db->where($column, $value);
        $row = $this->db->get($tabla);

        if ($row->num_rows() > 1) {
            return $row->result();
        } elseif ($row->num_rows() == 1) {
            return $row->row();
        } else {
            return false;
        }
    }

    public function getDeportistas()
    {
        $this->no_deleted(['clubs']);
        $this->db->select('users.id, users.first_name, users.last_name, clubs.nombre');
        $this->db->where('users.active', 1);
        $this->db->where('users_groups.group_id', 6);
        $this->db->join('users_groups', 'users.id = users_groups.user_id');
        $this->db->join('clubs', 'users.club_id = clubs.club_id');
        return $this->db->get('users')->result();
    }

    public function getClubs()
    {
        $this->no_deleted();
        return $this->db->get('clubs')->result();
    }

    public function getUserClub($club_id)
    {
        $this->no_deleted();
        $this->db->where('club_id', $club_id);
        return $this->db->get('clubs')->row();
    }

    public function getCompeticionesTorneo($torneo_id, $modalidad = '', $genero = '')
    {
        $this->no_deleted();
        if ($modalidad != '') {
            $this->db->where('torneos_competiciones.modalidad', $modalidad);
        }
        if ($genero != '') {
            $this->db->where('torneos_competiciones.genero', $genero);
            $this->db->order_by('categoria', 'asc');
            $this->db->order_by('nivel', 'asc');
        } else {
            $this->db->order_by('categoria', 'asc');
            $this->db->order_by('nivel', 'asc');
            $this->db->order_by('torneos_competiciones.genero', 'asc');
        }
        $this->db->where('torneos_competiciones.torneo_id', $torneo_id);
        $this->db->select('torneos_competiciones.*');

        return $this->db->get('torneos_competiciones')->result();
    }

    /*
    public function getCompeticionesTorneo($torneo_id, $modalidad = '', $genero = '')
    {
        $this->no_deleted();
        if ($modalidad != '') {
            $this->db->where('torneos_competiciones.modalidad', $modalidad);
        }
        if ($genero != '') {
            $this->db->where('torneos_competiciones.genero', $genero);
        }
        $this->db->where('torneos_competiciones.torneo_id', $torneo_id);
        $this->db->select('torneos_competiciones.*, CONCAT(torneos_categorias.categoria, " (", torneos_categorias.year, " años)") AS categoria_text, torneos_categorias.categoria');
        $this->db->join('torneos_categorias', 'torneos_categorias.t_categoria_id = 	torneos_competiciones.t_categoria_id');
        return $this->db->get('torneos_competiciones')->result();
    }
    */

    public function userClub()
    {
        $this->no_deleted();
        $this->db->where('user_id', $this->user->id);
        $club = $this->db->get('clubs')->result();
        if (count($club) == 1) {
            return $club[0];
        } else {
            return $club;
        }
    }

    public function clubUsers($club_id, $group_id)
    {
        $this->db->select('users.*');
        $this->db->where('club_id', $club_id);
        $this->db->where('active', 1);
        $this->db->where_in('users_groups.group_id', $group_id);
        $this->db->join('users_groups', 'users_groups.user_id = users.id');
        $users = $this->db->get('users')->result();
        return $users;
    }

    public function incripcionesClub($club_id, $torneo_id)
    {
        $this->db->select('u.id, u.first_name, u.last_name, c.modalidad, c.genero, c.peso, cat.categoria, t.titulo');
        $this->db->from('torneos_inscripciones i');
        $this->db->join('users u', 'i.user_id = u.id');
        $this->db->join('torneos_competiciones c', 'i.competicion_torneo_id = c.competicion_torneo_id');
        $this->db->join('torneos_categorias cat', 'cat.t_categoria_id = c.competicion_torneo_id');
        $this->db->join('torneos t', 't.torneo_id = c.torneo_id');
        $this->db->where('u.active', 1);
        $this->db->where('i.deletedAt', '0000-00-00 00:00:00');
        $this->db->where('c.deletedAt', '0000-00-00 00:00:00');
        $this->db->where('t.deletedAt', '0000-00-00 00:00:00');

        $inscripciones = $this->db->get()->result();
        return $inscripciones;
    }

    public function torneoIncripcionesClubDeportistas($club_id, $torneo_id)
    {
        $this->no_deleted();
        $this->db->select('torneos_inscripciones.*');
        $this->db->where('torneo_id', $torneo_id);
        $this->db->where('club_id', $club_id);
        $this->db->join('users', 'users.id = torneos_inscripciones.user_id');
        $inscripc = $this->db->get('torneos_inscripciones')->result();
        $incripciones = [];
        foreach ($inscripc as $key => $value) {
            $incripciones[$value->user_id][] = $value->competicion_torneo_id;
            if ($value->estado > 0) {
                $incripciones[$value->user_id]['blocked'] = $value->estado;
            }
        }
        return $incripciones;
    }

    public function torneoClubs($torneo_id)
    {
        $this->db->select('c.club_id, c.nombre, c.img');
        $this->db->from('torneos_inscripciones i');
        $this->db->join('users u', 'i.user_id = u.id');
        $this->db->join('clubs c', 'c.club_id = u.club_id');
        $this->db->where('i.torneo_id', $torneo_id);
        $this->db->where('u.active', 1);
        $this->db->where('i.deletedAt', '0000-00-00 00:00:00');
        $this->db->where('c.deletedAt', '0000-00-00 00:00:00');
        $this->db->group_by('c.club_id');
        $clubs = $this->db->get()->result();
        return $clubs;
    }

    public function torneoEntrenadores($torneo_id)
    {
        $this->db->select('c.club_id');
        $this->db->from('torneos_inscripciones i');
        $this->db->join('users u', 'i.user_id = u.id');
        $this->db->join('clubs c', 'c.club_id = u.club_id');
        $this->db->where('i.torneo_id', $torneo_id);
        $this->db->where('u.active', 1);
        //$this->db->where('i.estado', 1);
        $this->db->where('i.deletedAt', '0000-00-00 00:00:00');
        $this->db->where('c.deletedAt', '0000-00-00 00:00:00');
        $this->db->group_by('c.club_id');
        $clubs = $this->db->get()->result();
        $entrenadores = [];
        foreach ($clubs as $key => $club) {
            $this->db->where('users_groups.group_id', 5);
            $this->db->where('users.club_id', $club->club_id);
            $this->db->join('users_groups', 'users_groups.user_id = users.id');
            $entren = $this->db->get('users')->result();
            foreach ($entren as $k => $en) {
                $entrenadores[] = $en;
            }
        }
        return $entrenadores;
    }

    public function torneoInscripciones($torneo_id)
    {
        $this->db->select('u.first_name, u.last_name, u.usercode, c.nombre, i.user_id, i.inscripcion_id');
        $this->db->where('i.torneo_id', $torneo_id);
        $this->db->where('i.deletedAt', '0000-00-00 00:00:00');
        //$this->db->where('i.estado', 1);
        $this->db->where('u.active', 1);
        $this->db->join('users u', 'u.id = i.user_id');
        $this->db->join('clubs c', 'c.club_id = u.club_id');
        return $this->db->get('torneos_inscripciones i')->result();
    }

    public function torneoCategoriasInscripciones($torneo_id, $modalidad = '')
    {
        $this->db->select('competicion_torneo_id, torneos_competiciones.modalidad, (CASE WHEN genero = "M" THEN "Masculino" WHEN genero = "F" THEN "Femenino" ELSE "Mixto" END) AS genero, torneos_competiciones.nivel AS peso, categoria');
        $this->db->where('torneo_id', $torneo_id);
        $this->db->where('torneos_competiciones.deletedAt', '0000-00-00 00:00:00');
        if ($modalidad != '') {
            $this->db->where('torneos_competiciones.modalidad', $modalidad);
        }
        //$this->db->join('torneos_categorias', 'torneos_categorias.t_categoria_id = torneos_competiciones.t_categoria_id');
        $this->db->order_by('torneos_competiciones.modalidad', 'asc');
        //$this->db->order_by('torneos_categorias.t_categoria_id', 'asc');
        $this->db->order_by('torneos_competiciones.genero', 'desc');
        return $this->db->get('torneos_competiciones')->result();
    }

    public function torneoArchivos($torneo_id)
    {

       $this->db->where('item_id', $torneo_id);
       $this->db->where('item_rel', 'torneo');
       $this->db->where('estado', 'disponible');
       $this->db->where('deletedAt', '0000-00-00 00:00:00');
       $archivos = $this->db->get('documentos')->result();
       return $archivos;
    }

    public function getCompeticion($competicion_torneo_id)
    {
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        //$this->db->join('torneos_categorias', 'torneos_categorias.t_categoria_id = torneos_competiciones.t_categoria_id');
        $competicion = $this->db->get('torneos_competiciones')->row();
        return $competicion;
    }

    public function inscritosCompeticion($competicion_torneo_id)
    {
        $this->db->select('u.first_name, u.last_name, u.usercode, c.nombre, i.user_id, i.inscripcion_id');
        $this->db->where('i.competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('i.deletedAt', '0000-00-00 00:00:00');
        $this->db->where('i.estado', 1);
        $this->db->where('u.active', 1);
        $this->db->join('users u', 'u.id = i.user_id');
        $this->db->join('clubs c', 'c.club_id = u.club_id');
        return $this->db->get('torneos_inscripciones i')->result();
    }

    public function actualizarorden($competicion_torneo_id, $inscripcion_id, $orden, $grupo = 1)
    {
        // buscar si esxiste
        $data = [];
        $data = ['grupo' => $grupo, 'orden' => $orden, 'updatedAt' => date('Y-m-d H:i:s')];
        $this->db->where('inscripcion_id', $inscripcion_id);
        $this->db->update('torneos_inscripciones', $data);
    }

    public function inscritosOrdenCompeticion($competicion_torneo_id)
    {
        $this->db->select('u.first_name, u.last_name, u.usercode, c.nombre, i.user_id, i.inscripcion_id, i.grupo, i.orden');
        $this->db->where('i.competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('i.deletedAt', '0000-00-00 00:00:00');
        $this->db->where('i.estado', 1);
        $this->db->where('u.active', 1);
        $this->db->where('i.orden >', 0);
        $this->db->join('users u', 'u.id = i.user_id');
        $this->db->join('clubs c', 'c.club_id = u.club_id');
        $this->db->order_by('grupo', 'asc');
        $this->db->order_by('orden', 'asc');
        $orden =  $this->db->get('torneos_inscripciones i')->result();

        $this->db->select('u.first_name, u.last_name, u.usercode, c.nombre, i.user_id, i.inscripcion_id, i.grupo, i.orden');
        $this->db->where('i.competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('i.deletedAt', '0000-00-00 00:00:00');
        $this->db->where('i.estado', 1);
        $this->db->where('u.active', 1);
        $this->db->where('i.orden', 0);
        $this->db->join('users u', 'u.id = i.user_id');
        $this->db->join('clubs c', 'c.club_id = u.club_id');

        $noorden =  $this->db->get('torneos_inscripciones i')->result();

        return ['ordenados' => $orden, 'noordenados' => $noorden];
    }

    public function actualizarPuntosKata($competicion_torneo_id, $user_id, $ronda, $juez, $puntos)
    {
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('user_id', $user_id);
        $this->db->where('ronda', $ronda);
        $this->db->where('juez', $juez);
        $puntuacion = $this->db->get('puntoskata')->row();
        if (!isset($puntuacion)) {
            $data = [
                'competicion_torneo_id' => $competicion_torneo_id,
                'user_id' => $user_id,
                'ronda' => $ronda,
                'juez' => $juez,
                'puntos' => $puntos
            ];
            $this->db->insert('puntoskata', $data);
            return $this->db->insert_id();
        } else {
            $data = [
                'competicion_torneo_id' => $competicion_torneo_id,
                'user_id' => $user_id,
                'ronda' => $ronda,
                'juez' => $juez,
                'puntos' => $puntos
            ];
            $this->db->where('puntos_id', $puntuacion->puntos_id);
            $this->db->update('puntoskata', $data);
            return $puntuacion->puntos_id;
        }
    }

    public function clasificacionKata($competicion_torneo_id, $rondas)
    {
        $this->db->select('user_id, SUM(puntos) AS total, count(puntos_id) AS valoraciones, ROUND(AVG(puntos),2) AS media');
        $this->db->where('puntoskata.competicion_torneo_id', $competicion_torneo_id);
        $this->db->where_in('puntoskata.ronda', $rondas);
        $this->db->group_by('user_id');
        $this->db->order_by('total', 'DESC');
        $this->db->order_by('media', 'DESC');
        $clasificacion = $this->db->get('puntoskata')->result();
        foreach ($clasificacion as $key => $user) {
            $this->db->join('clubs c', 'c.club_id = users.club_id');
            $this->db->where('users.id', $user->user_id);
            $userd = $this->db->get('users')->row();
            $user->first_name = $userd->first_name;
            $user->last_name = $userd->last_name;
            $user->nombre = $userd->nombre;
            $user->first_name = $userd->first_name;

            $this->db->where('competicion_torneo_id', $competicion_torneo_id);
            $this->db->where('user_id', $user->user_id);
            $inscripcion = $this->db->get('torneos_inscripciones')->row();
            $user->inscripcion_id = $inscripcion->inscripcion_id;
        }
        return $clasificacion;
    }

    public function clasificacionFinalKata($competicion_torneo_id, $rondas)
    {
        $this->db->select('user_id, SUM(puntos) AS total, count(puntos_id) AS valoraciones, ROUND(AVG(puntos),2) AS media');
        $this->db->where('puntoskata.competicion_torneo_id', $competicion_torneo_id);
        $this->db->group_by('user_id');
        //$this->db->order_by('media', 'DESC');
        $clasificacion = $this->db->get('puntoskata')->result();

        foreach ($clasificacion as $key => $user) {
            // usuario y club
            $this->db->join('clubs c', 'c.club_id = users.club_id');
            $this->db->where('users.id', $user->user_id);
            $userd = $this->db->get('users')->row();
            $user->first_name = $userd->first_name;
            $user->last_name = $userd->last_name;
            $user->nombre = $userd->nombre;
            $user->first_name = $userd->first_name;
            // inscripcion
            $this->db->where('competicion_torneo_id', $competicion_torneo_id);
            $this->db->where('user_id', $user->user_id);
            $inscripcion = $this->db->get('torneos_inscripciones')->row();
            $user->inscripcion_id = $inscripcion->inscripcion_id;
            // rondas
            $user->total = 0;
            $user->media = 0;
            $user->valoraciones = 0;
            $user->rondas = [];
            foreach ($rondas as $key => $ronda) {
                $user->rondas[$ronda] = $this->getPuntosRondaKata($competicion_torneo_id, $ronda, $user->user_id);
            }
            foreach ($user->rondas as $key => $rondauser) {
                if (isset($rondauser)) {
                    $user->total = $user->total + $rondauser->total;
                    $user->valoraciones += $rondauser->valoraciones;
                }
            }
            if ($user->valoraciones > 0) {
                $user->media = round($user->total / $user->valoraciones, 2);
            }

            $puntos = $this->getPuntosordenadosKata($competicion_torneo_id, $user->user_id);
            $user->puntos_max = (isset($puntos[0])) ? $puntos[0] : 0;
            $user->puntos_max2 = (isset($puntos[1])) ? $puntos[1] : 0;
            $user->puntos_max3 = (isset($puntos[2])) ? $puntos[2] : 0;
        }

        usort($clasificacion, function ($a, $b) {
            $totalb = (isset($b->rondas[3]->total)) ? $b->rondas[3]->total : 0;
            $totala = (isset($a->rondas[3]->total)) ? $a->rondas[3]->total : 0;
            $retval =  $totalb <=> $totala;
            if ($retval == 0) {
                $retval = $b->total <=> $a->total;
                if ($retval == 0) {
                    $retval = $b->media <=> $a->media;
                    if ($retval == 0) {
                        $retval = $b->puntos_max <=> $a->puntos_max;
                        if ($retval == 0) {
                            $retval = $b->puntos_max2 <=> $a->puntos_max2;
                            if ($retval == 0) {
                                $retval = $b->puntos_max3 <=> $a->puntos_max3;
                                if ($retval == 0) {
                                    $retval = $b->puntos_max4 <=> $a->puntos_max4;
                                }
                            }
                        }
                    }
                }
            }

            return $retval;
        });

        return $clasificacion;
    }

    public function getPuntosRondaKata($competicion_torneo_id, $ronda, $user_id)
    {
        $this->db->select('user_id, SUM(puntos) AS total, count(puntos_id) AS valoraciones, ROUND(AVG(puntos),2) AS media');
        $this->db->where('puntoskata.competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('puntoskata.user_id', $user_id);
        $this->db->where_in('puntoskata.ronda', $ronda);
        $this->db->where('puntoskata.puntos >', 0);
        $this->db->group_by('user_id');
        return $this->db->get('puntoskata')->row();
    }
    public function getPuntosordenadosKata($competicion_torneo_id, $user_id)
    {
        $this->db->where('puntoskata.competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('puntoskata.user_id', $user_id);
        $this->db->order_by('puntos', 'ASC');
        $todos =  $this->db->get('puntoskata')->result();
        return $todos;
    }
    public function actualizarFinalistasKata($competicion_torneo_id, $users_id)
    {
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('ronda', 3);
        $this->db->delete('puntoskata');

        foreach ($users_id as $key => $user_id) {
            $data = [
                'competicion_torneo_id' => $competicion_torneo_id,
                'user_id' => $user_id,
                'ronda' => 3,
                'juez' => 0,
                'puntos' => 0
            ];
            $this->db->insert('puntoskata', $data);
        }
        return TRUE;
    }

    public function finalKata($competicion_torneo_id)
    {
        $this->db->select('first_name, last_name, usercode, c.nombre, i.user_id, i.inscripcion_id, i.grupo, i.orden, SUM(puntos) AS total, count(puntos_id) AS valoraciones, ROUND(AVG(puntos),2) AS media');
        $this->db->join('users', 'users.id = puntoskata.user_id');
        $this->db->join('clubs c', 'c.club_id = users.club_id');
        $this->db->join('torneos_inscripciones i', 'i.user_id = users.id');
        $this->db->where('puntoskata.competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('puntoskata.ronda', 3);
        $this->db->group_by('puntoskata.user_id');
        $this->db->order_by('total', 'DESC');
        $this->db->order_by('media', 'DESC');
        return $this->db->get('puntoskata')->result();
    }

    public function getMatches($competicion_torneo_id)
    {
        $this->no_deleted();
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->order_by('grupo', 'asc');
        $this->db->order_by('ncombate', 'asc');
        return $this->db->get('matches')->result();
    }

    public function getMatchesTree($competicion_torneo_id)
    {
        $this->no_deleted();
        $this->db->select('grupo');
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('grupo >', 0);
        $this->db->distinct('grupo');
        $grupos = $this->db->get('matches')->result();
        if ($grupos) {
            foreach ($grupos as $key => $grupo) {
                $this->no_deleted();
                $this->db->select('ronda');
                $this->db->where('grupo', $grupo->grupo);
                $this->db->where('competicion_torneo_id', $competicion_torneo_id);
                $this->db->distinct('ronda');
                $rondas = $this->db->get('matches')->result();
                $grupo->rondas = $rondas;
                foreach ($rondas as $key2 => $ronda) {
                    $this->no_deleted();
                    //$this->db->select('match_id, user_rojo, user_azul');
                    $this->db->where('ronda', $ronda->ronda);
                    $this->db->where('grupo', $grupo->grupo);
                    $this->db->where('competicion_torneo_id', $competicion_torneo_id);
                    $matches = $this->db->get('matches')->result();
                    $ronda->matches = $matches;
                    foreach ($matches as $key3 => $match) {
                        $rojo = $this->ion_auth->user($match->user_rojo)->row();
                        $azul = $this->ion_auth->user($match->user_azul)->row();
                        $clubrojo = $this->getUserClub($rojo->club_id)->nombre;
                        $clubazul = $this->getUserClub($azul->club_id)->nombre;
                        $match->rojo = (object)[];
                        $match->azul = (object)[];
                        $match->rojo->nombre = $rojo->first_name . ' ' . $rojo->last_name . '<br><small>' . $clubrojo . '</small>';;
                        $match->azul->nombre = $azul->first_name . ' ' . $azul->last_name . '<br><small>' . $clubazul . '</small>';;
                        /*$this->db->join('users')
                        $this->db->where('grupo', $grupo->grupo);
                        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
                        $matches = $this->db->get('matches')->result();
                        $ronda->matches = $matches;*/
                    }
                }
            }

            /*
            usort($grupos, function($a, $b) {
                $retval = $a->grupo <=> $b->grupo;
                if ($retval == 0) {
                    $retval = $a->ronda <=> $b->ronda;
                    if ($retval == 0) {
                        $retval = $a->ncombate <=> $b->ncombate;
                    }
                }
                return $retval;
            });
            */
            return $grupos;
        } else {
            return [];
        }
    }

    public function getMatchesTreePdf($competicion_torneo_id, $grupo = [])
    {
        $this->no_deleted();
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        if (!empty($grupo)) {
            $this->db->where_in('grupo', $grupo);
        } else {
            $this->db->where('grupo >', 0);
        }
        $this->db->order_by('grupo', 'asc');
        $this->db->order_by('ronda', 'asc');
        $matches = $this->db->get('matches')->result();
        foreach ($matches as $key3 => $match) {
            $rojo = $this->ion_auth->user($match->user_rojo)->row();
            $azul = $this->ion_auth->user($match->user_azul)->row();
            $clubrojo = $this->getUserClub($rojo->club_id)->nombre;
            $clubazul = $this->getUserClub($azul->club_id)->nombre;
            $match->rojo = (object)[];
            $match->azul = (object)[];
            $match->rojo->nombre = $rojo->first_name . ' ' . $rojo->last_name . '<br><small>' . $clubrojo . '</small>';
            $match->azul->nombre = $azul->first_name . ' ' . $azul->last_name . '<br><small>' . $clubazul . '</small>';
        }

        return $matches;
        /*if ($grupos) {
            foreach ($grupos as $key => $grupo) {
                $this->no_deleted();
                $this->db->select('ronda');
                $this->db->where('grupo', $grupo->grupo);
                $this->db->where('competicion_torneo_id', $competicion_torneo_id);
                $this->db->distinct('ronda');
                $rondas = $this->db->get('matches')->result();
                $grupo->rondas = $rondas;
                foreach ($rondas as $key2 => $ronda) {
                    $this->no_deleted();
                    //$this->db->select('match_id, user_rojo, user_azul');
                    $this->db->where('ronda', $ronda->ronda);
                    $this->db->where('grupo', $grupo->grupo);
                    $this->db->where('competicion_torneo_id', $competicion_torneo_id);
                    $matches = $this->db->get('matches')->result();
                    $ronda->matches = $matches;
                    foreach ($matches as $key3 => $match) {
                        $rojo = $this->ion_auth->user($match->user_rojo)->row();
                        $azul = $this->ion_auth->user($match->user_azul)->row();
                        $clubrojo = $this->getUserClub($rojo->club_id)->nombre;
                        $clubazul = $this->getUserClub($azul->club_id)->nombre;
                        $match->rojo = (object)[];
                        $match->azul = (object)[];
                        $match->rojo->nombre = $rojo->first_name . ' ' . $rojo->last_name . '<br><small>' . $clubrojo . '</small>';
                        $match->azul->nombre = $azul->first_name . ' ' . $azul->last_name . '<br><small>' . $clubazul . '</small>';
                    }
                }
            }
            return $grupos;
        } else {
            return [];
        }*/
    }

    public function getEliminatoriasTree($competicion_torneo_id)
    {
        $this->no_deleted();
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('grupo', 0);
        $this->db->distinct('ronda');
        $grupos = $this->db->get('matches')->result();
        //printr($grupos);
        $rounds = [];
        if ($grupos) {
            foreach ($grupos as $key => $grupo) {
                $this->no_deleted();
                $this->db->where('competicion_torneo_id', $competicion_torneo_id);
                $this->db->where('grupo', 0);
                $this->db->where('ronda', $grupo->ronda);
                $matches = $this->db->get('matches')->result();
                //printr($matches);
                foreach ($matches as $key3 => $match) {
                    if ($match->user_rojo > 0) {
                        $rojo = $this->ion_auth->user($match->user_rojo)->row();
                        $club = $this->getUserClub($rojo->club_id)->nombre;
                        $match->rojo = (object)[];
                        $match->rojo->nombre = $rojo->first_name . ' ' . $rojo->last_name . '<br><small>' . $club . '</small>';
                    }
                    if ($match->user_azul > 0) {
                        $azul = $this->ion_auth->user($match->user_azul)->row();
                        $club = $this->getUserClub($azul->club_id)->nombre;
                        $match->azul = (object)[];
                        $match->azul->nombre = $azul->first_name . ' ' . $azul->last_name . '<br><small>' . $club . '</small>';
                    }
                    /*$this->db->join('users')
                    $this->db->where('grupo', $grupo->grupo);
                    $this->db->where('competicion_torneo_id', $competicion_torneo_id);
                    $matches = $this->db->get('matches')->result();
                    $ronda->matches = $matches;*/
                }
                $rounds[$grupo->ronda] = $matches;
            }
            return $rounds;
        } else {
            return [];
        }
    }

    public function actualizarmatches($competicion_torneo_id, $matches)
    {   // HAY QUE COMPARAR CON LO QUE EXISTE Y SI LOM QUE EXISTE ES MENOR; ELIMINAR LOS QUE SOBREN
        $matches_old = $this->getMatches($competicion_torneo_id);
        // recorrer el array
        foreach ($matches as $key => $value) {
            // buscar el comabte en la tabla
            $this->db->where('competicion_torneo_id', $competicion_torneo_id);
            $this->db->where('ncombate', $value->ncombate);
            $match = $this->db->get('matches')->row();
            if ($match) {
                $value->updatedAt = date('Y-m-d H:i:s');
                $value->estado = 'pendiente';
                $this->db->where('match_id', $match->match_id);
                $this->db->update('matches', $value);
                $matches_new[] = $match->match_id;
            } else {
                $value->competicion_torneo_id = $competicion_torneo_id;
                $value->estado = 'pendiente';
                $this->db->insert('matches', $value);
                $matches_new[] = $this->db->insert_id();
            }
        }
        foreach ($matches_old as $key => $value) {
            if (!in_array($value->match_id, $matches_new)) {
                $this->db->where('match_id', $value->match_id);
                $this->db->delete('matches');
            }
        }
    }


    //SELECT first_name, last_name, nombre, SUM(puntos) AS total, count(puntos_id) AS valoraciones, ROUND(AVG(puntos),2) AS media FROM puntoskata JOIN users ON users.id = puntoskata.user_id JOIN clubs ON clubs.club_id = users.club_id WHERE competicion_torneo_id=9 GROUP BY puntoskata.user_id ORDER BY media DESC;

    public function getMatch($match_id)
    {
        $this->no_deleted();
        //$this->db->select('match_id, user_rojo, user_azul');
        $this->db->where('match_id', $match_id);
        $matches = $this->db->get('matches')->result();
        if (count($matches) > 0) {
            $match = $matches[0];
            if ($match->user_rojo > 0) {
                $rojo = $this->ion_auth->user($match->user_rojo)->row();
                $match->rojo = (object)[];
                $match->rojo->nombre = $rojo->first_name . ' ' . $rojo->last_name;
            }
            if ($match->user_azul > 0) {
                $azul = $this->ion_auth->user($match->user_azul)->row();
                $match->azul = (object)[];
                $match->azul->nombre = $azul->first_name . ' ' . $azul->last_name;
            }
            return $match;
        } else {
            return FALSE;
        }
    }


    //CREATE TABLE `accioneskumite` ( `accion_id` INT(21) NOT NULL AUTO_INCREMENT , `match_id` INT(11) NOT NULL , `accion` VARCHAR(25) NOT NULL , `user_id` INT(11) NOT NULL , `user_color` VARCHAR(25) NOT NULL , `time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `estado` INT(1) NOT NULL DEFAULT '1' , PRIMARY KEY (`accion_id`)) ENGINE = InnoDB;
    /*
    public function updateMatchActions($match_id, $delete = '')
    {
        $this->db->where('estado', 1);
        $this->db->where('match_id', $match_id);
        $acciones = $this->db->get('accioneskumite')->result();

        $puntos_rojo = 0;
        $senshu = '';
        $hantei = '';
        $puntos_azul = 0;
        $primer_punto = 0;
        foreach ($acciones as $key => $accion) {
            if($delete == ''){
                switch ($accion->accion) {
                    case 'rest':
                        if($accion->user_color == 'user_rojo'){
                            $puntos_rojo = $puntos_rojo - 1;
                        }else{
                            $puntos_azul = $puntos_azul - 1;
                        }
                        break;
                    case 'yuko':
                        if($accion->user_color == 'user_rojo'){
                            $puntos_rojo = $puntos_rojo + 1;
                        }else{
                            $puntos_azul = $puntos_azul + 1;
                        }
                        if($primer_punto == 0){
                            if($accion->user_color == 'user_rojo'){
                                $senshu = 'rojo';
                            }else{
                                $senshu = 'azul';
                            }
                        }
                        $primer_punto++;
                        break;

                    case 'wazari':
                        if($accion->user_color == 'user_rojo'){
                            $puntos_rojo = $puntos_rojo + 2;
                        }else{
                            $puntos_azul = $puntos_azul + 2;
                        }
                        if($primer_punto == 0){
                            if($accion->user_color == 'user_rojo'){
                                $senshu = 'rojo';
                            }else{
                                $senshu = 'azul';
                            }
                        }
                        $primer_punto++;
                        break;

                    case 'ippon':
                        if($accion->user_color == 'user_rojo'){
                            $puntos_rojo = $puntos_rojo + 3;
                        }else{
                            $puntos_azul = $puntos_azul + 3;
                        }
                        if($primer_punto == 0){
                            if($accion->user_color == 'user_rojo'){
                                $senshu = 'rojo';
                            }else{
                                $senshu = 'azul';
                            }
                        }
                        $primer_punto++;
                        break;

                    case 'senshu':
                        $senshu = $accion->user_color;
                        break;

                    case 'hantei': 
                        $hantei = $accion->user_color;
                        break;

                    case 'chuko':
                        break;
                    default:
                        # code...
                        break;
                }
            }else{
                $this->db->where('accion_id', $accion->accion_id);
                $this->db->delete('accioneskumite');
            }
        }
        
        if($delete != ''){
            $data = [
                'estado' => 'pendiente',
                'senshu' => '',
                'hantei' => '',
                'puntos_rojo' => 0,
                'puntos_azul' => 0,
                'tatami' => 0,
            ];
        }else{
            $data = [
                'puntos_rojo' => $puntos_rojo,
                'puntos_azul' => $puntos_azul,
                'senshu' => $senshu,
                'hantei' => $hantei,
                'updatedAt' => date('Y-m-d H:i:s')
            ];
        }
        $this->actualizar('matches', $data, 'match_id', $match_id);

        return $this->buscarDato('matches', 'match_id', $match_id);
    }
    */

    public function clasificacionGrupoKumite($competicion_torneo_id, $grupo)
    {
        $this->no_deleted(['torneos_inscripciones']);
        $this->db->select('torneos_inscripciones.inscripcion_id, torneos_inscripciones.user_id, users.first_name, users.last_name, clubs.nombre');
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('grupo', $grupo);
        $this->db->join('users', 'users.id = torneos_inscripciones.user_id');
        $this->db->join('clubs', 'users.club_id = clubs.club_id');
        $players = $this->db->get('torneos_inscripciones')->result();

        foreach ($players as $key => $player) {
            // lo primero, buscar los combates de ese usuario en ese grupo de esa competicion
            $this->db->select(
                'SUM(CASE WHEN user_rojo=' . $player->user_id . ' THEN puntos_rojo ELSE puntos_azul END) AS puntos,
                SUM(CASE WHEN user_rojo=' . $player->user_id . ' THEN puntos_azul ELSE puntos_rojo END) AS puntos_contra,
                SUM(CASE WHEN winner=' . $player->user_id . ' THEN 1 ELSE 0 END) AS ganados,
                SUM(CASE WHEN (user_rojo=' . $player->user_id . ' AND senshu="rojo") OR  (user_azul=' . $player->user_id . ' AND senshu="azul") THEN 1 ELSE 0 END) AS senshu,
                SUM(CASE WHEN (user_rojo=' . $player->user_id . ' AND hantei="rojo") OR  (user_azul=' . $player->user_id . ' AND hantei="azul") THEN 1 ELSE 0 END) AS hantei,
                '
            );
            $this->db->where('competicion_torneo_id', $competicion_torneo_id);
            $this->db->where('grupo', $grupo);
            $this->db->group_start();
            $this->db->where('user_rojo', $player->user_id);
            $this->db->or_where('user_azul', $player->user_id);
            $this->db->group_end();
            $totaluser = $this->db->get('matches')->row();
            $player->puntos = $totaluser->puntos;
            $player->puntos_contra = $totaluser->puntos_contra;
            $player->ganados = $totaluser->ganados;
            $player->senshu = $totaluser->senshu;
            $player->hantei = $totaluser->hantei;
        }
        usort($players, function ($a, $b) {
            $retval = $b->ganados <=> $a->ganados;
            if ($retval == 0) {
                $retval = $b->puntos <=> $a->puntos;
                if ($retval == 0) {
                    $retval = $a->puntos_contra <=> $b->puntos_contra;
                    if ($retval == 0) {
                        $retval = $b->senshu <=> $a->senshu;
                        if ($retval == 0) {
                            $retval = $b->hantei <=> $a->hantei;
                        }
                    }
                }
            }
            return $retval;
        });

        return $players;
    }

    public function eliminatoriasCompeticionKumite($competicion_torneo_id)
    {
        $this->no_deleted(['matches']);
        $this->db->select('matches.ronda');
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('grupo', 0);
        $this->db->order_by('ronda', 'asc');
        $this->db->distinct('ronda');
        $rondas_result = $this->db->get('matches')->result();
        $rondas = [];
        $nronda = 0;
        foreach ($rondas_result as $key => $value) {
            $this->no_deleted(['matches']);
            $this->db->select('matches.*');
            $this->db->where('competicion_torneo_id', $competicion_torneo_id);
            $this->db->where('grupo', 0);
            $this->db->where('ronda', $value->ronda);
            //$this->db->where('user_rojo >', 0);
            //$this->db->where('user_azul >', 0);
            $this->db->order_by('ronda', 'asc');
            $marches_result = $this->db->get('matches')->result();
            if (count($marches_result) > 0) {
                $ronda = [];
                //se recorren los combates
                foreach ($marches_result as $m => $mat) {
                    $match = [];
                    $player = [];
                    if ($mat->user_rojo != 0) {
                        $rojo = $this->ion_auth->user($mat->user_rojo)->row();
                        $player = [
                            "match_id" => $mat->match_id,
                            "id" => $mat->user_rojo,
                            "name" => $rojo->first_name . ' ' . $rojo->last_name,
                            "score" => $mat->puntos_rojo
                        ];
                    } else {
                        // valorar el parent rojo
                        $rojo = $this->getParentDataMatch($mat->match_id, 'rojo');
                        if (is_object($rojo)) {
                            $player = [
                                "match_id" => $mat->match_id,
                                'id' => $rojo->user_id,
                                "name" => $rojo->first_name . ' ' . $rojo->last_name,
                                'score' => 0,
                            ];
                        }
                    }
                    // se añade el luchador al combate
                    $match[] = (object)$player;
                    $player = [];
                    if ($mat->user_azul != 0) {
                        $azul = $this->ion_auth->user($mat->user_azul)->row();
                        $player = [
                            "match_id" => $mat->match_id,
                            "id" => $mat->user_azul,
                            "name" => $azul->first_name . ' ' . $azul->last_name,
                            "score" => $mat->puntos_azul
                        ];
                    } else {
                        // valorar el parent rojo
                        $azul = $this->getParentDataMatch($mat->match_id, 'azul');
                        $player = [
                            "match_id" => $mat->match_id,
                            "id" => $azul->user_id,
                            "name" => $azul->first_name . ' ' . $azul->last_name,
                            "score" => 0
                        ];
                    }
                    // se añade el luchador al combate
                    $match[] = (object)$player;
                    // se añade el combate a la ronda
                    $ronda[] = $match;
                }
                // se añade el total a las rondas
                $rondas[] = $ronda;
                $nronda++;
                if ($nronda == count($rondas_result) && count($marches_result) > 0) {
                    $final = [];
                    $ganador = [];
                    if ($mat->winner != 0) {
                        $winner = $this->ion_auth->user($mat->winner)->row();
                        $ganador = [
                            "id" => $winner->id,
                            "name" => $winner->first_name . ' ' . $winner->last_name,
                            "score" => ''
                        ];
                    } else {
                        $ganador = [
                            'id' => $mat->winner,
                            'name' => 'Ganador',
                            'score' => '',

                        ];
                    }
                    $final[] = (object)$ganador;
                    $ronda = [];
                    $ronda[] = $final;
                    $rondas[] = $ronda;
                }
            }
        }
        return $rondas;
    }

    public function getParentDataMatch($match_id, $color)
    {
        $match  = $this->getMatch($match_id);
        $parent = ($color == 'rojo') ? $match->parent_rojo : $match->parent_azul;
        if ($parent != '') {
            $parenttipo = $parent[0];
            if ($parenttipo == 'g') {
                $parenttipo_ = substr($parent, 1);
                $dat = explode('|', $parenttipo_);
                $grupo = $dat[0];
                $posicion = $dat[1] - 1;
                // calcular la clasificacion del grupo
                $clasificacion = $this->clasificacionGrupoKumite($match->competicion_torneo_id,  $grupo);
                $parentData = $clasificacion[$posicion];
                //return $parentData;
                return (object)[
                    'user_id' => 0,
                    'first_name' => 'Clasificado',
                    'last_name' => 'Grupo' . $posicion . '-' . $dat[1],
                ];
            } else {
                // buscar la eliminatoria previa
                $parenttipo_ = substr($parent, 1);
                $dat = explode('|', $parenttipo_);
                $grupo = 0;
                $ronda = $dat[0];

                $this->db->where('competicion_torneo_id', $match->competicion_torneo_id);
                $this->db->where('grupo', 0);
                $this->db->where('ronda', $ronda);
                $matches = $this->db->get('matches')->result();
                $match = $matches[$dat[1] - 1];
                // ganador
                if ($match->winner > 0) {
                    $winner = $this->ion_auth->user($match->winner)->row();
                    return (object)[
                        'user_id' => $match->winner,
                        'first_name' => $winner->first_name,
                        'last_name' => $winner->last_name,
                    ];
                } else {
                    return (object)[
                        'user_id' => 0,
                        'first_name' => 'Ganador',
                        'last_name' => 'R' . $ronda . '-' . $dat[1],
                    ];
                }
            }
        } else {
            return 2;
        }
    }

    public function getEliminatoriasGrupo($competicion_torneo_id, $grupo)
    {
        $this->no_deleted(['matches']);
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('grupo', 0);
        $this->db->group_start();
        $this->db->like('parent_rojo', 'g' . $grupo . '|');
        $this->db->or_like('parent_rojo', 'g|' . $grupo );
        $this->db->or_like('parent_azul', 'g' . $grupo . '|');
        $this->db->or_like('parent_azul', 'g|' . $grupo);
        $this->db->group_end();
        $matches = $this->db->get('matches')->result();
        return $matches;
    }

    public function getEliminatoriaSiguiente($match)
    {
        //obtener posicion en la ronda del propio combate
        $posicion = $this->posicionEliminatoria($match);

        $this->no_deleted(['matches']);
        $this->db->where('competicion_torneo_id', $match->competicion_torneo_id);
        $this->db->where('grupo', $match->grupo);
        $this->db->where('ronda', $match->ronda + 1);
        $this->db->group_start();
        $this->db->like('parent_rojo', 'r' . $match->ronda . '|' . $posicion);
        $this->db->or_like('parent_azul', 'r' . $match->ronda . '|' . $posicion);
        $this->db->group_end();
        $siguiente = $this->db->get('matches')->result();
        // printr($this->db->last_query());
        if (isset($siguiente)) {
            return $siguiente;
        } else {
            return FALSE;
        }
    }

    public function posicionEliminatoria($match)
    {
        $this->no_deleted(['matches']);
        $this->db->where('competicion_torneo_id', $match->competicion_torneo_id);
        $this->db->where('grupo', $match->grupo);
        $this->db->where('ronda', $match->ronda);
        $mismaronda = $this->db->get('matches')->result();

        $posicion = 1;
        $posicion_match = 0;
        foreach ($mismaronda as $key => $sibling) {
            if ($match->match_id == $sibling->match_id) {
                $posicion_match = $posicion;
                break;
            } else {
                $posicion++;
            }
        }
        return $posicion_match;
    }

    public function getEliminatoriasConSegundos($competicion_torneo_id)
    {
        $this->no_deleted(['matches']);
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->group_start();
        $this->db->like('parent_rojo', 'm2');
        $this->db->or_like('parent_azul', 'm2');
        $this->db->group_end();
        return $this->db->get('matches')->result();
    }

    public function getSegundosClasificados($competicion_torneo_id)
    {
        $this->no_deleted();
        $this->db->select('grupo');
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->where('grupo >', 0);
        $this->db->distinct('grupo');
        $grupos = $this->db->get('matches')->result();
        $segundos = [];
        foreach ($grupos as $key => $grupo) {
            $segundos[] = $this->clasificacionGrupoKumite($competicion_torneo_id, $grupo->grupo)[1];
        }
        return $segundos;
    }

    public function clasificacionKumite($competicion_torneo_id, $ids = '')
    {
        $this->db->where('grupo', 0);
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->order_by('ronda', 'desc');
        $this->db->order_by('parent_rojo', 'asc');
        $finales = $this->db->get('matches')->result();

        $clasificacion = [];
        $anadidos = [];
        foreach ($finales as $key => $value) {
            if ($value->winner > 0 && $value->user_rojo > 0 && $value->user_azul > 0) {
                $ganador = $this->ion_auth->user($value->winner)->row();
                if (!in_array($value->winner, $anadidos)) {
                    $clasificacion[] = (object) [
                        'first_name' => $ganador->first_name,
                        'last_name' => $ganador->last_name,
                        'nombre' => $this->getUserClub($ganador->club_id)->nombre,
                    ];
                    $anadidos[] = $value->winner;
                }
                if ($value->winner != $value->user_rojo) {
                    $segundo = $this->ion_auth->user($value->user_rojo)->row();
                } else {
                    $segundo = $this->ion_auth->user($value->user_azul)->row();
                }
                if (!in_array($segundo->id, $anadidos)) {
                    $clasificacion[] = (object)[
                        'first_name' => $segundo->first_name,
                        'last_name' => $segundo->last_name,
                        'nombre' => $this->getUserClub($segundo->club_id)->nombre,
                    ];
                    $anadidos[] = $segundo->id;
                }
            }
        }
        if ($ids == '') {
            return $clasificacion;
        } else {
            return $anadidos;
        }
    }

    public function clasificacionGlobalKumite($competicion_torneo_id)
    {
        $this->db->where('grupo', 0);
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        $this->db->order_by('ronda', 'desc');
        $this->db->order_by('parent_rojo', 'asc');
        $finales = $this->db->get('matches')->result();

        $eliminatorias_players = [];
        $anadidos = [];
        foreach ($finales as $key => $value) {
            if ($value->winner > 0 && $value->user_rojo > 0 && $value->user_azul > 0) {
                if (!in_array($value->winner, $anadidos)) {
                    $anadidos[] = $value->winner;
                    $player_id = $value->winner;
                    $player = $this->ion_auth->user($player_id)->row();
                    $club = $this->getUserClub($player->club_id);
                    $player->nombre = $club->nombre;
                    $player->user_id = $value->winner;
                    $this->db->select(
                        'SUM(CASE WHEN user_rojo=' . $player_id . ' THEN puntos_rojo ELSE puntos_azul END) AS puntos,
                        SUM(CASE WHEN user_rojo=' . $player_id . ' THEN puntos_azul ELSE puntos_rojo END) AS puntos_contra,
                        SUM(CASE WHEN winner=' . $player_id . ' THEN 1 ELSE 0 END) AS ganados,
                        SUM(CASE WHEN (user_rojo=' . $player_id . ' AND senshu="rojo") OR  (user_azul=' . $player_id . ' AND senshu="azul") THEN 1 ELSE 0 END) AS senshu,
                        SUM(CASE WHEN (user_rojo=' . $player_id . ' AND hantei="rojo") OR  (user_azul=' . $player_id . ' AND hantei="azul") THEN 1 ELSE 0 END) AS hantei,
                        '
                    );
                    $this->db->where('competicion_torneo_id', $competicion_torneo_id);
                    $this->db->group_start();
                    $this->db->where('user_rojo', $player_id);
                    $this->db->or_where('user_azul', $player_id);
                    $this->db->group_end();
                    $totaluser = $this->db->get('matches')->row();
                    $player->puntos = $totaluser->puntos;
                    $player->puntos_contra = $totaluser->puntos_contra;
                    $player->ganados = $totaluser->ganados;
                    $player->senshu = $totaluser->senshu;
                    $player->hantei = $totaluser->hantei;
                    $player->ronda = $value->ronda;
                    $eliminatorias_players[] = $player;
                }
                if ($value->winner != $value->user_rojo) {
                    $segundo =  $value->user_rojo;
                } else {
                    $segundo = $value->user_azul;
                }
                if (!in_array($segundo, $anadidos)) {
                    $anadidos[] = $segundo;
                    $player_id = $segundo;
                    $player = $this->ion_auth->user($player_id)->row();
                    $club = $this->getUserClub($player->club_id);
                    $player->nombre = $club->nombre;
                    $player->user_id = $segundo;
                    $this->db->select(
                        'SUM(CASE WHEN user_rojo=' . $player_id . ' THEN puntos_rojo ELSE puntos_azul END) AS puntos,
                        SUM(CASE WHEN user_rojo=' . $player_id . ' THEN puntos_azul ELSE puntos_rojo END) AS puntos_contra,
                        SUM(CASE WHEN winner=' . $player_id . ' THEN 1 ELSE 0 END) AS ganados,
                        SUM(CASE WHEN (user_rojo=' . $player_id . ' AND senshu="rojo") OR  (user_azul=' . $player_id . ' AND senshu="azul") THEN 1 ELSE 0 END) AS senshu,
                        SUM(CASE WHEN (user_rojo=' . $player_id . ' AND hantei="rojo") OR  (user_azul=' . $player_id . ' AND hantei="azul") THEN 1 ELSE 0 END) AS hantei,
                        '
                    );
                    $this->db->where('competicion_torneo_id', $competicion_torneo_id);
                    $this->db->group_start();
                    $this->db->where('user_rojo', $player_id);
                    $this->db->or_where('user_azul', $player_id);
                    $this->db->group_end();
                    $totaluser = $this->db->get('matches')->row();
                    $player->puntos = $totaluser->puntos;
                    $player->puntos_contra = $totaluser->puntos_contra;
                    $player->ganados = $totaluser->ganados;
                    $player->senshu = $totaluser->senshu;
                    $player->hantei = $totaluser->hantei;
                    $player->ronda = $value->ronda;
                    $eliminatorias_players[] = $player;
                }
            }
            
        }
        $finalistas = [];
        foreach ($eliminatorias_players as $key => $value) {
           if($key < 4){
                $finalistas[] =  $value;
               unset($eliminatorias_players[$key]);
           }
        }
        
        if(count($eliminatorias_players) > 0){
            usort($eliminatorias_players, function ($a, $b) {
                $retval = $b->ronda <=> $a->ronda;
                if ($retval == 0) {
                    $retval = $b->ganados <=> $a->ganados;
                    if ($retval == 0) {
                        $retval = $b->puntos <=> $a->puntos;
                        if ($retval == 0) {
                            $retval = $a->puntos_contra <=> $b->puntos_contra;
                            if ($retval == 0) {
                                $retval = $b->senshu <=> $a->senshu;
                                if ($retval == 0) {
                                    $retval = $b->hantei <=> $a->hantei;
                                }
                            }
                        }
                    }
                }
                return $retval;
            }); 
            $finalistas = array_merge($finalistas, $eliminatorias_players);
        }

        $this->no_deleted(['torneos_inscripciones']);
        $this->db->select('torneos_inscripciones.inscripcion_id, torneos_inscripciones.user_id, users.first_name, users.last_name, clubs.nombre');
        $this->db->where('competicion_torneo_id', $competicion_torneo_id);
        if(count($anadidos) > 0){
            $this->db->where_not_in('users.id', $anadidos);
        }
        $this->db->join('users', 'users.id = torneos_inscripciones.user_id');
        $this->db->join('clubs', 'users.club_id = clubs.club_id');
        $players = $this->db->get('torneos_inscripciones')->result();

        foreach ($players as $key => $player) {
            // lo primero, buscar los combates de ese usuario en ese grupo de esa competicion
            $this->db->select(
                'SUM(CASE WHEN user_rojo=' . $player->user_id . ' THEN puntos_rojo ELSE puntos_azul END) AS puntos,
                SUM(CASE WHEN user_rojo=' . $player->user_id . ' THEN puntos_azul ELSE puntos_rojo END) AS puntos_contra,
                SUM(CASE WHEN winner=' . $player->user_id . ' THEN 1 ELSE 0 END) AS ganados,
                SUM(CASE WHEN (user_rojo=' . $player->user_id . ' AND senshu="rojo") OR  (user_azul=' . $player->user_id . ' AND senshu="azul") THEN 1 ELSE 0 END) AS senshu,
                SUM(CASE WHEN (user_rojo=' . $player->user_id . ' AND hantei="rojo") OR  (user_azul=' . $player->user_id . ' AND hantei="azul") THEN 1 ELSE 0 END) AS hantei,
                '
            );
            $this->db->where('competicion_torneo_id', $competicion_torneo_id);
            $this->db->group_start();
            $this->db->where('user_rojo', $player->user_id);
            $this->db->or_where('user_azul', $player->user_id);
            $this->db->group_end();

            $totaluser = $this->db->get('matches')->row();

            $player->puntos = $totaluser->puntos;
            $player->puntos_contra = $totaluser->puntos_contra;
            $player->ganados = $totaluser->ganados;
            $player->senshu = $totaluser->senshu;
            $player->hantei = $totaluser->hantei;
            if(in_array($player->user_id, $anadidos)){
                $player->finalista = array_search($player->user_id, $anadidos);
            }else{
                $player->finalista = 100;
            }
        }
        usort($players, function ($a, $b) {
            $retval = $a->finalista <=> $b->finalista;
            if ($retval == 0) {
                $retval = $b->ganados <=> $a->ganados;
                if ($retval == 0) {
                    $retval = $b->puntos <=> $a->puntos;
                    if ($retval == 0) {
                        $retval = $a->puntos_contra <=> $b->puntos_contra;
                        if ($retval == 0) {
                            $retval = $b->senshu <=> $a->senshu;
                            if ($retval == 0) {
                                $retval = $b->hantei <=> $a->hantei;
                            }
                        }
                    }
                }
            }
            return $retval;
        });

        $return = array_merge($finalistas, $players);
        return $return;
    }

    public function getCompeticionesLM($year)
    {
        $this->db->where_in('torneo_id', $this->config->item('lm'.$year));
        $this->db->where('estado !=', 3);
        $this->db->where('deletedAt', '0000-00-00 00:00:00');
        $this->db->order_by('competicion_torneo_id', 'asc');
        return $this->db->get('torneos_competiciones')->result();
    }

    public function getSiblings($competicion_torneo_id, $array)
    {
        $this->db->where('sibling_id', $competicion_torneo_id);
        $this->db->where('deletedAt', '0000-00-00 00:00:00');
        $this->db->order_by('competicion_torneo_id', 'asc');
        $sibling_ids = $this->db->get('torneos_competiciones')->result();
        $array = [];
        $array[] = $competicion_torneo_id; 
        foreach ($sibling_ids as $key => $sibling_id) {
            $array[] = $sibling_id->competicion_torneo_id;
        }
        return $array;

       /* if($sibling_id){
            $array[] = $sibling_id->competicion_torneo_id;
            return $this->getSiblings($sibling_id->competicion_torneo_id, $array);
        }else{
            return $array;
        }*/
    }

    public function get_clasificacionLM($competicion_torneo_id)
    {
        // obtenemos los hermanos de esta competicion
        //printr($competicion_torneo_id);
        $ids = $this->getSiblings($competicion_torneo_id, [$competicion_torneo_id]);
        //printr($ids);

        $this->db->select('user_id, nombre, apellidos');
        $this->db->distinct('user_id');
        $this->db->where_in('competicion_torneo_id', $ids);
        $this->db->order_by('user_id', 'asc');
        $competidores = $this->db->get('puntos_liga_municipal')->result();
        foreach ($competidores as $key => $comp) {
            $j = 1;
            $total = 0;
            $max = 0;
            foreach ($ids as $k => $competicion_id) {
                $this->db->select('puntos');
                $this->db->where('user_id', $comp->user_id);
                $this->db->where('competicion_torneo_id', $competicion_id);
                $puntos = $this->db->get('puntos_liga_municipal')->row();
                $jornada = 'jornada_'.$j;
                $comp->$jornada = (isset($puntos)) ? $puntos->puntos : 0;
                $total = $total + $comp->$jornada;
                $max = ($max > $comp->$jornada) ? $max : $comp->$jornada;
                $j++;
            }
            $comp->club = $this->getUserClub($this->ion_auth->user($comp->user_id)->row()->club_id)->nombre;
            $comp->puntos_max = $max;
            $comp->total = $total;
        }

        usort($competidores, function ($a, $b) {
            $retval = $b->total <=> $a->total;
            if ($retval == 0) {
                $retval = $b->puntos_max <=> $a->puntos_max;
            }
            return $retval;
        });
        return $competidores;
    }


    public function limpiar_tipo_competicion($competicion_torneo_id){
        $competicion = $this->database->getCompeticion($competicion_torneo_id);
        if($competicion->tipo == 'puntos'){
            // se borran los matches con la competicion indicada
            $this->db->where('competicion_torneo_id', $competicion_torneo_id);
            $this->db->delete('matches');
        }
    }
}
