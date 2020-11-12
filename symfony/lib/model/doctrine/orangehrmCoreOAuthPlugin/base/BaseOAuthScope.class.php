<?php

/**
 * BaseOAuthScope
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string      $scope                  Type: string(255), primary key
 * @property bool        $isDefault              Type: boolean
 *  
 * @method string        getScope()              Type: string(255), primary key
 * @method bool          getIsdefault()          Type: boolean
 *  
 * @method OAuthScope    setScope(string $val)   Type: string(255), primary key
 * @method OAuthScope    setIsdefault(bool $val) Type: boolean
 *  
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseOAuthScope extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_oauth_scope');
        $this->hasColumn('scope', 'string', 255, array(
             'type' => 'string',
             'primary' => true,
             'length' => 255,
             ));
        $this->hasColumn('is_default as isDefault', 'boolean', null, array(
             'type' => 'boolean',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}