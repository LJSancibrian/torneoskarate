url = window.location.href;
segment = url.replace(base_url, '');
array = segment.split('/')
title_file = 'Compras'
var tabla;
tabla = $("#tabla").DataTable({
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
        title: "ID",
        data: "bonoID",
        render: function(data, type, row) {
            html = '<div class="btn-group dropright"><button type="button" class="btn btn- btn-dropdown-card-header btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' + row.bonoID + '</button><div class="dropdown-menu">';

            html += '<div class="dropdown-item">';
            html += '<div class="form-button-action">';
            html += '<a class="btn btn-sm btn-outline-dark" href="#" data-toggle="tooltip" title="Ver compra" data-ver-compra="' + row.ordertpv + '"><i class="fas fa-shopping-cart"></i></a>';
            html += '<a class="btn btn-sm btn-outline-dark" href="#" data-ver-movimientos="' + row.bono + '" data-toggle="tooltip" title="Ver canjeos"><i class="fas fa-ticket-alt"></i></a>';
            html += '<a class="btn btn-sm btn-outline-dark" href="' + base_url + 'assets/bonos/pdf/tarjeta' + row.bono + '.pdf" target="_blank" data-toggle="tooltip" title="Ver tarjeta"><i class="fas fa-qrcode"></i></a>';
            html += '<a class="btn btn-sm btn-outline-dark" data-toggle="tooltip" title="Cancelar tarjeta" data-borrar="' + row.bonoID + '"><i class="fas fa-trash"></i></a></div></div></div>';
            return html
        }
    }, {
        name: "Nº TPV",
        title: "Nº TPV",
        data: "ordertpv",
        /*render: function(data, type, row) {
            html = `<a class="btn btn-sm btn-link" data-ver-compra="${row.ordertpv}"><i class="fas fa-info-circle mr-3"></i>${row.ordertpv}</a>`
            return html
        }*/
    }, {
        name: "Nº Tarjeta",
        title: "Nº Tarjeta",
        data: "bono"
    }, {
        name: "Precio",
        title: "Precio",
        data: "precio"
    }, {
        name: "Valor",
        title: "Valor",
        data: "valor"
    }, {
        name: "Bonificación",
        title: "Bonificación",
        data: "bonificado"
    }, {
        name: "Fecha generación",
        title: "Fecha generación",
        data: "createdAt",
        className: "text-truncate"
    }, {
        name: "Código Promocional",
        title: "Código promocional",
        data: "codigopromocional",
        className: "text-truncate",
        render: function(data, type, row) {
            if (row.codigopromocional != '') {
                html = `<a class="btn btn-sm btn-link" data-ver-codigo="${row.codigopromocional}"><i class="fas fa-info-circle mr-3"></i>${row.codigopromocional}</a>`
                return html
            } else {
                return '';
            }
        }
    }, {
        name: "Canjeado",
        title: "Canjeado",
        data: "canjeado"
    }, {
        name: "Acción",
        title: "Acción",
        data: "bonoID",
        className: "text-truncate",
        render: function(data, type, row) {
            html = '<div class="form-button-action">';
            html += '<a class="btn btn-sm btn-outline-dark" href="#" data-toggle="tooltip" title="Ver compra" data-ver-compra="' + row.ordertpv + '"><i class="fas fa-shopping-cart"></i></a>';
            html += '<a class="btn btn-sm btn-outline-dark" href="#" data-ver-movimientos="' + row.bono + '" data-toggle="tooltip" title="Ver canjeos"><i class="fas fa-ticket-alt"></i></a>';
            html += '<a class="btn btn-sm btn-outline-dark" href="' + base_url + 'assets/bonos/pdf/tarjeta' + row.bono + '.pdf" target="_blank" data-toggle="tooltip" title="Ver tarjeta"><i class="fas fa-qrcode"></i></a>';
            html += '<a class="btn btn-sm btn-outline-dark" data-toggle="tooltip" title="Cancelar tarjeta" data-borrar="' + row.bonoID + '"><i class="fas fa-trash"></i></a>';
            return html
        }
    }, ],
    ajax: {
        url: base_url + "Tarjetas/get_tarjetas",
        type: "GET",
        datatype: "json",
        data: function(data) {
            var estado = $('[name="estado"]').val();
            var dni = $('[name="dni"]').val();
            var fecha_inicio = $('[name="fecha_inicio"]').val();
            var fecha_fin = $('[name="fecha_fin"]').val();

            if (fecha_inicio != '') {
                data.fecha_inicio = fecha_inicio;
            }
            if (fecha_fin != '') {
                data.fecha_fin = fecha_fin;
            }
            if (dni != '') {
                data.dni = dni;
            }
        }
    },
    language: {
        url: base_url + "assets/admin/js/spanish.lang.json"
    },
    createdRow: function(row, data, dataIndex) {
        $(row).attr('data-dni', data.dni).attr('data-ordertpv', data.ordertpv)

    },
    drawCallback: function() {
        // bonificado
        var api = this.api();
        pagina_bonificado = api.column(5).data().sum();
        $('#pagina_bonificado').html(
            pagina_bonificado.toFixed(2) + '€'
        );
        //valor
        pagina_valor = api.column(4).data().sum();
        $('#pagina_valor').html(
            pagina_valor.toFixed(2) + '€'
        );
        //canjeado
        pagina_canjeado = api.column(8).data().sum();
        $('#pagina_canjeado').html(
            pagina_canjeado.toFixed(2) + '€'
        );
        $('[data-toggle="tooltip"]').tooltip()
    },
    columnDefs: [{
        orderable: false,
        targets: -1
    }]
});
$(document).ready(function() {
    var titledoc = 'tarjetas_obulcula';
    var buttons = new $.fn.dataTable.Buttons(tabla, {
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
            }
        ]
    }).container().appendTo($('#buttons'));
})
$('#fecha_inicio, #fecha_fin').datetimepicker({
    locale: 'es',
    format: 'YYYY-MM-DD',
    debug: true
});
$(document).on('click', '#searchdesc', function() {
    tabla.draw();
});


