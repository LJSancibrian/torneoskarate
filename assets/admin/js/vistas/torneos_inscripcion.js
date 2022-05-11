var tabla;
var titledoc = 'torneos';
tabla = $(".datatable").DataTable({
    processing: false,
    serverSide: false,
    stateSave: false,
    createdRow: function (row, data, dataIndex) {
        if (dataIndex > 0) {
            $(row).find('[data-tooltip]').tooltip();
        } else {
            $(row).find('[data-tooltip]').tooltip({ boundary: 'window' });
        }
        // buscar el valor de los select
        var selectkataval = $(row).find('[data-modalidad="kata"] option:selected').val();
        $(row).attr('data-kata-original', selectkataval)
        $(row).attr('data-kata-actual', selectkataval)
        if( selectkataval != 0){
            $(row).find('[data-modalidad="kata"]').closest('td').addClass('bg-success2')
        }
        var selectkumiteval = $(row).find('[data-modalidad="kumite"] option:selected').val()
        $(row).attr('data-kumite-original', selectkumiteval)
        $(row).attr('data-kumite-actual', selectkumiteval)
        if( selectkumiteval != 0){
            $(row).find('[data-modalidad="kumite"]').closest('td').addClass('bg-success2')
        }
        if(selectkumiteval != 0 || selectkataval != 0 ){
            $(row).addClass('bg-info font-weight-bold odd text-white')  
        }
        if($(row).attr('blocked') > 0) {
            //$(row).removeClass('bg-info');
           // $(row).find('select').closest('td').removeClass('bg-success2')
            $(row).find('select').attr('disabled', 'disabled')
            $(row).find('select').attr('readonly', 'readonly')
            /*if($(row).attr('blocked') == 1){
                $(row).addClass('bg-primary') 
            }else{
                $(row).addClass('bg-danger') 
            } */
        }

    },
    drawCallback: function (settings) { },
    initComplete: function () { }
});

$(document).on('click', '[data-ver-torneo]', function () {
    torneo_id = $(this).attr('data-ver-torneo')
    slug = $(this).attr('data-slug')
    var fd = new FormData();
    fd.append("torneo_id", torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Torneos/ver_torneo_fetch',
        method: "POST",
        contentType: false,
        processData: false,
        data: fd
    }).done(function (response) {
        if (response === '' || typeof response == 'undefined') {
            window.location.reload();
        }
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
            var html = '';
            var torneo = response.data.torneo;

            html += '<div class="card-list">';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Título</strong><span>' + torneo.titulo + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Código del torneo</strong><span>' + torneo.slug + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Descripción</strong><span>' + torneo.descripcion + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Dirección</strong><span>' + torneo.direccion + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Organizador</strong><span>' + torneo.organizador + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Email de contacto</strong><span>' + torneo.email + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Teléfono de contacto</strong><span>' + torneo.telefono + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Fecha</strong><span>' + torneo.fecha + '</span></div>';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Inscripcion hasta</strong><span>' + torneo.limite + '</span></div>';
            var estado = (torneo.estado == 1) ? 'Activo' : 'Inactivo';
            html += '<div class="item-list justify-content-between border-bottom p-2"><strong>Estado del torneo</strong><span>' + estado + '</span></div>';
            html += '</div>';
            swal.fire({
                icon: 'info',
                title: 'Información del torneo',
                html: html,
                showCancelButton: true,
                confirmButtonText: 'Ir a la inscripcion',
                cancelButtonText: 'Cerrar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = base_url + 'torneos/inscripcion/' + slug
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
});

$(document).on('click', '[data-inscribir]', function () {
    torneo_id = $(this).attr('data-inscribir')
    slug = $(this).attr('data-slug')
    window.location.href = base_url + 'torneos/inscripcion/' + slug
});


$(document).on('change', '[name="competicion_torneo_id"]', function () {
    var select = $(this);
    var accion = 'add';
    var tr = $(this).closest('tr');
    var td = $(this).closest('td');
    var competicion_nueva_torneo_id = $(this).val(); //competicion_torneo_id
    var forlabel = $(this).attr('id');
    var competicion_text = $(this).find('option:selected').text();
    var modalidad = $(this).attr('data-modalidad');
    var deportista = tr.attr('data-dep');
    var kataoriginal = tr.attr('data-kata-original');
    var kataactual = tr.attr('data-kata-actual');
    var kumiteoriginal = tr.attr('data-kumite-original');
    var kumiteactual = tr.attr('data-kumite-actual');
    if (modalidad == 'kata') {
        var competicion_previa_torneo_id = kataactual;
    }else{
        var competicion_previa_torneo_id = kumiteactual;  
    }
    // enviar
    var fd = new FormData();
    fd.append("torneo_id", $('[name="torneo_id"]').val());
    fd.append("competicion_nueva_torneo_id", competicion_nueva_torneo_id);
    fd.append("competicion_previa_torneo_id", competicion_previa_torneo_id);
    fd.append("user_id", deportista);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Torneos/manage_inscripciones',
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
                willClose: function () {
                    if (response.hasOwnProperty('redirect')) {
                        if(response.redirect == 'refresh'){
                            location.reload()
                        }else{
                            window.location.href = response.redirect
                        }
                    }
                }
            });
            select.val(competicion_previa_torneo_id);
            return;
        } else {
            tr.attr('data-' + modalidad + '-actual', competicion_nueva_torneo_id);
            if (tr.attr('data-kata-actual') > 0 || tr.attr('data-kumite-actual') > 0) {
                td.addClass('bg-success2')
                tr.addClass('bg-info font-weight-bold odd text-white')
            } else {
                td.removeClass('bg-success2')
                tr.removeClass('bg-info font-weight-bold odd text-white')
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




})
