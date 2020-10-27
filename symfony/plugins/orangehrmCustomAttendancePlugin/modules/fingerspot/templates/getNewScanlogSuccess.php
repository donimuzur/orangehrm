<?php echo javascript_include_tag(plugin_web_path('orangehrmCustomAttendancePlugin', 'js/getScanlogSuccess')); ?>

<div class="box" id="DeviceInfo">
    <div class="head">
        <h1><?php echo __('Get New Scanlog Data'); ?></h1>
    </div>
    <div class="inner">
        <?php if ($records == null): ?>  
            <?php echo __("Error :".$Errors) ?>
        <?php else: ?> 
        <form action="" method="post">
            <fieldset>
                <ol class="normal">
                    <li>
                        <label for="Scanlog_count">Total data Scanlog downloaded</label>
                        <input class="block default" maxlength="50" type="text" name="Scanlog[Count]" value="<?php echo __($records) ?>" disabled="disabled">
                    </li>
                    <li>
                        <p class="formbuttons">
                            <input type="button" id="btnSimpanData" value="<?php echo __("Simpan Data Scanlog"); ?>" />
                        </p>
                    </li>
                </ol>
            </fieldset>
        </form>
        <?php endif; ?>
    </div>
</div>