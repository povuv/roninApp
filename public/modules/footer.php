<?php
defined('_RONIN') or die;
$sectionsNum = count($roninData['section']);
?>
<ul id="menu_pie" class="ronin-flex-container ronin-flex-rows menu-pie">
    <?php
    if($sectionsNum > 1 && $roninApp['page']['alias'] != 'admin'){
        $sectionCount = 0;
        foreach($roninData['section'] as $sectionKey => $sectionValue){
            $sectionCount++;
            ?>
            <li class="ronin-flex-item" onclick="footMenuUpdate(<?php echo $sectionCount; ?>);" style="width:<?php echo (100 / $sectionsNum); ?>%;">
                <?php echo $sectionValue['content']; ?>
            </li>
            <?php
        }
    }else{
        ?>
        <li class="ronin-flex-item footer-app-default" nclick="" style=""><?php echo $roninApp['options']['footer-default']; ?></li>
        <?php
    }
    ?>
</ul>
