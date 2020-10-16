<?php

/**
 * Description of viewBirthdayComponent
 *
 * @author M Zulfi Rusdani
 */
class viewBirthdayComponent extends sfComponent {

    protected $buzzService;

    public function execute($request) {
        $this->setBuzzService(new BuzzService());

        $employeeBirthdayForNext30Days = $this->buzzService->getEmployeesHavingBirthdayOnMonth(date("Y-m-d"));
        
        $employeeBirthdayForNextYearFirstMonth = array();
        if (date("m") == 12) {
            $employeeBirthdayForNextYearFirstMonth = $this->buzzService->getEmployeesHavingBirthdayNextYear(date("Y-m-d"));
        }
        

        $this->employeeList = array_merge($employeeBirthdayForNext30Days, $employeeBirthdayForNextYearFirstMonth);

        $this->employeeService = new EmployeeService();
        $this->birthdayEmpList = array();
        foreach ($this->employeeList as $employee) {
            array_push($this->birthdayEmpList, $this->employeeService->getEmployee($employee['emp_number']));
        }
    }

    protected function setBuzzService(BuzzService $buzzService) {
        if (!($this->buzzService instanceof BuzzService)) {
            $this->buzzService = $buzzService;
        }
    }

}
