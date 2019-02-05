<?php
defined('_RONIN') or die;
$sectionsNum = count($roninData['section']);
?>
 <div id="sections" class="ronin-flex-container ronin-flex-rows sections" style="width:<?php echo $sectionsNum; ?>00%;">
    <?php
    $sectionCount = 0;
    foreach($roninData['section'] as $sectionKey => $sectionData){
        $sectionCount++;
        ?>
        <div class="ronin-flex-item page-content section section-<?php echo $sectionCount; ?>" style="width:<?php echo (100 / $sectionsNum); ?>%;">
            <div class="section-content">
                <div class="view">
                    <div class="container-home">
                        <?php
                        foreach($roninData['content'] as $contentKey => $contentData){
                            if($contentData['xreference'] == $sectionData['id']){
                                ?>
                                <div style="width:100%;"><?php echo $contentData['content']; ?></div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
