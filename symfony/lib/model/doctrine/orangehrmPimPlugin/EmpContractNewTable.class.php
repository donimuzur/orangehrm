<?php

/**
 * EmpContractTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EmpContractNewTable extends PluginEmpContractNewTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object EmpContractTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EmpContractNew');
    }
}