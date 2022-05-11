<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php echo form_open('', ['class' => 'login-form card-section']); ?>
<h4 class="text-center my-3">Acceso</h4>
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
    <input type="text" name="identity" id="identity" class="form-control" placeholder="Usuario">
</div>
<div class="form-group">
    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
</div>
<?php if ($this->session->flashdata('gotourl') != '') {
    echo form_hidden('gotourl', $this->session->flashdata('gotourl'));
} ?>
<div class="form-group">
    <button class="btn btn-form" type="submit">Entrar</button>
</div>
<a class="forgot" href="<?php echo base_url();?>recuperar-contrasena">Recuperar contraseña</a>
<?php echo form_close(); ?>