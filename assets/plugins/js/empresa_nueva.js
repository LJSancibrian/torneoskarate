$(document).ready(function() {
    $('#crear-empresa').click(function() {
        swal.fire({
            icon: 'question',
            title: 'Confirmar acción',
            html: '¿crear nueva empresa?',
            //showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Si, crear',
            cancelButtonText: 'Cerrar sin cambios',
        }).then((result) => {
            if (result.isConfirmed) {
                var fd = new FormData();
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
                if ($('#welcome').is(':checked')) {
                    fd.append("welcome", $("#welcome").val());
                }
                $.ajax({
                    url: base_url + "Gestion/nueva_empresa_form",
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
                                window.location.href = response.redirect;
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