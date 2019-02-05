window.onload = function(){
    var povuv = getParameterByName('povuv');
    //if(povuv == 'ac'){
        fAvisoCookies();
    //}
};
function getParameterByName(name, url) {
    if(typeof url === 'undefined'){url = window.location.href;}
    name = name.replace(/[\[\]]/g, '\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
    var results = regex.exec(url);
    if(!results){return null;}
    if(!results[2]){return '';}
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}
function textareaAction(buttonItem, taId){
    textAreaItem = document.getElementById(taId);
    switch(buttonItem.id){
        case 'tb_p':
            insertAtCursor(textAreaItem, '<p></p>');
        break;
        case 'tb_div':
            insertAtCursor(textAreaItem, '<div class=""></div>');
        break;
        case 'tb_he':
            var contador = 0;
            if(confirm("Transform to HTMLEntities?")){
                var newString = textAreaItem.value;
                var newString = newString.replace(/</g, '&lt;');
                var newString = newString.replace(/>/g, '&gt;');
                textAreaItem.value = newString;
            }
        break;
    }
}
function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}
var ajaxStatus = -1;
var ajaxJson = {
  "json": "Sin datos"
};
var sections = [];
var scrollTimer = null;
var currentSection = 1;
var resizeTimer;
if(document.getElementById('ronin_main')){
    window.onresize = function(){
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function(){
            toSection(1);
        }, 250);
    };
    var divSections = document.getElementById('ronin_main');
    divSections.onscroll = function(){onSectionsScrool();};
    sections = divSections.querySelectorAll('.section');
    onSectionsScrool();
    footMenuUpdate(currentSection);
}
function footMenuUpdate(current){
    if(document.getElementById('menu_pie')){
        var menuPie = document.getElementById('menu_pie');
        var itemsMenuPie = menuPie.getElementsByTagName('li');
        for(var i = 0; i < itemsMenuPie.length; i++){
            itemsMenuPie[i].classList.remove('current');
        }
        itemsMenuPie[current -1].classList.add('current');
    }
    toSection(current);
}
function toSection(sectionNumber){
    if(document.getElementById('ronin_main')){
        var divSections = document.getElementById('ronin_main');
        var mainWidth = divSections.offsetWidth;
        var mainHeight = divSections.offsetHeight;
        var xScroll = divSections.scrollLeft;
        var currentSection = Math.floor(xScroll / mainWidth) + 1;
        var destinoScroll = (divSections.offsetWidth * sectionNumber) - divSections.offsetWidth;
        scrollTo(divSections, destinoScroll);
        //divSections.scrollLeft = (divSections.offsetWidth * sectionNumber) - divSections.offsetWidth;
    }
}
function onSectionsScrool(){
    var divSections = document.getElementById('ronin_main');
    var mainWidth = divSections.offsetWidth;
    var mainHeight = divSections.offsetHeight;
    var xScroll = divSections.scrollLeft;
    var tendencia = Math.floor(Math.floor(xScroll / (mainWidth / 2) + 1) * 0.5) + 1;
    currentSection = Math.floor(xScroll / mainWidth) + 1;
    if(scrollTimer !== null) {
        clearTimeout(scrollTimer);
    }
    scrollTimer = setTimeout(function(){
        divSections.onmouseup = function(){
            toSection(tendencia);
            clearTimeout(scrollTimer);
        };
        footMenuUpdate(tendencia);
        toSection(tendencia);
    }, 200);
}
function scrollTo(item, destino){
    var contador = 1;
    var maximo = 50;
    var destinoFinal = destino;
    var inercia = 5; //a mayor inercia menor velocidad
    var velocidad_maxima = 100;
    var scrollTimer = setInterval(function(){myScrollTimer()}, 10);
    function myScrollTimer(){
        contador++;
        var distancia = (destinoFinal * -1) + item.scrollLeft;
        if(distancia < 0){distancia = distancia * -1;}
        var velocidad = distancia / inercia;
        if(velocidad > velocidad_maxima){
            velocidad = velocidad_maxima;
        }
        if(destinoFinal < item.scrollLeft){
            if(distancia < 1 || contador > maximo){
                item.scrollLeft = destinoFinal;
                clearInterval(scrollTimer);
                sePuede = true;
            }else{
                item.scrollLeft = item.scrollLeft - velocidad;
                sePuede = false;
            }
        }else{
            if(distancia < 1 || contador > maximo){
                item.scrollLeft = destinoFinal;
                clearInterval(scrollTimer);
                //currentItem(menuItem);
                sePuede = true;
            }else{
                item.scrollLeft = item.scrollLeft + velocidad;
                sePuede = false;
            }
        }
    }
    //footMenuUpdate(currentSection);
}
/*
function inicio() {
  if (document.querySelectorAll('span.cerrar')) {
    var cierres = document.querySelectorAll('span.cerrar');
    for (var i = 0; i < cierres.length; i++) {
      cierres[i].onclick = function(event) {
        event.target.parentNode.style.display = 'none';
      };
    }
  }
}
function menuResponsive(){
    var menu = document.querySelector('main');
    var menuPrincipal = document.querySelector('nav');
    menu.classList.toggle('visible');
    menuPrincipal.classList.toggle('visible');
}
*/

