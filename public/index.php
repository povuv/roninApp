<?php
define('_RONIN', 1);

session_start();
include '../functions.php';
$app                 = include '../app.php';
$config              = include '../config.php';
if($config['debug']){ error_reporting(E_ALL); ini_set("display_errors", 1); }

$users                   = $app['users'];
$userGroups              = $app['user-groups'];
$roninApp['url']         = roninParseUrl(RONIN_URL_BASE.$_SERVER['REQUEST_URI']);
$roninApp['users']       = $app['users'];
$roninApp['user-groups'] = $app['user-groups'];
$roninApp['user']        = getUser($users, $userGroups);
$roninApp['options']     = $config;
$roninApp['nav']         = $app['nav'];
$roninApp['page']        = getPage($roninApp['url']['params'], $app['nav']);
$roninApp['data']        = getData();

$typedData = array(
    'section' => array(),
    'content' => array(),
    'article' => array(),
);
foreach($roninApp['data'] as $dataKey => $dataValue){
    $typedData[$dataValue['type']][] = $dataValue;
}
foreach($typedData as $dataTypeKey => $dataTypeValue){
    $typedData[$dataTypeKey] = orderBy($typedData[$dataTypeKey], 'ordering');
}
foreach($typedData as $dataTypeKey => $dataTypeValue){
    foreach($dataTypeValue as $dataKey => $dataValue){
        if($dataValue['state'] > 0){
            $roninData[$dataTypeKey][$dataKey] = $dataValue;
        }
    }
}
$viewFile = getViewFile($roninApp['page']['view']);
switch($roninApp['page']['alias']){
    case 'rfh':
        include $viewFile;
    break;
    default:
        ?>
        <!DOCTYPE html>
        <html lang="es-ES">
            <head>
                <meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <meta name="keywords" content="" />
                <meta name="description" content="" />
                <title><?php echo $roninApp['page']['title'].' | '.$roninApp['options']['site-name']; ?></title>
                <link href="" rel="canonical" />
                <link rel="stylesheet" href="<?php echo RONIN_URL_BASE; ?>/css/default.css" type="text/css" />
                <!--
                <link rel="stylesheet" href="<?php echo RONIN_URL_BASE; ?>/css/user.css" type="text/css" />
                -->
                <link rel="icon" href="<?php echo RONIN_URL_BASE; ?>/images/ronin.jpg" type="image/jpg" sizes="16x16">
            </head>
            <body>
                <!--<div id="dev_info" class="dev-info">---</div>-->
                <div class="ronin-page ronin-flex-container ronin-flex-columns">
                    <?php
                    if(!$roninApp['page']['modal']){
                        ?>
                        <div class="ronin-header ronin-flex-item">
                            <header class="ronin-cabecero">
                                <?php include 'modules/header.php'; ?>
                            </header>
                        </div>
                        <?php
                    }
                    ?>
                    <main id="ronin_main" class="ronin-main ronin-flex-item">
                        <?php include $viewFile; ?>
                    </main>
                    <?php
                    if(!$roninApp['page']['modal']){
                        ?>
                        <div class="ronin-footer ronin-flex-item">
                            <div class="footer-container">
                                <footer class="ronin-footer">
                                    <?php include 'modules/footer.php'; ?>
                                </footer>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                if(!$roninApp['page']['modal']){
                    ?>
                    <div class="ronin-hidden">
                        <div class="ronin-module">
                            <?php include 'modules/nav.php'; ?>
                        </div>
                        <div class="ronin-module">
                            <?php include 'modules/user.php'; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <script type="text/javascript">
                    var roninUrlBase  = '<?php echo RONIN_URL_BASE; ?>';
                    var rootPath = '<?php echo RONIN_ROOT_PATH; ?>';
                </script>
                <script src="<?php echo RONIN_URL_BASE; ?>/js/default.js" type="text/javascript"></script>
                <!--
                <script src="<?php echo RONIN_URL_BASE; ?>/js/user.js" type="text/javascript"></script>
                -->
            </body>
        </html>
        <?php
    break;
}
?>
