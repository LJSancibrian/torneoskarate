<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route = [
    'default_controller' => 'Home',
    '404_override' => '',
    'translate_uri_dashes' => FALSE,
    'login' => 'Home/login',
    'logout' => 'Home/logout',
    'recuperar-contrasena' => 'Home/recordar_password',
    'recuperar-password/(:any)' => 'Home/recuperar_password_code/$1',

    'torneo' => 'Home/torneos',
    'torneo/(:any)' => 'Home/torneos/$1',
    'vercompeticion' => 'Home/vercompeticion',
    'vercompeticion/(:any)' => 'Home/vercompeticion/$1',
    'vercompeticion/(:any)/(:any)' => 'Home/vercompeticion/$1/$2',
    'verronda' => 'Competiciones/verronda/',
    'verronda/(:any)' => 'Competiciones/verronda/$1',
    'verronda/(:any)/(:any)' => 'Competiciones/verronda/$1/$2',

    //'ligamunicipal2022' => 'Home/LM2022',
    //'ligamunicipal2022/(:any)' => 'Home/compLM2022/$1',
    'clasificaciongrupo/(:any)' => 'Home/clasificaciongrupo/$1',
    'clasificacioncompeticiongrupo/(:any)' => 'Home/compLM2022/$1',

    'area-privada' => 'Area_privada',
    'mi-perfil' => 'Area_privada/perfil',
    'mi-equipo' => 'Equipos/vermiequipo',
    'entrenadores-equipo' => 'Equipos/entrenadores',
    'deportistas-equipo' => 'Equipos/deportistas',


    
    'gestion' => 'Gestion',

    'usuarios' => 'Usuarios',
    'usuarios/administradores' => 'Usuarios/administradores',
    'usuarios/auxiliares' => 'Usuarios/auxiliares',
    'usuarios/nuevo' => 'Usuarios/nuevo',
    'usuarios/editar' => 'Usuarios/editar',

    'equipos' => 'Equipos',
    'equipos/nuevo-equipo' => 'Equipos/nuevo_equipo',
    'equipos/ver' => 'Equipos/ver',
    'equipos/ver/(:any)' => 'Equipos/ver/$1',
    'equipos/ver/(:any)/(:any)' => 'Equipos/ver/$1/$2',
    'equipos/editar-equipo/(:any)' => 'Equipos/editar_equipo/$1',

    'equipos/usuarios' => 'Equipos/usuarios',
    'equipos/nuevo-usuario' => 'Equipos/nuevo_usuario',
    'equipos/ver-usuario/(:any)' => 'Equipos/ver_usuario/$1',
    'equipos/editar-usuario/(:any)' => 'Equipos/editar_usuario/$1',

    'equipos/deportistas' => 'Equipos/deportistas',
    'equipos/nuevo-deportista' => 'Equipos/nuevo_deportista',
    'equipos/ver-deportista/(:any)' => 'Equipos/ver_deportista/$1',
    'equipos/editar-deportista/(:any)' => 'Equipos/editar_deportista/$1',

    'equipos/entrenadores' => 'Equipos/entrenadores',
    'equipos/nuevoentrenadores' => 'Equipos/nuevo_entrenadores',
    'equipos/ver-entrenadores/(:any)' => 'Equipos/nuevo_entrenadores/$1',
    'equipos/editar-entrenadores/(:any)' => 'Equipos/editar_entrenadores/$1',

    'torneos' => 'Torneos',
    'torneos/nuevo' => 'Torneos/nuevo',
    'torneos/gestion' => 'Torneos/gestion',
    'torneos/gestion/(:any)' => 'Torneos/gestion/$1',
    'torneos/gestion/(:any)/(:any)' => 'Torneos/gestion/$1/$2',
    'torneos/editar-torneo/(:any)' => 'Torneos/editar_torneo/$1',
    'proximos-torneos' => 'Torneos/proximos',
    'torneos-finalizados' => 'Torneos/finalizados',
    'torneos/inscripcion' => 'Torneos/inscripcion',
    'torneos/inscripcion/(:any)' => 'Torneos/inscripcion/$1',




    'archivos' => 'Archivos',
    'archivos/categorias' => 'Archivos/categorias',
    'archivos/categoria_nueva' => 'Archivos/categoria_nueva',
    'archivos/nuevo' => 'Archivos/nuevo',
    'archivos/ver-archivo/(:any)' => 'Archivos/nuevo_archivo/$1',
    'archivos/editar-archivo/(:any)' => 'Archivos/editar_archivo/$1',

    'eventos' => 'Eventos',
    'eventos/categorias' => 'Eventos/categorias',
    'eventos/categoria_nueva' => 'Eventos/categoria_nueva',
    'eventos/nuevo' => 'Eventos/nuevo',
    'eventos/calendario' => 'Eventos/calendario',
    'eventos/ver-evento/(:any)' => 'Eventos/nuevo_evento/$1',
    'eventos/editar-evento/(:any)' => 'Eventos/editar_evento/$1',
];


