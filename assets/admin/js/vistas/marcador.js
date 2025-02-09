// Panel de control (panel.html)
// 📌 Comprobar si el marcador está abierto desde localStorage
let marcadorVentana = null;

function comprobarMarcadorAbierto() {
    if (localStorage.getItem('marcadorAbierto') === 'true') {
        marcadorVentana = window.open('', 'scoreboard'); // Intentar referenciar la ventana
        if (!marcadorVentana || marcadorVentana.closed) {
            localStorage.setItem('marcadorAbierto', 'false');
            marcadorVentana = null;
        }
    }
}

const toggleButtonScoreboard = document.getElementById('toggle-marcador-btn');
let timerInterval = null; 
let totalTime = 90;

// Función para abrir/cerrar la ventana del marcador
function toggleScoreboard() {
    if (marcadorVentana && !marcadorVentana.closed) {
        marcadorVentana.close();
        localStorage.setItem('marcadorAbierto', 'false');
        toggleButtonScoreboard.innerText = 'Abrir marcador en segunda pantalla';
    } else {
        if (localStorage.getItem('marcadorAbierto') !== 'true') {
            marcadorVentana = window.open(
                base_url + 'scoreboard',
                'scoreboard',
                `left=${window.screen.availWidth},top=0,width=${screen.width},height=${screen.height}`
            );

            if (!marcadorVentana || marcadorVentana.closed || typeof marcadorVentana.closed === 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo abrir la ventana en la segunda pantalla. Verifica la configuración del navegador.'
                });
            } else {
                localStorage.setItem('marcadorAbierto', 'true');
                toggleButtonScoreboard.innerText = 'Cerrar marcador en segunda pantalla';

                // 🔄 Verificar cada segundo si la ventana sigue abierta
                let checkWindowClosed = setInterval(() => {
                    if (marcadorVentana.closed) {
                        clearInterval(checkWindowClosed);
                        localStorage.setItem('marcadorAbierto', 'false');
                        toggleButtonScoreboard.innerText = 'Abrir marcador en segunda pantalla';
                    }
                }, 1000);
            }
        } else {
            // Intentar reconectar si ya está abierta
            marcadorVentana = window.open('', 'scoreboard');
            if (!marcadorVentana || marcadorVentana.closed) {
                localStorage.setItem('marcadorAbierto', 'false');
            }
        }
    }
}

toggleButtonScoreboard.addEventListener('click', toggleScoreboard);

