// Encapsulamos todo el código en una IIFE para evitar variables globales innecesarias
(function() {
    // ============================================================
    // Gestión del marcador en segunda pantalla
    // ============================================================
    let marcadorVentana = null;
    const toggleButtonScoreboard = document.getElementById('toggle-marcador-btn');
    let timerInterval = null;
    let totalTime = 60; // Valor por defecto en segundos (1:30)

    // Comprueba si la ventana del marcador está abierta
    function isScoreboardOpen() {
        return marcadorVentana && !marcadorVentana.closed;
    }

    // Envía mensajes al marcador comprobando que la ventana sigue activa
    function postToScoreboard(message) {
        if (isScoreboardOpen()) {
            marcadorVentana.postMessage(message, '*');
        }
    }

    // Actualiza el texto del botón según el estado del marcador
    function updateToggleButton() {
        toggleButtonScoreboard.innerText = isScoreboardOpen()
            ? 'Cerrar marcador en segunda pantalla'
            : 'Abrir marcador en segunda pantalla';
    }

    // Comprueba si en localStorage figura que el marcador está abierto y trata de referenciarlo
    function comprobarMarcadorAbierto() {
        if (localStorage.getItem('marcadorAbierto') === 'true') {
            marcadorVentana = window.open('', 'scoreboard');
            if (!isScoreboardOpen()) {
                localStorage.setItem('marcadorAbierto', 'false');
                marcadorVentana = null;
            }
        }
    }

    // Abre la ventana del marcador y establece un chequeo periódico de su estado
    function openScoreboard() {
        marcadorVentana = window.open(
            base_url + 'Home/scoreboard',
            'scoreboard',
            `left=${window.screen.availWidth},top=0,width=${screen.width},height=${screen.height}`
        );

        if (!isScoreboardOpen()) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo abrir la ventana en la segunda pantalla. Verifica la configuración del navegador.'
            });
        } else {
            localStorage.setItem('marcadorAbierto', 'true');
            updateToggleButton();

            // Chequea cada segundo si la ventana se ha cerrado
            const checkWindowClosed = setInterval(() => {
                if (!isScoreboardOpen()) {
                    clearInterval(checkWindowClosed);
                    localStorage.setItem('marcadorAbierto', 'false');
                    updateToggleButton();
                }
            }, 1000);
        }
    }

    // Función para alternar (abrir/cerrar) el marcador
    function toggleScoreboard() {
        if (isScoreboardOpen()) {
            marcadorVentana.close();
            localStorage.setItem('marcadorAbierto', 'false');
            updateToggleButton();
        } else {
            if (localStorage.getItem('marcadorAbierto') !== 'true') {
                openScoreboard();
            } else {
                // Intentar reconectar
                marcadorVentana = window.open('', 'scoreboard');
                if (!isScoreboardOpen()) {
                    localStorage.setItem('marcadorAbierto', 'false');
                }
            }
        }
    }

    toggleButtonScoreboard.addEventListener('click', toggleScoreboard);

    // ============================================================
    // Gestión del cronómetro y de la actualización del partido
    // ============================================================
    document.addEventListener('DOMContentLoaded', () => {
        comprobarMarcadorAbierto();
        updateToggleButton();

        // Elementos del DOM
        const inputTimeField = document.getElementById('default-time');
        const botonMostrarEnMarcador = document.getElementById('mostrar-en-segunda');
        const botonesPuntos = document.querySelectorAll(
            '[data-accion="ippon"], [data-accion="wazari"], [data-accion="yuko"], [data-accion="rest"]'
        );
        const botonesSenshuHantei = document.querySelectorAll(
            '[data-accion="senshu"], [data-accion="hantei"]'
        );
        const botonesPenalizaciones = document.querySelectorAll('[data-accion^="c"]');
        const marcadorAzul = document.getElementById('puntostotalesazul');
        const marcadorRojo = document.getElementById('puntostotalesrojo');
        const minutesDisplay = document.querySelector('.minutes');
        const secondsDisplay = document.querySelector('.seconds');
        const startBtn = document.getElementById('start-btn');
        const pauseBtn = document.getElementById('pause-btn');
        const restartBtn = document.getElementById('restart-btn');
        const addTimeBtn = document.getElementById('add-timer');
        const delTimeBtn = document.getElementById('del-timer');
		const audioAlert16 = new Audio(base_url + 'assets/sounds/whistle_1_loud.mp3');
		const audioAlert0  = new Audio(base_url + 'assets/sounds/whistle_2_loud.mp3');
        // Si no hay valor, establece el tiempo por defecto
        if (!inputTimeField.value) {
            inputTimeField.value = "01:30";
        }
        reiniciarCronometro();

        // Actualiza la visualización del cronómetro y sincroniza con el marcador
        function actualizarCronometro() {
            const minutos = Math.floor(totalTime / 60);
            const segundos = totalTime % 60;
            minutesDisplay.textContent = minutos.toString().padStart(2, '0');
            secondsDisplay.textContent = segundos.toString().padStart(2, '0');
            postToScoreboard({ action: 'syncTime', time: totalTime });
        }

        function iniciarCronometro() {
            if (!timerInterval) {
                timerInterval = setInterval(() => {
                    if (totalTime > 0) {
						totalTime--;
						// Si el tiempo llega a 16 segundos, reproducir el audio correspondiente
						if (totalTime === 16) {
							audioAlert16.play().catch(err => {
								console.error("Error reproduciendo audioAlert16:", err);
							});
						}
						// Si el tiempo llega a 0, reproducir el audio y cambiar el botón a play
						if (totalTime === 0) {
							audioAlert0.play().catch(err => {
								console.error("Error reproduciendo audioAlert0:", err);
							});
						}
						actualizarCronometro();
					} else {
						clearInterval(timerInterval);
						timerInterval = null;
						// Al llegar a 0, se muestra el botón de play y se oculta el de pause
						startBtn.classList.remove('d-none');
						pauseBtn.classList.add('d-none');
					}
                }, 1000);
                startBtn.classList.add('d-none');
                pauseBtn.classList.remove('d-none');
            }
        }

        function pausarCronometro() {
            clearInterval(timerInterval);
            timerInterval = null;
            pauseBtn.classList.add('d-none');
            startBtn.classList.remove('d-none');
            postToScoreboard({ action: 'pauseCountdown' });
        }

        function reiniciarCronometro() {
            const inputTime = inputTimeField.value || "01:30";
            inputTimeField.value = inputTime;
            const [mins, secs] = inputTime.split(':').map(Number);
            totalTime = (mins * 60) + secs;

            // Reinicia los puntos y desmarca Senshu/Hantei y penalizaciones
            marcadorAzul.textContent = '0';
            marcadorRojo.textContent = '0';
            ['senshu_azul', 'hantei_azul', 'senshu_rojo', 'hantei_rojo'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.checked = false;
            });
            document.querySelectorAll('[data-accion^="c"]').forEach(checkbox => checkbox.checked = false);

            actualizarCronometro();
            postToScoreboard({ action: 'resetCountdown', time: totalTime });
        }

        // Eventos del cronómetro
        startBtn.addEventListener('click', iniciarCronometro);
        pauseBtn.addEventListener('click', pausarCronometro);
        restartBtn.addEventListener('click', reiniciarCronometro);
        addTimeBtn.addEventListener('click', () => {
            totalTime++;
            actualizarCronometro();
        });
        delTimeBtn.addEventListener('click', () => {
            totalTime = Math.max(0, totalTime - 1);
            actualizarCronometro();
        });

        // Inicializa la partida en la segunda pantalla
        botonMostrarEnMarcador.addEventListener('click', () => {
            if (!isScoreboardOpen()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El marcador en la segunda pantalla no está abierto. Ábrelo primero.'
                });
                return;
            }
            const match_id = $('#marcadorauxiliar').find('[data-match-id]').attr('data-match-id');
            const puntos_rojo = parseInt($('#puntostotalesrojo').html()) || 0;
            const puntos_azul = parseInt($('#puntostotalesazul').html()) || 0;
            const user_rojo = $('#user_rojo').text();
            const user_azul = $('#user_azul').text();
            const senshu = $('#senshu_azul').prop('checked')
                ? 'azul'
                : ($('#senshu_rojo').prop('checked') ? 'rojo' : '');
            const hantei = $('#hantei_azul').prop('checked')
                ? 'azul'
                : ($('#hantei_rojo').prop('checked') ? 'rojo' : '');
            const penalizaciones_azul = $("input[id^='c'][id$='_azul']:checked")
                .map(function () { return this.value; })
                .get()
                .sort()
                .reverse()[0] || null;
            const penalizaciones_rojo = $("input[id^='c'][id$='_rojo']:checked")
                .map(function () { return this.value; })
                .get()
                .sort()
                .reverse()[0] || null;

            postToScoreboard({
                action: 'initMatch',
                match_id,
                user_rojo,
                user_azul,
                puntos_rojo,
                puntos_azul,
                senshu,
                hantei,
                penalizaciones_azul,
                penalizaciones_rojo
            });
            actualizarCronometro();
        });

        // ============================================================
        // Gestión de puntos, Senshu/Hantei y penalizaciones
        // ============================================================
        // Mapeo de valores para cada acción de puntos
        const pointValues = {
            ippon: 3,
            wazari: 2,
            yuko: 1,
            rest: -1
        };

        // Eventos para sumar/restar puntos
        botonesPuntos.forEach(boton => {
            boton.addEventListener('click', () => {
                const accion = boton.getAttribute('data-accion');
                const esAzul = boton.closest('[data-acciones="azul"]') !== null;
                const marcador = esAzul ? marcadorAzul : marcadorRojo;
                const puntos = pointValues[accion] || 0;
                const nuevoPuntaje = Math.max(0, parseInt(marcador.textContent) + puntos);
                marcador.textContent = nuevoPuntaje;
                postToScoreboard({
                    action: 'updatePoints',
                    puntos_rojo: marcadorRojo.textContent,
                    puntos_azul: marcadorAzul.textContent
                });
            });
        });

        // Eventos para Senshu y Hantei (evitar la activación doble)
        botonesSenshuHantei.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) {
                    const rivalSelector = `[data-accion="${checkbox.getAttribute('data-accion')}"]:not(#${checkbox.id})`;
                    const rivalCheckbox = document.querySelector(rivalSelector);
                    if (rivalCheckbox) rivalCheckbox.checked = false;
                }
                postToScoreboard({
                    action: 'updateSenshuHantei',
                    senshu: document.getElementById('senshu_azul').checked
                        ? 'azul'
                        : document.getElementById('senshu_rojo').checked
                        ? 'rojo'
                        : '',
                    hantei: document.getElementById('hantei_azul').checked
                        ? 'azul'
                        : document.getElementById('hantei_rojo').checked
                        ? 'rojo'
                        : ''
                });
            });
        });

        // Eventos para las penalizaciones
        botonesPenalizaciones.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                checkbox.disabled = false; // Permite desmarcar
                const penalizaciones_azul = $("input[id^='c'][id$='_azul']:checked")
                    .map(function () { return this.value; })
                    .get()
                    .sort()
                    .reverse()[0] || null;
                const penalizaciones_rojo = $("input[id^='c'][id$='_rojo']:checked")
                    .map(function () { return this.value; })
                    .get()
                    .sort()
                    .reverse()[0] || null;
                postToScoreboard({
                    action: 'updatePenalizaciones',
                    user_rojo: document.getElementById('user_rojo').innerText,
                    user_azul: document.getElementById('user_azul').innerText,
                    penalizaciones_azul,
                    penalizaciones_rojo
                });
            });
        });

        // Validación para Hantei: si algún Senshu está marcado, no se permite Hantei
        document.querySelectorAll('[data-accion="hantei"]').forEach(hanteiEl => {
            hanteiEl.addEventListener('change', function () {
                if (
                    document.getElementById('senshu_azul').checked ||
                    document.getElementById('senshu_rojo').checked
                ) {
                    this.checked = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Acción no permitida',
                        text: 'Si hay un Senshu marcado, no se puede marcar Hantei.'
                    });
                }
            });
        });
    });

    // Función global para actualizar puntos en el marcador (opcional si se requiere en otros contextos)
    function actualizarPuntosMarcador(color, puntos) {
        postToScoreboard({
            action: 'updatePoints',
            color,
            puntos
        });
    }

    // ============================================================
    // Gestión de mensajes y de la actualización de partidos vía AJAX
    // ============================================================
    window.addEventListener('message', (event) => {
        if (event.data.action === 'closeMarcador') {
            localStorage.setItem('marcadorAbierto', 'false');
            updateToggleButton();
        }
    });

    // Gestión del partido mediante jQuery
    $(document).on('click', '[data-manage-match]', function () {
        const match = $(this);
        const match_id = match.attr('data-manage-match');
        const fd = new FormData();
        fd.append("match_id", match_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());

        $.ajax({
            url: base_url + 'Competiciones/getMatch',
            method: "POST",
            contentType: false,
            processData: false,
            data: fd
        }).done(function (response) {
            const res = JSON.parse(response);
            $('[name="csrf_token"]').val(res.csrf);

            if (res.error > 0) {
                let errorhtml = '';
                if (res.error_validation) {
                    $.each(res.error_validation, function (i, value) {
                        errorhtml += value + '<br>';
                    });
                }
                if (res.error_msn) {
                    errorhtml += res.error_msn;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'ERROR',
                    html: errorhtml,
                    willClose: function () {
                        if (res.redirect) {
                            res.redirect === 'refresh'
                                ? location.reload()
                                : window.location.href = res.redirect;
                        }
                    }
                });
                return;
            } else {
                const thisMatch = res.match;
                $('#marcadorauxiliar').find('[data-match-id]').attr('data-match-id', thisMatch.match_id);
                const nombre_rojo = thisMatch.rojo ? thisMatch.rojo.nombre : '';
                const nombre_azul = thisMatch.azul ? thisMatch.azul.nombre : '';

                $('#user_rojo').html(nombre_rojo).attr('data-user', thisMatch.user_rojo);
                $('#user_azul').html(nombre_azul).attr('data-user', thisMatch.user_azul);
                $('#puntostotalesrojo').html(thisMatch.puntos_rojo);
                $('#puntostotalesazul').html(thisMatch.puntos_azul);
                $('#marcadorauxiliar [type="radio"]').prop("checked", false);

                if (thisMatch.senshu === 'azul') {
                    $('[name="senshu_azul"]').prop('checked', true);
                } else if (thisMatch.senshu === 'rojo') {
                    $('[name="senshu_rojo"]').prop('checked', true);
                } else if (thisMatch.hantei === 'azul') {
                    $('[name="hantei_azul"]').prop('checked', true);
                } else if (thisMatch.hantei === 'rojo') {
                    $('[name="hantei_rojo"]').prop('checked', true);
                }

                if (thisMatch.penalizaciones_azul > 0) {
                    $("input[name^='c'][id$='_azul']").each(function () {
                        const penalizacionValor = parseInt($(this).val(), 10);
                        $(this).prop('checked', penalizacionValor <= thisMatch.penalizaciones_azul);
                    });
                }

                if (thisMatch.penalizaciones_rojo > 0) {
                    $("input[name^='c'][id$='_rojo']").each(function () {
                        const penalizacionValor = parseInt($(this).val(), 10);
                        $(this).prop('checked', penalizacionValor <= thisMatch.penalizaciones_rojo);
                    });
                }

                $('[data-match-rojo]').toggle(!!thisMatch.rojo);
                $('[data-match-azul]').toggle(!!thisMatch.azul);
                $('#marcadorauxiliar').modal('show');
            }
        });

        // Al cerrar la modal, notifica a la segunda pantalla
        $('#marcadorauxiliar').on('hidden.bs.modal', function () {
            postToScoreboard({ action: 'hideMatch' });
        });
    });

    $(document).on('click', '#guardar-marcador', function () {
		Swal.fire({
			icon: 'question',
			title: 'Confirmar guardado',
			text: '¿Desea guardar el marcador?',
			showCancelButton: true,
			confirmButtonText: 'Sí, guardar',
			cancelButtonText: 'Cancelar'
		}).then((result) => {
			if (!result.isConfirmed) {
				// Si se cancela, no se realiza nada
				return;
			}
			// Si se confirma, se recogen los datos y se prepara el envío
			const match_id = $('#marcadorauxiliar').find('[data-match-id]').attr('data-match-id');
			const user_rojo = $('#user_rojo').attr('data-user') || null;
			const user_azul = $('#user_azul').attr('data-user') || null;
			const puntos_rojo = parseInt($('#puntostotalesrojo').html()) || 0;
			const puntos_azul = parseInt($('#puntostotalesazul').html()) || 0;
			const senshu = $('#senshu_azul').prop('checked')
				? "azul"
				: ($('#senshu_rojo').prop('checked') ? "rojo" : "");
			const hantei = $('#hantei_azul').prop('checked')
				? "azul"
				: ($('#hantei_rojo').prop('checked') ? "rojo" : "");
			const penalizaciones_azul = $("input[name^='c'][id$='_azul']:checked")
				.map(function () { return this.value; })
				.get()
				.sort()
				.reverse()[0] || 0;
			const penalizaciones_rojo = $("input[name^='c'][id$='_rojo']:checked")
				.map(function () { return this.value; })
				.get()
				.sort()
				.reverse()[0] || 0;

			const fd = new FormData();
			fd.append("match_id", match_id);
			fd.append("puntos_rojo", puntos_rojo);
			fd.append("puntos_azul", puntos_azul);
			fd.append("senshu", senshu);
			fd.append("hantei", hantei);
			fd.append("penalizaciones_azul", penalizaciones_azul);
			fd.append("penalizaciones_rojo", penalizaciones_rojo);
			fd.append("estado", 'completado');
			fd.append("csrf_token", $('[name="csrf_token"]').val());

			let enviarform = true;
			let winner = 0;

			if (puntos_rojo > puntos_azul) {
				winner = user_rojo;
			} else if (puntos_azul > puntos_rojo) {
				winner = user_azul;
			} else {
				// En caso de empate, se define el ganador según Senshu o Hantei
				if (senshu === "rojo") {
					winner = user_rojo;
				} else if (senshu === "azul") {
					winner = user_azul;
				} else if (hantei === "rojo") {
					winner = user_rojo;
				} else if (hantei === "azul") {
					winner = user_azul;
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Confirmar empate',
						text: 'No hay Senshu ni Hantei marcados. ¿Seguro que es un empate?',
						showCancelButton: true,
						confirmButtonText: 'Sí, guardar como empate',
						cancelButtonText: 'No, volver atrás'
					}).then((result) => {
						if (result.isConfirmed) {
							winner = 0;
							fd.append("winner", winner);
							saveMatch(fd);
						}
					});
					enviarform = false;
				}
			}
			fd.append("winner", winner);
			if (enviarform) {
				saveMatch(fd);
			}
		});
    });

    function saveMatch(fd) {
        $.ajax({
            url: base_url + 'Competiciones/updateMatch',
            method: "POST",
            contentType: false,
            processData: false,
            data: fd
        }).done(function (response) {
            const res = JSON.parse(response);
            $('[name="csrf_token"]').val(res.csrf);

            if (res.error > 0) {
                let errorhtml = '';
                if (res.error_validation) {
                    $.each(res.error_validation, function (i, value) {
                        errorhtml += value + '<br>';
                    });
                }
                if (res.error_msn) {
                    errorhtml += res.error_msn;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'ERROR',
                    html: errorhtml,
                    willClose: function () {
                        if (res.redirect) {
                            res.redirect === 'refresh'
                                ? location.reload()
                                : window.location.href = res.redirect;
                        }
                    }
                });
                return;
            } else {
                const match = res.match;
                Swal.fire({
                    icon: 'success',
                    title: 'OK',
                    html: 'Marcador actualizado correctamente',
                    willClose: function () {
                        $('#marcadorauxiliar').modal('hide');
                        
                        if (typeof updateClasificacionGrupo === 'function') {
                            updateClasificacionGrupo(match.competicion_torneo_id, match.grupo)
                        }
                        if (typeof updateMatchesListGrupo === 'function') {
                            updateMatchesListGrupo(match.competicion_torneo_id, match.grupo);
                        }
                        if (typeof cargar_puntos === 'function') {
                            cargar_puntos();
                        }else{
                            location.reload();
                        }
                    }
                });
            }
        }).always(function (jqXHR, textStatus) {
            if (textStatus !== "success") {
                Swal.fire({
                    icon: 'error',
                    title: 'Ha ocurrido un error AJAX',
                    html: jqXHR.statusText,
                    timer: 5000
                });
            }
        });
    }
})();