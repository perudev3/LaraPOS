<?php require_once('../../globales_sistema.php'); ?>
jQuery.fn.reset = function () {
$(this).each (function() { this.reset(); });
}

function insert(){
var id = $('#id').val();

var nombre = $('#nombre').val();

var valor = $('#valor').val();

var tipo = $('#tipo').find('option:selected').val();

var cargo = $('#cargo').find('option:selected').val();

var estado_fila = $('#estado_fila').val();

$.post('ws/impuesto.php', {op: 'add',id:id,nombre:nombre,valor:valor,tipo:tipo,cargo:cargo,estado_fila:estado_fila}, function(data) {
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
swal("Se registro correctamente","Impuesto","success");
location.reload();
}
}, 'json');
}
function update(){
var id = $('#id').val();

var nombre = $('#nombre').val();

var valor = $('#valor').val();

var tipo = $('#tipo').find('option:selected').val();

var cargo = $('#cargo').find('option:selected').val();

var estado_fila = $('#estado_fila').val();

$.post('ws/impuesto.php', {op: 'mod',id:id,nombre:nombre,valor:valor,tipo:tipo,cargo:cargo,estado_fila:estado_fila}, function(data) {
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
$.post('ws/impuesto.php', {op: 'get', id: id}, function(data) {
if(data !== 0){

$('#id').val(data.id);

$('#nombre').val(data.nombre);

$('#valor').val(data.valor);

$('#tipo option[value="'+data.tipo+'"]').attr('selected', true);

$('#cargo option[value="'+data.cargo+'"]').attr('selected', true);

$('#estado_fila').val(data.estado_fila);

}
}, 'json');
}
function del(id) {
    Swal.fire({
        title: 'Eliminar Impuesto',
        text: "¿Desea eliminar impuesto?",
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'No, cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.value === true) {
            $.post('ws/impuesto.php', {op: 'del', id: id}, function (data) {
                if (data === 0) {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                  swal("Oh no se pudo eliminar", "Error", "error");
                }
                else {
                    swal("Se elimino correctamente", "Impuesto eliminado", "success");
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