$(document).on('click', '[data-ver-movimientos]', function() {
    var bono = $(this).attr('data-ver-movimientos')
    return fetch(`${base_url}consultarsaldotarjeta/${bono}`)
        .then(resp => resp.json())
        .then(json => {
            if (json.hasOwnProperty('saldo')) {

                Swal.fire({
                    confirmButtonColor: '#d2635f',
                    buttonsStyling: 'background-color: #d2635f; color: #ffffff; font-weight: bolder;',
                    confirmButtonText: 'Cerrar',
                    title: json.msn,
                    html: json.compras
                })
            } else {
                Swal.fire({
                    title: json.msn,
                })
            }
        });
})

$(document).on('click', '[data-ver-compra]', function() {
    var ordertpv = $(this).attr('data-ver-compra');
    var fd = new FormData();
    fd.append("ordertpv", ordertpv);
    fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

    return fetch(`${base_url}Tarjetas/getOrderTarjeta/${ordertpv}`, {
            method: "POST",
            body: fd
        })
        .then(resp => resp.json())
        .then(response => {
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
            }

            if (response.hasOwnProperty('html')) {
                Swal.fire({
                    confirmButtonColor: '#d2635f',
                    buttonsStyling: 'background-color: #d2635f; color: #ffffff; font-weight: bolder;',
                    confirmButtonText: 'Cerrar',
                    html: response.html
                })
            } else {
                Swal.fire({
                    title: json.msn,
                })
            }
        });
})

$(document).on('click', '[data-ver-codigo]', function() {
    var codigo = $(this).attr('data-ver-codigo');
    var fd = new FormData();
    fd.append("codigo", codigo);
    fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

    return fetch(`${base_url}Tarjetas/getCodigoTarjeta/${codigo}`, {
            method: "POST",
            body: fd
        })
        .then(resp => resp.json())
        .then(response => {
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
            }

            if (response.hasOwnProperty('html')) {
                Swal.fire({
                    confirmButtonColor: '#d2635f',
                    buttonsStyling: 'background-color: #d2635f; color: #ffffff; font-weight: bolder;',
                    confirmButtonText: 'Cerrar',
                    html: response.html
                })
            } else {
                Swal.fire({
                    title: json.msn,
                })
            }
        });
})



tabla.on('xhr', function() {
    var json = tabla.ajax.json();
    $('#total_bonificado').html(
        json.bonificado + '€'
    );
    $('#total_valor').html(
        json.valor + '€'
    );
    $('#total_canjeado').html(
        json.canjeado + '€'
    );

});



