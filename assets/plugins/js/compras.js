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
        data: "orderID",
        render: function(data, type, row) {
            html = '<div class="btn-group dropright"><button type="button" class="btn btn- btn-dropdown-card-header btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' + row.orderID + '</button><div class="dropdown-menu">';
            html += '<div class="dropdown-item">';
            html += '<div class="form-button-action">';
            if (row.estado == 1) {
                html += '<a class="btn btn-sm btn-outline-dark" data-sendmail="' + row.ordertpv + '" data-toggle="tooltip" title="Enviar tarjetas al email"><i class="fas fa-envelope"></i></a>';
            }
            if (row.estado == 0) {
                html += '<a class="btn btn-sm btn-outline-dark" data-completar="' + row.ordertpv + '" data-toggle="tooltip" title="Marcar como pagado"><i class="fas fa-shopping-cart"></i></a>';
            }
            html += '<a class="btn btn-sm btn-outline-dark" data-borrar="' + row.orderID + '" data-toggle="tooltip" title="Cancelar compra"><i class="fas fa-trash"></i></a>';
            html += '</div></div></div>';
            return html
        }
    }, {
        name: "Nombre",
        title: "Nombre",
        data: "first_name"
    }, {
        name: "Apellidos",
        title: "Apellidos",
        data: "last_name"
    }, {
        name: "DNI",
        title: "DNI",
        data: "dni"
    }, {
        name: "email",
        title: "Email",
        data: "email"
    }, {
        name: "Teléfono",
        title: "Teléfono",
        data: "phone"
    }, {
        name: "Total",
        title: "Total",
        data: "total"
    }, {
        name: "Estado",
        title: "Estado",
        data: "estado",
        render: function(data, type, row) {
            if (row.estado == 1) {
                var html = 'Pagado'
            } else if (row.estado == 2) {
                var html = 'Cancelado'
            } else if (row.estado == 3) {
                var html = 'Generado'
            } else {
                var html = 'Sin completar'
            }
            return html
        }
    }, {
        name: "Nº TPV",
        title: "Nº TPV",
        data: "ordertpv"
    }, {
        name: "Fecha - Hora",
        title: "Fecha - Hora",
        data: "createdAt",
        className: "text-truncate"
    }, {
        name: "Acción",
        title: "Acción",
        data: "descuentoID",
        render: function(data, type, row) {
            html = '<div class="form-button-action">';
            if (row.estado == 1) {
                html += '<a class="btn btn-sm btn-outline-dark" data-sendmail="' + row.ordertpv + '" data-toggle="tooltip" title="Enviar tarjetas al email"><i class="fas fa-envelope"></i></a>';
            }
            if (row.estado == 0) {
                html += '<a class="btn btn-sm btn-outline-dark" data-completar="' + row.ordertpv + '" data-toggle="tooltip" title="Marcar como pagado"><i class="fas fa-shopping-cart"></i></a>';
            }
            html += '<a class="btn btn-sm btn-outline-dark" data-borrar="' + row.orderID + '" data-toggle="tooltip" title="Cancelar compra"><i class="fas fa-trash"></i></a>';
            html += '</div></div>';
            return html
        }
    }, ],
    ajax: {
        url: base_url + "Compras/get_compras",
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
            if (estado != '') {
                data.estado = estado;
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
        pagina_compras = api.column(6).data().sum();
        $('#pagina_compras').html(
            pagina_compras.toFixed(2) + '€'
        );
        $('[data-toggle="tooltip"]').tooltip()
    },
    columnDefs: [{
        orderable: false,
        targets: -1
    }]
});
$(document).ready(function() {
    var titledoc = 'commercium_compras';
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

tabla.on('xhr', function() {
    var json = tabla.ajax.json()
    $('#total_compras').html(
        json.total + '€'
    );

});

$('#fecha_inicio, #fecha_fin').datetimepicker({
    locale: 'es',
    format: 'YYYY-MM-DD',
    debug: true
});
$(document).on('click', '#searchdesc', function() {
    tabla.draw();
})


$(document).on('click', '[data-completar]', function() {
    var ordertpv = $(this).data('completar')
    var importe = $(this).data('importe')
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: '¿Completar la compra indicada?',
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonText: 'Si, completar',
        cancelButtonText: 'Cerrar sin cambios',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            var fd = new FormData();
            fd.append("ordertpv", ordertpv);
            fd.append("importe", importe);
            fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());
            $.ajax({
                url: base_url + "Compras/gestionarPedido",
                method: "POST",
                contentType: false,
                processData: false,
                data: fd
            }).done(function(response) {

                var response = JSON.parse(response);
                return response;
                /*$('[name="csrf_fecos"]').val(response.csrf)
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
                    swal.fire({
                        icon: 'success',
                        title: 'Correcto',
                        html: response.msn,
                        willClose: function() {
                            $('#searchdesc').click();
                        }
                    })
                }*/
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
    }).then((response) => {
        console.log(response)
        if (response.isConfirmed) {
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
                swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    html: response.msn,
                    willClose: function() {
                        $('#searchdesc').click();
                    }
                })
            }
        }
    });
})

$(document).on('click', '[data-sendmail]', function() {
    var ordertpv = $(this).data('sendmail')
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: '¿Enviar las tarjetas de esta compra al email del cliente?',
        showCancelButton: true,
        confirmButtonText: 'Si, enviar',
        cancelButtonText: 'Cerrar sin cambios',
    }).then((result) => {
        if (result.isConfirmed) {
            var fd = new FormData();
            fd.append("ordertpv", ordertpv);
            fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

            $.ajax({
                url: base_url + "Compras/enviarTarjetas",
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
                    swal.fire({
                        icon: 'success',
                        title: 'Correcto',
                        html: response.msn,
                        willClose: function() {
                            $('#searchdesc').click();
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
})


$(document).on('click', '[data-borrar]', function() {
    var orderID = $(this).data('borrar')
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: '¿Anular la compra que se ha indicado?',
        showCancelButton: true,
        confirmButtonText: 'Si, anular',
        cancelButtonText: 'Cerrar sin cambios',
    }).then((result) => {
        if (result.isConfirmed) {

            var fd = new FormData();
            fd.append("orderID", parseInt(orderID));
            fd.append("csrf_fecos", $('[name="csrf_fecos"]').val());

            $.ajax({
                url: base_url + "Compras/anularCompra",
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
                    swal.fire({
                        icon: 'success',
                        title: 'Correcto',
                        html: response.msn,
                        willClose: function() {
                            $('#searchdesc').click();
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
})