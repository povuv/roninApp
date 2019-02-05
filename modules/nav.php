<?php
defined('_RONIN') or die;
?>
<div class="ronin-flex-container ronin-flex-columns main-menu-module">
    <div class="ronin-flex-item">
        <h3>Menú</h3>
        <nav class="ronin-nav ronin-flex-item">
            <ul class="nav menu">
                <?php
                foreach($roninData['article'] as $valueItem){
                    ?>
                    <li><a href="<?php echo RONIN_URL_BASE; ?>/item/?roninAction=view&itemId=<?php echo $valueItem['id']; ?>&roninRetorno=<?php echo base64_encode($roninApp['url']['current_url']); ?>" onclick="enlaceModal(event, this)"><?php echo $valueItem['title']; ?></a></li>
                    <?php
                }
                ?>
            </ul>
        </nav>
    </div>
    <div class="ronin-flex-item">
        <?php
        if($roninApp['user']['group']['level'] > 0){
            ?>
            <div class="admin-menu">
                <ul class="nav menu" style="margin-bottom:10px;">
                    <li class="<?php echo $itemClass; ?> menu-user-info">
                        <span class="close-session" title="Cerrar sesión" onclick="resetSession()"></span>
                        <span><strong>Usuario: </strong><?php echo $roninApp['user']['user']['name'] ?></span>
                    </li>
                    <li class=""><a href="<?php echo RONIN_URL_BASE; ?>/admin" target="">admin</a></li>
                </ul>
            </div>
            <?php
        }
        ?>
        <div class="admin-menu">
            <p class="copyright" onclick="abrirModal(document.querySelector('.user-module').parentNode.innerHTML, 'ronin-modal-600')">
                <?php echo '© RONIN '.date('Y'); ?>
            </p>
        </div>
    </div>
</div>
