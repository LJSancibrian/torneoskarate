var oldExportAction = function(self, e, dt, button, config) {
    if (button[0].className.indexOf('buttons-excel') >= 0) {
        if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
            $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
        } else {
            $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
        }
    } else if (button[0].className.indexOf('buttons-print') >= 0) {
        $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
    }
};
var newExportAction = function(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function(e, s, data) {
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function(e, settings) {
            oldExportAction(self, e, dt, button, config);
            dt.one('preXhr', function(e, s, data) {
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            setTimeout(dt.ajax.reload, 0);
            return false;
        });
    });
    dt.ajax.reload();
};

var tablainscripciones;
$(document).ready(function() {
    tablainscripciones = $("#tablainscripciones").DataTable({
        processing: true,
        serverSide: true,
        order: [2, "asc"],
        columns: [{ //0
            title: '<div class="form-check text-center"><input class="form-check-input" type="checkbox" name="selectAllInscripciones" id="selectAllInscripciones"><label class="form-check-label" for="selectAllInscripciones"></label></div>',
            name: "preinscripcionesID",
            data: "preinscripcionesID",
            render: function(data, type, row) {
                var tdid = row.inscripcionesID;
                if (row.estado != 'aceptada') {

                    var tdid = '<div class="form-check"><label class="form-check-label"><input class="form-check-input" type="checkbox" name="inscripcionesID[]" value="' + row.inscripcionesID + '"><span class="form-check-sign"></span></label></div>';

                    //var tdid = '<div class="form-check text-center"><input class="form-check-input" type="checkbox" name="inscripcionesID[]" value="' + row.inscripcionesID + '"></div>';
                }
                return tdid;
            }
        }, { //1
            title: "Estado",
            name: "estado",
            data: "estado",
            render: function(data, type, row) {
                return row.estado.charAt(0).toUpperCase() + row.estado.slice(1);
            }
        }, { //2
            title: "Fecha de inscripción",
            name: "Fecha de inscripción",
            data: "createdAt",
            className: "text-truncate"
        }, { //3
            title: "Nombre comercial",
            name: "Nombre comercial",
            data: "nombrecomercial",
            render: function(data, type, row) {
                html = `<a class="btn btn-link" data-ver-row="${row.inscripcionesID}"><i class="fas fa-info-circle"></i> ${row.nombrecomercial}</a>`
                return html
            }
        }, { //4 
            title: "Razón social",
            name: "razon_social",
            data: "razon_social"
        }, { //5
            title: "Sector",
            name: "Sector",
            data: "sector",
        }, { //6
            title: "Responsable",
            name: "responsable",
            data: "responsable"
        }, { //7
            title: "Dirección",
            name: "Dirección",
            data: "direccion"
        }, { //8
            title: "Código postal",
            name: "Código postal",
            data: "cp"
        }, { //9
            title: "Localidad",
            name: "Localidad",
            data: "localidad"
        }, { //10
            title: "Provincia",
            name: "Provincia",
            data: "provincia",
        }, { //11
            title: "Email",
            name: "email",
            data: "email",
        }, { //12
            title: "Telefono",
            name: "phone",
            data: "phone",
        }, { //13 
            title: "CIF",
            name: "cif",
            data: "cif"
        }, { //14 
            title: "IBAN",
            name: "IBAN",
            data: "iban"
        }, { //15 
            title: "Titular",
            name: "Titular",
            data: "titulariban"
        }, { //16 
            title: "Notas",
            name: "Notas",
            data: "notas"
        }],
        columnDefs: [{
            targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16],
            visible: true
        }, {
            targets: ["_all"],
            visible: false
        }, {
            targets: [0],
            orderable: false
        }],
        ajax: {
            url: base_url + 'Inscripciones/getEmpresasInscritas',
            type: "GET",
            datatype: "json",
            data: function(data) {
                var estado = $('#tablainscripciones').attr('data-estado');
                if (estado == 'aceptada' || estado == 'pendiente' || estado == 'rechazada') {
                    data.estado = estado;
                }
            }
        },
        buttons: [{
            extend: 'collection',
            text: 'Mostrar en estado',
            className: 'btn btn-primary',
            autoClose: true,
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            buttons: [{
                text: 'TODAS',
                action: function(e, dt, node, config) {
                    $('#tablainscripciones').attr('data-estado', 'todas')
                    tablainscripciones.draw();
                }
            }, {
                text: 'ACEPTADAS',
                action: function(e, dt, node, config) {
                    $('#tablainscripciones').attr('data-estado', 'aceptada')
                    tablainscripciones.draw();
                }
            }, {
                text: 'PENDIENTES',
                action: function(e, dt, node, config) {
                    $('#tablainscripciones').attr('data-estado', 'pendiente')
                    tablainscripciones.draw();
                }
            }, {
                text: 'RECHAZADAS',
                action: function(e, dt, node, config) {
                    $('#tablainscripciones').attr('data-estado', 'rechazada')
                    tablainscripciones.draw();
                }
            }]
        }, {
            text: 'Exportar',
            extend: 'excelHtml5',
            title: 'inscripciones_empresas',
            action: newExportAction,
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
        }, {
            extend: 'collection',
            text: 'Cambiar estado a selección',
            className: 'btn btn-primary',
            autoClose: true,
            init: function(api, node, config) {
                $(node).removeClass('btn-secondary')
            },
            buttons: [{
                text: 'Marcar como ACEPTADAS',
                action: function(e, dt, node, config) {
                    cambiarEstadoSeleccion('aceptada')
                }
            }, {
                text: 'Marcar como PENDIENTES',
                action: function(e, dt, node, config) {
                    cambiarEstadoSeleccion('pendiente')
                }
            }, {
                text: 'Marcar como RECHAZADAS',
                action: function(e, dt, node, config) {
                    cambiarEstadoSeleccion('rechazada')
                }
            }, ]
        }],
        language: {
            url: base_url + "assets/admin/js/spanish.lang.json"
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
            $(row).attr('data-empresa-id', data.empresaID);
            $(row).attr('data-empresa', data.razon_social);
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
        },
        initComplete: function() {}
    });
});

