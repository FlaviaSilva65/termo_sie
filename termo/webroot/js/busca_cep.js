/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {

    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $("#logradouro").val("");
        $("#bairro").val("");
        $("#cidade").val("");
        $("#uf").val("");
        //$("#ibge").val("");
    }

    //Quando o campo cep perde o foco.
    $("#cep").blur(function () {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#logradouro").val("...");
                $("#bairro").val("...");
                $("#cidade").val("...");
                $("#uf").val("...");
                // $("#vl-numero").val("...");
                //$("#ibge").val("...");

                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {
                    var bairroSel = dados.bairro;
                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#logradouro").val(dados.logradouro);
                       $("#bairro").val(dados.bairro);

                        // $("#bairro option").filter(function(){ return this.text == bairroSel;
                        // }).attr('selected', true);
                                                

                        $("#cidade").val(dados.localidade);
                        $("#uf").val(dados.uf);
                        //$("#ibge").val(dados.ibge);
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                        $("#cep").val("");
                        $("#cep").focus();
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });


    // Quando o campo logradouro perde o foco.
    $("#cidade").blur(function () {

        //Nova variável "logradouro".
        var aux = document.getElementById('logradouro').value;

        var logradouro = aux.replace(/ /g, '%20');

        //Verifica se campo logradouro possui valor informado.
        //alert (logradouro);
        //alert (cidade);
        if (logradouro != "") {

            //Consulta o webservice viacep.com.br/
            $.getJSON("https://viacep.com.br/ws/SP/" + cidade.value + "/" + logradouro + "/json/?callback=?", function (dados) {
                if (!("erro" in dados)) {
                    //Atualiza os campos com os valores da consulta.
                    $("#cep").val(dados[0].cep);
                    $("#logradouro").val(dados[0].logradouro);
                    $("#cidade").val(dados[0].localidade);
                } 
                else {
                    //CEP pesquisado não foi encontrado.
                    limpa_formulário_cep();
                    alert("CEP não encontrado.");
                }

            });
        } else {
            //cep é inválido.
            limpa_formulário_cep();
            alert("Formato de CEP inválido.");
        }
    });

});



    $(document).ready(function () {

        function limpa_formulário_cep_resp() {
            // Limpa valores do formulário de cep.
            $("#logradouroempr").val("");
            $("#bairroempr").val("");
            $("#cidadeempr").val("");
            $("#uf_empr").val("");
            // $("#num_rua_resp").val("");
            
//            $("#ibge").val("");
        }

        //Quando o campo cep perde o foco.
        $("#cepempr").blur(function () {

            dados = '';

            //Nova variável "cep" somente com dígitos.
            var cep = $(this).val().replace(/\D/g, '');

            //Verifica se campo cep possui valor informado.
            if (cep != "") {

                //Expressão regular para validar o CEP.
                var validacep = /^[0-9]{8}$/;

                //Valida o formato do CEP.
                if (validacep.test(cep)) {

                    //Preenche os campos com "..." enquanto consulta webservice.
                    $("#logradouroempr").val("...");
                    $("#bairroempr").val("...");
                    $("#cidadeempr").val("...");
                    $("#uf_empr").val("...");
//                    $("#ibge").val("...");

                    //Consulta o webservice viacep.com.br/
                    $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?", function (dados) {
                        var bairroSel = dados.bairro;

                        if (!("erro" in dados)) {
                            //Atualiza os campos com os valores da consulta.
                            $("#logradouroempr").val(dados.logradouro);
                           
                            $("#cidadeempr").val(dados.localidade);
                            $("#uf_empr").val(dados.uf);
                            $("#bairroempr").val(dados.bairro);
                            // $("#bairroempr option").filter(function(){ return this.text == bairroSel;
                            // }).attr('selected', true);

//                            $("#ibge").val(dados.ibge);
                        } //end if.
                        else {
                            //CEP pesquisado não foi encontrado.
                            limpa_formulário_cep_resp();
                            alert("CEP não encontrado.");
                            $("#cepempr").val("");
                            $("#cepempr").focus();
                        }
                    });
                } //end if.
                else {
                    //cep é inválido.
                    limpa_formulário_cep_resp();
                    alert("Formato de CEP inválido.");
                }
            } //end if.
            else {
                //cep sem valor, limpa formulário.
                limpa_formulário_cep_resp();
            }
        });
    

    // Quando o campo logradouro perde o foco.
    $("#cidadeempr").blur(function () {

        //Nova variável "logradouro".
        var aux = document.getElementById('logradouroempr').value;

        var logradouro_resp = aux.replace(/ /g, '%20');

        //Verifica se campo logradouro possui valor informado.
        //alert (logradouro);
        //alert (cidade);
        if (logradouro_resp != "") {

            //Consulta o webservice viacep.com.br/
            $.getJSON("https://viacep.com.br/ws/SP/" + cidadeempr.value + "/" + logradouro_resp + "/json/?callback=?", function (dados) {
                if (!("erro" in dados)) {
                    //Atualiza os campos com os valores da consulta.
                    $("#cepempr").val(dados[0].cep);
                    $("#logradouroempr").val(dados[0].logradouro);
                    //$("#bairroresp").val(dados[0].bairro);
                    $("#cidadeempr").val(dados[0].localidade);
                    //$("#uf").val(dados.uf);
                    //$("#ibge").val(dados.ibge);
                } //end if.
                else {
                    //CEP pesquisado não foi encontrado.
                    limpa_formulário_cep();
                    alert("CEP não encontrado.");
                }

            });
        } else {
            //cep é inválido.
            limpa_formulário_cep();
            alert("Formato de CEP inválido.");
        }
    });

});

// function carrega_endereco() {
    
//     //console.log(document.getElementById("logradouro").innerHTML);
//     document.getElementById("cepempr").value = document.getElementById("cep").value;
//     document.getElementById("logradouroempr").value = document.getElementById("logradouro").value;
//     document.getElementById("num-empr").value = document.getElementById("num").value;
//     document.getElementById("bairroempr").value = document.getElementById("bairro").value;
//     document.getElementById("cidadeempr").value = document.getElementById("cidade").value;
    
// }
