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
class updateEmpContractnewAction extends basePimAction {

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
        
        $empContractNew = $request->getParameter('empContactNew');
        $empNumber = (isset($empContractNew['emp_number']))?$empContractNew['emp_number']:$request->getParameter('empNumber');
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
        
        $this->form = new EmployeeContractNewForm(array(), $param, true);

        if ($this->empContractNewPermissions->canUpdate()) {

            if ($this->getRequest()->isMethod('post')) {

                $this->form->bind($request->getParameter($this->form->getName()));

                if ($this->form->isValid()) {
                    
                    $this->form->save();
                    $this->getUser()->setFlash('empContractNewMessage.success', __(TopLevelMessages::SAVE_SUCCESS));
                } else {
                    $this->handleBadRequest();
                    $this->forwardToSecureAction();
                }
            }
        }

        $empNumber = $request->getParameter('empNumber');

        $this->redirect('pim/viewJobDetails?empNumber='. $empNumber. '#employeeContractNew');
    }

}
