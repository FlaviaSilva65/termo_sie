// SEDUC DPID - Manuel Afonso 47061 em 11/03/2025

function _(objId){ return document.getElementById(objId); }

// Bloqueia CTRL+P
window.addEventListener('keydown', (event)=>{
    if (event.keyCode === 80 && (event.ctrlKey || event.metaKey) && !event.altKey && (!event.shiftKey || window.chrome || window.opera)) {
        event.preventDefault();
        if (event.stopImmediatePropagation) event.stopImmediatePropagation();
        else event.stopPropagation();
        return;
    }
}, true);

// Inicializa o popover bootstrap 5.3.3
function popover(){
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

// Monitora a tecla Caps Lock
function capslock(){
    const cps = _('cps');
    if (!cps) return;
    document.addEventListener('keydown', (event)=>{
        if (event.getModifierState('CapsLock')) { cps.classList.remove('invisible'); }
        else { cps.classList.add('invisible'); }
    });
}

// Exibe ou Oculta a senha de login
function mostra_pass(viewPass,obj) {
    if( _(viewPass).getAttribute('type') == 'password' ) { _(viewPass).type = 'text'; obj.className = 'bi bi-eye eyegreen'; }
    else { _(viewPass).type = 'password'; obj.className = 'bi bi-eye-slash eyegray'; }
}

// Validação de campos
document.addEventListener('DOMContentLoaded', () => {
    // CAMPO RF, somente Nr e não permite o digito inicial ser 0
    const rf = document.getElementById('rf');
    if (rf) {
        rf.addEventListener('keydown', function(event) {
            // teclas permitidas
            if (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab'].includes(event.key)) { return;  }
            // somente números
            if (event.key >= '0' && event.key <= '9') {
                // impede 0 no início
                if (this.value.length === 0 && event.key === '0') { event.preventDefault(); }
                return;
            }
            // bloqueia qualquer outra tecla
            event.preventDefault();
        });
        rf.addEventListener('input', function() {
            this.value = this.value
                .replace(/\D/g, '')
                .replace(/^0+/, '');
        });
        rf.addEventListener('paste', function(event) { event.preventDefault(); });
    }

    // CAMPO NOME, somente letras, acentos, apóstrofe e espaço
    const nome = document.getElementById('nome');
    if (nome) {
        nome.style.textTransform = 'uppercase';
        nome.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^\p{L}' ]/gu, '');
        });
    }
});

// Modal formulário de login AJAX
function frmAjax(idFormulario = null) {
    const form = document.getElementById(idFormulario);
    if (!form || form.dataset.iniciado) return;
    form.dataset.iniciado = '1';
    document.getElementById(idFormulario).addEventListener('submit', async function(e){
        e.preventDefault();
        const form = this;
        const resposta = await fetch(form.action,{
            method:'POST',
            body:new FormData(form),
            headers:{
                'X-Requested-With':'XMLHttpRequest'
            }
        });
        const json = await resposta.json();
        //console.log(json);
        if(json.status){
            if(json.redirect) location.href = json.redirect;
            else location.reload();
        }else{
            let erro = document.getElementById('erroLogin');
            erro.innerHTML = json.message;
            erro.classList.remove('d-none');
        }
    });
    // Limpa o modal quando sair
    const modal = document.getElementById('modalLogin');
    modal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        const erro = document.getElementById('erroLogin');
        erro.classList.add('d-none');
        erro.textContent = '';
    });
}

// Focus para o primeiro elemento em fomulário
// Evita inputs hidden e disabled
// basta incluir <script> focus(); </script> acima da tag </body>
//function focus() {
//  // Formulários em modal
//  document.querySelectorAll('.modal').forEach(function (modal) {
//    modal.addEventListener('shown.bs.modal', function () {
//      const form = modal.querySelector('form');
//      if (!form) return;
//      // Busca o primeiro campo visível e habilitado
//      const firstInput = form.querySelector('input:not([type=hidden]):not([disabled]), select:not([disabled]), textarea:not([disabled])');
//      if (firstInput && firstInput.offsetParent !== null) {
//        firstInput.focus();
//      }
//    });
//  });
//  // formulários fora de modal
//  document.querySelectorAll('form').forEach(function (form) {
//    const firstInput = form.querySelector('input:not([type=hidden]):not([disabled]), select:not([disabled]), textarea:not([disabled])');
//    if (firstInput && firstInput.offsetParent !== null) {
//      firstInput.focus();
//    }
//  });
//}

