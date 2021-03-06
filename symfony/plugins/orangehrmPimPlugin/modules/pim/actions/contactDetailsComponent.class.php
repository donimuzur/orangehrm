<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

class contactDetailsComponent extends sfComponent {

    private $employeeEventService;

    /**
     * Get Employee event Service
     *
     * @return EmployeeEventService|mixed
     */
    private function getEmployeeEventService() {

        if(is_null($this->employeeEventService)) {
            $this->employeeEventService = new EmployeeEventService();
        }

        return $this->employeeEventService;
    }

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }
    
    public function execute($request) {
        
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        
        $contact = $request->getParameter('contact');
        $empNumber = (isset($contact['empNumber']))?$contact['empNumber']:$request->getParameter('empNumber');
        $this->empNumber = $empNumber;
              
        $this->contactDetailsPermission = $this->getDataGroupPermissions('contact_details', $empNumber);
        
                
        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }

        $param = array('empNumber' => $empNumber,  'contactDetailsPermission' => $this->contactDetailsPermission);
        $this->setForm(new EmployeeContactDetailsForm(array(), $param, true));

    }

    protected function getDataGroupPermissions($dataGroups, $empNumber) { 
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        
        $entities = array('Employee' => $empNumber);
        
        $self = $empNumber == $loggedInEmpNum;
        
         return $this->getContext()->getUserRoleManager()->getDataGroupPermissions($dataGroups, array(), array(), $self, $entities);
    }
      
    protected function IsActionAccessible($empNumber) {
        
        $isValidUser = true;
        
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();     
        
        $userRoleManager = $this->getContext()->getUserRoleManager();            
        $accessible = $userRoleManager->isEntityAccessible('Employee', $empNumber);
            
        if ($empNumber != $loggedInEmpNum && (!$accessible)) {
            $isValidUser = false;
        }      
        
        return $isValidUser;
    }

}

