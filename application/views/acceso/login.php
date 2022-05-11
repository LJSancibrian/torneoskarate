<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/public/bootstrap-4.5.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url();?>assets/public/styles.css">
	<title>Black Friday Vialia Málaga</title>
</head>

<body class="forms">
	<div class="container text-center">
		<h3>Inicia sesión</h3>
		<h2 class="color-brand font-bold text-uppercase text-height-0 mb-5">Autorizados</h2>
		<?php echo form_open('', ['class' => 'mx-auto', 'style' => 'max-width: 300px;']);?>
		<div class="form-group">
			<?php echo form_input($identity);?>
		</div>
		<div class="form-group">
			<?php echo form_input($password);?>
		</div>
		<div class="form-group">
			<button type="submit" class="btn btn-brand mt-10">Entrar</button>
			<?php if($this->session->flashdata('message') != ''){
     echo '<p class="text-center color-brand font-book">'.$this->session->flashdata('message').'</p>';
}?>
		</div>
		<?php echo form_close();?>
	</div>
	<div class="container text-center">
		<img src="<?php echo base_url();?>assets/public/imagenes/logo.png" alt="Black Friday Vialia" class="img-fluid mt-5" style="width:150px;">
	</div>
	<script src="<?php echo base_url();?>assets/public/jquery/jquery-3.5.1.min.js"></script>
	<script src="<?php echo base_url();?>assets/public/bootstrap-4.5.3-dist/js/bootstrap.bundle.min.js"></script>
	<?php if (isset($error) || $this->session->flashdata('error') != '') {
    
    $error = ($this->session->flashdata('error') != '') ? $this->session->flashdata('error') : $error;
    printr($error);
    $error = preg_replace("/\r|\n/", "", $error); ?>
    <script>
        swal.fire({
            icon: 'error',
            html: '<?php echo $error; ?>',
        })
    </script>
<?php $this->session->unset_userdata('error');} ?>

<?php if (isset($info)) {
    $info = preg_replace("/\r|\n/", "", $info); ?>
    <script>
        swal.fire({
            icon: 'info',
            html: '<?php echo $info; ?>',
        })
    </script>
<?php $this->session->unset_userdata('info'); } ?>

<?php if (isset($success)) {
    $success = preg_replace("/\r|\n/", "", $success); ?>
    <script>
        swal.fire({
            icon: 'success',
            html: '<?php echo $success; ?>',
        })
    </script>
    
<?php $this->session->unset_userdata('success'); } ?>
</body>

</html>
