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
class FingerspotSearchForm extends sfForm {

    public function configure() {

        $fromDate = $this->getOption('fromDate');
		$toDate = $this->getOption('toDate');
        $employeeId = $this->getOption('employeeId');
        $trigger = $this->getOption('trigger');

        $this->setWidgets(array(
            'employeeName' => new ohrmWidgetEmployeeNameAutoFill(array('jsonList' => $this->getEmployeeListAsJson()), array('class' => 'formInputText')),
			'fromDate' => new ohrmWidgetDatePicker(array(), array('id' => 'from_attendance_date'), array('class' => 'date')),
			'toDate' => new ohrmWidgetDatePicker(array(), array('id' => 'to_attendance_date'), array('class' => 'date')),
        ));

        if ($trigger) {
            $this->setDefault('employeeName', $this->getEmployeeName($employeeId));
			$this->setDefault('fromDate', set_datepicker_date_format($fromDate));
            $this->setDefault('toDate', set_datepicker_date_format($toDate));
        }

        $this->widgetSchema->setNameFormat('fingerspot[%s]');

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        $this->setValidators(array(
			'fromDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'toDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'employeeName' => new ohrmValidatorEmployeeNameAutoFill()
        ));

        $this->getWidgetSchema()->setLabels($this->getFormLabels());
		
		$this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('fromDate', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'toDate',
                        array('throw_global_error' => true),
                        array('invalid' => 'The from date ("%left_field%") must be before the to date ("%right_field%")')
        ));
    }
    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $requiredMarker = ' <em> *</em>';

        $labels = array(
            'employeeName' => __('Employee Name'),
            'toDate' => __('To Date') . $requiredMarker,
			'fromDate' => __('From Date') . $requiredMarker
        );

        return $labels;
    }

    public function getEmployeeListAsJson() {

        $jsonArray = array();
        $employeeService = new EmployeeService();
        $employeeService->setEmployeeDao(new EmployeeDao());

        $employeeList = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntities('Employee');
        $employeeUnique = array();
        $jsonArray[] = array('name' => __('All'), 'id' => '');
        foreach ($employeeList as $employee) {

            if (!isset($employeeUnique[$employee->getEmpNumber()])) {

                $name = $employee->getFullName();
                $employeeUnique[$employee->getEmpNumber()] = $name;
                $jsonArray[] = array('name' => $name, 'id' => $employee->getEmpNumber());
            }
        }

        $jsonString = json_encode($jsonArray);

        return $jsonString;
    }

    public function getEmployeeName($employeeId) {

        $employeeService = new EmployeeService();
        $employee = $employeeService->getEmployee($employeeId);
        if ($employee->getMiddleName() != null) {
            return $employee->getFirstName() . " " . $employee->getMiddleName() . " " . $employee->getLastName();
        } else {
            return $employee->getFirstName() . " " . $employee->getLastName();
        }
    }

}