// Verificar estado del marcador al cargar la página
document.addEventListener('DOMContentLoaded', () => {
	comprobarMarcadorAbierto();
    if (localStorage.getItem('marcadorAbierto') === 'true') {
        toggleButtonScoreboard.innerText = 'Cerrar marcador en segunda pantalla';
    }


	const inputTimeField = document.getElementById('default-time');
	const botonMostrarEnMarcador = document.getElementById('mostrar-en-segunda');
	const botonesPuntos = document.querySelectorAll('[data-accion="ippon"], [data-accion="wazari"], [data-accion="yuko"], [data-accion="rest"]');
	const botonesSenshuHantei = document.querySelectorAll('[data-accion="senshu"], [data-accion="hantei"]');
	const botonesPenalizaciones = document.querySelectorAll('[data-accion^="c"]');
	const marcadorAzul = document.getElementById('puntostotalesazul');
	const marcadorRojo = document.getElementById('puntostotalesrojo');

	let timerInterval;
	let totalTime = 90; // Valor por defecto 1:30
	const minutesDisplay = document.querySelector('.minutes');
	const secondsDisplay = document.querySelector('.seconds');
	const startBtn = document.getElementById('start-btn');
	const pauseBtn = document.getElementById('pause-btn');
	const restartBtn = document.getElementById('restart-btn');
	const addTimeBtn = document.getElementById('add-timer');
	const delTimeBtn = document.getElementById('del-timer');
	
	// Iniciar el marcador con los datos del combate
	botonMostrarEnMarcador.addEventListener('click', () => {
		if (!marcadorVentana || marcadorVentana.closed) {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'El marcador en la segunda pantalla no está abierto. Ábrelo primero.'
			});
			return;
		}
	
		var match_id = $('#marcadorauxiliar').find('[data-match-id]').attr('data-match-id');
		var puntos_rojo = parseInt($('#puntostotalesrojo').html()) || 0;
		var puntos_azul = parseInt($('#puntostotalesazul').html()) || 0;
		var user_rojo = $('#user_rojo').text();
		var user_azul = $('#user_azul').text();
	
		// Obtener Senshu y Hantei
		var senshu = $('#senshu_azul').prop('checked') ? 'azul' : ($('#senshu_rojo').prop('checked') ? 'rojo' : '');
		var hantei = $('#hantei_azul').prop('checked') ? 'azul' : ($('#hantei_rojo').prop('checked') ? 'rojo' : '');
	
		var penalizaciones_azul = $("input[id^='c'][id$='_azul']:checked")
			.map(function () { return this.value; })
			.get()
			.sort()
			.reverse()[0] || null;
	
		var penalizaciones_rojo = $("input[id^='c'][id$='_rojo']:checked")
			.map(function () { return this.value; })
			.get()
			.sort()
			.reverse()[0] || null;
	
		marcadorVentana.postMessage({
			action: 'initMatch',
			match_id: match_id,
			user_rojo: user_rojo,
			user_azul: user_azul,
			puntos_rojo: puntos_rojo,
			puntos_azul: puntos_azul,
			senshu: senshu,
			hantei: hantei,
			penalizaciones_azul: penalizaciones_azul,
			penalizaciones_rojo: penalizaciones_rojo
		}, '*');
		actualizarCronometro();
	});
	
	// Eventos para sumar/restar puntos
	botonesPuntos.forEach(boton => {
		boton.addEventListener('click', () => {
			const accion = boton.getAttribute('data-accion');
			const esAzul = boton.closest('[data-acciones="azul"]') !== null;
			let marcador = esAzul ? marcadorAzul : marcadorRojo;
			let puntos = (accion === "ippon") ? 3 : (accion === "wazari") ? 2 : (accion === "yuko") ? 1 : -1;
			let nuevoPuntaje = Math.max(0, parseInt(marcador.textContent) + puntos);
			marcador.textContent = nuevoPuntaje;
			// Enviar actualización del marcador a la segunda pantalla con nombres correctos
			if (marcadorVentana && !marcadorVentana.closed) {
				marcadorVentana.postMessage({
					action: 'updatePoints',
					puntos_rojo: marcadorRojo.textContent,
					puntos_azul: marcadorAzul.textContent,
				}, '*');
			}
		});
	});

	// Eventos para Senshu y Hantei (evitar activación doble)
	botonesSenshuHantei.forEach(checkbox => {
		checkbox.addEventListener('change', () => {
			if (checkbox.checked) {
				let rivalCheckbox = document.querySelector(
					`[data-accion="${checkbox.getAttribute('data-accion')}"]:not(#${checkbox.id})`
				);
				rivalCheckbox.checked = false;
			}

			// Enviar actualización al marcador secundario
			if (marcadorVentana && !marcadorVentana.closed) {
				marcadorVentana.postMessage({
					action: 'updateSenshuHantei',
					senshu: document.getElementById('senshu_azul').checked ? 'azul' :
						document.getElementById('senshu_rojo').checked ? 'rojo' : '',
					hantei: document.getElementById('hantei_azul').checked ? 'azul' :
						document.getElementById('hantei_rojo').checked ? 'rojo' : '',
				}, '*');
			}
		});
	});

	// Penalizaciones (permite desmarcar si es necesario)
	botonesPenalizaciones.forEach(checkbox => {
		checkbox.addEventListener('change', () => {
			checkbox.disabled = false; // Permite desmarcar  
			// Obtener penalizaciones más altas marcadas
			let penalizaciones_azul = $("input[id^='c'][id$='_azul']:checked")
				.map(function () { return this.value; })
				.get()
				.sort()
				.reverse()[0] || null;

			let penalizaciones_rojo = $("input[id^='c'][id$='_rojo']:checked")
				.map(function () { return this.value; })
				.get()
				.sort()
				.reverse()[0] || null;

			// Enviar actualización al marcador secundario
			if (marcadorVentana && !marcadorVentana.closed) {
				marcadorVentana.postMessage({
					action: 'updatePenalizaciones',
					user_rojo: document.getElementById('user_rojo').innerText,
					user_azul: document.getElementById('user_azul').innerText,
					penalizaciones_azul: penalizaciones_azul,
					penalizaciones_rojo: penalizaciones_rojo
				}, '*');
			}
		});
	});

	// Funciones del cronómetro
	function actualizarCronometro() {
		let minutos = Math.floor(totalTime / 60);
		let segundos = totalTime % 60;
		minutesDisplay.textContent = minutos.toString().padStart(2, '0');
		secondsDisplay.textContent = segundos.toString().padStart(2, '0');
		if (marcadorVentana && !marcadorVentana.closed) {
			marcadorVentana.postMessage({
				action: 'syncTime',
				time: totalTime
			}, '*');
		}
	}

	function iniciarCronometro() {
		if (!timerInterval) {
			timerInterval = setInterval(() => {
				if (totalTime > 0) {
					totalTime--;
					actualizarCronometro();
					if (marcadorVentana && !marcadorVentana.closed) {
						marcadorVentana.postMessage({
							action: 'syncTime',
							time: totalTime
						}, '*');
					}
				} else {
					clearInterval(timerInterval);
					timerInterval = null;
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
		if (marcadorVentana && !marcadorVentana.closed) {
			marcadorVentana.postMessage({
				action: 'pauseCountdown'
			}, '*');
		}
	}

	function reiniciarCronometro() {
		let inputTime = inputTimeField.value || "01:30";
		inputTimeField.value = inputTime; // Establece el valor en el input

		let partes = inputTime.split(':');
		totalTime = (parseInt(partes[0]) * 60) + parseInt(partes[1]);

		document.getElementById('puntostotalesazul').textContent = '0';
		document.getElementById('puntostotalesrojo').textContent = '0';
		// Desmarcar Senshu y Hantei
		document.getElementById('senshu_azul').checked = false;
		document.getElementById('hantei_azul').checked = false;
		document.getElementById('senshu_rojo').checked = false;
		document.getElementById('hantei_rojo').checked = false;
		// Desmarcar penalizaciones
		document.querySelectorAll('[data-accion^="c"]').forEach(checkbox => {
			checkbox.checked = false;
		});

		actualizarCronometro();
		if (marcadorVentana && !marcadorVentana.closed) {
			marcadorVentana.postMessage({
				action: 'resetCountdown',
				time: totalTime
			}, '*');
		}
	}

	// Eventos del cronómetro
	startBtn.addEventListener('click', iniciarCronometro);
	pauseBtn.addEventListener('click', pausarCronometro);
	restartBtn.addEventListener('click', reiniciarCronometro);
	addTimeBtn.addEventListener('click', () => {
		totalTime++;
		actualizarCronometro();
		if (marcadorVentana && !marcadorVentana.closed) {
			marcadorVentana.postMessage({
				action: 'syncTime',
				time: totalTime
			}, '*');
		}
	});
	delTimeBtn.addEventListener('click', () => {
		totalTime = Math.max(0, totalTime - 1);
		actualizarCronometro();
		if (marcadorVentana && !marcadorVentana.closed) {
			marcadorVentana.postMessage({
				action: 'syncTime',
				time: totalTime
			}, '*');
		}
	});

	// Inicializa el cronómetro con el valor predeterminado si no hay valor
	if (!inputTimeField.value) {
		inputTimeField.value = "01:30";
	}
	reiniciarCronometro();
});

// 📢 Función para actualizar el marcador en la segunda pantalla
function actualizarPuntosMarcador(color, puntos) {
    if (marcadorVentana && !marcadorVentana.closed) {
        marcadorVentana.postMessage({
            action: 'updatePoints',
            color: color,
            puntos: puntos
        }, '*');
    }
}

// HANTEI solo si ningún competidor tiene SENSHU activo
document.querySelectorAll('[data-accion="hantei"]').forEach(hantei => {
	hantei.addEventListener('change', function () {
		let senshuAzul = document.getElementById('senshu_azul').checked;
		let senshuRojo = document.getElementById('senshu_rojo').checked;

		if (senshuAzul || senshuRojo) {
			this.checked = false;
			Swal.fire({
				icon: 'warning',
				title: 'Acción no permitida',
				text: 'Si hay un Senshu marcado, no se puede marcar Hantei.'
			});
		}
	});
});

// Escuchar mensajes del marcador cuando se cierre manualmente
window.addEventListener('message', (event) => {
	if (event.data.action === 'closeMarcador') {
		localStorage.setItem('marcadorAbierto', 'false');
		toggleButtonScoreboard.innerText = 'Abrir marcador en segunda pantalla';
	}
});

$(document).on('click', '[data-manage-match]', function () {
	var match = $(this);
	var match_id = match.attr('data-match_id');
	var fd = new FormData();
	fd.append("match_id", match_id);
	fd.append("csrf_token", $('[name="csrf_token"]').val());

	$.ajax({
		url: base_url + 'Competiciones/getMatch',
		method: "POST",
		contentType: false,
		processData: false,
		data: fd
	}).done(function (response) {
		var response = JSON.parse(response);
		$('[name="csrf_token"]').val(response.csrf);

		if (response.error > 0) {
			let errorhtml = '';
			if (response.hasOwnProperty('error_validation')) {
				$.each(response.error_validation, function (i, value) {
					errorhtml += value + '<br>';
				});
			}
			if (response.hasOwnProperty('error_msn')) {
				errorhtml += response.error_msn;
			}
			swal.fire({
				icon: 'error',
				title: 'ERROR',
				html: errorhtml,
				willClose: function () {
					if (response.hasOwnProperty('redirect')) {
						if (response.redirect == 'refresh') {
							location.reload();
						} else {
							window.location.href = response.redirect;
						}
					}
				}
			});
			return;
		} else {
			var thisMatch = response.match;
			$('#marcadorauxiliar').find('[data-match-id]').attr('data-match-id', thisMatch.match_id);
			var nombre_rojo = thisMatch.hasOwnProperty('rojo') ? thisMatch.rojo.nombre : '';
			var nombre_azul = thisMatch.hasOwnProperty('azul') ? thisMatch.azul.nombre : '';

			$('#user_rojo').html(nombre_rojo);
			$('#user_rojo').attr('data-user', thisMatch.user_rojo);
			$('#user_azul').html(nombre_azul);
			$('#user_azul').attr('data-user', thisMatch.user_azul);
			$('#puntostotalesrojo').html(thisMatch.puntos_rojo);
			$('#puntostotalesazul').html(thisMatch.puntos_azul);
			$('#marcadorauxiliar [type="radio"]').prop("checked", false);

			if (thisMatch.senshu == 'azul') {
				$('[name="senshu_azul"]').prop('checked', true);
			} else if (thisMatch.senshu == 'rojo') {
				$('[name="senshu_rojo"]').prop('checked', true);
			} else if (thisMatch.hantei == 'azul') {
				$('[name="hantei_azul"]').prop('checked', true);
			} else if (thisMatch.hantei == 'rojo') {
				$('[name="hantei_rojo"]').prop('checked', true);
			}

			if (thisMatch.penalizaciones_azul > 0) {
				$("input[name^='c'][id^='c'][id$='_azul']").each(function () {
					var penalizacionValor = parseInt($(this).val(), 10);
					if (penalizacionValor <= thisMatch.penalizaciones_azul) {
						$(this).prop('checked', true);
					} else {
						$(this).prop('checked', false);
					}
				});
			}

			if (thisMatch.penalizaciones_rojo > 0) {
				$("input[name^='c'][id^='c'][id$='_rojo']").each(function () {
					var penalizacionValor = parseInt($(this).val(), 10);
					if (penalizacionValor <= thisMatch.penalizaciones_rojo) {
						$(this).prop('checked', true);
					} else {
						$(this).prop('checked', false);
					}
				});
			}

			if (thisMatch.hasOwnProperty('rojo')) {
				$('[data-match-rojo]').show();
			} else {
				$('[data-match-rojo]').hide();
			}
			if (thisMatch.hasOwnProperty('azul')) {
				$('[data-match-azul]').show();
			} else {
				$('[data-match-azul]').hide();
			}
			$('#marcadorauxiliar').modal('show');
		}
	});

	// Cuando se cierra la modal, avisamos al marcador de la segunda pantalla
	$('#marcadorauxiliar').on('hidden.bs.modal', function () {
		if (marcadorVentana && !marcadorVentana.closed) {
			marcadorVentana.postMessage({ action: 'hideMatch' }, '*');
		}
	});
});

$(document).on('click', '#guardar-marcador', function () {
	var match_id = $('#marcadorauxiliar').find('[data-match-id]').attr('data-match-id');
	var ul = $('[data-match_id="' + match_id + '"]');

	// 📢 Obtener jugadores
	var user_rojo = $('#user_rojo');
	var user_azul = $('#user_rojo');

	// 📢 Asegurar que los jugadores existen antes de acceder a sus valores
	user_rojo = user_rojo ? $(user_rojo).attr('data-user') : null;
	user_azul = user_azul ? $(user_azul).attr('data-user') : null;

	// 📢 Obtener puntos
	var puntos_rojo = parseInt($('#puntostotalesrojo').html()) || 0;
	var puntos_azul = parseInt($('#puntostotalesazul').html()) || 0;

	// 📢 Validar Senshu (solo puede ser "azul", "rojo" o "")
	var senshu = "";
	if ($('#senshu_azul').prop('checked')) {
		senshu = "azul";
	} else if ($('#senshu_rojo').prop('checked')) {
		senshu = "rojo";
	}

	// 📢 Validar Hantei (solo puede ser "azul", "rojo" o "")
	var hantei = "";
	if ($('#hantei_azul').prop('checked')) {
		hantei = "azul";
	} else if ($('#hantei_rojo').prop('checked')) {
		hantei = "rojo";
	}

	var penalizaciones_azul = $("input[name^='c'][id^='c'][id$='_azul']:checked")
		.map(function () { return this.value; })
		.get()
		.sort()
		.reverse()[0] || 0;

	var penalizaciones_rojo = $("input[name^='c'][id^='c'][id$='_rojo']:checked")
		.map(function () { return this.value; })
		.get()
		.sort()
		.reverse()[0] || 0;

	var fd = new FormData();
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
	// 📢 Determinar el ganador
	var winner = 0;

	if (puntos_rojo > puntos_azul) {
		winner = user_rojo;
	} else if (puntos_azul > puntos_rojo) {
		winner = user_azul;
	} else {
		// 📢 Empate - Determinar ganador por Senshu o Hantei
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
				} else {
					enviarform = false; // 📢 Cancelado, detener la ejecución
				}
			});
			return;
		}
	}
	fd.append("winner", winner);
	if (enviarform) {
		saveMatch(fd);
	}
});

