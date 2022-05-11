var tablaempresas;
var titledoc = 'empresas';
$.extend($.fn.dataTable.ext.classes, {
    sWrapper: "dataTables_wrapper dt-bootstrap4",
    sFilterInput: "form-control",
    sLengthSelect: "custom-select form-control",
    sProcessing: "dataTables_processing card",
    sPageButton: "paginate_button page-item"
});

tablaempresas = $("#tablaempresas").DataTable({
    processing: true,
    serverSide: true,
    order: [1, "asc"],
    columns: [{ //0
        title: "ID",
        name: "empresaID",
        data: "empresaID",
        render: function(data, type, row) {
            html = '<div class="btn-group dropright"><button type="button" class="btn btn- btn-dropdown-card-header btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' + row.empresaID + '</button><div class="dropdown-menu">';
            html += '<div class="dropdown-item">';
            html += '<div class="form-button-action">';
            html += '<a class="btn btn-sm btn-outline-dark" href="#" data-empresa-id="' + row.empresaID + '" data-empresa="' + row.razon_social + '" data-toggle="tooltip" title="Ver empresa"><i class="fas fa-edit"></i></a>';
            html += '<a class="btn btn-sm btn-outline-primary" href="#" data-email_id_empresa="' + row.empresaID + '" data-email_empresa="' + row.razon_social + '" data-toggle="tooltip" title="Regenerar contraseña"><i class="fas fa-envelope"></i></a>';
            html += '</div></div></div>';
            return html
        }
    }, { //1 
        title: "CIF",
        name: "cif",
        data: "cif"
    }, { //2 
        title: "Razón social",
        name: "razon_social",
        data: "razon_social"
    }, { //3
        title: "Telefono",
        name: "phone",
        data: "phone",
    }, { //4
        title: "Email",
        name: "email",
        data: "email",
    }, { //5
        title: "Responsable",
        name: "responsable",
        data: "responsable",
    }, { //6
        title: "Sector",
        name: "Sector",
        data: "sector",
    }, { //7
        title: "Estado",
        name: "estado",
        data: "estado",
    }, { //8
        title: "Canjeado",
        name: "Canjeado",
        data: "canjeado",
    }, {
        title: "Acciones",
        name: "acciones",
        className: "text-truncate",
        render: function(data, type, row) {
            html = '<div class="form-button-action">';
            html += '<a class="btn btn-sm btn-outline-dark" href="#" data-empresa-id="' + row.empresaID + '" data-empresa="' + row.razon_social + '" data-toggle="tooltip" title="Ver empresa"><i class="fas fa-edit"></i></a>';
            html += '<a class="btn btn-sm btn-outline-primary" href="#" data-email_id_empresa="' + row.empresaID + '" data-email_empresa="' + row.razon_social + '" data-toggle="tooltip" title="Regenerar contraseña"><i class="fas fa-envelope"></i></a>';
            html += '</div></div>';
            return html
        }
    }],
    columnDefs: [{
        targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        visible: true
    }, {
        targets: ["_all"],
        visible: false
    }, {
        targets: [],
        orderable: false
    }],
    ajax: {
        url: base_url + 'Gestion/getEmpresas',
        type: "GET",
        datatype: "json",
        data: function(data) {
            var sector = $('[name="filterselector"]').val();
            if (sector != '') {
                data.sector = sector;
            }
        }
    },
    buttons: [{
        text: 'Exportar',
        extend: 'excelHtml5',
        title: titledoc,
        className: 'btn btn-primary',
        attr: {
            "data-tooltip": 'Exportar tabla en excel',
            "data-placement": 'auto',
            "title": 'Exportar tabla en excel'
        },
        exportOptions: {
            columns: ':not(.noexp)'
        },
        init: function(api, node, config) {
            $(node).removeClass('btn-secondary')
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
            //$(row).attr('data-empresa-id', data.empresaID);
            //$(row).attr('data-empresa', data.razon_social);
    },
    drawCallback: function(settings) {
        $('[data-tooltip]').tooltip();
        var api = this.api(),
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
        $('#total').html(total.toFixed(2) + '€');
        $('[data-toggle="tooltip"]').tooltip()
    },
    initComplete: function() {
        var json = tablaempresas.ajax.json();
        $('.dt-buttons').parent('div').addClass('d-flex').append('<div class="filterselector mx-2"></div>');
        var select = $('<select class="form-control btn-primary" name="filterselector" id="filterselector" style="max-width:250px;"><option value="">Selecciona un sector</option></select>').appendTo($('.filterselector').empty())
        $.each(json.sectores, function(key, val) {
            if (val.sector != '') {
                $('#filterselector').append('<option value="' + val.sector + '">' + val.sector + '</option>')
            }
        });
    }
});

//$(document).on('click', '#tablaempresas tbody tr', function() {
$(document).on('click', '[data-empresa-id]', function() {
    empresaID = $(this).data('empresa-id')
    empresa = $(this).data('empresa')

    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: 'Ir a la vista de <span style="font-size:24px; font-weight:bold;">' + empresa + '</span>?',
        showCancelButton: true,
        confirmButtonText: 'Si, ir ',
        cancelButtonText: 'No, cerrar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + 'gestion-ver-empresa/' + empresaID
        }
    })
});

$(document).on('change', '[name="filterselector"]', function() {
    tablaempresas.draw();
})

$(document).on('click', '[data-email_id_empresa]', function() {
    id_empresa = $(this).data('email_id_empresa')
    empresa = $(this).data('email_empresa')
    var modal = '<div id="modalTemp" class="modal fade" tabindex="-1" role="dialog">';
    modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
    modal += '<div class="modal-content">';
    modal += '<div class="modal-body">';
    modal += '<p style="font-size: 20px;text-align:center">¿Enviar de nuevo en email de bienvenida a la empresa <span style="font-size:24px; font-weight:bold;">' + empresa + '</span>?</p>';
    modal += '<p style="font-size: 16px;text-align:center">Se cambiará la contraseña por una nueva aleatoria que se enviará en el email, quedando la anterior contraseña anulada</p>';
    modal += '</div>';
    modal += '<div class="modal-footer">';
    modal += '<button type="button" class="btn btn-primary" onclick="reenviar_email(\'' + id_empresa + '\')">Si, reenviar email</button>';
    modal += '<button type="button" class="btn btn-secondary" data-dismiss="modal">No, cerrar</button>';
    modal += '</div></div></div></div>';
    $("body").append(modal);
    $('#modalTemp').modal('show')
});

function reenviar_email(id_empresa) {
    var fd = new FormData();
    fd.append("empresaID", id_empresa);
    $.ajax({
        url: base_url + "Gestion/enviarEmailBienvenida",
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function(response) {
        $('#modalTemp').modal('hide')
        var response = JSON.parse(response);
        if (response.error > 0) {
            var errorhtml = ''
            if (response.hasOwnProperty('error_validation')) {
                $.each(response.error_validation, function(i, value) {
                    errorhtml += value + '<br>'
                })
            }
            if (response.hasOwnProperty('error_msn')) {
                errorhtml += response.error_msn
            }
            setTimeout(function() {
                showmsg(errorhtml);
            }, 500)
            return;
        } else {
            setTimeout(function() {
                showmsg(response.msn);
            }, 500)
            return;
        }
    }).always(function(jqXHR, textStatus) {
        if (textStatus != "success") {
            showmsg(jqXHR.statusText);
        }
    });
}