function abrirModal(contenido, tipoModal){
    contenido = '<div style="overflow:auto; height:100%;">' + contenido + '</div>';
    if(typeof contenido === 'undefined'){contenido = '';}
    if(typeof tipoModal === 'undefined'){tipoModal = 'default';}
    if(document.getElementById('ronin_modal')){
        document.getElementById('ronin_modal').remove();
    }
    var modal = document.createElement('div');
    document.body.insertBefore(modal, document.body.firstChild);
    modal.setAttribute('id', 'ronin_modal');
    modal.innerHTML = '<div class="shadow"></div><div class="window"><div class="close"><span title="cerrar" class="cerrar-modal"></span></div><div class="content" style="max-width:initial;"></div></div>';
    if(tipoModal != 'ronin-modal-ac'){
        document.getElementById('ronin_modal').querySelector('.shadow').onclick = function(){cerrarModal();};
        document.getElementById('ronin_modal').querySelector('.close').onclick = function(){cerrarModal();};
    }
    modal.className = '';
    modal.classList.add('ronin-modal');
    modal.classList.add(tipoModal);
    window.setTimeout(function(){modal.classList.add('ronin-modal-visible');}, 100);
    modal.querySelector('.content').innerHTML = contenido;
}
function cerrarModal(){
    if(document.getElementById('ronin_modal')){
        var modal = document.getElementById('ronin_modal');
        modal.classList.remove('ronin-modal-visible');
        modal.querySelector('.content').innerHTML = '';
    }
}
function urlModal(event, url, tipoModal){
    if(typeof url === 'undefined'){url = '';}
    if(typeof tipoModal === 'undefined'){tipoModal = '';}
    event.preventDefault();
    event.stopPropagation();
    abrirModal('<iframe src="' + url + '" style="border:none; width:100%; min-height:96%;"></iframe>', tipoModal)
    //console.log(url);
}
function moduloModal(modulo, tipoModal){
    if(typeof tipoModal === 'undefined'){tipoModal = '';}
    abrirModal('', tipoModal);
    var modal = document.getElementById('ronin_modal');
    var contenidoModal = modal.querySelector('.content').firstChild;
    contenidoModal.appendChild(modulo);
}
function enlaceModal(event, item, tipoModal){
    if(typeof tipoModal === 'undefined'){tipoModal = 'ronin-modal';}
    event.preventDefault();
    urlModal(event, item.href, tipoModal);
}

function openFullscreen() {
    htmlNodes = document.getElementsByTagName('html');
    itemToFullScreen = htmlNodes[0];
    if(itemToFullScreen.requestFullscreen){
        itemToFullScreen.requestFullscreen();
    }else if(itemToFullScreen.mozRequestFullScreen){/* Firefox */
        itemToFullScreen.mozRequestFullScreen();
    }else if(itemToFullScreen.webkitRequestFullscreen){/* Chrome, Safari & Opera */
        itemToFullScreen.webkitRequestFullscreen();
    }else if(itemToFullScreen.msRequestFullscreen){/* IE/Edge */
        itemToFullScreen.msRequestFullscreen();
    }
    document.getElementById('fs_icon').classList.remove('fs-on-icon');
    document.getElementById('fs_icon').classList.add('fs-off-icon');
    document.getElementById('fs_button').setAttribute('onclick', 'closeFullscreen()');
}
function closeFullscreen() {
    if(document.exitFullscreen){
        document.exitFullscreen();
    }else if(document.mozCancelFullScreen){
        document.mozCancelFullScreen();
    }else if(document.webkitExitFullscreen){
        document.webkitExitFullscreen();
    }else if(document.msExitFullscreen){
        document.msExitFullscreen();
    }
    document.getElementById('fs_icon').classList.remove('fs-off-icon');
    document.getElementById('fs_icon').classList.add('fs-on-icon');
    document.getElementById('fs_button').setAttribute('onclick', 'openFullscreen()');
}


