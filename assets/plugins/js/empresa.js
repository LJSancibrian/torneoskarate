$(document).ready(function() {
    $('#guardar-datos').click(function() {
        swal.fire({
            icon: 'question',
            title: 'Confirmar acción',
            html: '¿Guardar los datos de la empresa?',
            showCancelButton: true,
            confirmButtonText: 'Si, crear',
            cancelButtonText: 'Cerrar sin cambios',
        }).then((result) => {
            if (result.isConfirmed) {
                var fd = new FormData();
                fd.append("empresaID", $('[name="empresaID"]').val());
                fd.append("razon_social", $("#razon_social").val());
                fd.append("nombrecomercial", $("#nombrecomercial").val());
                fd.append("direccion", $("#direccion").val());
                fd.append("cp", $("#cp").val());
                fd.append("localidad", $("#localidad").val());
                fd.append("provincia", $("#provincia").val());
                fd.append("cif", $("#cif").val());
                fd.append("responsable", $("#responsable").val());
                fd.append("email", $("#email").val());
                fd.append("phone", $("#phone").val());
                fd.append("notas", $("#notas").val());
                fd.append("sector", $("#sector").val());
                fd.append("titulariban", $("#titulariban").val());
                fd.append("iban", $("#iban").val());
                fd.append("newpassword", $("#newpassword").val());
                fd.append("confirmpassword", $("#confirmpassword").val());
                fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());
                if ($('#estado').is(':checked')) {
                    fd.append("estado", $("#estado").val());
                }
                $.ajax({
                    url: base_url + "Gestion/editar_empresa_form",
                    method: "POST",
                    contentType: false,
                    processData: false,
                    data: fd
                }).done(function(response) {
                    var response = JSON.parse(response);
                    $('[name="csrf_fecos"]').val(response.csrf)
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
                        swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            html: errorhtml,
                        });
                        return;
                    } else {
                        $('#nameempresa').html($("#razon_social").val());
                        swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            html: response.msn,
                            willClose: function() {
                                //window.location.href = response.redirect;
                            }
                        })
                    }
                }).always(function(jqXHR, textStatus) {
                    if (textStatus != "success") {
                        swal.fire({
                            icon: 'error',
                            title: 'Ha ocurrido un error AJAX',
                            html: jqXHR.statusText,
                            timer: 5000,
                            willClose: function() {
                                //window.location.reload();
                            }
                        })
                    }
                });
            }
        });
    });

    $('#eliminar-empresa').click(function() {
        swal.fire({
            icon: 'question',
            title: 'Confirmar acción',
            html: '¿Eliminar la empresa?. Aun eliminando, no se podrá registrar otra empresa con el mismo CIF',
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cerrar sin cambios',
        }).then((result) => {
            if (result.isConfirmed) {
                var fd = new FormData();
                fd.append("empresaID", $('[name="empresaID"]').val());
                fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());
                $.ajax({
                    url: base_url + "Gestion/eliminar_empresa_form",
                    method: "POST",
                    contentType: false,
                    processData: false,
                    data: fd
                }).done(function(response) {
                    var response = JSON.parse(response);
                    $('[name="csrf_fecos"]').val(response.csrf)
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
                        swal.fire({
                            icon: 'error',
                            title: 'ERROR',
                            html: errorhtml,
                        });
                        return;
                    } else {
                        $('#nameempresa').html($("#razon_social").val());
                        swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            html: response.msn,
                            willClose: function() {
                                window.location.href = base_url + 'gestion-empresas';
                            }
                        })
                    }
                }).always(function(jqXHR, textStatus) {
                    if (textStatus != "success") {
                        swal.fire({
                            icon: 'error',
                            title: 'Ha ocurrido un error AJAX',
                            html: jqXHR.statusText,
                            timer: 5000,
                            willClose: function() {
                                //window.location.reload();
                            }
                        })
                    }
                });
            }
        });
    });

});

$(document).on('click', '#searchdesc', function() {
    table.draw();
})

function appendLeadingZeroes(n) {
    if (n <= 9) {
        return "0" + n;
    }
    return n
}

