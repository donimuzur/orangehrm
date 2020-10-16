<?php

/**
 * BaseAttendanceRecord
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property int               $id                                Type: integer, primary key
 * @property int               $employeeId                        Type: integer
 * @property string            $punchInUtcTime                    Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @property string            $punchInNote                       Type: string(255)
 * @property string            $punchInTimeOffset                 Type: string(255)
 * @property string            $punchInUserTime                   Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @property string            $punchOutUtcTime                   Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @property string            $punchOutNote                      Type: string(255)
 * @property string            $punchOutTimeOffset                Type: string(255)
 * @property string            $punchOutUserTime                  Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @property string            $state                             Type: string(255)
 * @property Employee          $Employee                          
 *  
 * @method int                 getId()                            Type: integer, primary key
 * @method int                 getEmployeeid()                    Type: integer
 * @method string              getPunchinutctime()                Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @method string              getPunchinnote()                   Type: string(255)
 * @method string              getPunchintimeoffset()             Type: string(255)
 * @method string              getPunchinusertime()               Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @method string              getPunchoututctime()               Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @method string              getPunchoutnote()                  Type: string(255)
 * @method string              getPunchouttimeoffset()            Type: string(255)
 * @method string              getPunchoutusertime()              Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @method string              getState()                         Type: string(255)
 * @method Employee            getEmployee()                      
 *  
 * @method AttendanceRecord    setId(int $val)                    Type: integer, primary key
 * @method AttendanceRecord    setEmployeeid(int $val)            Type: integer
 * @method AttendanceRecord    setPunchinutctime(string $val)     Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @method AttendanceRecord    setPunchinnote(string $val)        Type: string(255)
 * @method AttendanceRecord    setPunchintimeoffset(string $val)  Type: string(255)
 * @method AttendanceRecord    setPunchinusertime(string $val)    Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @method AttendanceRecord    setPunchoututctime(string $val)    Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @method AttendanceRecord    setPunchoutnote(string $val)       Type: string(255)
 * @method AttendanceRecord    setPunchouttimeoffset(string $val) Type: string(255)
 * @method AttendanceRecord    setPunchoutusertime(string $val)   Type: datetime, Date and time in ISO-8601 format (YYYY-MM-DD HH:MI)
 * @method AttendanceRecord    setState(string $val)              Type: string(255)
 * @method AttendanceRecord    setEmployee(Employee $val)         
 *  
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseFingerspotRecord extends sfDoctrineRecord
{
     public function setTableDefinition()
     {
         $this->setTableName('view_ohrm_att_log');
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