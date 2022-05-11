<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="Spanish">
<!--<![endif]-->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="DC.Language" SCHEME="RFC1766" content="Spanish">
	<meta name="AUTHOR" content="Luis Javier Sancibrian Rodriguez">
	<meta name="REPLY-TO" content="secore@secore.es">
	<link rev="made" href="mailto:secore@secore.es">
	<meta name="DESCRIPTION" content="Gestion de torneos de Kárate del Ayuntamiento de Piélagos">
	<meta name="Resource-type" content="Document">
	<meta name="DateCreated" content="Fri, 18 March 2022 00:00:00 GMT+1">
	<meta name="Revisit-after" content="15 days">
	<meta name="robots" content="all">

  <title><?php echo site_title();?></title>
  <!-- Mobile Specific Meta
  ================================================== -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
  <!-- CSS
  ================================================== -->
  <!-- Themefisher Icon font -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/plugins/themefisher-font/style.css">
  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/plugins/bootstrap/css/bootstrap.min.css">
  <!-- Lightbox.min css -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/plugins/lightbox2/dist/css/lightbox.min.css">
  <!-- animation css -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/plugins/animate/animate.css">
  <!-- Slick Carousel -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/plugins/slick/slick.css">
  <!-- Main Stylesheet -->
  
  <?php if (isset($css_files) && is_array($css_files) && count($css_files) > 0) {
    foreach ($css_files as $kcss => $file) {
      if ($file != '') {
        echo '<link rel="stylesheet" href="' . $file . '?v_' . $this->config->item('version') . '">';
      }
    }
  } ?>
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/dist/secore.min.css?v_<?php echo time();?>">
  <script>
    var base_url = '<?php echo base_url(); ?>';
    var assets_url = '<?php echo assets_url(); ?>';
    var api_url = '<?php echo $this->config->item("api_url"); ?>';
  </script>
</head>