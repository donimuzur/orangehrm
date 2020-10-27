<?php
abstract class BaseFingerspotRecord extends sfDoctrineRecord
{
     public function setTableDefinition()
     {
         $this->setTableName('ohrm_att_log');
           $this->hasColumn('sn', 'string', 30, array(
              'type' => 'string',
              'length' => 30,
              'primary' => true,
                 'notnull' => true,
              ));
           $this->hasColumn('scan_date', 'datetime', null, array(
              'type' => 'datetime',
              'primary' => true,
                 'notnull' => true,
              ));
           $this->hasColumn('pin', 'string', 32, array(
              'type' => 'string',
              'length' => 32,
              'primary' => true,
                 'notnull' => true,
              ));		
           $this->hasColumn('verifymode', 'integer', null, array(
              'type' => 'integer',
                 'notnull' => true,
              ));
           $this->hasColumn('inoutmode', 'integer', null, array(
              'type' => 'integer',
                 'notnull' => true,
              ));
           $this->hasColumn('reserved', 'integer', null, array(
              'type' => 'integer',
                 'notnull' => true,
              ));	 
           $this->hasColumn('work_code', 'integer', null, array(
              'type' => 'integer',
                 'notnull' => true,
              ));	
           $this->hasColumn('att_id', 'string', 50, array(
              'type' => 'string',
              'length' => 50,
                 'notnull' => true,
              ));	
     }
     public function setUp()
     {
         parent::setUp();
         $this->hasOne('Employee', array(
            'local' => 'pin',
            'foreign' => 'pin'));
     }
}


abstract class BaseFingerspotRecordTemp extends sfDoctrineRecord
{
     public function setTableDefinition()
     {
         $this->setTableName('ohrm_att_log_tmp');
           $this->hasColumn('sn', 'string', 30, array(
              'type' => 'string',
              'length' => 30,
              'primary' => true,
                 'notnull' => true,
              ));
           $this->hasColumn('scan_date', 'datetime', null, array(
              'type' => 'datetime',
              'primary' => true,
                 'notnull' => true,
              ));
           $this->hasColumn('pin', 'string', 32, array(
              'type' => 'string',
              'length' => 32,
              'primary' => true,
                 'notnull' => true,
              ));		
           $this->hasColumn('verifymode', 'integer', null, array(
              'type' => 'integer',
                 'notnull' => true,
              ));
           $this->hasColumn('iomode', 'integer', null, array(
              'type' => 'integer',
                 'notnull' => true,
              ));
           $this->hasColumn('workcode', 'integer', null, array(
              'type' => 'integer',
                 'notnull' => true,
              ));	
     }
     public function setUp()
     {
         parent::setUp();
         $this->hasOne('Employee', array(
            'local' => 'pin',
            'foreign' => 'pin'));
     }
}
?>