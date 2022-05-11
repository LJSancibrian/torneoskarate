var tabla;
var titledoc = 'usuarios';
$.extend($.fn.dataTable.ext.classes, {
    sWrapper: "dataTables_wrapper dt-bootstrap4",
    sFilterInput: "form-control",
    sLengthSelect: "custom-select form-control",
    sProcessing: "dataTables_processing card",
    sPageButton: "paginate_button page-item"
});

tabla = $("#tabla").DataTable({
    processing: true,
    serverSide: true,
    order: [1, "asc"],
    columns: [{ //0
        title: "ID",
        name: "ID",
        data: "id",
    }, { //1 
        title: "Nombre",
        name: "Nombre",
        data: "first_name"
    }, { //2 
        title: "Apellidos",
        name: "Apellidos",
        data: "first_name"
    }, { //3
        title: "Telefono",
        name: "phone",
        data: "phone",
    }, { //4
        title: "Email",
        name: "email",
        data: "email",
    }, { //5
        title: "Estado",
        name: "estado",
        data: "active",
    }],
    columnDefs: [{
        targets: [0, 1, 2, 3, 4, 5],
        visible: true
    }, {
        targets: ["_all"],
        visible: false
    }, {
        targets: [],
        orderable: false
    }],
    ajax: {
        url: base_url + 'Usuarios/getUsuarios',
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
        },
        className: 'btn btn-primary',
        init: function(api, node, config) {
            $(node).removeClass('btn-secondary')
        },
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
        $(row).attr('data-userid', data.id);
        $(row).attr('data-nombre', data.first_name + ' ' + data.last_name);
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



$(document).on('click', '#tabla tbody tr', function() {
    userid = $(this).data('userid')
    nombre = $(this).data('nombre')

    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: 'Ir a la vista de <span style="font-size:24px; font-weight:bold;">' + nombre + '</span>?',
        showCancelButton: true,
        confirmButtonText: 'Si, ir ',
        cancelButtonText: 'No, cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + 'ver-usuario/' + userid
        }
    })

});