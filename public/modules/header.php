<?php
defined('_RONIN') or die;
?>
<div class="header-container">
    <div class="header-left-container">
        <div class="ronin-cabecero-icono-menu" onclick="abrirModal(document.querySelector('.main-menu-module').parentNode.innerHTML, 'ronin-main-menu')">
            <span class="icono-menu"></span>
        </div>
    </div>
    <div class="header-center-container">
        <div class="ronin-cabecero-logo">
            <a class="enlace-home" href="/">
                <div class="ronin-logo"></div>
            </a>
        </div>
    </div>
    <div class="header-right-container">
        <div id="fs_icon" class="fs-icon fs-on-icon"></div>
        <div id="fs_button" onclick="openFullscreen()"></div>
    </div>
</div>
