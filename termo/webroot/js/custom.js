$(document).ready(function() {
    if(window.innerWidth >= 1024){
    vw = 400;
    vh = 250;
    bw = 430;
    bh = 280;
}else{
    vw = 326;
    vh = 194;
    bw = 356;
    bh = 230;
}
$image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width: vw,
      height: vh,
      quality : 1,
      type:'square' //circle
    },
    boundary:{
      width: bw,
      height: bh
    },
    resultimg:{
        width: 600, //350
        height: 400 //220
    },
    enableOrientation: true
});
    // $image_crop = $('#image_demo').croppie({
    //     enableExif: true,
    //     viewport: {
    //         width: 400,
    //         height: 300,
    //         type: 'square' //circle
    //     },
    //     boundary: {
    //         width: 450,
    //         height: 350
    //     }
    // });

    $('#upload_imagem').on('change', function() {
        var reader = new FileReader();
        reader.onload = function(event) {
            $image_crop.croppie('bind', {
                url: event.target.result
            }).then(function() {
                console.log('Jquery bind complete');
            });
        }
        reader.readAsDataURL(this.files[0]);
        $('#uploadimageModal').modal('show');
    });

    $('.crop_imagem').click(function(event) {
        console.log('Click');
        // var urlenviada = document.getElementById('urlupload').value;

        // alert(urlenviada);

        $image_crop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function(response) {
            console.log('Response');
            $('#img_foto').attr("src", response)
            document.getElementsByName("imagem")[0].value = response
            document.getElementById('upload_imagem').value = ''
            $('#uploadimageModal').modal('hide');
        })
    });

});