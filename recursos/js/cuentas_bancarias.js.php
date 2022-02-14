<?php require_once('../../globales_sistema.php'); ?>
jQuery.fn.reset = function () {
$(this).each (function() { this.reset(); });
}



function insert(){
var id = $('#id').val();

var banco = $('#banco').val();

var numero_cuenta = $('#numero_cuenta').val();

var codigo_cci = $('#codigo_cci').val();

var tipo_cuenta = $('#tipo_cuenta').val();

var estado_fila = $('#estado_fila').val();

$.post('ws/cuentas_bancarias.php', {op: 'add',id:id,banco:banco,numero_cuenta,codigo_cci,tipo_cuenta,estado_fila:estado_fila}, function(data) {
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
swal.fire('Se registro correctamente','¡Cuenta Bancaria!','success');

}
}, 'json');
}




function update(){
    var id = $('#id').val();

    var banco = $('#banco').val();

    var numero_cuenta = $('#numero_cuenta').val();

    var codigo_cci = $('#codigo_cci').val();

    var tipo_cuenta = $('#tipo_cuenta').find('option:selected').val();

    var estado_fila = 1;

    
    $.post('ws/cuentas_bancarias.php', {op: 'mod',id:id,banco:banco,numero_cuenta:numero_cuenta,codigo_cci:codigo_cci,tipo_cuenta:tipo_cuenta,estado_fila:estado_fila}, function(data) {
    if(data === 0){
    $('body,html').animate({
    scrollTop: 0
    }, 800);
    $('#merror').show('fast').delay(4000).hide('fast');
    }
    else{
    $("#modal_cargando").modal("show");    
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
$.post('ws/cuentas_bancarias.php', {op: 'get', id: id}, function(data) {
if(data !== 0){

$('#id').val(data.id);

$('#banco').val(data.banco);

$('#numero_cuenta').val(data.numero_cuenta);

$('#codigo_cci').val(data.codigo_cci);

$('#tipo_cuenta option[value="'+data.tipo_cuenta+'"]').attr('selected', true);

$('#estado_fila').val(data.estado_fila);

}
}, 'json');
}

function del(id){
    if (confirm("¿Desea eliminar esta operación?")) {
        $.post('ws/cuentas_bancarias.php', {op: 'del', id: id}, function (data) {
            if (data === 0) {
                $('body,html').animate({scrollTop: 0}, 800);
                $('#merror').show('fast').delay(4000).hide('fast');
            }
            else {
                $('body,html').animate({scrollTop: 0}, 800);
                $('#msuccess').show('fast').delay(4000).hide('fast');
                location.reload();
            }
        }, 'json');
    }
}


$(document).ready(function() {
var tbl = $('#tb').DataTable({
    responsive: true,
        "order": [[ 0, "asc" ]],
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
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


