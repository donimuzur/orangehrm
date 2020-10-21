<?php

abstract class BaseFingerspotDevices extends sfDoctrineRecord
{
     public function setTableDefinition()
     {
         $this->setTableName('ohrm_devices');
         $this->hasColumn('No', 'integer', null, array(
            'type' => 'integer',
            'primary' => true,
            'autoincrement' => true,
            'notnull' => true,
            ));
         $this->hasColumn('server_IP', 'string', 255, array(
            'type' => 'string',
            'length' => 255,
            'notnull' => true,
            ));
         $this->hasColumn('server_port', 'string', 255, array(
            'type' => 'string',
            'length' => 255,
            'notnull' => true,
         ));
         $this->hasColumn('device_sn', 'string', 255, array(
            'type' => 'string',
            'length' => 255,
            'notnull' => true,
         ));
     }
     public function setUp()
     {
         parent::setUp();
     }
}
?>