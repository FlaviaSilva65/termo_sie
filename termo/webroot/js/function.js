// Esse documento foi criado a partir do site how-to-crop-image-using-jquery-and-php

$(document).ready(function () {
    var size;
    $('#cropbox').Jcrop({
        aspectRatio: 1,
        onSelect: function (c) {
            size = {
                x: c.x,
                y: c.y,
                w: c.w,
                h: c.h
            };
            $("#crop").css("visibility", "visible");
        }
    });

    // $("#crop").click(function () {
    //     var img = $("#cropbox").attr('src');
    //     // var upload = document.getElementById('urlupload').value;
    //     // alert(img);
    //     // $("#cropped_img").show();
    //     // $("#cropped_img").attr('src', 'image-crop.php?x=' + size.x + '&y=' + size.y + '&w=' + size.w + '&h=' + size.h + '&img=' + img);

    //     $.ajax({
    //         url: 'upload.php',
    //         method: 'POST',
    //         data: { status: 'ok'},
    //         success: function(data){
    //             $("#mensagens").html('Consegui');
    //         },
    //         error: function(){
    //             $("#mensagens").html("Tivemos um erro ao salvar.");
    //         }
    //     });
    // });
});
