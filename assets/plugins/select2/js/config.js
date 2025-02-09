document.querySelectorAll('.select2').forEach(function (element) {
	$(element).select2({
		theme : "bootstrap",
		language: "es",
		minimumInputLength: 2,
		allowClear: true,
	});
});

function initializeSelectAjax() {

	

	document.querySelectorAll('.selectajax').forEach(function (element) {
		const ajaxUrl = element.getAttribute('data-url');
		const selectedValue = JSON.parse(element.getAttribute('data-selected') || null);
		$(element).select2({
			theme : "bootstrap",
			language: "es",
			minimumInputLength: 2,
			allowClear: true,
			ajax: {
				delay: 500,
				url: function (params) {
					console.log('Término buscado:', params.term);
					const terms = params.term.replace(/\s+/g, '-');
					return ajaxUrl + terms;
				},
				dataType: 'json',
				processResults: function (data) {
					return {
						results: data.map(item => ({ id: item.id, text: item.name }))
					};
				},
				error: function (xhr, status, error) {
					console.error('Error en la solicitud AJAX:', status, error);
					showalert({
						title: 'Error al cargar datos',
						text: 'Hubo un problema al intentar obtener los resultados. Por favor, inténtalo de nuevo más tarde.',
						icon: 'error',
						buttons: false,
						timer: 3000
					});
				}
			}
		});

		if (selectedValue) {
			const option = new Option(selectedValue.text, selectedValue.id, true, true);
			$(element).append(option).trigger('change');
		}
	});
}