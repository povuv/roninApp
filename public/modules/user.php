<?php
defined('_RONIN') or die;
$roninRetorno  = base64_encode(RONIN_URL_BASE);
if(isset($_POST['formulario']) && $_POST['formulario'] == 'enviar'){
    $users = getData('users');
    if(isset($users[$_POST['access']['user']])){//existe Usuario
        $md5Password = md5($_POST['access']['password']);
        if($users[$_POST['access']['user']]['password'] == $md5Password){//validación correcta
            $user = $users[$_POST['access']['user']];
            $groupArray = getData('user-groups', $user['group']);
            $cookieUser  = base64_encode(json_encode($user));
            $cookieGroup = base64_encode(json_encode($groupArray[$user['group']]));
            setcookie('f8032d5cae3de20fcec887f395ec9a6a', $cookieUser,  time() + RONIN_SESSION_LIMIT);
            setcookie('9fbe2661c5e16cb142faed9abda2a806', $cookieGroup, time() + RONIN_SESSION_LIMIT);
            header('Location: '.RONIN_URL_BASE);
        }else{
            ?>
            <div class="width-limit margin-top">
                <div class="alert alert-warning">
                    <strong>Contraseña incorrecta -</strong> Lo sentimos, la contraseña introducida no es correcta. Inténtelo de nuevo.
                </div>
            </div>
            <?php
        }
    }else{
        ?>
        <div class="width-limit margin-top">
            <div class="alert alert-warning">
                <strong>Usuario no registrado -</strong> Lo sentimos, los datos de usuario no son correctos. Inténtelo de nuevo.
            </div>
        </div>
        <?php
    }
}else{
    ?>
    <?php
}
?>
<div class="user-module">
    <h2>Acceso de usuarios</h2>
    <form action="<?php echo RONIN_URL_BASE; ?>/rfh" method="post" target="_top" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="ronin-row">
            <label>Usuario</label>
            <input type="text" name="access[user]" value="" required>
        </div>
        <div class="ronin-row">
            <label>Contraseña</label>
            <input type="password" name="access[password]" value="" required>
        </div>
        <input type="hidden" name="ronin-form" value="access">
        <input type="hidden" name="reload" value="true">
        <input type="hidden" name="roninRetorno" value="<?php echo $roninRetorno; ?>">
        <div class="ronin-row">
            <input type="submit" name="enviar" value="Enviar">
        </div>
    </form>
</div>
