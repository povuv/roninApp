<?php
defined('_RONIN') or die;
$formParams = $_POST;
if(isset($formParams['ronin-form'])){
    switch($formParams['ronin-form']){
        case 'delete':
        case 'item':
            formsHandler($formParams);
            header('Location: '.base64_decode($formParams['roninRetorno']));
        break;
    }
}
?>
