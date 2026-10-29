$(document).ready(function () {

    // alert('Carregou');
    if (window.innerWidth >= 1024) {
        vw = 600;
        vh = 400;
        bw = 630;
        bh = 430;
    } else {
        vw = 326;
        vh = 194;
        bw = 356;
        bh = 230;
    }

    // Carregar o espaço para o preview da imagem
    var redimensionar = $('#preview').croppie({
        // Ativar a leitura de orientação para renderizar corretamente a imagem
        enableExif: true,

        // Ativar orientação personalizada
        enableOrientation: true,

        // O recipiente interno do coppie. A parte visível da imagem
        viewport: {
            width: 350,
            height: 350,
            // width: vw,
            // height: vh,
            // quality: 1,
            // type: 'square'
        },

        // O recipiente externo do cortador
        boundary: {
            width: 400,
            height: 400
            // width: bw,
            // height: bh
        },
        // resultimg: {
        //     width: 600, //350
        //     height: 400 //220
        // },
        // enableOrientation: true

    });
    $('.rotate').on('click', function (event) {
        $image_crop.croppie('rotate', parseInt($(this).data('deg')));
    });

    // Executar a instrução quando o usuário selecionar uma imagem
    $('#imagem').on('change', function () {
        
        // alert('Carregou');

        // FileReader para ler de forma assincrona o conteúdo dos arquivos
        var reader = new FileReader();

        // onload - Execute após ler o conteúdo
        reader.onload = function (e) {
            redimensionar.croppie('bind', {
                // Recuperar a imagem base64
                url: e.target.result
            }).then(function () {
                console.log('jQuery bind complete');
            });
        }

        // O método readAsDataURL é usado para ler o conteúdo do tipo Blob ou File
        reader.readAsDataURL(this.files[0]);
    });



    // Executar a instrução quando o usuário clicar no botão enviar
    $('.btn-upload-imagem').on('click', function () {
        var urlEnviada = document.getElementById("urlenviada").value;
        // alert(urlEnviada);
        redimensionar.croppie('result', {
            type: 'canvas', // Tipo de arquivos permitidos - base64, html, blob
            size: 'viewport', // O tamanho da imagem cortada
            // format: 'jpeg',
            // quality: 1 // valor entre 0 e 1 - default = 1
        }).then(function (img) {
            // $('#preview').attr("src",response)
            // document.getElementById('preview')[0].value = response

            // Enviar os dados para um arquivo PHP
            
            // var urlEnviada = "<?php echo $this->Url->build(['controller' => 'Usuarios', 'action' => 'imagemCelke']); ?>"
            // alert(urlEnviada);
            $.ajax({
                url: urlEnviada,
                //"upload.php", // Enviar os dados para o arquivo upload.php
                type: "POST", // Método utilizado para enviar os dados
                data: { // Dados que deve ser enviado
                    "image": img
                },
                success: function () {
                    // sweetalert - https://celke.com.br/artigo/como-usar-sweetalert-no-formulario-com-javascript-e-php
                    alert("Imagem enviada com sucesso!");
                }
            });
        });
    });
});