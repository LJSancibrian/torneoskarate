<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php echo form_open('', ['class' => 'login-form card-section']); ?>
<h4 class="text-center my-3">Recuperar contraseña</h4>
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
    <label for="identity">Indica el correo electrónico de la cuenta de usuario. Enviaremos un email con un enlace para cambiar la contraseña</label>
    <input type="text" name="identity" id="identity" class="form-control" placeholder="Usuario" Value="<?php echo $this->form_validation->set_value('identity'); ?>">
</div>

<div class="form-group">
    <button class="btn btn-form" type="submit">Enviar email</button>
</div>
<a class="forgot" href="<?php echo base_url(); ?>login">Iniciar sesión</a>
<?php echo form_close(); ?>