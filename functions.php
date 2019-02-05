<?php
function pintar($var, $label = ''){
    $styleRules = 'margin:10px;padding:10px; white-space:pre; width:100%;';
    if($label != ''){$label = '<h3>'.$label.'</h3>'; }
    if(is_array($var) || is_object($var)){
        echo '<pre style="'.$styleRules.'">'.$label; print_r($var); echo '</pre>';
    }else{
        echo '<pre style="'.$styleRules.'">'.$label.$var.'</pre>';
    }
}
function roninParseUrl($current_url){
    $parsedURL   = parse_url($current_url);
    if(isset($parsedURL['query'])){
        $pairValues  = noEmptyParams($parsedURL['query'], '&');
        foreach($pairValues as $pairValue){
            $par_valor = noEmptyParams($pairValue, '=');
            if(isset($par_valor[1])){
                $vars[$par_valor[0]] = $par_valor[1];
            }else{
                $vars[$par_valor[0]] = '';
            }
        }
    }else{
        $vars  = array();
    }
    if(strpos($current_url, '?')){
        $startQueryPos  = strpos($current_url, '?');
        $currentURLPage = substr($current_url, 0, $startQueryPos);
    }else{
        $currentURLPage = $current_url;
    }
    $urlData['current_url']      = $current_url;
    $urlData['current_url_page'] = $currentURLPage;
    $urlData['params']           = noEmptyParams($parsedURL['path'], '/');
    $urlData['vars']             = $vars;
    $urlData['params_blocks']    = paramsBlocks($urlData['params']);
    return $urlData;
}
function noEmptyParams($string, $separator){
    $params  = explode($separator, $string);
    $noEmptyParams = array();
    if(count($params)){
        foreach ($params as $key => $value) {
            if($value != ''){
                $noEmptyParams[] = $value;
            }
        }
    }else{
        if($string != ''){
            $noEmptyParams[] = $string;
        }
    }
    return $noEmptyParams;
}
function paramsBlocks($params){
    $paramsBlocks = array(
        'request' => array(),
    );
    $blockTypes   = array('rest', 'filter', 'order', 'pagination', 'page');
    if(count($params) > 0){
        foreach($params as $param){
            if(in_array($param, $blockTypes)){
                $paramsBlocks[$param] = array();
            }
        }
        $currentBlock = 'request';
        foreach($params as $param){
            if(isset($paramsBlocks[$param])){
                $currentBlock = $param;
            }else{
                $paramsBlocks[$currentBlock][] = $param;
            }
        }
    }
    return $paramsBlocks;
}
function getUser($users, $userGroups){
    if(isset($_POST['ronin-form']) && $_POST['ronin-form'] == 'access'){
        if(isset($users[$_POST['access']['user']])){
            $md5Password = md5($_POST['access']['password']);
            if($users[$_POST['access']['user']]['password'] == $md5Password){
                $user  = $users[$_POST['access']['user']];
                $group = $user['group'];
                $userCookieData = array(
                    'alias' => $user['alias'],
                    'group' => $user['group'],
                );
                $cookieUser  = base64_encode(json_encode($userCookieData));
                setcookie('f8032d5cae3de20fcec887f395ec9a6a', $cookieUser,  time() + RONIN_SESSION_LIMIT, '/');
            }else{
                $_SESSION['message'] = '<div class="width-limit margin-top"> <div class="alert alert-warning"> <strong>Contraseña incorrecta -</strong> Lo sentimos, la contraseña introducida no es correcta. Inténtelo de nuevo. </div> </div> ';
                $_SESSION['mostrar-mensaje'] = 2;
            }
        }else{
            $_SESSION['message'] = '<div class="width-limit margin-top"> <div class="alert alert-warning"> <strong>Usuario no registrado -</strong> Lo sentimos, los datos de usuario no son correctos. Inténtelo de nuevo. </div> </div>';
            $_SESSION['mostrar-mensaje'] = 2;
        }
        header('Location: '.RONIN_URL_BASE);
    }
    $data['limit'] = RONIN_SESSION_LIMIT;
    if(isset($_COOKIE['f8032d5cae3de20fcec887f395ec9a6a'])){
        $dataUserCookie  = json_decode(base64_decode($_COOKIE['f8032d5cae3de20fcec887f395ec9a6a']), true);
        $user       = $dataUserCookie['alias'];
        $group      = $dataUserCookie['group'];
        setcookie('f8032d5cae3de20fcec887f395ec9a6a', $_COOKIE['f8032d5cae3de20fcec887f395ec9a6a'], time() + RONIN_SESSION_LIMIT, '/');
    }else{
        $user       = 'public';
        $group      = 'public';
    }
    $data['user']  = $users[$user];
    $data['group'] = $userGroups[$group];
    return $data;
}
function getPage($urlParams, $nav){
    if(!isset($urlParams[0])){
        $data = $nav['home'];
        $data['error'] = '';
    }else{
        if(isset($nav[$urlParams[0]])){
            $data = $nav[$urlParams[0]];
            $data['error'] = '';
        }else{
            $data = $nav['home'];
            $data['error'] = '404';
            $_SESSION['message'] = addMessage('404');
            $_SESSION['mostrar-mensaje'] = 1;
        }
    }
    return $data;
}
function getViewFile($roninView){
    if($roninView == ''){$roninView = 'default';}
    $file = RONIN_PATH_BASE.'/views/'.$roninView.'.php';
    return $file;
}
function getData(){
    $data = array();
    foreach (glob(RONIN_ROOT.'/data/*.json') as $fileName){
        $typeDataFileName    = basename($fileName, '.json');
        $firstDashPosition   = strpos($typeDataFileName, '-');
        $typeDataName        = substr($typeDataFileName, $firstDashPosition + 1);
        $typeData            = getJson($fileName);
        $data[$typeDataName] = $typeData;
        $data[$typeDataName]['file_name'] = $fileName;
    }
    return $data;
}
function getJson($file, $path = ''){
    $filePath = $path.$file;
    $data     = file_get_contents($filePath);
    $data     = json_decode($data, true);
    return $data;
}
function formsHandler($params){
    $forms['data'] = array();
    if(isset($params['ronin-form'])){
        switch($params['ronin-form']){
            case 'delete':
                unlink($params['fileName']);
            break;
            case 'item':
                if($params['itemFields']['alias'] == ''){
                    $params['itemFields']['alias'] = textToAlias($params['itemFields']['title']);
                }
                saveData($params);
                return $forms;
            break;
        }
    $forms['return'] = $params['roninRetorno'];
    return $forms;
    }
}
function addMessage($type){
    $message = '';
    switch($type){
        case '404':
            $message = '<div class="width-limit margin-top"> <div class="alert alert-warning"> <!--<span class="cerrar"></span>--> <strong>Error 404 -</strong> Lo sentimos, la página que buscas no se ha encontrado. Asegúrate de haber escrito bien la dirección e inténtalo de nuevo. </div> </div>';
        break;
        case 'forbidden':
            $message = '<div class="width-limit margin-top"> <div class="alert alert-warning"> <!--<span class="cerrar"></span>--> <strong>Acceso prohibido -</strong> Lo sentimos, no tiene permisos para acceder a esta página. </div> </div> ';
        break;
        case 'created':
            $message = '<div class="width-limit margin-top"> <div class="alert alert-success"> <!--<span class="cerrar"></span>--> <strong>Mensaje -</strong> Artículo guardado correctamente. </div> </div>';
        break;
    }
    return $message;
}
function orderBy($data, $field){
    $fieldsToOrderBy = array();
    $orderedData = array();
    foreach($data as $keyData => $valueData){
        $fieldsToOrderBy[$keyData] = $valueData[$field];
    }
    array_multisort($fieldsToOrderBy, $data);
    return $data;
}
function textToAlias($string, $separator = '-'){
    $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
    $special_cases = array('&' => 'and');
    $string        = mb_strtolower(trim($string), 'UTF-8');
    $string        = str_replace(array_keys($special_cases), array_values($special_cases), $string);
    $string        = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
    $string        = preg_replace('/[^a-z0-9]/u', $separator, $string);
    $string        = preg_replace('/['.$separator.']+/u', $separator, $string);
    return $string;
}
function saveData($data){
    $fileName = $data['itemFields']['id'].'.json';
    $fileToWrite = RONIN_DATA_BASE.'/'.$fileName;
    $fileDir = dirname($fileToWrite);
    if (!is_dir($fileDir)){
        mkdir($fileDir, 0755, true);
    }
    $file = fopen($fileToWrite, 'w');
    fwrite($file, json_encode($data['itemFields']));
    fclose($file);
    return true;
}
?>