function saveMatch(fd) {
	$.ajax({
		url: base_url + 'Competiciones/updateMatch',
		method: "POST",
		contentType: false,
		processData: false,
		data: fd
	}).done(function (response) {
		var response = JSON.parse(response);
		$('[name="csrf_token"]').val(response.csrf);

		if (response.error > 0) {
			let errorhtml = '';

			if (response.hasOwnProperty('error_validation')) {
				$.each(response.error_validation, function (i, value) {
					errorhtml += value + '<br>';
				});
			}
			if (response.hasOwnProperty('error_msn')) {
				errorhtml += response.error_msn;
			}

			swal.fire({
				icon: 'error',
				title: 'ERROR',
				html: errorhtml,
				willClose: function () {
					if (response.hasOwnProperty('redirect')) {
						if (response.redirect == 'refresh') {
							location.reload();
						} else {
							window.location.href = response.redirect;
						}
					}
				}
			});
			return;
		} else {
			var match = response.match;
			swal.fire({
				icon: 'success',
				title: 'OK',
				html: 'Marcador actualizado correctamente',
				willClose: function () {
					$('#marcadorauxiliar').modal('hide');
					cargar_puntos();
					updateClasificacionGrupo(match.competicion_torneo_id, match.grupo);
					updateMatchesListGrupo(match.competicion_torneo_id, match.grupo)
				}
			});
		}
	}).always(function (jqXHR, textStatus) {
		if (textStatus != "success") {
			swal.fire({
				icon: 'error',
				title: 'Ha ocurrido un error AJAX',
				html: jqXHR.statusText,
				timer: 5000,
				willClose: function () { }
			});
		}
	});
}