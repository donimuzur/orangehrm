<?php

class FingerspotDevicesTable extends PluginFingerspotDevicesTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object AttendanceRecordTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('FingerspotDevices');
    }
}

?>