$(document).on('click', '#add-new-codes', function() {
    var modal = '<div id="modalTemp" class="modal fade" tabindex="-1" role="dialog">';
    modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
    modal += '<div class="modal-content">';
    modal += '<div class="modal-body">';
    modal += '<p style="font-size: 20px;text-align:center"><span style="font-size:24px; font-weight:bold;">Completa los campos para generar las tarjetas</span></p>';

    modal += '<div class="input-group mx-1 my-1">';
    modal += '<div class="input-group-prepend"><span class="input-group-text bg-primary text-white" style="text-align:center; width: 140px; display:block">Nº de tarjetas</span></div>';
    modal += '<input type="number" class="form-control" name="cantidad_generar"  id="cantidad_generar"/>';
    modal += '</div>';

    modal += '<div class="input-group mx-1 my-1">';
    modal += '<div class="input-group-prepend"><span class="input-group-text bg-primary text-white" style="text-align:center; width: 140px;display:block;">Precio (€)</span></div>';
    modal += '<input type="number" class="form-control" name="precio_generar"  id="precio_generar"/>';
    modal += '</div>';

    modal += '<div class="input-group mx-1 my-1">';
    modal += '<div class="input-group-prepend"><span class="input-group-text bg-primary text-white" style="text-align:center; width: 140px;display:block;">Valor (€)</span></div>';
    modal += '<input type="number" class="form-control" name="valor_generar"  id="valor_generar"/>';
    modal += '</div>';

    modal += '<div class="input-group mx-1 my-1">';
    modal += '<div class="input-group-prepend"><span class="input-group-text bg-primary text-white" style="text-align:center; width: 140px;display:block;">Caducidad</span></div>';
    modal += '<input type="date" class="form-control" name="caducidad_generar"  id="caducidad_generar"/>';
    modal += '</div>';

    modal += '<div class="input-group mx-1 my-1">';
    modal += '<div class="input-group-prepend"><span class="input-group-text bg-primary text-white" style="text-align:center; width: 140px;display:block;">Destinatario</span></div>';
    modal += '<input type="text" class="form-control" name="first_name"  id="first_name"/>';
    modal += '</div>';

    modal += '<div class="input-group mx-1 my-1">';
    modal += '<div class="input-group-prepend"><span class="input-group-text bg-primary text-white" style="text-align:center; width: 140px;display:block;">Plantilla</span></div>';
    modal += '<select class="form-control" name="plantilla"  id="plantilla">';
    modal += '<option value="base">Base</option>';
    modal += '</select>';

    modal += '</div>';
    modal += '<div class="modal-footer">';
    modal += '<button type="button" class="btn btn-primary" onclick="generartarjetas();" data-dismiss="modal" data-backdrop="false">Generar</button>';
    modal += '<button type="button" class="btn btn-secondary" data-dismiss="modal">No, cerrar</button>';
    modal += '</div></div></div></div>';
    $("body").append(modal);
    $('#modalTemp').modal('show');
});


function generartarjetas() {
    var cantidad = $('input[name="cantidad_generar"]').val()
    var precio = $('input[name="precio_generar"]').val()
    var valor = $('input[name="valor_generar"]').val()
    var caducidad = $('input[name="caducidad_generar"]').val()
    var first_name = $('input[name="first_name"]').val()
    var plantilla = $('[name="plantilla"]').val()

    var fd = new FormData();
    fd.append("cantidad", cantidad);
    fd.append("precio", precio);
    fd.append("valor", valor);
    fd.append("caducidad", caducidad);
    fd.append("first_name", first_name);
    fd.append("plantilla", plantilla);
    fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: '¿Generar tarjetas con las condiciones indicadas?',
        showCancelButton: true,
        confirmButtonText: 'Si, generar ',
        cancelButtonText: 'Cerrar sin cambios',
        willClose: function() {
            $('#modalTemp').modal('hide');
            $('#modalTemp').remove();
        }
    }).then((result) => {
        $.ajax({
            url: base_url + "Tarjetas/generarTarjetas",
            method: "POST",
            contentType: false,
            processData: false,
            data: fd,
            beforeSend: function() {
                swal.fire({
                    icon: 'info',
                    title: 'No cierres esta página',
                    html: 'Generando las tarjetas',
                    showCancelButton: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                })
            }
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
                swal.close()
                swal.fire({
                    icon: 'error',
                    title: 'ERROR',
                    html: errorhtml,
                });
                return;
            } else {
                swal.close()
                swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    html: response.msn,
                    willClose: function() {
                        $('#modalTemp').modal('hide');
                        $('#modalTemp').remove();
                        tabla.draw();
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
    });
}