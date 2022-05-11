<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div>

<h4>Se ha encontrado un error PHP</h4>

<p>Gravedad: <?php echo $severity; ?></p>
<p>Mensaje:  <?php echo $message; ?></p>
<p>Archivo: <?php echo $filepath; ?></p>
<p>Linea: <?php echo $line; ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

	<p>Backtrace:</p>
	<?php foreach (debug_backtrace() as $error): ?>

		<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>

			<p style="margin-left:10px">
			Archivo: <?php echo $error['file'] ?><br />
			Lina: <?php echo $error['line'] ?><br />
			Funciónn: <?php echo $error['function'] ?>
			</p>

		<?php endif ?>

	<?php endforeach ?>

<?php endif ?>

</div>