var table;
table = $('#tablaempresa').DataTable({
    info: true,
    paging: true,
    ordering: true,
    searching: true,
    stateSave: true,
    processing: true,
    serverSide: true,
    scrollX: true,
    autoWidth: false,
    order: [0, "desc"],
    pageLength: 50,
    lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, 'Todos']
    ],
    columns: [{
        name: "ID",
        title: '<div class="form-check text-center"><input class="form-check-input" type="checkbox" name="selectAll" id="selectAll"><label class="form-check-label" for="selectAll">Todos</label></div>',
        data: "descuentoID",
        render: function(data, type, row) {
            var tdid = '';
            if (row.fecha_amortizacion == '0000-00-00 00:00:00') {
                var tdid = '<div class="form-check text-center"><input class="form-check-input" type="checkbox" name="descuentoID[]" value="' + row.descuentoID + '"></div>';
            }
            return tdid;
        }
    }, {
        name: "ID",
        title: "ID",
        data: "descuentoID",
    }, {
        name: "Nº Tarjeta",
        title: "Nº Tarjeta",
        data: "dni"
    }, {
        name: "Fecha - Hora",
        title: "Fecha - Hora",
        data: "createdAt"
    }, {
        name: "Cantidad",
        title: "Cantidad",
        data: "cantidad"
    }, {
        name: "Fecha Pagado",
        title: "Fecha Pagado",
        data: "fecha_amortizacion",
        render: function(data, type, row) {
            if (row.fecha_amortizacion == '0000-00-00 00:00:00') {
                var fecha_amortizacion = '-'
            } else {
                var fecha_amortizacion = new Date(row.fecha_amortizacion);
                fecha_amortizacion = fecha_amortizacion.getFullYear() + '-' + appendLeadingZeroes(fecha_amortizacion.getMonth() + 1) + '-' + appendLeadingZeroes(fecha_amortizacion.getDate())
            }
            return fecha_amortizacion;
        }
    }, {
        name: "Acción",
        title: "Acción",
        data: "descuentoID",
        render: function(data, type, row) {
            html = `
	               <button class="btn btn-sm btn-link" data-borrar="${row.descuentoID}"><i class="fas fa-trash"></i></button>`
            return html
        }
    }],
    language: {
        url: base_url + "assets/admin/js/spanish.lang.json"
    },
    ajax: {
        url: base_url + "Descuentos/get_descuentos_empresa",
        type: "GET",
        datatype: "json",
        data: function(data) {
            var fecha_inicio = $('[name="fecha_inicio"]').val();
            var fecha_fin = $('[name="fecha_fin"]').val();

            if (fecha_inicio != '') {
                data.fecha_inicio = fecha_inicio;
            }
            if (fecha_fin != '') {
                data.fecha_fin = fecha_fin;
            }
            data.empresaID = $('[name="empresaID"]').val();
        }
    },
    drawCallback: function() {
        var api = this.api();
        total_descuentos = api.column(4, {
            page: 'current'
        }).data().sum();

        $('#total_descuentos').html(
            total_descuentos.toFixed(2) + '€'
        );
        pagina_descuentos = api.column(4).data().sum();

        $('#pagina_descuentos').html(
            pagina_descuentos.toFixed(2) + '€'
        );
    },
    columnDefs: [{
        orderable: false,
        targets: [-1]
    }]
});
var titledoc = 'commercium_empresas';
var buttons = new $.fn.dataTable.Buttons(table, {
    buttons: [
        'copyHtml5', {
            extend: 'excelHtml5',
            title: titledoc
        }, {
            extend: 'csvHtml5',
            title: titledoc
        }, {
            extend: 'pdfHtml5',
            title: titledoc
        },
        'pdfHtml5', {
            text: 'Marcar como pagados',
            className: 'btn btn-danger ',
            action: function(e, dt, node, config) {
                marcar_pagados();
            }
        }
    ]
}).container().appendTo($('#buttons'));

$('#fecha_inicio, #fecha_fin').datetimepicker({
    locale: 'es',
    format: 'YYYY-MM-DD',
    debug: true
});

$(document).on('click', '[name="selectAll"]', function() {
    $('[name="descuentoID[]"]').prop("checked", this.checked);
});

$(document).on('click', '[name="descuentoID[]"]', function() {
    if ($('[name="descuentoID[]"]').length == $('[name="descuentoID[]"]:checked').length) {
        $('[name="selectAll"]').prop("checked", true);
    } else {
        $('[name="selectAll"]').prop("checked", false);
    }
});

