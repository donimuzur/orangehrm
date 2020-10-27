<?php echo javascript_include_tag(plugin_web_path('orangehrmCustomAttendancePlugin', 'js/getDeviceInfoSuccess')); ?>

<div class="box" id="DeviceInfo">
    <div class="head">
        <h1><?php echo __('Devices Info'); ?></h1>
    </div>
    <div class="inner">
        <?php if ($records == null): ?>  
            <?php echo __("Error :".$Errors) ?>
        <?php else: ?> 
        <form action="" id="employeeRecordsForm" method="post">
            <fieldset>
                <ol class="normal">
                    <li>
                        <label for="Devices_Jam">Jam</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[Jam]" value="<?php echo __($Jam) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_Admin">Admin</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[Admin]" value="<?php echo __($Admin) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_User">User</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[User]" value="<?php echo __($User) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_Fp">FP</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[Fp]" value="<?php echo __($Fp) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_Face">Face</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[Face]" value="<?php echo __($Face) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_Vein">Vein</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[Vein]" value="<?php echo __($Vein) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_Card">Card</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[Card]" value="<?php echo __($Card) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_Pwd">Pwd</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[Pwd]" value="<?php echo __($Pwd) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_AllOperasional">All Operasional</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[AllOperasional]" value="<?php echo __($AllOperasional) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_NewOperasional">New Operasional</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[NewOperasional]" value="<?php echo __($NewOperasional) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_AllPresensi">All Presensi</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[AllPresensi]" value="<?php echo __($AllPresensi) ?>" disabled="disabled">
                    </li>
                    <li>
                        <label for="Devices_NewPresensi">New Presensi</label>
                        <input class="block default" maxlength="50" type="text" name="Devices[NewPresensi]" value="<?php echo __($NewPresensi) ?>" disabled="disabled">
                    </li>
                </ol>
            </fieldset>
        </form>
        <?php endif; ?>
    </div>
</div>