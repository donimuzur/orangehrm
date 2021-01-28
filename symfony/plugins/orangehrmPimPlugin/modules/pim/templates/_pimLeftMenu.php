<div id="sidebar">

    <?php
    $width = '200';
    $height = '200';

    if ((!empty($empPicture)) && ($photographPermissions->canRead())) {
        $width = $empPicture->width;
        $height = $empPicture->height;
    }

    include_partial('photo', array('empNumber' => $empNumber,
        'width' => $width, 'height' => $height,
        'editMode' => isset($editPhotoMode) ? $editPhotoMode : false,
        'fullName' => htmlspecialchars($form->fullName), 'photographPermissions' => $photographPermissions));
    ?>        

    <ul id="sidenav">
        <?php
        foreach ($menuItems as $action => $properties):
            $label = $properties['label'];
            $listClass = ($action == $currentAction) ? ' class="selected"' : '';
            $url = url_for($properties['module'] . '/' . $action . '?empNumber=' . $empNumber);
            ?>
            <li<?php echo $listClass; ?>><a href="<?php echo $url; ?>"><?php echo __($label); ?></a></li>
            <?php
        endforeach;
        ?>
        <?php include_component('core', 'ohrmPluginPannel', array('location' => 'pim_left_menu_bottom')); ?>
    </ul>

    <p>
        <input type="button" class="" id="btnPrintToPdf" value="<?php echo __("Print to PDF"); ?>" tabindex="2" style="
            margin-top: 10px;
            width: 100%;"/>
    </p>

</div> <!-- sidebar -->

<script type="text/javascript">

    var linkPrintToPDF ='<?php echo url_for('pim/printToPdf'); ?>';
    var lang_processing = '<?php echo __js(CommonMessages::LABEL_PROCESSING);?>';
    var linkToDownloadFile='<?php echo url_for('pim/downloadFile'); ?>'
    var empNumber = <?php echo $empNumber; ?>;
    $(document).ready(function(){
        $("#btnPrintToPdf").click(function() {
           $("#btnPrintToPdf").val(lang_processing);
           printToPdf();
        });
    });
    
    function printToPdf(){
        $.ajax({
            type:     "post",
            data:    {
                empNumber: empNumber
            },
            timeout: 0,
            cache:    false,
            url:      linkPrintToPDF,
            dataType: "text",
            error: function(xhr, status, error) {
                $("#btnPrintToPdf").val("Print to PDF");
            },
            success: function (data) {
                if(data.includes("sukses")){
                    window.location = linkToDownloadFile;
                    $("#btnPrintToPdf").val("Print to PDF");
                }
                else{
                    location.reload();
                }
            }
        });  
        return false;
            
    }

    
</script>