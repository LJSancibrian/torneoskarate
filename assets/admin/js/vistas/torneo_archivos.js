// PESTAÑA Archivos
$(document).on("click", ".browse", function () {
    var file = $(this).closest('.input-group').siblings('input[type="file"]');
    file.trigger("click");
});
$('input[type="file"]').change(function (e) {
    var fileName = e.target.files[0].name;
    var text_elem = $(this).attr('text');
    var preview = $(this).attr('preview')
    if(text_elem != ''){
        $(text_elem).val(fileName);
    }
    if(preview != ''){
        var reader = new FileReader();
        reader.onload = function (e) {
            $(preview).attr('src' , e.target.result)
            //document.getElementById("preview").src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    }
});


var tabla_archivos;
tabla_archivos = $("#tabla_archivos").DataTable({
    processing: true,
    serverSide: true,
    columns: [{ //0
        title: "Titulo",
        name: "titulo",
        data: "titulo",
    }, { //1
        title: "Tipo",
        name: "tipo",
        data: "tipo",
    }, { //2
        title: "Acceso",
        name: "acceso",
        data: "acceso",
    }, { //3
        title: "Enlace",
        name: "url",
        data: "url",
    }, { // 4
        name: "Acción",
        title: "Acción",
        data: "bonoID",
        className: "text-truncate",
        render: function (data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Vista rápida del archivo" data-ver="' + row.url + '"><i class="fas fa-link"></i></button>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver usuario responsable" data-editar="' + row.archivo_id + '" data-slug="' + row.slug + '"><i class="fa fa-edit"></i></button>';
            html += '</div>';
            return html
        }
    },],
    columnDefs: [{
        targets: [0, 1, 2, 3, 4],
        visible: true
    }, {
        targets: ["_all"],
        visible: false
    }, {
        //targets: [6],
        //orderable: false
    }],
    ajax: {
        url: base_url + 'Archivos/getArchivosTorneo',
        type: "GET",
        datatype: "json",
        data: function (data) {
            var torneo_id = $("#tabla_archivos").data('default');
            if (torneo_id != '') {
                data.torneo_id = torneo_id;
            }
            var estado = $('[name="f_estado"]').val();
            console.log(estado);
            if (estado != '') {
                data.estado = estado;
            }
            var equipo = $('[name="f_equipo"]').val();
            if (equipo != '') {
                data.equipo = equipo;
            }
            var modalidad = $('[name="f_modalidad"]').val();
            if (modalidad != '') {
                data.modalidad = modalidad;
            }
            var t_categoria_id = $('[name="f_t_categoria_id"]').val();
            if (t_categoria_id != '') {
                data.t_categoria_id = t_categoria_id;
            }
        }
    },
    createdRow: function (row, data, dataIndex) {
        if (dataIndex > 0) {
            $(row).find('[data-tooltip]').tooltip();
        } else {
            $(row).find('[data-tooltip]').tooltip({ boundary: 'window' });
        }
    },
    drawCallback: function (settings) { },
    initComplete: function () { }
});


$(document).on('click', '[data-ver]', function () {
    var link = $(this).attr('data-ver');
    window.open(link, '_blank');
})


$(document).on('click', '[data-editar-archivo]', function () {
    var archivo_id = $(this).attr('data-editar-archivo')
    if (archivo_id == 'nuevo') {
        $('#modal_crear_archivo form').trigger('reset');
        $('#modal_crear_archivo .fw-mediumbold').html('Crear nuevo archivo');
        $('[name="archivo_id"]').val('nuevo');
        $('#modal_crear_archivo').modal('show');

    } else {
        slug = $(this).attr('data-slug')
        var fd = new FormData();
        fd.append("archivo_id", archivo_id);
        fd.append("csrf_token", $('[name="csrf_token"]').val());

        $.ajax({
            url: base_url + 'Archivos/ver_archivo_fetch',
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
                var archivo = response.data.archivo;
                $('#modal_crear_archivo form').trigger('reset');
                $('[name="archivo_id"]').val(archivo_id);
                $("#titulo").val(archivo.titulo);
                $("#descripcion").val(archivo.descripcion);
                $("#direccion").val(archivo.direccion);
                $("#organizador").val(archivo.organizador);
                $("#tipo").val(archivo.tipo);
                $("#fecha").val(archivo.fecha);
                $("#limite").val(archivo.limite);
                $("#email").val(archivo.email);
                $("#telefono").val(archivo.telefono);
                $('#modal_crear_archivo .fw-mediumbold').html('Editar archivo ' + archivo.slug);
                $('#modal_crear_archivo').modal('show');
                if (archivo.estado == 1) {
                    $('#estado').attr('checked', 'checked');
                }
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


$(document).on('click', '[data-edit]', function () {
 
})