<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>



<script>
function handleScreenChange(mql) {
    if (mql.matches) {
        alert('Se ha conectado una pantalla');
    } else {
        alert('Se ha desconectado una pantalla');
    }
}

// Establecer un listener para cambios en la configuración de medios
var mediaQueryList = window.matchMedia('print');
mediaQueryList.addListener(handleScreenChange);

// Llamar a la función inicialmente para verificar el estado actual
handleScreenChange(mediaQueryList);

</script>