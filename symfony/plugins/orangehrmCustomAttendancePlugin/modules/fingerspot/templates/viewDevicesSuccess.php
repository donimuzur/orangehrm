
<div class="box">
    <div class="head">
        <h1><?php echo __('Fingerspot Devices'); ?></h1>
    </div>
    <div class="inner">
        
        <?php include_partial('global/flash_messages', array('prefix' => 'fingerspotDevices')); ?>

        <form action="<?php echo url_for("fingerspot/viewDevices"); ?>" id="reportForm" name="frmDevices" method="post">
            <fieldset>
                <ol class="normal">
					<li>
                        <?php echo $form['serverIp']->renderLabel(__('Server IP').'<em> *</em>'); ?>
                        <?php echo $form['serverIp']->render(array("class" => "block default editable", "maxlength" => 50)); ?>
                    </li>
                    <li>
                        <?php echo $form['serverPort']->renderLabel(__('Server Port').'<em> *</em>'); ?>
                        <?php echo $form['serverPort']->render(array("class" => "block default editable", "maxlength" => 50)); ?>
                    </li>
					 <li>
                        <?php echo $form['devicesSn']->renderLabel(__('Devices SN').'<em> *</em>'); ?>
                        <?php echo $form['devicesSn']->render(array("class" => "block default editable", "maxlength" => 50)); ?>
                        <?php echo $form->renderHiddenFields(); ?>
                    </li>
					<li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
                <p class="formbuttons">
                    <input type="button" id="btnSave" value="<?php echo __("Edit"); ?>" />
                    <input type="button" id="btnInfo" value="<?php echo __("Device Info"); ?>" />
                    <input type="button" id="btnGetAllScanLog" value="<?php echo __("Get All Scanlog"); ?>" />
                    <input type="button" id="btnGetNewScanLog" value="<?php echo __("Get New Scanlog"); ?>" />
                </p>
            </fieldset>
        </form>
    </div>
</div>

<script type="text/javascript">
    //<![CDATA[
    //we write javascript related stuff here, but if the logic gets lengthy should use a seperate js file
    var edit = "<?php echo __js("Edit"); ?>";
    var save = "<?php echo __js("Save"); ?>";
    var lang_Required = "This Field is mandatory";
    var lang_lastNameRequired = "<?php echo __js(ValidationMessages::REQUIRED); ?>";
    var lang_selectGender = "<?php echo __js(ValidationMessages::REQUIRED); ?>";
    var lang_processing = '<?php echo __js(CommonMessages::LABEL_PROCESSING);?>';
    var lang_invalidDate = '<?php echo __js(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';

    var fileModified = 0;
    
</script>
<?php echo javascript_include_tag(plugin_web_path('orangehrmCustomAttendancePlugin', 'js/viewDevicesSuccess')); ?>
