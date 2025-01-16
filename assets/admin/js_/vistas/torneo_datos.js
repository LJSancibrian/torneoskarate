// PESTAÑA DATOS

$(document).on("click", ".browse", function () {
    var file = $(this).parents().find(".file");
    file.trigger("click");
});
$('input[type="file"]').change(function (e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);
    var reader = new FileReader();
    reader.onload = function (e) {
        document.getElementById("preview").src = e.target.result;
    };
    reader.readAsDataURL(this.files[0]);
});


$('#submit-torneo-form').click(function () {
    var torneo_id = $('[name="torneo_id"]').val()
    if (torneo_id == 'nuevo') {
        var stitle = '¿Crear torneo con los datos indicados?';
        var sconfirm = 'Si, crear torneo';
        var action = base_url + 'Torneos/nuevo_torneo_form';
    } else {
        var stitle = '¿Guardar los datos del torneo?';
        var sconfirm = 'Si, guardar datos';
        var action = base_url + 'Torneos/editar_torneo_form';
    }
    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: stitle,
        showCancelButton: true,
        confirmButtonText: sconfirm,
        cancelButtonText: 'Cerrar sin cambios',
    }).then((result) => {
        if (result.isConfirmed) {
            var fd = new FormData();
            fd.append("torneo_id", torneo_id);
            fd.append("titulo", $("#titulo").val());
            fd.append("descripcion", $("#descripcion").val());
            fd.append("direccion", $("#direccion").val());
            fd.append("organizador", $("#organizador").val());
            fd.append("tipo", $("#tipo").val());
            fd.append("fecha", $("#fecha").val());
            fd.append("limite", $("#limite").val());
            fd.append("email", $("#email").val());
            fd.append("telefono", $("#telefono").val());
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            if ($('#estado').is(':checked')) {
                fd.append("estado", $("#estado").val());
            }
            $.ajax({
                url: action,
                method: "POST",
                contentType: false,
                processData: false,
                data: fd
            }).done(function (response) {
                var response = JSON.parse(response);
                $('[name="csrf_token"]').val(response.csrf)
                if (response.error > 0) {
                    var errorhtml = ''
                    if (response.hasOwnProperty('error_validation')) {
                        $.each(response.error_validation, function (i, value) {
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
                        willClose: function () {
                            if (response.hasOwnProperty('redirect')) {
                                window.location.href = response.redirect
                            } else {
                                //tabla.draw();
                                if ($('#modal_crear_torneo').lenght > 0) {
                                    $('#modal_crear_torneo').modal('hide');
                                }

                            }
                        }
                    })
                }
            }).always(function (jqXHR, textStatus) {
                if (textStatus != "success") {
                    swal.fire({
                        icon: 'error',
                        title: 'Ha ocurrido un error AJAX',
                        html: jqXHR.statusText,
                        timer: 5000,
                        willClose: function () { }
                    })
                }
            });
        }
    });
});