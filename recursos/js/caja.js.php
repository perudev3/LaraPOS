<?php require_once('../../globales_sistema.php'); ?>
jQuery.fn.reset = function () {
$(this).each (function() { this.reset(); });
}

function insert(){
var id = $('#id').val();

var nombre = $('#nombre').val();

var ubicacion = $('#ubicacion').val();

var serie_impresora = $('#serie_impresora').val();

var estado_fila = $('#estado_fila').val();

$.post('ws/caja.php', {op: 'add',id:id,nombre:nombre,ubicacion:ubicacion,serie_impresora:serie_impresora,estado_fila:estado_fila}, function(data) {
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
swal("Se registro correctamente","Caja","success");
location.reload();
}
}, 'json');
}

function update(){
var id = $('#id').val();

var nombre = $('#nombre').val();

var ubicacion = $('#ubicacion').val();

var serie_impresora = $('#serie_impresora').val();

var estado_fila = $('#estado_fila').val();

$.post('ws/caja.php', {op: 'mod',id:id,nombre:nombre,ubicacion:ubicacion,serie_impresora:serie_impresora,estado_fila:estado_fila}, function(data) {
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
$('#msuccess').show('fast').delay(4000).hide('fast');
location.reload();
}
}, 'json');
}

function sel(id){
$.post('ws/caja.php', {op: 'get', id: id}, function(data) {
if(data !== 0){

$('#id').val(data.id);

$('#nombre').val(data.nombre);

$('#ubicacion').val(data.ubicacion);

$('#serie_impresora').val(data.serie_impresora);

$('#estado_fila').val(data.estado_fila);

}
}, 'json');
}

function del(id){
    Swal.fire({
        title: 'Eliminar Caja',
        text: "¿Desea eliminar caja?",
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'No, cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.value === true) {
            $.post('ws/caja.php', {op: 'del', id: id}, function (data) {
                if (data === 0) {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                  swal("Oh no se pudo eliminar", "Error", "error");
                }
                else {
                    swal("Se elimino correctamente", "Caja eliminada", "success");
                    location.reload();
                }
            }, 'json');
        }
    })

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
{
insert();
}
else
{
update();
}
}
