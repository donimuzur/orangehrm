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

/**
 * Actions class for PIM module updateEmpContractnewAction
 */
class UpdateEmpEndContractNewAction extends basePimAction {

    /**
     * Add / update employee empContactNew
     *
     * @param int $empNumber Employee number
     *
     * @return boolean true if successfully assigned, false otherwise
     */
    public function execute($request) {
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $this->showBackButton = true;

        $empNumber = $request->getParameter('empNumber');
        $ContractNumber = $request->getParameter('emp_contract_number');

        $this->$ContractNumber=$ContractNumber;
        $this->empNumber = $empNumber;
        
        $this->empContractNewPermissions = $this->getDataGroupPermissions('job_details', $empNumber);
  
        $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);
        
        //hiding the back button if its self ESS view
        if($loggedInEmpNum == $empNumber) {

            $this->showBackButton = false;
        }
        
        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }

        $essMode = !$adminMode && !empty($loggedInEmpNum) && ($empNumber == $loggedInEmpNum);
        $param = array('empNumber' => $empNumber, 'ESS' => $essMode , 'empContractNewPermissions' => $this->empContractNewPermissions);
        
        if ($this->empContractNewPermissions->canUpdate()) {

            if ($this->getRequest()->isMethod('post')) {
                $service = new EmployeeService();
                $empContractNew = $service->updateEmployeeContractNewStatus($empNumber, $ContractNumber);
                
                $this->getUser()->setFlash('empContractNewMessage.success', __(TopLevelMessages::SAVE_SUCCESS).$empNumber.$ContractNumber);
            }
            else{
                $this->getUser()->setFlash('empContractNewMessage.warning', __(TopLevelMessages::SAVE_FAILURE));
            }
            
        }
        $this->redirect('pim/viewJobDetails?empNumber='. $empNumber. '#employeeContractNew');
    }

}
