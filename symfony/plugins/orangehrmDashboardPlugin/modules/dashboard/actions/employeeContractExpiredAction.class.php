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
 * Description of pendingLeaveRequestsAction
 */
class employeeContractExpiredAction extends BaseDashboardAction {

    public function preExecute() {
        $this->setLayout(false);
        parent::preExecute();
    }

    public function execute($request) {
        $empNumber = null;
        $EmployeeExpiredContractList = $this->getEmployeeService()->getEmployeeContractExpiredIn30DaysNew(date('y-m-d'));
        $this->arr = array();
        foreach($EmployeeExpiredContractList as $item)
        {
            $employee = $this->getEmployeeService()->getEmployee($item['emp_number']);
            array_push($this->arr,array_merge($item,array('full_name'=>$employee->getFullName(),'sub_unit'=>$employee->getSubDivision() )));
        }

        $this->recordCount = count($this->arr);
    }
}
