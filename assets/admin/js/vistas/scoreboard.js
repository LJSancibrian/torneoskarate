document.addEventListener('DOMContentLoaded', () => {
    localStorage.setItem('marcadorAbierto', 'true');

    let isFullscreen = false; // Estado de pantalla completa
    let countdownInterval; // Variable para controlar el intervalo de countdown
    // Ocultar el marcador al inicio y mostrar el carrusel
    document.getElementById('marcador').style.display = 'none';
    document.getElementById('imageCarousel').style.display = 'block';
    // Hacer clic en cualquier parte para activar pantalla completa (solo una vez)
    document.addEventListener('click', () => {
        if (!isFullscreen) {
            document.documentElement.requestFullscreen().then(() => {
                isFullscreen = true;
                document.body.style.pointerEvents = 'none'; // Bloquea interacciones posteriores
                document.body.style.cursor = 'none'; // Oculta el cursor
            }).catch(err => console.error('Error al activar fullscreen:', err));
        }
    }, { once: true });
    // Notificar al opener cuando la ventana se cierre manualmente
    window.addEventListener('unload', () => {
        localStorage.setItem('marcadorAbierto', 'false');
        if (window.opener && !window.opener.closed) {
            window.opener.postMessage({ action: 'closeMarcador' }, '*');
        }
    });
    // Recibe mensajes desde panel.js y actualiza el marcador en tiempo real
    window.addEventListener('message', (event) => {
        if (event.data.action === 'initMatch') {
            document.getElementById('imageCarousel').style.display = 'none';
            document.getElementById('marcador').style.display = 'flex';
            document.getElementById('score-rojo').innerText = event.data.puntos_rojo || '0';
            document.getElementById('score-azul').innerText = event.data.puntos_azul || '0';
            document.getElementById('nombre-rojo').innerText = event.data.user_rojo || 'ROJO';
            document.getElementById('nombre-azul').innerText = event.data.user_azul || 'AZUL';
            // Mostrar Senshu y Hantei
            let senshuHanteiRojo = document.getElementById('penalizaciones-rojo').querySelector('.sensho_hantei');
            let senshuHanteiAzul = document.getElementById('penalizaciones-azul').querySelector('.sensho_hantei');
            let iconoRojo = senshuHanteiRojo.querySelector('i');
            let iconoAzul = senshuHanteiAzul.querySelector('i');
            // Actualizar Senshu/Hantei para Rojo
            if (event.data.senshu === 'rojo') {
                senshuHanteiRojo.classList.add('sensho');
                senshuHanteiRojo.classList.remove('hantei');
                iconoRojo.classList.remove('d-none');
            } else if (event.data.hantei === 'rojo') {
                senshuHanteiRojo.classList.add('hantei');
                senshuHanteiRojo.classList.remove('sensho');
                iconoRojo.classList.remove('d-none');
            } else {
                senshuHanteiRojo.classList.remove('sensho', 'hantei');
                iconoRojo.classList.add('d-none');
            }
            // Actualizar Senshu/Hantei para Azul
            if (event.data.senshu === 'azul') {
                senshuHanteiAzul.classList.add('sensho');
                senshuHanteiAzul.classList.remove('hantei');
                iconoAzul.classList.remove('d-none');
            } else if (event.data.hantei === 'azul') {
                senshuHanteiAzul.classList.add('hantei');
                senshuHanteiAzul.classList.remove('sensho');
                iconoAzul.classList.remove('d-none');
            } else {
                senshuHanteiAzul.classList.remove('sensho', 'hantei');
                iconoAzul.classList.add('d-none');
            }
            // Mostrar penalizaciones
            actualizarPenalizaciones(event.data.penalizaciones_rojo, 'penalizaciones-rojo');
            actualizarPenalizaciones(event.data.penalizaciones_azul, 'penalizaciones-azul');
        }

        if (event.data.action === 'updatePoints') {
            document.getElementById('imageCarousel').style.display = 'none';
            document.getElementById('marcador').style.display = 'flex';

            document.getElementById('score-rojo').innerText = event.data.puntos_rojo || '0';
            document.getElementById('score-azul').innerText = event.data.puntos_azul || '0';
        }

        if(event.data.action === 'updateSenshuHantei'){
            // Mostrar Senshu y Hantei
            let senshuHanteiRojo = document.getElementById('penalizaciones-rojo').querySelector('.sensho_hantei');
            let senshuHanteiAzul = document.getElementById('penalizaciones-azul').querySelector('.sensho_hantei');
            let iconoRojo = senshuHanteiRojo.querySelector('i');
            let iconoAzul = senshuHanteiAzul.querySelector('i');

            // Actualizar Senshu/Hantei para Rojo
            if (event.data.senshu === 'rojo') {
                senshuHanteiRojo.classList.add('sensho');
                senshuHanteiRojo.classList.remove('hantei');
                iconoRojo.classList.remove('d-none');
            } else if (event.data.hantei === 'rojo') {
                senshuHanteiRojo.classList.add('hantei');
                senshuHanteiRojo.classList.remove('sensho');
                iconoRojo.classList.remove('d-none');
            } else {
                senshuHanteiRojo.classList.remove('sensho', 'hantei');
                iconoRojo.classList.add('d-none');
            }

            // Actualizar Senshu/Hantei para Azul
            if (event.data.senshu === 'azul') {
                senshuHanteiAzul.classList.add('sensho');
                senshuHanteiAzul.classList.remove('hantei');
                iconoAzul.classList.remove('d-none');
            } else if (event.data.hantei === 'azul') {
                senshuHanteiAzul.classList.add('hantei');
                senshuHanteiAzul.classList.remove('sensho');
                iconoAzul.classList.remove('d-none');
            } else {
                senshuHanteiAzul.classList.remove('sensho', 'hantei');
                iconoAzul.classList.add('d-none');
            }
        }


        if(event.data.action === 'updatePenalizaciones'){
            // Mostrar penalizaciones
            actualizarPenalizaciones(event.data.penalizaciones_rojo, 'penalizaciones-rojo');
            actualizarPenalizaciones(event.data.penalizaciones_azul, 'penalizaciones-azul');
        }

        // ⏳ Sincronizar tiempo SIN iniciar su propio temporizador
        if (event.data.action === 'syncTime') {
            let countdownElement = document.getElementById('tiempo');
            let marcadorContainer = document.getElementById('marcador');

            let minutos = Math.floor(event.data.time / 60);
            let segundos = event.data.time % 60;
            countdownElement.innerText = minutos.toString().padStart(2, '0') + ":" + segundos.toString().padStart(2, '0');

            if (event.data.time <= 16 && event.data.time > 0) {
                countdownElement.classList.add('baraku');
                marcadorContainer.classList.add('baraku');
            } else {
                countdownElement.classList.remove('baraku');
                marcadorContainer.classList.remove('baraku');
            }
        }

        if (event.data.action === 'pauseCountdown') {
            // Solo detener la actualización si el panel lo manda
            clearInterval(countdownInterval);
        }

        if (event.data.action === 'resetCountdown') {
            let countdownElement = document.getElementById('tiempo');
            let minutos = Math.floor(event.data.time / 60);
            let segundos = event.data.time % 60;
            countdownElement.innerText = minutos.toString().padStart(2, '0') + ":" + segundos.toString().padStart(2, '0');
        }
    });

    function actualizarPenalizaciones(cantidad, contenedorId) {
        let contenedor = document.getElementById(contenedorId);
        let penalizacionIcons = contenedor.querySelector('.penalizaciones').querySelectorAll('i'); // Selecciona todos los iconos de penalización
        penalizacionIcons.forEach((icono, index) => {
            if (index < cantidad) {
                icono.classList.remove('d-none'); // Mostrar penalización activa
            } else {
                icono.classList.add('d-none'); // Ocultar penalización inactiva
            }
        });
    }

});
