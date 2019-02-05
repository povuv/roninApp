/*
document.addEventListener('DOMContentLoaded', function(){solicitarNotificaciones()});
function solicitarNotificaciones(){
    if(!Notification){
        alert('Tu navegador no permite notificaciones de Escritorio. Prueba con Chrome.');
        return;
    }
    if(Notification.permission !== "granted"){
        Notification.requestPermission();
    }
}
function notificar(){
    if(Notification.permission !== "granted"){
        Notification.requestPermission();
    }else{
        var notificacion = new Notification('Notification de povuv', {icon:'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png', body:"POVUV.ES Torneos online!"});
        notificacion.onclick = function(){window.open("http://povuv.es");};
    }
}
*/
/*
var guest = true;
var sePuede = true;
window.onload = function(){inicio()};
function inicio(){
    if(document.querySelectorAll('span.cerrar')){
        var cierres = document.querySelectorAll('span.cerrar');
        for(var i = 0; i < cierres.length; i++){
            cierres[i].onclick = function(event){event.target.parentNode.style.display = 'none';};
        }
    }
}
function itemActual(){
    var currentScroll = window.pageYOffset + window.innerHeight;
    var finalScroll = document.body.scrollHeight;
    var menu = document.getElementById('menu');
    var menuItems = menu.getElementsByTagName('li');
    var contenido = document.querySelector('.main-content');
    var contenidoItems = contenido.querySelectorAll('.seccion');
    var currentItem = 0;
    for(var i = 0; i < menuItems.length; i++){
        menuItems[i].classList.remove('current');
    }
    for(var i = 0; i < contenidoItems.length; i++){
        if(contenidoItems[i].getBoundingClientRect().top < 121){
            currentItem = i;
        }
    }
    if(finalScroll == currentScroll){
        menuItems[menuItems.length - 1].classList.add('current');
    }else{
        menuItems[currentItem].classList.add('current');
    }
}
function restCall(url){
    ajaxStatus = -1;
    var xhr = new XMLHttpRequest();
    var random = Math.floor(Math.random() * 99999999) + 10000000;
    if(url.indexOf('?') < 0){random = '?' + random;}else{random = '&' + random;}
    xhr.open('GET', url + random, true);
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
function fAvisoCookies(){
    var avisoC = '';
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
        var mensaje = 'Utilizamos cookies propias y de terceros para mejorar nuestros servicios. Si continÃºa navegando, consideramos que acepta su uso.';
        avisoC = getCookie('avisoC');
        if (avisoC != '') {
            //return;
        }
        //urlModal(location.protocol + '//' + location.host + rootPath + '/modal/cookies');
        abrirModal(mensaje, 'ronin-modal-ac');
        setCookie('avisoC', 'aceptado', 365);
    }
    checkCookie();
}
function getItemNumber(item, clase, tipo, padreId){
    if(typeof clase === 'undefined'){clase ='';}
    if(typeof tipo === 'undefined'){tipo = item.tagName;}
    if(typeof padreId === 'undefined'){padre = item.parentNode;}else{padre = document.getElementById(padreId);}
    var itemNumber = 0;
    var items = padre.getElementsByTagName(tipo);
    for(var i = 0; i < items.length; i++){
        if(clase != ''){
            if(items[i].classList.contains(clase)){
                itemNumber = i + 1;
                break;
            }
        }else{
            if(items[i] == item){
                itemNumber = i + 1;
                break;
            }
        }
    }
//console.log(itemNumber);
    return itemNumber;
}
function scrollA(destino){
    var contador = 1;
    var maximo = 50;
    var destinoFinal = destino - 100;
    //console.log(document.body.offsetHeight);
//  if(document.body.offsetWidth < 1200){
//      var destinoFinal = destino - 120;
//  }else{
//      var destinoFinal = destino;
//  }
    var inercia = 10; //a mayor inercia menor velocidad
    var velocidad_maxima = 100;
    var scrollTimer = setInterval(function(){myScrollTimer()}, 10);
    function myScrollTimer(){
        contador++;
        var distancia = (destinoFinal * -1) + window.pageYOffset +1;
        if(distancia < 0){distancia = distancia * -1;}
        var velocidad = distancia / inercia;
        if(velocidad > velocidad_maxima){
            velocidad = velocidad_maxima;
        }
        if(destinoFinal < window.pageYOffset){
            if(distancia < 1 || contador > maximo){
                window.scrollTo(0, destinoFinal);
                clearInterval(scrollTimer);
                //currentItem(menuItem);
                sePuede = true;
            }else{
                window.scrollTo(0, window.pageYOffset - velocidad);
                sePuede = false;
            }
        }else{
            if(distancia < 1 || contador > maximo){
                window.scrollTo(0, destinoFinal);
                clearInterval(scrollTimer);
                //currentItem(menuItem);
                sePuede = true;
            }else{
                window.scrollTo(0, window.pageYOffset + velocidad);
                sePuede = false;
            }
        }
    }
    if(document.body.offsetWidth < 1200){
        menuResponsive();
    }
}
function irA(event, item){
    event.preventDefault()
    var posicion = document.getElementById(item).getBoundingClientRect().top + window.pageYOffset;
    if(sePuede){
        scrollA(posicion);
    }
}
function currentItem(item){
    var menu = document.getElementById('menu');
    var menuItems = menu.getElementsByTagName('li');
    for(var i = 0; i < menuItems.length; i++){
        menuItems[i].classList.remove('current');
    }
    item.parentNode.classList.add('current');
}
function mostrarCargador(){
    var spanCargador = '<span class="animacion-cargando"></span>';
    var contenido = '<div style="overflow:auto; height:100%;">' + spanCargador + '</div>';
    if(document.getElementById('ronin_cargador')){
        var cargador = document.getElementById('ronin_cargador');
        cargador.className = 'ronin-cargador';
        window.setTimeout(function(){cargador.classList.add('ronin-cargador-visible');}, 1);
        cargador.querySelector('.content').innerHTML = contenido;
    }else{
        var cargador = document.createElement('div');
        document.body.insertBefore(cargador, document.body.firstChild);
        cargador.setAttribute('id', 'ronin_cargador');
        cargador.innerHTML = '<div class="shadow"></div><div class="window"><div class="close"></span></div><div class="content"></div></div>';
        cargador.className = 'ronin-cargador';
        window.setTimeout(function(){cargador.classList.add('ronin-cargador-visible');}, 1);
        cargador.querySelector('.content').innerHTML = contenido;
    }
}
function ocultarCargador(){
    //console.log('ok');
    if(document.getElementById('ronin_cargador')){
        //console.log('ok2');
        var cargador = document.getElementById('ronin_cargador');
        cargador.className = 'ronin-cargador';
        //document.getElementById('ronin_cargador').classList.remove('ronin-cargador-visible');
        //console.log(document.getElementById('ronin_cargador').className);
        cargador.classList.remove('ronin-cargador-visible');
    }
}
function imagesSRC(){
    var images = document.getElementsByTagName('img');
    for(var i = 0; i < images.length; i++){
        console.log(images[i].src);
        images[i].src = images[i].src.replace('http://www.cms.povuv.es/des/munditec/images/', 'http://www.cms.povuv.es/images/')
    }
}
function mostrarImagen(galeria, idImagen, galeriaName){
    if(idImagen < (galeria.length - 1)){imagenSiguiente = idImagen + 1;}else{imagenSiguiente = 0;}
    if(idImagen < 1){imagenAnterior = (galeria.length - 1);}else{imagenAnterior = idImagen - 1;}
    var contenidoModal = '<div style="background:';
    contenidoModal += 'url(' + galeria[idImagen] + ') no-repeat center center; background-size:contain; height:100%;"></div>';
    if(galeria.length > 1){
        contenidoModal += '<div id="imagenBack" class="back" onclick="mostrarImagen(' + galeriaName + ', ' + imagenAnterior + ', \'' + galeriaName + '\')"></div>';
        contenidoModal += '<div id="imagenNext" class="next" onclick="mostrarImagen(' + galeriaName + ', ' + imagenSiguiente + ', \'' + galeriaName + '\')"></div>';
    }
    mostrarCargador();
    abrirModal(contenidoModal, 'ronin-modal-img');
    window.setTimeout(function(){ocultarCargador()}, 500);
}
*/
