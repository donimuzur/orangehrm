<?php

/**
 * FingerspotRecordTempTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class FingerspotRecordTempTable extends PluginFingerspotRecordTempTable
{
    /**
     * Returns an instance of this class.
     *
     * @return FingerspotRecordTempTable The table instance
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('FingerspotRecordTemp');
    }
}