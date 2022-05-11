<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div>

<h4>Excepción no detectada</h4>

<p>Type: <?php echo get_class($exception); ?></p>
<p>Mensaje: <?php echo $message; ?></p>
<p>Archivo: <?php echo $exception->getFile(); ?></p>
<p>Linea: <?php echo $exception->getLine(); ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	<p>Backtrace:</p>
	<?php foreach ($exception->getTrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p style="margin-left:10px">
			Archivo: <?php echo $error['file']; ?><br />
			Linea: <?php echo $error['line']; ?><br />
			Función: <?php echo $error['function']; ?>
			</p>
		<?php endif ?>

	<?php endforeach ?>

<?php endif ?>

</div>