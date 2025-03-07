<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Error general</title>
<meta name="description" content="404 Not Found">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body class="py-5" onload="javascript:loadDomain();">
<!-- Error Page Content -->
<div class="container">
    <div class="hero text-center my-4">
        <h1 class="display-5"><i class="bi bi-emoji-dizzy text-danger mx-3"></i></h1>
        <h1 class="display-5 fw-bold"><?php echo $heading; ?></h1>
        <p class="lead"><?php echo $message; ?>.
        </p>
		<p><em><span id="display-domain"></span></em></p>
        <p><btn onclick=javascript:goToHomePage(); class="btn btn-outline-success btn-lg">Ir a la página de incio</a></btn>
    </div>

    
</div>
<script type="text/javascript">
    function loadDomain() {
        var display = document.getElementById("display-domain");
        display.innerHTML = document.domain;
    }
    // CTA button actions
    function goToHomePage() {
        window.location = '/';
    }
    function reloadPage() {
        document.location.reload(true);
    }
</script>
</body>
</html>
