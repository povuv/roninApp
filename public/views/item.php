<?php
defined('_RONIN') or die;
$allFields = $app['data-fields'];
$activeFields = array();
foreach($allFields as $keyField => $valueField){
    if($valueField['state'] == 1){
        $activeFields[$keyField] = $valueField;
    }
}
$roninAction = $roninApp['url']['vars']['roninAction'];
$roninRetorno = $roninApp['url']['vars']['roninRetorno'];
$itemId = $roninApp['url']['vars']['itemId'];
foreach($roninApp['data'] as $keyData => $valueData){
    if($valueData['id'] == $itemId){
        $itemData = $valueData;
        break;
    }else{
        $itemData = array();
    }
}
?>
<div class="view view-item width-limit block-centered">
    <?php
    switch($roninAction){
        case 'edit':
            if($roninApp['user']['group']['level'] < 5){
                echo addMessage('forbidden');
            }else{
            ?>
            <form action="<?php echo RONIN_URL_BASE; ?>/rfh" method="post" target="_top" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="ronin-row edit-toolbar">
            <h2>Editar item</h2>
            <input type="submit" name="save" value="Guardar">
            </div>
            <div class="edit-top-pad"></div>
            <div class="ronin-flex-container edit-form" style="width:95%; margin:0 auto;">
            <?php
            $orderedFields = $activeFields;
            $orderedFields = orderBy($activeFields, 'ordering');
            foreach($orderedFields as $keyField => $field){
                if(isset($itemData[$field['name']])){$fieldValue = $itemData[$field['name']];}else{$fieldValue = $field['value'];}
                if($fieldValue == 'auto'){
                    switch($keyField){
                        case 'id':
                            $fieldValue = date('Ymdhis');
                        break;
                    }
                }
                $idField = 'field_'.rand(1001, 9999);
                switch($field['xreference']){
                    case 'row-start':
                        ?>
                        <div class="ronin-row">
                        <?php
                    break;
                }
                if($field['ordering'] < 10){
                    $fieldClass = 'ronin-item ronin-xs-6 ronin-sm-6 ronin-md-6 ronin-lg-6 ronin-xl-6';
                }elseif($field['ordering'] > 9 && $field['ordering'] < 20){
                    $fieldClass = 'ronin-item ronin-xs-12 ronin-sm-12 ronin-md-12 ronin-lg-12 ronin-xl-12 margin-bottom-field';
                }elseif($field['ordering'] > 19 && $field['ordering'] < 30){
                    $fieldClass = 'ronin-item ronin-xs-6 ronin-sm-6 ronin-md-6 ronin-lg-6 ronin-xl-6';
                }elseif($field['ordering'] > 29 && $field['ordering'] < 40){
                    $fieldClass = 'ronin-item ronin-xs-4 ronin-sm-4 ronin-md-4 ronin-lg-4 ronin-xl-4';
                }
                switch($field['type']){
                    case 'access':
                        ?>
                        <div class="ronin-flex-item <?php echo $fieldClass; ?>">
                            <label><?php echo $field['title']; ?></label>
                            <select onchange="updateAccessField(this.value)">
                                <?php
                                $groups = $roninApp['user-groups'];
                                foreach($groups as $keyGroup => $group){
                                    ?>
                                    <option value="<?php echo $group['level']; ?>" <?php if($group['level'] == $fieldValue){echo 'selected';} ?>><?php echo $group['title']; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <input id="field_<?php echo $idField; ?>" type="hidden" name="itemFields[<?php echo $field['name']; ?>]" value="<?php echo $fieldValue; ?>" <?php if($field['readonly']){echo 'readonly style="background-color:#eee;"';} ?> <?php if($field['required']){echo 'required';} ?>>
                        </div>
                        <script type="text/javascript">
                            function updateAccessField(accessValue){
                                var editField = document.getElementById('field_<?php echo $idField; ?>');
                                editField.value = accessValue;
                            }
                        </script>
                        <?php
                    break;
                    case 'toggle':
                        ?>
                        <div class="ronin-flex-item <?php echo $fieldClass; ?>">
                            <label style="text-align:center; display:block; margin-bottom:-2px;"><?php echo $field['title']; ?></label>
                            <label class="switch" style="display:block; margin:0 auto"><input type="checkbox" onchange="updateToggleField(this.checked, 'field_<?php echo $idField; ?>')" <?php if($fieldValue == 1){echo 'checked';} ?>><span class="slider round"></span></label>
                            <input id="field_<?php echo $idField; ?>" type="hidden" name="itemFields[<?php echo $field['name']; ?>]" value="<?php echo $fieldValue; ?>" <?php if($field['readonly']){echo 'readonly style="background-color:#eee;"';} ?> <?php if($field['required']){echo 'required';} ?>>
                        </div>
                        <?php
                    break;
                    case 'text':
                        if(isset($_GET[$field['name']])){$fieldValue = $_GET[$field['name']];}
                        ?>
                        <div class="ronin-flex-item <?php echo $fieldClass; ?>">
                            <label><?php echo $field['title']; ?></label>
                            <input type="<?php echo $field['type']; ?>" name="itemFields[<?php echo $field['name']; ?>]" value="<?php echo $fieldValue; ?>" <?php if($field['readonly']){echo 'readonly style="background-color:#eee;"';} ?> <?php if($field['required']){echo 'required';} ?>>
                        </div>
                        <?php
                    break;
                    case 'textarea':
                        ?>
                        <div class="ronin-flex-item <?php echo $fieldClass; ?>">
                            <label><?php echo $field['title']; ?></label>
                            <div class="textarea-toolbar">
                                <ul class="ronin-flex-container">
                                    <li id="tb_p" class="ronin-flex-item" onclick="textareaAction(this, '<?php echo $idField; ?>')">&lt;p&gt;</li>
                                    <li id="tb_div" class="ronin-flex-item" onclick="textareaAction(this, '<?php echo $idField; ?>')">&lt;div&gt;</li>
                                    <li id="tb_he" class="ronin-flex-item" onclick="textareaAction(this, '<?php echo $idField; ?>')">htmlentities</li>
                                </ul>
                            </div>
                            <textarea id="<?php echo $idField; ?>" style="border-radius:0 0 5px 5px" name="itemFields[<?php echo $field['name']; ?>]" <?php if($field['required']){echo 'required';} ?>><?php echo htmlentities($fieldValue); ?></textarea>
                        </div>
                        <?php
                    break;
                    case 'hidden':
                        ?>
                        <input type="<?php echo $field['type']; ?>" name="itemFields[<?php echo $field['name']; ?>]" value="<?php echo $fieldValue; ?>" <?php if($field['readonly']){echo 'readonly style="background-color:#eee;"';} ?>>
                        <?php
                    break;
                }
                switch($field['xreference']){ case 'row-end': ?> </div> <?php break; }
            }
            ?>
            </div>
            <input type="hidden" name="ronin-form" value="item">
            <input type="hidden" name="roninAction" value="<?php echo $roninAction; ?>">
            <input type="hidden" name="roninRetorno" value="<?php echo $roninRetorno; ?>">
            </form>
            <?php
            }
        break;
        case 'delete':
            if($roninApp['user']['group']['level'] < 5){
                echo addMessage('forbidden');
            }else{
            ?>
            <div class="ronin-flex-container edit-form" style="width:95%; margin:25px auto;">
                <h2>Borrar item</h2>
                <div class="alert alert-warning">
                    Estás a punto de eliminar el siguiente item:<br>
                    <strong><?php echo $itemData['title']; ?></strong>
                </div>
                <form action="<?php echo RONIN_URL_BASE; ?>/rfh" method="post" target="_top" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <div class="ronin-row">
                            <input type="submit" name="save" value="Eliminar">
                        </div>
                        <input type="hidden" name="ronin-form" value="delete">
                        <input type="hidden" name="roninAction" value="<?php echo $roninAction; ?>">
                        <input type="hidden" name="roninRetorno" value="<?php echo $roninRetorno; ?>">
                        <input type="hidden" name="fileName" value="<?php echo $itemData['file_name']; ?>">
                </form>
            </div>
            <?php
            }
        break;
        default:
            ?>
            <div class="ronin-flex-container" style="width:100%; margin:25px auto;">
                <h2><?php echo $itemData['title']; ?></h2>
                <div style="width:100%;"><?php echo $itemData['content']; ?></div>
            </div>
            <?php
        break;
    }
    ?>
</div>
<script type="text/javascript">
    function validateForm(){
        return true;
    }
    function updateToggleField(inputChecked, idField){
        var editField = document.getElementById(idField);
        if(inputChecked){editField.value = 1;}else{editField.value = 0;}
    }
</script>
