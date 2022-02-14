<?php require_once('../../globales_sistema.php'); ?>
jQuery.fn.reset = function () {
$(this).each (function() { this.reset(); });
}

function insert(){
var id = $('#id').val();

var id_taxonomias = '2';

var valor = $('#valor').val();

var padre = null;

var estado_fila = '1';

$.post('ws/taxonomias_valor.php', {op: 'add',id:id,id_taxonomias:id_taxonomias,valor:valor,padre:padre,estado_fila:estado_fila}, function(data) {
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
swal("Se registro correctamente","Categoria Servicio","success");
location.reload();
}
}, 'json');
}

function update(){
var id = $('#id').val();

var id_taxonomias = '2';

var valor = $('#valor').val();

var padre = null;

var estado_fila = '1';

$.post('ws/taxonomias_valor.php', {op: 'mod',id:id,id_taxonomias:id_taxonomias,valor:valor,padre:padre,estado_fila:estado_fila}, function(data) {
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
swal("Se actualizo correctamente","Categoria Servicio","success");
location.reload();
}
}, 'json');
}

function sel(id){
$.post('ws/taxonomias_valor.php', {op: 'get', id: id}, function(data) {
if(data !== 0){

$('#id').val(data.id);

$('#valor').val(data.valor);

}
}, 'json');
}

function del(id) {
    Swal.fire({
        title: 'Eliminar Categoria Servicio',
        text: "¿Desea eliminar categoria servicio?",
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar',
        cancelButtonText: 'No, cancelar',
        reverseButtons: true
        }).then((result) => {
            if (result.value === true) {
                $.post('ws/taxonomias_valor.php', {op: 'del', id: id}, function (data) {
                    if (data === 0) {
                    $('body,html').animate({
                        scrollTop: 0
                    }, 800);
                    swal("Oh no se pudo eliminar", "Error", "error");
                    }
                    else {
                        swal("Se elimino correctamente", "Categoria Servicio eliminada", "success");
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

function img(id){
    $("#muestra").attr("src","recursos/uploads/valores_atributos_servicios/"+id+".png");
    $("#idimg").val(id);
    $('#modal_imagen').modal('show');
}

function upload_image(){
    var id = $("#idimg").val();
    var archivos = document.getElementById("imge");

    var arc = 0;
    try {
        arc = archivos.files;
    }
    catch (err)
    {
    }

    var data = new FormData();

    for (i = 0; i <arc.length; i++) {
        data.append('img', arc[i]);
    }

    data.append('op','img');
    data.append('id',id);
    
    var request = $.ajax({
        url: 'ws/taxonomias_valor.php',
        type: 'POST',
        contentType: false,
        data: data,
        processData: false,
        cache: false
    });
    request.done(function() {
        $("#imge").val("");
        $('#modal_imagen').modal('hide');
    });
    request.fail(function() {
        $("#imge").val("");
        $('#modal_imagen').modal('hide');
    });
}