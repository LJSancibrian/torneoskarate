<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="DC.Language" SCHEME="RFC1766" content="Spanish">
	<meta name="AUTHOR" content="Luis Javier Sancibrian Rodriguez">
	<meta name="REPLY-TO" content="secore@secore.es">
	<link rev="made" href="mailto:secore@secore.es">
	<meta name="DESCRIPTION" content="Gestion de torneos de Kárate del Ayuntamiento de Piélagos">
	<meta name="Resource-type" content="Document">
	<meta name="DateCreated" content="Fri, 18 March 2022 00:00:00 GMT+1">
	<meta name="Revisit-after" content="15 days">
	<meta name="robots" content="all">
	<title><?php echo site_title(); ?></title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>assets/img/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url(); ?>assets/img/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>assets/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>assets/img/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="<?php echo base_url(); ?>assets/img/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo base_url(); ?>assets/img/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<!-- Fonts and icons -->
	<script src="<?php echo base_url(); ?>assets/admin/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {
				"families": ["Lato:300,400,700,900"]
			},
			custom: {
				"families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
				urls: ['<?php echo base_url(); ?>assets/admin/css/fonts.min.css']
			},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/DataTables/datatables.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/sweetalert2/dist/sweetalert2.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datepicker/tempusdominus-bootstrap-4.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/animate/animate.css">

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/dist/secore.min.css">
	<?php if (isset($css_files) && is_array($css_files) && count($css_files) > 0) {
		foreach ($css_files as $kcss => $file) {
			if ($file != '') {
				echo '<link rel="stylesheet" href="' . $file . '?v_' . $this->config->item('version') . '">';
			}
		}
	} ?>
	<script>
		var base_url = '<?php echo base_url(); ?>';
		var assets_url = '<?php echo assets_url(); ?>';
		var api_url = '<?php echo $this->config->item("api_url"); ?>';
	</script>
</head>