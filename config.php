<?php
defined('_RONIN') or die;

$options = array(
    'debug'          => true,
    'https'          => true,
    'site-name'      => 'RONIN',
    'footer-default' => 'RoninApp 1.0',
    'default-view'   => 'default',
);

if($options['https']){
    $protocolo = 'https';
    if($_SERVER['HTTPS'] != 'on'){
        header('Location: '.$protocolo.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); exit();
    }
}else{
    $protocolo = 'http';
}

define('RONIN_ROOT_PATH',        ''                                                            );
define('RONIN_MESSAGE',          array()                                                       );
define('RONIN_SESSION_LIMIT',    3600                                                          );
define('RONIN_PATH_BASE',        $_SERVER['DOCUMENT_ROOT'].RONIN_ROOT_PATH                     );
define('RONIN_ROOT',             str_replace('/public', '', RONIN_PATH_BASE)                   );
define('RONIN_DATA_BASE',        str_replace('/public', '', RONIN_PATH_BASE).'/data'           );
define('RONIN_UPLOADS_BASE',     str_replace('/public', '', RONIN_PATH_BASE).'/public/uploads' );
define('RONIN_URL_BASE',         $protocolo.'://'.$_SERVER['HTTP_HOST'].RONIN_ROOT_PATH        );

return $options;
?>