function marcar_pagados() {
    var data = $('input[name="descuentoID[]"]').serializeArray()
    if (data.length > 0) {
        swal.fire({
            icon: 'question',
            title: 'Confirmar acción',
            html: '¿Marcar los descuentos seleccionados como ya pagados?',
            showCancelButton: true,
            confirmButtonText: 'Si, marcar pagados',
            cancelButtonText: 'Cerrar sin cambios',
        }).then((result) => {
            var modal = '<div id="modalTempPagado" class="modal fade" tabindex="-1" role="dialog">';
            modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
            modal += '<div class="modal-content">';
            modal += '<div class="modal-body">';
            modal += '<p style="font-size: 20px;text-align:center"><span style="font-size:24px; font-weight:bold;">Indica la fecha de pago de los descuentos marcados</span></p>';
            modal += '<div class="input-group date mx-1 my-1" id="fecha_pagado" data-target-input="nearest">';
            modal += '<input type="text" class="form-control datetimepicker-input" data-target="#fecha_pagado" name="fecha_pagado" placeholder="Fecha de pago" data-target="#fecha_pagado" data-toggle="datetimepicker"/>';
            modal += '<div class="input-group-append" data-target="#fecha_pagado" data-toggle="datetimepicker">';
            modal += '<div class="input-group-text bg-primary text-white"><i class="fas fa-calendar-alt"></i></div>';
            modal += '</div>';
            modal += '</div>';

            modal += '</div>';
            modal += '<div class="modal-footer">';
            modal += '<button type="button" class="btn btn-primary" onclick="marcar_pagados_confirm();">Marcar como pagados</button>';
            modal += '<button type="button" class="btn btn-secondary" data-dismiss="modal">No, cerrar</button>';
            modal += '</div></div></div></div>';
            $("body").append(modal);
            $('#fecha_pagado').datetimepicker({
                locale: 'es',
                format: 'YYYY-MM-DD'
            });
            $('#modalTempPagado').modal('show')
        })
    } else {
        var modal = '<div id="modalTempPagado" class="modal fade" tabindex="-1" role="dialog">';
        modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
        modal += '<div class="modal-content">';
        modal += '<div class="modal-body">';
        modal += '<p style="font-size: 20px;text-align:center"><span style="font-size:24px; font-weight:bold;">No hay descuentos marcados</span></p>';
        modal += '</div>';
        modal += '<div class="modal-footer">';
        modal += '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>';
        modal += '</div></div></div></div>';
        $("body").append(modal);
        $('#modalTempPagado').modal('show')
    }
}

function marcar_pagados_confirm(event) {
    var fecha_pagado = $('[name="fecha_pagado"]').val()
    if (fecha_pagado != '') {
        $('#modalTempPagado').modal('hide')
        var descuentoIDs = [];
        var checkboxes = $('input[name="descuentoID[]"]:checked');
        $.each(checkboxes, function(key, val) {
            descuentoIDs.push($(val).val())
        });

        var fd = new FormData();
        fd.append("descuentoID", descuentoIDs);
        fd.append("fecha_amortizacion", fecha_pagado);
        fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

        $.ajax({
            url: base_url + "Descuentos/marcarDescuentoPagado",
            method: "POST",
            contentType: false,
            processData: false,
            data: fd
        }).done(function(response) {
            var response = JSON.parse(response);
            $('[name="csrf_fecos"]').val(response.csrf)
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
                swal.fire({
                    icon: 'error',
                    title: 'ERROR',
                    html: errorhtml,
                });
                return;
            } else {
                $('#nameempresa').html($("#razon_social").val());
                swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    html: response.msn,
                    willClose: function() {
                        $.each(checkboxes, function(key, val) {
                            $(val).closest('.form-check').remove()
                        });
                    }
                })
            }
        }).always(function(jqXHR, textStatus) {
            if (textStatus != "success") {
                swal.fire({
                    icon: 'error',
                    title: 'Ha ocurrido un error AJAX',
                    html: jqXHR.statusText,
                    timer: 5000,
                    willClose: function() {
                        //window.location.reload();
                    }
                })
            }
        });

        /*var par = new Object();
        par.id_descuentos = $('input[name="id_descuento[]').serializeArray();
        par.fecha_pagado = $('[name="fecha_pagado"]').val();
        var settings = {
        	"url": base_url + "Descuentos/marcarDescuentoPagado",
        	"method": "POST",
        	"timeout": 0,
        	"headers": {
        		"Content-Type": "application/json"
        	},
        	"data": JSON.stringify({
        		"token": localStorage.getItem("penaGestion"),
        		"par": par
        	}),
        };
        $.ajax(settings).done(function(response) {
        	if (response.error == 0) {
        		showmsg(response.msg)
        		$('#searchdesc').click();
        	} else {
        		showmsg(response.msg)
        	}
        }).fail(function(response) {
        	showmsg(response.responseJSON.msg)
        });*/


    } else {
        swal.fire({
            icon: 'error',
            html: 'Indica la fecha de pago',
            timer: 5000,
            willClose: function() {}
        })
    }
}