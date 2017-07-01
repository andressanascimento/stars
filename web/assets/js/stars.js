//search form action
$( "form#search-form" ).submit(function(event) {
    // Stop form from submitting normally
    event.preventDefault();

    var $form = $( this );
    var url = $form.attr( "action" );
    var grid = $form.attr( "list-grid" );
    var data = $form.serialize();

    $.post(url, data, function(data, status){
        if (status == 'success') {
            $(grid).html(data);
        }
    });
});

//open modal
$(document).on('click','.btn-modal',function(){
    console.log('oi');
    var url = $(this).attr('modal-url');

    $(".modal-title").html($(this).attr('modal-title'));
    $.get(url, function(data, status){
        if (status == 'success') {
            
            $(".modal-body").html(data);
            $('#modal').modal('show');

        }
    });
});

//submit form modal
$(document).on('submit','form#modal-form',function(){
    // Stop form from submitting normally
    event.preventDefault();

    var $form = $( this );
    var url = $form.attr( "action" );
    var data = $form.serialize();

    $.post(url, data, function(data, status){
        if (status == 'success') {
            $(".modal-body").html(data);
        }
    });
});


//reload grid after close modal
$('#modal').on('hidden.bs.modal', function () {
    var url = $("#grid").attr('url');

    $.get(url, function(data, status){
        if (status == 'success') {
            $("#grid").html(data);
        }
    });
});

$(".btn-delete").click(function() {
    var url = $(this).attr('url');
    var grid = $(this).attr('list-grid');

    var selected = [];
    $('input.checkbox-delete').each(function() {
       if ($(this).is(":checked")) {
           selected.push($(this).val());
       }
    });
    var data = {'ids':selected};

    $.post(url, data, function(data, status){
        if (status == 'success') {
            $(grid).html(data);
        }
    });

});
