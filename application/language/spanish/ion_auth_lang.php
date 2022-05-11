<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:     Ion Auth Lang - Spanish
*
* Author:   Wilfrido García Espinosa
*           contacto@wilfridogarcia.com
*           @wilfridogarcia
*
* Location: https://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  05.04.2010
*
* Description:  Spanish language file for Ion Auth messages and errors
*
*/

// Account Creation
$lang['account_creation_successful'] 	  	      = 'Cuenta creada con éxito';
$lang['account_creation_unsuccessful'] 	 	      = 'No se ha podido crear la cuenta';
$lang['account_creation_duplicate_email'] 	    = 'Email en uso o inválido';
$lang['account_creation_duplicate_identity']    = 'Nombre de usuario en uso o inválido';
$lang['account_creation_missing_default_group'] = 'No se ha especificado grupo por defecto';
$lang['account_creation_invalid_default_group'] = 'Nombre de grupo no válido';

// Password
$lang['password_change_successful'] 	 	        = 'Contraseña renovada con éxito';
$lang['password_change_unsuccessful'] 	  	    = 'No se ha podido cambiar la contraseña';
$lang['forgot_password_successful'] 	 	        = 'Nueva contraseña enviada por email';
$lang['forgot_password_unsuccessful'] 	 	      = 'No se ha podido crear una nueva contraseña';

// Activation
$lang['activate_successful']                    = 'Cuenta activada con éxito';
$lang['activate_unsuccessful']                  = 'No se ha podido activar la cuenta';
$lang['deactivate_successful']                  = 'Cuenta desactivada con éxito';
$lang['deactivate_unsuccessful']                = 'No se ha podido desactivar la cuenta';
$lang['activation_email_successful']            = 'Email de activación enviado';
$lang['activation_email_unsuccessful']          = 'No se ha podido enviar el email de activación';
$lang['deactivate_current_user_unsuccessful']= 'No se puede desactivar usted mismo';

// Login / Logout
$lang['login_successful']                       = 'Sesión iniciada con éxito';
$lang['login_unsuccessful']                     = 'No se ha podido iniciar sesión con los datos indicados';
$lang['login_unsuccessful_not_active']          = 'Cuenta inactiva';
$lang['login_timeout']                          = 'Temporalmente bloqueado. Vuelva a intentarlo luego.';
$lang['logout_successful']                      = 'Sesión finalizada con éxito';

// Account Changes
$lang['update_successful']                      = 'Información de la cuenta actualizada con éxito';
$lang['update_unsuccessful']                    = 'No se ha podido actualizar la información de la cuenta';
$lang['delete_successful']                      = 'Usuario eliminado';
$lang['delete_unsuccessful']                    = 'No se ha podido eliminar el usuario';

// Groups
$lang['group_creation_successful']              = 'Grupo creado';
$lang['group_already_exists']                   = 'Nombre de grupo en uso';
$lang['group_update_successful']                = 'Grupo actualizado';
$lang['group_delete_successful']                = 'Grupo borrado';
$lang['group_delete_unsuccessful']              = 'Imposible borrar grupo';
$lang['group_delete_notallowed']                = 'No se puede borrar el grupo de administradores';
$lang['group_name_required']                    = 'Se requiere un nombre de grupo';
$lang['group_name_admin_not_alter']             = 'El nombre del grupo de administradores no puede ser modificado';

// Activation Email
$lang['email_activation_subject']               = 'Activación de la cuenta';
$lang['email_activate_heading']                 = 'Cuenta activada para %s';
$lang['email_activate_subheading']              = 'Por favor, haga click en este link para %s.';
$lang['email_activate_link']                    = 'Activa tu cuenta';

// Forgot Password Email
$lang['email_forgotten_password_subject']       = 'Verificación de contraseña olvidada';
$lang['email_forgot_password_heading']          = 'Resetea contraseña para %s';
$lang['email_forgot_password_subheading']       = 'Por favor, haga click en este link para %s.';
$lang['email_forgot_password_link']             = 'Resetea tu contraseña';

/**
* Name:  Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Author: Daniel Davis
*         @ourmaninjapan
*
* Author: Josue Ibarra
*         @josuetijuana
*
* Location: https://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  03.09.2013
*
* Description:  Spanish language file for Ion Auth example views
*
*/