function desplegarPanel(panel){
    var acordeon = panel.parentNode.parentNode;
    var paneles = acordeon.querySelectorAll('.panel');
    var actual = panel.parentNode;
    for(var i = 0; i < paneles.length; i++){
        paneles[i].classList.add('cerrado');
    }
    actual.classList.remove('cerrado');
}

var ajaxStatus = -1;
var ajaxJson = {"json": "Sin datos"};
function relativeAjax(url){
    ajaxStatus = -1;
    var xhr = new XMLHttpRequest();
    var random = Math.floor(Math.random() * 99999999) + 10000000;
    if(url.indexOf('?') < 0){random = '?' + random;}else{random = '&' + random;}
    if(url.charAt(0) != '/'){url = '/' + url;}
    xhr.open('GET', rootPath + url + random, true);
    xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
    xhr.onload = function(){
        if (xhr.status === 200){
            ajaxStatus = 1;
            ajaxJson = JSON.parse(xhr.responseText);
        }else{
            ajaxStatus = 0;
            ajaxJson = {"data": "Error al cargar el JSON"};
        }
        return ajaxJson;
    };
    xhr.send();
}
function probarAjax(){
    ajaxJson = relativeAjax('ajax?action=prueba');
    var intervalo = setInterval(function(){checkAjaxStatus()}, 50);
    function checkAjaxStatus(){
        if(ajaxStatus > -1){
            document.getElementById('ajax-data').innerHTML = ajaxJson.data;
            clearInterval(intervalo);
        }else{
            console.log(':(');
        }
    }
}
function getAjaxJson(url){
    ajaxJson = restCall(url);
    var intervalo = setInterval(function(){checkAjaxStatus()}, 50);
    function checkAjaxStatus(){
        if(ajaxStatus > -1){
            clearInterval(intervalo);
            if(ajaxJson.data.guest == 0){
                guest = false;
            }else{
                guest = true;
            }
            return ajaxJson;
        }else{
            //console.log(':(');
        }
    }
}
function delete_cookie(name){
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
function resetSession(){
    delete_cookie('f8032d5cae3de20fcec887f395ec9a6a');
    delete_cookie('9fbe2661c5e16cb142faed9abda2a806');
    //location.reload(true);
    window.location = roninUrlBase;
}
function previewImage(input, previewId){
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).setAttribute('style', 'background-image:url(\'' + e.target.result + '\')');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function fAvisoCookies(){
    var avisoC = '';
    checkCookie();
    //if(document.querySelector('.ronin-modal-ac')){
       // document.querySelector('.ronin-modal-ac').setAttribute('onclick', 'this.remove()');
    //}
}
function setCookie(cname, cvalue, exdays){
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = 'expires=' + d.toGMTString();
    document.cookie = cname + '=' + cvalue + '; ' + expires;
}
function getCookie(cname){
    var name = cname + '=';
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return '';
}
function checkCookie(){
    var mensaje = '<div class="ac-container">En esta web utilizamos cookies propias y de terceros para mejorar y personalizar nuestros servicios y facilitarte la navegación. ' +
    'Si continuas navegando, consideramos que aceptas su instalación y uso. ' +
    'Puedes cambiar la configuración u obtener <a href="/politica-de-cookies" target="_blank">más información aquí</a>. ' +
    '<button onclick="acOk(document.querySelector(\'.ronin-modal-ac\'));">ok</button></div>';
    avisoC = getCookie('avisoC');
    if (avisoC != '') {
        return;
    }
    //urlModal(location.protocol + '//' + location.host + rootPath + '/modal/cookies');
    abrirModal(mensaje, 'ronin-modal-ac');
}
function acOk(modalAviso){
    setCookie('avisoC', 'aceptado', 365);
    modalAviso.remove();
}
/*
function delete_cookie(name, path, domain){
    if(get_cookie(name)){
        document.cookie = name + '='' + ((path) ? ';path = ' + path: '') + ((domain)?'; domain=' + domain:'') + ';expires=Thu, 01 Jan 1970 00:00:01 GMT';
    }
}
function set_cookie(name, value) {
  document.cookie = name +'='+ value +'; Path=/;';
}
function delete_cookie(name) {
  document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
*/
