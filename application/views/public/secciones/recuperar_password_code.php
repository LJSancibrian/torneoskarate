<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php echo form_open('', ['class' => 'login-form card-section']); ?>
<h4 class="text-center my-3">Crear nueva contraseña</h4>
<div class="illustration"></div>

<?php if (isset($error) || $this->session->flashdata('error') != '') {
    $error = ($this->session->flashdata('error') != '') ? $this->session->flashdata('error') : $error;
    $error = preg_replace("/\r|\n/", "", $error); ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php $this->session->unset_userdata('error');} ?>

<?php if (isset($info)) {
    $info = preg_replace("/\r|\n/", "", $info); ?>
    <div class="alert alert-info"><?php echo $info; ?></div>
<?php $this->session->unset_userdata('info'); } ?>

<?php if (isset($success)) {
    $success = preg_replace("/\r|\n/", "", $success); ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php $this->session->unset_userdata('success'); } ?>


<div class="form-group">
    <label for="new">Indica y confirma la nueva contraseña de acceso de la cuenta. Es necesario que tenga al menos 8 caracteres.</label>
    <input type="password" name="new" id="new" class="form-control" placeholder="Nueva contraseña">
</div>
<div class="form-group">
    <input type="password" name="new_confirm" id="new_confirm" class="form-control" placeholder="Confirma la nueva contraseña">
</div>

<?php echo form_hidden('user_id', $user_id);?>
<div class="form-group">
    <button class="btn btn-form" type="submit">Crear nueva contraseña</button>
</div>
<a class="forgot" href="<?php echo base_url();?>login">Iniciar sesión</a>
<?php echo form_close(); ?>