// Errors
$lang['error_csrf'] = 'Este formulario no pasó nuestras pruebas de seguridad.';

// Login
$lang['login_heading']         = 'Ingresar';
$lang['login_subheading']      = 'Por favor, introduce tu email/usuario y contraseña.';
$lang['login_identity_label']  = 'Email/Usuario:';
$lang['login_password_label']  = 'Contraseña:';
$lang['login_remember_label']  = 'Recuérdame:';
$lang['login_submit_btn']      = 'Ingresar';
$lang['login_forgot_password'] = '¿Has olvidado tu contraseña?';

// Index
$lang['index_heading']           = 'Usuarios';
$lang['index_subheading']        = 'Debajo está la lista de usuarios.';
$lang['index_fname_th']          = 'Nombre';
$lang['index_lname_th']          = 'Apellidos';
$lang['index_email_th']          = 'Email';
$lang['index_groups_th']         = 'Grupos';
$lang['index_status_th']         = 'Estado';
$lang['index_action_th']         = 'Acción';
$lang['index_active_link']       = 'Activo';
$lang['index_inactive_link']     = 'Inactivo';
$lang['index_create_user_link']  = 'Crear nuevo usuario';
$lang['index_create_group_link'] = 'Crear nuevo grupo';

// Deactivate User
$lang['deactivate_heading']                  = 'Desactivar usuario';
$lang['deactivate_subheading']               = '¿Estás seguro que quieres desactivar el usuario \'%s\'';
$lang['deactivate_confirm_y_label']          = 'Sí:';
$lang['deactivate_confirm_n_label']          = 'No:';
$lang['deactivate_submit_btn']               = 'Enviar';
$lang['deactivate_validation_confirm_label'] = 'confirmación';
$lang['deactivate_validation_user_id_label'] = 'ID de usuario';

// Create User
$lang['create_user_heading']                           = 'Crear Usuario';
$lang['create_user_subheading']                        = 'Por favor, introduzca la información del usuario.';
$lang['create_user_fname_label']                       = 'Nombre:';
$lang['create_user_lname_label']                       = 'Apellidos:';
$lang['create_user_identity_label']                    = 'Identity:';
$lang['create_user_company_label']                     = 'Compañía:';
$lang['create_user_email_label']                       = 'Email:';
$lang['create_user_phone_label']                       = 'Teléfono:';
$lang['create_user_password_label']                    = 'Contraseña:';
$lang['create_user_password_confirm_label']            = 'Confirmar contraseña:';
$lang['create_user_submit_btn']                        = 'Crear Usuario';
$lang['create_user_validation_fname_label']            = 'Nombre';
$lang['create_user_validation_lname_label']            = 'Apellidos';
$lang['create_user_validation_identity_label']         = 'Identity';
$lang['create_user_validation_email_label']            = 'Correo electrónico';
$lang['create_user_validation_phone_label']            = 'Teléfono';
$lang['create_user_validation_company_label']          = 'Nombre de la compañía';
$lang['create_user_validation_password_label']         = 'Contraseña';
$lang['create_user_validation_password_confirm_label'] = 'Confirmación de contraseña';

// Edit User
$lang['edit_user_heading']                           = 'Editar Usuario';
$lang['edit_user_subheading']                        = 'Por favor introduzca la nueva información del usuario.';
$lang['edit_user_fname_label']                       = 'Nombre:';
$lang['edit_user_lname_label']                       = 'Apellidos:';
$lang['edit_user_company_label']                     = 'Compañía:';
$lang['edit_user_email_label']                       = 'Email:';
$lang['edit_user_phone_label']                       = 'Teléfono:';
$lang['edit_user_password_label']                    = 'Contraseña: (si quieres cambiarla)';
$lang['edit_user_password_confirm_label']            = 'Confirmar contraseña: (si quieres cambiarla)';
$lang['edit_user_groups_heading']                    = 'Miembro de grupos';
$lang['edit_user_submit_btn']                        = 'Guardar Usuario';
$lang['edit_user_validation_fname_label']            = 'Nombre';
$lang['edit_user_validation_lname_label']            = 'Apellidos';
$lang['edit_user_validation_email_label']            = 'Correo electrónico';
$lang['edit_user_validation_phone_label']            = 'Teléfono';
$lang['edit_user_validation_company_label']          = 'Compañía';
$lang['edit_user_validation_groups_label']           = 'Grupos';
$lang['edit_user_validation_password_label']         = 'Contraseña';
$lang['edit_user_validation_password_confirm_label'] = 'Confirmación de contraseña';

