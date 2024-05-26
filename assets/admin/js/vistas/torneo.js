


// PESTAÑA DATOS
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


// PESTAÑA CATEGORIAS
$('[data-add-modalidad]').click(function () {
    var btn = $(this);
    var tr = btn.closest('tr');
    var table = btn.closest('table');
    var t_categoria_id = tr.find('select option:selected').val()
    var categoria_text = tr.find('select option:selected').text()
    var pesokg = tr.find('input').val();
    if (pesokg < 25) {
        swal.fire({
            icon: 'error',
            title: 'ERROR',
            html: 'El peso mímino es de 25',
        });
        return;
    }
    var peso = '< ' + tr.find('input').val();
    var newtr = '<tr data-id="nuevo" data-categoria_id="' + t_categoria_id + '" data-peso="' + pesokg + '"><td>' + categoria_text + '</td><td>' + peso + '</td><td><button type="button" class="btn btn-outlime-danger btn-outline-danger btn-sm rounded-0" data-del-modalidad><i class="fa fa-minus-circle"></i></button></td></tr>'
    table.find('tbody').append(newtr);
})

$(document).on('click', '[data-del-modalidad]', function () {
    var btn = $(this);
    var tr = btn.closest('tr');
    var table = btn.closest('table');
    var competicion_torneo_id = tr.attr('data-id');
    if (competicion_torneo_id == 'nuevo') {
        tr.addClass('collapse');
        tr.remove()
    } else {
        // se envia el ajax
        // si no hay error, se elimina
        swal.fire({
            icon: 'question',
            title: 'Confirmar acción',
            html: '¿Elimiar la categoría? Esta acción cambia los datos almacenados en el sistema',
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cerrar sin cambios',
        }).then((result) => {
            if (result.isConfirmed) {
                var fd = new FormData();
                fd.append("competicion_torneo_id", competicion_torneo_id);
                fd.append("csrf_token", $('[name="csrf_token"]').val());
                $.ajax({
                    url: base_url + 'Torneos/del_competiciones',
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
                        tr.addClass('collapse');
                        tr.remove()
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
        })
    }
})

$('[data-guardar-modalidad]').click(function () {
    var btn = $(this);
    var tabla = btn.prev();
    var modalidad = tabla.data('modalidad')
    var genero = tabla.data('genero')
    if (genero == 'M') {
        var genero_n = 1;
        var generotext = 'masculino'
    } else {
        var genero_n = 2;
        var generotext = 'femenino'
    }

    swal.fire({
        icon: 'question',
        title: 'Confirmar acción',
        html: '¿Guardar las categorias actuales para la competición de ' + modalidad + ' ' + generotext + '?',
        showCancelButton: true,
        confirmButtonText: 'Si, guardar',
        cancelButtonText: 'Cerrar sin cambios',
    }).then((result) => {
        if (result.isConfirmed) {
            var fd = new FormData();
            var torneoID = $('[name="torneo_id"]').val();
            fd.append("torneo_id", torneoID);
            fd.append("csrf_token", $('[name="csrf_token"]').val());
            var item = [];
            var competiciones = [];
            $(tabla).find('tbody tr[data-id="nuevo"]').each(function (index, element) {
                tr_item = $(tabla).find('tbody tr').eq(index)
                item = {
                    torneo_id: torneoID,
                    modalidad: modalidad,
                    genero: genero_n,
                    t_categoria_id: tr_item.data('categoria_id'),
                    peso: tr_item.data('peso')
                }
                competiciones.push(item)
            })
            fd.append("competiciones", JSON.stringify(competiciones));
            $.ajax({
                url: base_url + 'Torneos/add_competiciones',
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
                                getCompeticiones(modalidad, genero)
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
    })

})

function getCompeticiones(modalidad, genero) {
    var tbody = $('table[data-modalidad="' + modalidad + '"][data-genero="' + genero + '"] tbody')
    var fd = new FormData();
    var torneoID = $('[name="torneo_id"]').val();
    fd.append("torneo_id", torneoID);
    fd.append("modalidad", modalidad);
    fd.append("genero", genero);
    fd.append("csrf_token", $('[name="csrf_token"]').val());

    $.ajax({
        url: base_url + 'Torneos/get_competiciones_fetch',
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
            tbody.html('')
            $.each(response.competiciones, function (i, elem) {
                var newtr = '<tr data-id="' + elem.competicion_torneo_id + '" data-categoria_id="' + elem.t_categoria_id + '" data-peso="' + elem.pesokg + '"><td>' + elem.categoria_text + '</td><td>' + elem.peso + '</td><td><button type="button" class="btn btn-outlime-danger btn-outline-danger btn-sm rounded-0" data-del-modalidad><i class="fa fa-minus-circle"></i></button></td></tr>';
                tbody.append(newtr);
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
$(document).ready(function () {
    $('[data-modalidad]').each(function () {
        var mod = $(this).data('modalidad');
        var ges = $(this).data('genero');
        getCompeticiones(mod, ges)
    })
})

// PESTAÑA ELIMINATORIAS
$(document).on('change', '[name="ver_categoria"]', function () {
    // obtener las inscripciones
    var competicion_torneo_id = $(this).val();
    var fd = new FormData();
    fd.append("competicion_torneo_id", competicion_torneo_id);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Torneos/get_inscripciones_competicion',
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
                        if (response.redirect == 'refresh') {
                            location.reload()
                        } else {
                            window.location.href = response.redirect
                        }
                    }
                }
            });
            select.val(oldvalue);
            return;
        } else {
            var tabla = $('#inscritos');
            var tbody = $('#inscritos tbody');
            $('#card-body').slideUp("slow", function () {
                tbody.html('');
                $.each(response.inscritos, function (i, elem) {
                    var newtr = '<tr data-inscripcion_id="' + elem.inscripcion_id + '" data-user_id="' + elem.user_id + '"><td colspan="2"><h4 class="my-0 ml-1 seleccionable">' + elem.first_name + ' ' + elem.last_name + '<small class="d-block">' + elem.nombre + '</small></h4></td></tr>';
                    console.log(newtr)
                    tbody.append(newtr);
                })
                var totalinscritos = response.inscritos.length;
                $('#totalinscritos').html(totalinscritos)
                if(totalinscritos < 3){
                    var totalrondas = 1;
                    var casillas = 2;
                }else if(totalinscritos < 5){
                    var totalrondas = 2;
                    var casillas = 4;
                }else if(totalinscritos < 9){
                    var totalrondas = 3;
                    var casillas = 8;
                }else if(totalinscritos < 17){
                    var totalrondas = 4;
                    var casillas = 16;
                }else if(totalinscritos < 33){
                    var totalrondas = 5;
                    var casillas = 32;
                }else if(totalinscritos < 65){
                    var totalrondas = 6;
                    var casillas = 64;
                }else if(totalinscritos < 129){
                    var totalrondas = 7;
                    var casillas = 128;
                }else if(totalinscritos < 257){
                    var totalrondas = 8;
                    var casillas = 256;
                }
                $('#totalrondas').html(totalrondas)

                var eliminatoriasr1 = casillas / 2;
                $('#eliminatoriasprimera').html(eliminatoriasr1)


                $('#rondauno').html('')
                //$('.rondas').html('');
                for (let index = 0; index < eliminatoriasr1; index++) {
                    if(index > eliminatoriasr1/2 ){
                        var liclass = 'list-group-item border';
                    }else{
                        var liclass = 'list-group-item border bg-ligth';
                    }
                    var html = `<li class="`+liclass+`"></li>
                    <li class="`+liclass+`"></li>`
                    $('#ronda1').append(html)
                }
                /*for (let r = 1; r <= totalrondas; r++) {
                    var divronda = '<div class="ronda-content" id="ronda_'+r+'"></div>';
                    if(r > 1){
                        var divronda = '<div class="ronda-content" id="ronda_'+r+'" style="display: none;"></div>';
                    }
                    if( r == totalrondas){
                        tituloronda = 'FINAL';
                    }else if(r == (totalrondas - 1)){
                        tituloronda = 'SEMI-FINAL';
                    }else if(r == (totalrondas - 2)){
                        tituloronda = '1/4';
                    }else if(r == (totalrondas - 3)){
                        tituloronda = '1/8';
                    }else{
                        tituloronda = 'Ronda '+r
                    }
                    $('.rondas').append(divronda);
                    var tituloronda = '<h3 class="bg-primary font-weight-bold p-2 text-white">'+tituloronda+'</h3>'
                    $('#ronda_'+r).append(tituloronda);

                    for (let index = 0; index < eliminatoriasr1; index++) {
                        //const element = array[index];
                        var elim_num = index+1;
                        if(r == 1){
                            var eliminat = `<div class="card" id="r`+r+`-`+elim_num+`">
                            <div class="card-body">
                                <h5 class="ideliminatoria">R`+r+`/`+elim_num+`</h5>
                                <ul class="list-group border">
                                    <li class="list-group-item">
                                        <div class="casilla"></div>
                                        <span class="color red"></span>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="casilla"></div>
                                        <span class="color blue"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>`;
                        }else{
                            var rondaprev = r - 1;
                            var ganadorazul = elim_num*2;
                            var ganadorrojo = ganadorazul -1;
                            var eliminat = `<div class="card" id="r`+r+`-`+elim_num+`">
                                <div class="card-body">
                                    <h5 class="ideliminatoria">R`+r+`/`+elim_num+`</h5>
                                    <ul class="list-group border">
                                        <li class="list-group-item">
                                        <div class="casilla"><h4 class="my-0 ml-1" >Ganador R`+rondaprev+`/`+ganadorrojo+`<small class="d-block">Equipo</small></h4></div>
                                            <span class="color red"></span>
                                        </li>
                                        <li class="list-group-item">
                                        <div class="casilla"><h4 class="my-0 ml-1" >Ganador R`+rondaprev+`/`+ganadorazul+`<small class="d-block">Equipo</small></h4></div>
                                            <span class="color blue"></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>`;
                        }
    
                        $('#ronda_'+r).append(eliminat)
                    }
                    
                    if( r == totalrondas){
                        var tituloronda = '<h3 class="bg-primary font-weight-bold p-2 text-white">3º y 4º</h3>'
                        $('#ronda_'+r).append(tituloronda);
                        var eliminat = `<div class="card" id="r`+r+`-2">
                                <div class="card-body">
                                    <h5 class="ideliminatoria">R`+r+`/2</h5>
                                    <ul class="list-group border">
                                        <li class="list-group-item">
                                        <div class="casilla"><h4 class="my-0 ml-1" >No ganador R`+rondaprev+`/`+ganadorrojo+`<small class="d-block">Equipo</small></h4></div>
                                            <span class="color red"></span>
                                        </li>
                                        <li class="list-group-item">
                                        <div class="casilla"><h4 class="my-0 ml-1" >No ganador R`+rondaprev+`/`+ganadorazul+`<small class="d-block">Equipo</small></h4></div>
                                            <span class="color blue"></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>`;
                            $('#ronda_'+r).append(eliminat)
                    }
                    eliminatoriasr1 = eliminatoriasr1/2;
                }
                */
                setTimeout(function(){
                   // $('.list-group').sortable();
                    $('#card-body').slideDown('500')
                }, 300)
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
})
$(document).on('click', '[data-sortear]', function(){
    $('#ronda1 .list-group-item').html('')
    $('#ronda1 .list-group-item').removeAttr('data-li_inscripcion_id')
    var eliminatorias = $('#ronda1 .list-group-item').length;
    var posiciones = [];
    for (let index = 0; index < eliminatorias; index++) {
        posiciones.push(index)
    }

    var colocados = [];
    $('#inscritos tbody').find('tr').each(function(i) {
        var inscripcion_id = $(this).attr('data-inscripcion_id')
        var user_id = $(this).attr('data-user_id')
        var deportista = $(this).find('td:first-child').text()
        var equipo = $(this).find('td:last-child').text()
        var posicion = posiciones[Math.floor(Math.random()*posiciones.length)];
        var html = $(this).find('td').html();
        var li = $('#ronda1').find('.list-group-item').eq(posicion);
        li.attr('data-li_inscripcion_id', inscripcion_id)
        li.html(html)
        
        var index = posiciones.indexOf(posicion);
        if (index > -1) {
            posiciones.splice(index, 1);
        }
    })

    $(".list-group-item .seleccionable").draggable({
        revert: 'invalid',
        helper: 'clone',
        opacity: '0.7',
        stop: function(event, ui) {
            //Move back element to dropped position
            $(draggedElement).css('top', yPos).css('left', xPos);
        }
    });
    $(".list-group-item").droppable({
        accept: '.seleccionable',
        over: function(event, ui) {},
        drop: function(event, ui) {
      
          //Get dragged Element (checked)
          draggedElement = $(ui.draggable);
      
          //Get dropZone where element is dropped (checked)
          dropZone = $(event.target);
      
          //Move element from list, to dropZone (Change Parent, Checked)
          $(dropZone).append(draggedElement);
      
          //Get current position of draggable (relative to document)
          var offset = $(ui.helper).offset();
          xPos = offset.left;
          yPos = offset.top;
          $('#posX').text('x: ' + xPos);
          $('#posY').text('y: ' + yPos);
          
          //Move back element to dropped position
          $(draggedElement).css('top', yPos).css('left', xPos);
        }
        
    });
    $('.list-group').sortable();
})





//$('.list-group-item').draggable();
/*
const slider = document.querySelector('.rondas');
let isDown = false;
let startX;
let scrollLeft;

slider.addEventListener('mousedown', (e) => {
  isDown = true;
  slider.classList.add('active');
  startX = e.pageX - slider.offsetLeft;
  scrollLeft = slider.scrollLeft;
});
slider.addEventListener('mouseleave', () => {
  isDown = false;
  slider.classList.remove('active');
});
slider.addEventListener('mouseup', () => {
  isDown = false;
  slider.classList.remove('active');
});
slider.addEventListener('mousemove', (e) => {
  if(!isDown) return;
  e.preventDefault();
  const x = e.pageX - slider.offsetLeft;
  const walk = (x - startX) * 2; //scroll-fast
  slider.scrollLeft = scrollLeft - walk;
  console.log(walk);
});*/

// PESTAÑA INSCRIPCIONES
var table_inscripciones;
table_inscripciones = $("#table_inscripciones").DataTable({
    processing: true,
    serverSide: true,
    columns: [{ //0
        title: "CODIGO",
        name: "usercode",
        data: "usercode",
    }, { //1
        title: "Nombre",
        name: "first_name",
        data: "first_name",
    }, { //2
        title: "Apellidos",
        name: "last_name",
        data: "last_name",
    }, { //3
        title: "Competición",
        name: "genero",
        data: "genero",
        render: function (data, type, row) {
            if (row.genero == 1) {
                var competicion = 'Masculina';
            } else {
                var competicion = 'Femenina';
            }
            html = '<span class="badge badge-info">' + competicion + '</span>';
            return html
        }
    }, { //4 
        title: "Modalidad",
        name: "modalidad",
        data: "modalidad",
        render: function (data, type, row) {
            html = '<span class="badge badge-info">' + row.modalidad + '</span>';
            return html
        }
    }, { //5
        title: "Categoría",
        name: "categoria_text",
        data: "categoria_text",
    }, { //6
        title: "Equipo",
        name: "nombre",
        data: "nombre",
    }, { //7
        title: "Estado",
        name: "estado",
        data: "estado",
        render: function (data, type, row) {
            if (row.estado == 0) {
                var clase = 'badge badge-info';
            } else if (row.estado == 1) {
                var clase = 'badge badge-success';
            } else {
                var clase = 'badge badge-danger';
            }
            html = '<select class="' + clase + '" data-prev="' + row.estado + '" data-change-estado-inscripcion="' + row.inscripcion_id + '">';
            if (row.estado == 0) {
                html += '<option value="0" selected>Pendiente</option>';
            } else {
                html += '<option value="0">Pendiente</option>';
            }

            if (row.estado == 1) {
                html += '<option value="1" selected>Aceptada</option>';
            } else {
                html += '<option value="1">Aceptada</option>';
            }

            if (row.estado == 2) {
                html += '<option value="2" selected>Rechazada</option>';
            } else {
                html += '<option value="2">Rechazada</option>';
            }
            html += '</select>'
            return html
        }
    /*}, { // 8
        name: "Acción",
        title: "Acción",
        data: "bonoID",
        className: "text-truncate",
        render: function (data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Vista rápida del torneo" data-ver-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fas fa-trophy"></i></button>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver usuario responsable" data-editar-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fa fa-edit"></i></a>';
            html += '</div>';
            return html
        }*/
    },],
    columnDefs: [{
        targets: [0, 1, 2, 3, 4, 5, 6, 7],
        visible: true
    }, {
        targets: ["_all"],
        visible: false
    }, {
        /*targets: [6],
        orderable: false*/
    }],
    ajax: {
        url: base_url + 'Torneos/getInscripciones',
        type: "GET",
        datatype: "json",
        data: function (data) {
            var torneo_id = $("#table_datatable").data('default');
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
$(document).on('change', '[data-change-estado-inscripcion]', function () {
    var select = $(this)
    var inscripcion_id = select.attr('data-change-estado-inscripcion');
    var oldclass = select.attr('class')
    var oldvalue = select.attr('data-prev');
    var estado = select.val();
    var fd = new FormData();
    fd.append("inscripcion_id", inscripcion_id);
    fd.append("estado", estado);
    fd.append("csrf_token", $('[name="csrf_token"]').val());
    $.ajax({
        url: base_url + 'Torneos/manage_estado_inscripciones',
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
                        if (response.redirect == 'refresh') {
                            location.reload()
                        } else {
                            window.location.href = response.redirect
                        }
                    }
                }
            });
            select.val(oldvalue);
            return;
        } else {
            if (estado == 0) {
                var clase = 'badge badge-info';
            } else if (estado == 1) {
                var clase = 'badge badge-success';
            } else {
                var clase = 'badge badge-danger';
            }

            select.attr('class', clase);
            select.attr('data-prev', estado)
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
$(document).on('change', '#filtros_inscripciones select', function () {
    table_inscripciones.draw()
})

// PESTAÑA COMPETICIONES
var table_competiciones;
table_competiciones = $("#table_competiciones").DataTable({
    processing: true,
    serverSide: true,
    order: [],
    ordering: false,
    columns: [{ //0
        title: "Modalidad",
        name: "Modalidad",
        data: "modalidad",
    }, { //2
        title: "Categoría",
        name: "categoria",
        data: "categoria",
    }, { //3
        title: "Género",
        name: "genero",
        data: "genero",
        render: function (data, type, row) {
            if (row.genero == 1) {
                var genero = 'Masculina';
            } else {
                var genero = 'Femenina';
            }
            html = '<span class="badge badge-info">' + genero + '</span>';
            return html
        }
    }, { //3 
        title: "Peso",
        name: "peso",
        data: "peso",
        render: function (data, type, row) {
            html = '<span class="badge badge-danger"> < ' + row.peso + '</span>';
            return html
        }
    }, { //4
        title: "Estado",
        name: "estado",
        data: "estado",
        render: function (data, type, row) {
            if (row.estado == 0) {
                html = '<span class="badge badge-info">Pendiente</span>';
            } else if (row.estado == 1) {
                html = '<span class="badge badge-succes">Sorteado</span>';
            } else {
                html = '<span class="badge badge-secondary">Completado</span>';
            }
            return html
        }
    }, { // 5
        name: "Acción",
        title: "Acción",
        data: "bonoID",
        className: "text-truncate",
        render: function (data, type, row) {
            html = '<div class="form-button-action">';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver inscripciones" data-ver-inscripciones="' + row.competicion_torneo_id + '"><i class="fas fa-list"></i></button>';
            html += '<button type="button" data-tooltip title="" class="btn btn-sm btn-outline-primary" data-original-title="Ver usuario responsable" data-editar-torneo="' + row.torneo_id + '" data-slug="' + row.slug + '"><i class="fa fa-edit"></i></a>';
            html += '</div>';
            return html
        }
    },],
    columnDefs: [{
        targets: [0, 1, 2, 3, 4, 5],
        visible: true
    }, {
        targets: ["_all"],
        visible: false
    }, {
        targets: [5],
        orderable: false
    }],
    ajax: {
        url: base_url + 'Torneos/getCompeticiones',
        type: "GET",
        datatype: "json",
        data: function (data) {
            var torneo_id = $('[name="torneo_id"]').val();
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