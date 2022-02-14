<?php require_once('../../globales_sistema.php'); ?>
jQuery.fn.reset = function () {
$(this).each (function() { this.reset(); });
}

$(document).ready(function() {
    var tbl = $('#tb').DataTable({
    responsive: true,
        "order": [[ 0, "desc" ]],
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
        }
    });

});

function save(){
    var vid = $('#id').val();
    if(vid === '0')
        insert();
    else
        update();
}

function insert(){
    var codigo = $('#codigo').val();
    var descripcion = $('#descripcion').val();
    var estado_fila = $('#estado_fila').val();

    $.post('ws/cliente.php', {
        op: 'addConceptosus',
        codigo:codigo,
        descripcion:descripcion,
        estado_fila:estado_fila
    }, function(data) {
        console.log(data);

        if(data === 0){
            $('body,html').animate({
            scrollTop: 0
            }, 800);
            swal("Completar todos los campos","Error","error");
        }
        else{
            $('#frmall').reset();
            $('body,html').animate({
            scrollTop: 0
            }, 800);
            swal("Se registro correctamente","Concepto Suspension de Labores","success");
            location.reload();
        }
    }, 'json');
}

function update(){
    var codigo = $('#codigo').val();
    var descripcion = $('#descripcion').val();
    var estado_fila = $('#estado_fila').val();

    $.post('ws/cliente.php', {
        op: 'upConceptosus',
        codigo:codigo,
        descripcion:descripcion,
        estado_fila:estado_fila
    }, function(data) {
        console.log(data);

        if(data === 0){
            $('body,html').animate({
            scrollTop: 0
            }, 800);
            $('#merror').show('fast').delay(4000).hide('fast');
        }
        else{
            $('#frmall').reset();
            $('body,html').animate({
            scrollTop: 0
            }, 800);
            swal("Se actualizo correctamente","Concepto Suspension de Labores","success");
            location.reload();
        }
    }, 'json');
}
function sel(id){
    $.post('ws/cliente.php', {
        op: 'getconceptosus', 
        codigo: id
    }, function(data) {
        console.log(data);
        if(data !== 0){
            $('#id').val(data.codigo);
            $('#codigo').val(data.codigo);
            $('#descripcion').val(data.descripcion);
        }
    }, 'json');
}

function del(id) {
    Swal.fire({
        title: 'Eliminar Conceptos Suspension de Labores',
        text: "¿Desea eliminar concepto suspension de labores?",
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'No, cancelar',
        reverseButtons: true
        }).then((result) => {
            if (result.value === true) {
                $.post('ws/cliente.php', {op: 'delConceptosus',  id: id}, function (data) {
                    if (data === 0) {
                    $('body,html').animate({
                        scrollTop: 0
                    }, 800);
                    swal("Oh no se pudo eliminar", "Error", "error");
                    }
                    else {
                        swal("Se elimino correctamente", "Concepto Suspension de labores  eliminado", "success");
                        location.reload();
                    }
                }, 'json');
            }
        })
   
}