// Create Group
$lang['create_group_title']                  = 'Crear Grupo';
$lang['create_group_heading']                = 'Crear Grupo';
$lang['create_group_subheading']             = 'Por favor introduce la información del grupo.';
$lang['create_group_name_label']             = 'Nombre de Grupo:';
$lang['create_group_desc_label']             = 'Descripción:';
$lang['create_group_submit_btn']             = 'Crear Grupo';
$lang['create_group_validation_name_label']  = 'Nombre de Grupo';
$lang['create_group_validation_desc_label']  = 'Descripción';

// Edit Group
$lang['edit_group_title']                  = 'Editar Grupo';
$lang['edit_group_saved']                  = 'Grupo Guardado';
$lang['edit_group_heading']                = 'Editar Grupo';
$lang['edit_group_subheading']             = 'Por favor, registra la informacion del grupo.';
$lang['edit_group_name_label']             = 'Nombre de Grupo:';
$lang['edit_group_desc_label']             = 'Descripción:';
$lang['edit_group_submit_btn']             = 'Guardar Grupo';
$lang['edit_group_validation_name_label']  = 'Nombre de Grupo';
$lang['edit_group_validation_desc_label']  = 'Descripción';

// Change Password
$lang['change_password_heading']                               = 'Cambiar Contraseña';
$lang['change_password_old_password_label']                    = 'Antigua Contraseña:';
$lang['change_password_new_password_label']                    = 'Nueva Contraseña (de al menos %s caracteres de longitud):';
$lang['change_password_new_password_confirm_label']            = 'Confirmar Nueva Contraseña:';
$lang['change_password_submit_btn']                            = 'Cambiar';
$lang['change_password_validation_old_password_label']         = 'Antigua Contraseña';
$lang['change_password_validation_new_password_label']         = 'Nueva Contraseña';
$lang['change_password_validation_new_password_confirm_label'] = 'Confirmar Nueva Contraseña';

// Forgot Password
$lang['forgot_password_heading']                 = 'He olvidado mi Contraseña';
$lang['forgot_password_subheading']              = 'Por favor, introduce tu %s para que podamos enviarte un email para restablecer tu contraseña.';
$lang['forgot_password_email_label']             = '%s:';
$lang['forgot_password_submit_btn']              = 'Enviar';
$lang['forgot_password_validation_email_label']  = 'Correo Electrónico';
$lang['forgot_password_identity_label']          = 'Usuario';
$lang['forgot_password_email_identity_label']    = 'Email';
$lang['forgot_password_email_not_found']         = 'El correo electrónico no existe.';
$lang['forgot_password_identity_not_found']         = 'No hay registros de esa dirección de usuario.';

// Reset Password
$lang['reset_password_heading']                               = 'Cambiar Contraseña';
$lang['reset_password_new_password_label']                    = 'Nueva Contraseña (de al menos %s caracteres de longitud):';
$lang['reset_password_new_password_confirm_label']            = 'Confirmar Nueva Contraseña:';
$lang['reset_password_submit_btn']                            = 'Guardar';
$lang['reset_password_validation_new_password_label']         = 'Nueva Contraseña';
$lang['reset_password_validation_new_password_confirm_label'] = 'Confirmar Nueva Contraseña';

// Activation Email
$lang['email_activate_heading']    = 'Activar cuenta por %s';
$lang['email_activate_subheading'] = 'Por favor ingresa en este link para %s.';
$lang['email_activate_link']       = 'Activar tu cuenta';

// Forgot Password Email
$lang['email_forgot_password_heading']    = 'Reestablecer contraseña para %s';
$lang['email_forgot_password_subheading'] = 'Por favor ingresa en este link para %s.';
$lang['email_forgot_password_link']       = 'Restablecer Tu Contraseña';
