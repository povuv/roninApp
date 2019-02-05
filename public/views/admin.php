<?php
defined('_RONIN') or die;
?>
<div class="ronin-admin-container">
    <?php
    if($roninApp['user']['group']['level'] < 5){
        echo addMessage('forbidden');
    }else{
        ?>
        <div class="view width-limit block-centered grid admin-data">
            <div class="message">
                <?php
                //if(isset($_SESSION['mostrar-mensaje']) && $_SESSION['mostrar-mensaje'] > 0){
                //echo $_SESSION['message'];
                //$_SESSION['mostrar-mensaje']--;
                //}
                ?>
            </div>
            <h1><?php echo $roninApp['page']['title']; ?></h1>
            <?php
            foreach($typedData as $typeKey => $TypeValue){
                ?>
                <h2 class="data-type-title"><?php echo $typeKey; ?>s</h2>
                <table class="admin-data-table">
                    <thead>
                        <tr>
                            <th class="centered-col">Nº</th>
                            <th>Title</th>
                            <th class="centered-col">State</th>
                            <th class="centered-col">Order</th>
                            <th class="actions-col">Delete</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php
                    $itemNumber = 0;
                    foreach($TypeValue as $itemKey => $item){
                        $itemNumber++;
                        ?>
                        <tr>
                            <td class="centered-col"><?php echo $itemNumber; ?></td>
                            <td><a href="<?php echo RONIN_URL_BASE; ?>/item/?roninAction=edit&itemId=<?php echo $item['id']; ?>&roninRetorno=<?php echo base64_encode($roninApp['url']['current_url']); ?>" onclick="enlaceModal(event, this)"><?php echo $item['title']; ?></a></td>
                            <td class="centered-col"><?php echo $item['state']; ?></td>
                            <td class="centered-col"><?php echo $item['ordering']; ?></td>
                            <?php
                            if($roninApp['user']['group']['level'] > 4){
                                ?>
                                <td>
                                    <div class="ronin-icons">
                                        <a href="<?php echo RONIN_URL_BASE; ?>/item/?roninAction=delete&itemId=<?php echo $item['id']; ?>&roninRetorno=<?php echo base64_encode($roninApp['url']['current_url']); ?>" onclick="enlaceModal(event, this, 'ronin-modal-600')"><span class="delete-icon"></span></a>
                                    </div>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="6">
                            <div class="add-new-row">
                                <div class="ronin-new-icon"><a href="<?php echo RONIN_URL_BASE; ?>/item/?roninAction=edit&itemId=0&type=<?php echo $typeKey; ?>&roninRetorno=<?php echo base64_encode($roninApp['url']['current_url']); ?>" onclick="enlaceModal(event, this)"><span class="new-icon"></span></a></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                </table>
                <script type="text/javascript">
                </script>
                <?php
            }
        }
        ?>
    </div>
</div>
