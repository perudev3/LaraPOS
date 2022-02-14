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
    var tipo = $('#tipo').val();
    var monto = $('#monto').val();
    var estado_fila = $('#estado_fila').val();

    $.post('ws/cliente.php', {
        op: 'addConceptoemp',
        codigo:codigo,
        descripcion:descripcion,
        tipo:tipo,
        monto:monto,
        estado_fila:estado_fila
    }, function(data) {
        console.log(data);

        if(data === 0){
            $('body,html').animate({
            scrollTop: 0
            }, 800);
            swal("Todos los campos son obligatorios","Error","error");
        }
        else{
            $('#frmall').reset();
            $('body,html').animate({
            scrollTop: 0
            }, 800);
            swal("Se registro correctamente","Conceptos Aportes Empleados","success");
            location.reload();
        }
    }, 'json');
}

function update(){
    var codigo = $('#codigo').val();
    var descripcion = $('#descripcion').val();
    var tipo = $('#tipo').val();
    var monto = $('#monto').val();
    var estado_fila = $('#estado_fila').val();

    $.post('ws/cliente.php', {
        op: 'upConceptoemp',
        codigo:codigo,
        descripcion:descripcion,
        tipo:tipo,
        monto:monto,
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
            swal("Se actualizo correctamente","Concepto Aporte Empleados","success");
            location.reload();
        }
    }, 'json');
}
function sel(id){
    $.post('ws/cliente.php', {
        op: 'getconceptoemp', 
        codigo: id
    }, function(data) {
        console.log(data);
        if(data !== 0){
            $('#id').val(data.codigo);
            $('#codigo').val(data.codigo);
            $('#descripcion').val(data.descripcion);
            $('#tipo option[value="'+data.tipo+'"]').attr('selected', true);
            $('#monto').val(data.monto);
        }
    }, 'json');
}

function del(id) {
 
    Swal.fire({
        title: 'Eliminar Concepto Aporte Empleado',
        text: "¿Desea eliminar concepto aporte Empleado?",
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'No, cancelar',
        reverseButtons: true
        }).then((result) => {
            if (result.value === true) {
                $.post('ws/cliente.php', {op: 'delconceptoemp',  id: id}, function (data) {
                    if (data === 0) {
                    $('body,html').animate({
                        scrollTop: 0
                    }, 800);
                    swal("Oh no se pudo eliminar", "Error", "error");
                    }
                    else {
                        swal("Se elimino correctamente", "Concepto Aporte Empleado eliminado", "success");
                        location.reload();
                    }
                }, 'json');
            }
        })
}