$(document).on('click', '[name="selectAllInscripciones"]', function() {
    $('[name="inscripcionesID[]"]').prop("checked", this.checked);
});

$(document).on('click', '[name="inscripcionesID[]"]', function() {
    if ($('[name="inscripcionesID[]"]').length == $('[name="inscripcionesID[]"]:checked').length) {
        $('[name="selectAllInscripciones"]').prop("checked", true);
    } else {
        $('[name="selectAllInscripciones"]').prop("checked", false);
    }
});

$(document).on('click', '[data-ver-row]', function() {
    var row = $(this).attr('data-ver-row');
    var fd = new FormData();
    fd.append("row", row);
    fd.append("bbcinocsrf", $('[name="bbcinocsrf"]').val());
    return fetch(`${base_url}Inscripciones/getPreinscripcionData`, {
            method: "POST",
            body: fd
        })
        .then(resp => resp.json())
        .then(response => {
            $('[name="bbcinocsrf"]').val(response.csrf)
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
                showmsg(errorhtml)
                return;
            }
            if (response.hasOwnProperty('html')) {
                var modal = '<div id="modalTemp" class="modal fade" tabindex="-1" role="dialog">';
                modal += '<div class="modal-dialog modal-dialog-centered" role="document">';
                modal += '<div class="modal-content">';
                modal += '<div class="modal-body">';
                modal += '<p style="font-size: 20px;text-align:center">' + response.html + '</p>';
                modal += '</div>';
                modal += '<div class="modal-footer text-center">';
                modal += '<button type="button" class="btn btn-primary" data-edit-row="' + row + '">Editar</button>';
                modal += '</div></div></div></div>';
                $("body").append(modal);
                $('#modalTemp').modal('show')
                return;
            } else {
                swal.fire({
                    title: json.msn,
                })
            }
        });
})

