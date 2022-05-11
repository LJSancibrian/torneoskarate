var tablapromociones;
var titledoc = 'empresas';
$.extend($.fn.dataTable.ext.classes, {
	sWrapper: "dataTables_wrapper dt-bootstrap4",
	sFilterInput: "form-control",
	sLengthSelect: "custom-select form-control",
	sProcessing: "dataTables_processing card",
	sPageButton: "paginate_button page-item"
});

tablapromociones = $("#tablapromociones").DataTable({
	processing: true,
	serverSide: true,
	order: [1, "asc"],
	columns: [{ //0
		title: "ID",
		name: "promocionID",
		data: "promocionID",
	}, { //1 
		title: "Nombre",
		name: "nombre",
		data: "nombre"
	}, { //2 
		title: "Tipo promociónl",
		name: "tipo",
		data: "tipo"
	}, { //3
		title: "Descripción",
		name: "descripcion",
		data: "descripcion",
	}, { //4
		title: "Inicio",
		name: "startime",
		data: "startime",
	}, { //5
		title: "Final",
		name: "endtime",
		data: "endtime",
	}, { //6
		title: "Límite canje",
		name: "limittime",
		data: "limittime",
	}],
	columnDefs: [{
		targets: [0, 1, 2, 3, 4, 5, 6],
		visible: true
	}, {
		targets: ["_all"],
		visible: false
	}, {
		targets: [0],
		orderable: false
	}],
	ajax: {
		url: base_url + 'Gestion/getPromociones',
		type: "GET",
		datatype: "json",
		data: function(data) {}
	},
	buttons: [{
		text: 'Exportar',
		extend: 'excelHtml5',
		title: titledoc,
		action: newExportAction,
		attr: {
			"data-tooltip": 'Exportar tabla en excel',
			"data-placement": 'auto',
			"title": 'Exportar tabla en excel'
		},
		exportOptions: {
			columns: ':not(.noexp)'
		}
	}],
	language: {
		url: assets_url + "lib/DataTables/languages/spanish_alt.lang.json"
	},
	dom: "<'d-flex flex-wrap justify-content-between'<''l><''B><''f>>" +
		"<'row'<'col-sm-12'tr>>" +
		"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
	createdRow: function(row, data, dataIndex) {
		var i = 0;
		$.each(data, function(index, value) {
			var ti = i + 1;
			var thead = $('tr th:nth-child(' + ti + ')').text()
			$(row).find('td:eq(' + i + ')').attr('thead', thead);
			i++
		})
		$(row).attr('data-promocion-id', data.promocionID);
		$(row).attr('data-promocion', data.nombre);
	},
	drawCallback: function(settings) {
		$('[data-tooltip]').tooltip();
		/*var api = this.api(),
			data;
		var intVal = function(i) {
			return typeof i === 'string' ?
				i.replace(/[\$,]/g, '') * 1 :
				typeof i === 'number' ?
				i : 0;
		};
		var total = api
			.column(2)
			.data()
			.reduce(function(a, b) {
				return intVal(a) + intVal(b);
			}, 0);
		var pageTotal = api
			.column(2, {
				page: 'current'
			})
			.data()
			.reduce(function(a, b) {
				return intVal(a) + intVal(b);
			}, 0);
		$('#total').html(total.toFixed(2) + '€');*/
	},
	initComplete: function() {}
});

$(document).on('click', '#tablapromociones tbody tr', function() {
	promocionID = $(this).data('promocion-id')
	nombre = $(this).data('nombre')

	swal.fire({
		icon: 'question',
		title: 'Confirmar acción',
		html: 'Ver la promoción <span style="font-size:24px; font-weight:bold;">' + nombre + '</span>?',
		showCancelButton: true,
		confirmButtonText: 'Si, ir ',
		cancelButtonText: 'No, cerrar',
	}).then((result) => {
		if (result.isConfirmed) {
			window.location.href = base_url + 'gestion-ver-promocion/' + promocionID
		}
	})
});