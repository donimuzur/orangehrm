<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewMyAttendanceRecordAction
 *
 * @author orangehrm
 */
class viewFingerspotAttendanceAction extends ohrmBaseAction {
	  
	private $fingerspotService;
	
	
    public function getDataGroupPermissions($dataGroups, $self = false) {
        return $this->getContext()->getUserRoleManager()->getDataGroupPermissions($dataGroups, array(), array(), $self, array());
    }
	
	public function getFingerspotService() {

        if (is_null($this->fingerspotService)) {

            $this->fingerspotService = new FingerspotService();
        }

        return $this->fingerspotService;
    }

    public function setFingerspotService(FingerspotService $fingerspotService) {

        $this->fingerspotService = $fingerspotService;
    }
	
    public function execute($request) {

        $this->fingerspotService = $this->getFingerspotService();
        $this->employeeId = $this->getContext()->getUser()->getEmployeeNumber();
        $this->toDate = $this->request->getParameter('toDate');
		$this->fromDate = $this->request->getParameter('fromDate');
        $this->trigger = $request->getParameter('trigger');
        $this->actionRecorder="ViewFingerspotAttendance";
        $values = array('toDate' => $this->toDate, 'fromDate' => $this->fromDate , 'employeeId' => $this->employeeId, 'trigger' => $this->trigger);
        $this->form = new FingerspotSearchForm(array(), $values);
		
		
        $this->parmetersForListCompoment = array();
        $this->showEdit = false;

		$isPaging = $request->getParameter('pageNo');
        $pageNumber = $isPaging;
		
        $noOfRecords = $noOfRecords = sfConfig::get('app_items_per_page');
        $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;

        $records = array();
		
        $this->attendancePermissions = $this->getDataGroupPermissions('attendance_records');

        if ($this->attendancePermissions->canRead()) {
            $this->_setListComponent($records, $noOfRecords, $pageNumber, null, $this->showEdit);
        }
		
        if (!($this->trigger)) {
            if ($request->isMethod('post')) {
                $this->form->bind($request->getParameter('fingerspot'));
                if ($this->form->isValid()) {
					$post = $this->form->getValues();
					
					if (!$this->employeeId) {
                        $empData = $post['employeeName'];
                        $this->employeeId = $empData['empId'];
                    }
                    if (!$this->fromDate) {
                        $this->fromDate = $post['fromDate'];
                    }
					if (!$this->toDate) {
                        $this->toDate = $post['toDate'];
                    }
				
					$isPaging = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

                    $pageNumber = $isPaging;

                    $noOfRecords = sfConfig::get('app_items_per_page');
                    $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;
					
					
                    $empRecords = array();
					if (!$this->employeeId) {
//                      $empRecords = $this->employeeService->getEmployeeList('firstName', 'ASC', false);
                        $empRecords = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntities('Employee');
                        $count = count($empRecords);
                    } else {
                        $empRecords = $this->employeeService->getEmployee($this->employeeId);
                        $empRecords = array($empRecords);
                        $count = 1;
                    }
					
					 $records = array();
                    foreach ($empRecords as $employee) {
                        $hasRecords = false;

                        $attendanceRecords = $employee->getFingerstpotRecord();
                        $total = 0;
                        foreach ($attendanceRecords as $attendance) {
                            $from = $this->date . " " . "00:" . "00:" . "00";
                            $end = $this->date . " " . "23:" . "59:" . "59";
                            if (strtotime($attendance->getPunchInUserTime()) >= strtotime($from) && strtotime($attendance->getPunchInUserTime()) <= strtotime($end)) {
                                if ($attendance->getPunchOutUtcTime()) {
                                    $total = $total + round((strtotime($attendance->getPunchOutUtcTime()) - strtotime($attendance->getPunchInUtcTime())) / 3600, 2);
                                }
                                $records[] = $attendance;
                                $hasRecords = true;
                            }
                        }

                        if ($hasRecords) {
                            $last = end($records);
                            $last->setTotal($total);
                        } else {
                            $attendance = new AttendanceRecord();
                            $attendance->setEmployee($employee);
                            $attendance->setTotal('---');
                            $records[] = $attendance;
                        }
                    }
					$this->_setListComponent($records, $noOfRecords, $pageNumber, $count);
                    
                } else {
                    $this->handleBadRequest();
                    $this->getUser()->setFlash('warning', __(TopLevelMessages::VALIDATION_FAILED), false);
                }
            }
        }
    }
	
    private function _setListComponent($records, $noOfRecords, $pageNumber, $count = null) {

        $configurationFactory = new FingerspotRecordHeaderFactory();
        $userRoleManager = $this->getContext()->getUserRoleManager();
        $loggedInEmpNumber = $this->getUser()->getEmployeeNumber();

        ohrmListComponent::setActivePlugin('orangehrmCustomAttendancePlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($records);
        ohrmListComponent::setPageNumber($pageNumber);
        ohrmListComponent::setItemsPerPage($noOfRecords);
        ohrmListComponent::setNumberOfRecords($count);
    }
    
}

?>