$(document).on('click', '[data-edit-row]', function() {
    var row = $(this).attr('data-edit-row');
    var fd = new FormData();
    fd.append("row", row);
    $.ajax({
        url: base_url + "Inscripciones/getPreinscripcionForm",
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function(response) {
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
            showmsg(errorhtml)
            return;
        } else {
            $('.modal-content').slideUp('slow')
                // ponemos el formulario en una modal
                // se muestra la modal
            premiosorteoID = $(this).data('add_ganador')
            var modal = '<div class="modal-body">';
            modal += response.html;
            modal += '</div>';
            modal += '<div class="modal-footer text-center">';
            modal += '<button type="button" class="btn btn-primary" onclick="guardardatos(' + row + ');">Guardar</button>';
            modal += '<button type="button" class="btn btn-secondary" data-dismiss="modal">No, cerrar</button>';
            modal += '</div>';
            $(".modal-content").html(modal);
            $('.modal-content').slideDown('slow')
        }
    }).always(function(jqXHR, textStatus) {
        if (textStatus != "success") {
            showmsg(jqXHR.statusText)
            setTimeout(function() {
                window.location.reload();
            }, 5000)
        }
    });
})

function cambiarEstadoSeleccion(estado) {
    var data = $('input[name="inscripcionesID[]"]').serializeArray()
    if (data.length > 0) {
        var inscripcionesIDs = [];
        var checkboxes = $('input[name="inscripcionesID[]"]:checked');
        $.each(checkboxes, function(key, val) {
            inscripcionesIDs.push($(val).val())
        });

        var fd = new FormData();
        fd.append("inscripcionesID", inscripcionesIDs);
        fd.append("estado", estado);
        fd.append("bbcinocsrf", $('[name="bbcinocsrf"]').val());
        $.ajax({
            url: base_url + "Inscripciones/marcarInscripcionesComo",
            method: "POST",
            contentType: false,
            processData: false,
            data: fd
        }).done(function(response) {
            var response = JSON.parse(response);
            $('[name="bbcinocsrf"]').val(response.csrf)
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
                showmsg(errorhtml)
                return;
            } else {
                $('#nameempresa').html($("#razon_social").val());
                showmsg(response.msn)
                setTimeout(function() {
                    window.location.reload();
                }, 2500);
            }
        }).always(function(jqXHR, textStatus) {
            if (textStatus != "success") {
                showmsg(jqXHR.statusText)
            }
        });
    } else {
        showmsg('Es necesario al menos seleccioanr una inscripción')
    }
}

function guardardatos(inscripcionID) {
    var fd = new FormData();
    fd.append("inscripcionesID", inscripcionID);
    fd.append("nombrecomercial", $('[name="nombrecomercial"]').val());
    fd.append("sector", $('[name="sector"]').val());
    fd.append("responsable", $('[name="responsable"]').val());
    fd.append("direccion", $('[name="direccion"]').val());
    fd.append("cp", $('[name="cp"]').val());
    fd.append("localidad", $('[name="localidad"]').val());
    fd.append("provincia", $('[name="provincia"]').val());
    fd.append("phone", $('[name="phone"]').val());
    fd.append("email", $('[name="email"]').val());
    fd.append("razon_social", $('[name="razon_social"]').val());
    fd.append("cif", $('[name="cif"]').val());
    fd.append("iban", $('[name="iban"]').val());
    fd.append("titulariban", $('[name="titulariban"]').val());
    fd.append("notas", $('[name="notas"]').val());
    fd.append("bbcinocsrf", $('[name="bbcinocsrf"]').val());
    $.ajax({
        url: base_url + "Inscripciones/actualizarInscripcion",
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function(response) {
        var response = JSON.parse(response);
        $('[name="bbcinocsrf"]').val(response.csrf)
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
            showmsg(errorhtml)
            return;
        } else {
            $('#modalTemp').modal('hide')
            setTimeout(function() {
                tablainscripciones.draw();
                showmsg(response.msn)
            }, 1000);
        }
    }).always(function(jqXHR, textStatus) {
        if (textStatus != "success") {
            showmsg(jqXHR.statusText)
            setTimeout(function() {
                window.location.reload();
            }, 5000)
        }
    });
}