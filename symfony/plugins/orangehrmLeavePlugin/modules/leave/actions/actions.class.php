<?php 
class LeaveActions extends sfActions 
{
    protected $leavePeriodService;
    protected $employeeService;
    protected $leaveRequestService;
    protected $requestedMode;
    protected $leaveTypeService;

    /**
     * Returns Leave Period
     * @return LeavePeriodService
     */
    public function getLeavePeriodService() {

        if (is_null($this->leavePeriodService)) {
            $leavePeriodService = new LeavePeriodService();
            $leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());
            $this->leavePeriodService = $leavePeriodService;
        }

        return $this->leavePeriodService;
    }

    public function getEmployeeService() {
        if (is_null($this->employeeService)) {
            $empService = new EmployeeService();
            $empService->setEmployeeDao(new EmployeeDao());
            $this->employeeService = $empService;
        }
        return $this->employeeService;
    }

    /**
     * Sets EmployeeService
     * @param EmployeeService $service
     */
    public function setEmployeeService(EmployeeService $service) {
        $this->employeeService = $service;
    }

    /**
     *
     * @return LeaveRequestService
     */
    public function getLeaveRequestService() {
        if (is_null($this->leaveRequestService)) {
            $leaveRequestService = new LeaveRequestService();
            $leaveRequestService->setLeaveRequestDao(new LeaveRequestDao());
            $this->leaveRequestService = $leaveRequestService;
        }

        return $this->leaveRequestService;
    }

    /**
     *
     * @param LeaveRequestService $leaveRequestService
     * @return void
     */
    public function setLeaveRequestService(LeaveRequestService $leaveRequestService) {
        $this->leaveRequestService = $leaveRequestService;
    }

    protected function getMode() {
        $mode = LeaveListForm::MODE_ADMIN_LIST;

        return $mode;
    }

    protected function isEssMode() {
        $userMode = 'ESS';

        if ($_SESSION['isSupervisor']) {
            $userMode = 'Supervisor';
        }

        if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 'Yes') {
            $userMode = 'Admin';
        }

        return ($userMode == 'ESS');
    }

    public function executePrintLeaveReport ($request)
    {
        
        $this->mode = $mode = $this->getMode();
        $this->essMode = $this->isEssMode();
        $this->leaveListPermissions = $this->getPermissions();
        $this->commentPermissions = $this->getCommentPermissions();
        $this->leavecommentForm = new LeaveCommentForm(array(),array(),true);
        $this->form = $this->getLeaveListForm($mode);
        $values = array();
        $page = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

        $empNumber = $request->getParameter('empNumber');
        $fromDateParam = $request->getParameter('fromDate');
        $toDateParam = $request->getParameter('toDate');
        $leaveTypeId = $request->getParameter('leaveTypeId');
        $leaveStatusId = $request->getParameter('status');
        $stdDate = $request->getParameter('stddate');
        $test ="";
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $test .= "1";
                $values = $this->form->getValues();
                $this->_setFilters($mode, $values);
            } else {
                $this->getUser()->setFlash('warning', __(TopLevelMessages::VALIDATION_FAILED), false);
                $this->handleBadRequest();
            }
        }

        $subunitId = $this->_getFilterValue($values, 'cmbSubunit', null);
        $statuses = $this->_getFilterValue($values, 'chkSearchFilter', array());
        $terminatedEmp = $this->_getFilterValue($values, 'cmbWithTerminated', null);
        $fromDate = $this->_getFilterValue($values, 'calFromDate', null);
        $toDate = $this->_getFilterValue($values, 'calToDate', null);
        $empData = $this->_getFilterValue($values, 'txtEmployee', null);
        $employeeName = $empData['empName'];

        $message = $this->getUser()->getFlash('message', '');
        $messageType = $this->getUser()->getFlash('messageType', '');

        $employeeFilter = $this->getEmployeeFilter($mode, $empNumber);

        $searchParams = new ParameterObject(array(
                    'dateRange' => new DateRange($fromDate, $toDate),
                    'statuses' => $statuses,
                    'leaveTypeId' => $leaveTypeId,
                    'employeeFilter' => $employeeFilter,
                    'noOfRecordsPerPage' => sfConfig::get('app_items_per_page'),
                    'cmbWithTerminated' => $terminatedEmp,
                    'subUnit' => $subunitId,
                    'employeeName' => $employeeName
                ));

        $leaveStatusChoices = Leave::getStatusTextList();
        $allStatuses = array_keys($leaveStatusChoices);
        $prefetchLeave = count($allStatuses) === count(array_intersect($allStatuses, $statuses));
        $result = $this->printLeaveRequestsReport($searchParams, $page, $prefetchLeave);
        $list = $result['list'];
        $recordCount = $result['meta']['record_count'];

        if ($recordCount == 0 && $request->isMethod("post")) {
            $test .= "d";
            $message = __('No Records Found');
            $messageType = 'notice';
        }

        $list = empty($list) ? null : $list;
        $this->form->setList($list);
        //$this->form->setEmployeeList($this->getEmployeeList());

        $this->message = $message;
        $this->messageType = $messageType;
        $this->baseUrl = $mode == LeaveListForm::MODE_MY_LEAVE_LIST ? 'leave/viewMyLeaveList' : 'leave/viewLeaveList';

        $this->_setPage($mode, $page);

        if ($this->leaveListPermissions->canRead()) {
            $test .= "e";
            $this->setListComponent($list, $recordCount, $page);
        }
        $balanceRequest = array();

        foreach ($list as $row) {
            $dates = $row->getLeaveStartAndEndDate();
            $balanceRequest[] = array($row->getEmpNumber(), $row->getLeaveTypeId(), $dates[0], $dates[1]);
        }

        $this->balanceQueryData = json_encode($balanceRequest);

        $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('HR Polowijo Gosari Group');
        $pdf->SetTitle(__('Leave Report'));
        $pdf->SetSubject(__('Leave Report'));
        $pdf->SetKeywords(__('PDF, Leave Report, Report, Leave'));
        
        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
        
        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        
        // set margins
        // $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        // ---------------------------------------------------------
        
        // set font
        $pdf->SetFont('dejavusans', '', 12);
        
        // add a page
        $pdf->AddPage();
        
        // writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
        // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
        
        // create some HTML content
        $html = '<h2  style="text-align:center;">Polowijo Gosari Group</h2>
        <h2  style="text-align:center">'.__("Leave Report").'</h2>
        <br />
        <br />
        <p style="text-align:left">Nama:Muhammad Zulfi Rusdani<br/>NIK:318504</p>
        <br />';
        $pdf->writeHTML($html, true, false, true, false, '');

        $fullContent = "";
        $idx = 1;
        foreach($result["list"] as $list)
        {
            $header = '<tr>';
            $footer = '</tr>';
            $content = '
                <td style="valign: middle; text-align: center">'.$idx.'</td>
                <td style="valign: middle; text-align: center">'.$list->getNumberOfDays().'</td>
                <td style="valign: middle; text-align: center">'.__(date('l', strtotime($list->getLeaveDate()["startDate"]))).'</td>
                <td style="valign: middle; text-align: center">'.__(date('d-m-Y', strtotime($list->getLeaveDate()["startDate"]))).'</td>
                <td style="valign: middle; text-align: center">'.__(date('l', strtotime($list->getLeaveDate()["endDate"]))).'</td>
                <td style="valign: middle; text-align: center">'.__(date('d-m-Y', strtotime($list->getLeaveDate()["endDate"]))).'</td>
                <td style="valign: middle; text-align: center">'.$list->getLeave()[0]->getComments().'</td>
                <td style="valign: middle; text-align: center">'.$list->getLeaveBalance().'</td>
                <td style="valign: middle; text-align: center">'.$list->getLeavestatus().'</td>
                
            ';
            $idx ++;
            $fullContent .=$header.$content. $footer;
        }
        $html ='
        <table style="border:1px solid black;font-size:10" border="1" cellpadding="0">
            <tr style="line-height: 300%;">
                <th style="valign: middle; text-align: center" rowspan="3" width="5%">'.__("No").'</th>
                <th style="valign: middle; text-align: center" colspan="5" width="45%">'.__("Leave Details").'</th>
                <th style="valign: middle; text-align: center" rowspan="3" width="30%">'.__("Reason").'</th>
                <th style="valign: middle; text-align: center" rowspan="3" width="10%">'.__("Leave Balance").'</th>
                <th style="valign: middle; text-align: center" rowspan="3" width="10%">'.__("Status").'</th>
            </tr>
            <tr>
                <td style="valign: middle; text-align: center" rowspan="2">'.__("Days").'</td>
                <td style="valign: middle; text-align: center" colspan="2">'.__("From Date").'</td>
                <td style="valign: middle; text-align: center" colspan="2">'.__("To Date").'</td>
            </tr>
            <tr>
                <td style="valign: middle; text-align: center">'.__("Day").'</td>
                <td style="valign: middle; text-align: center">'.__("Date").'</td>
                <td style="valign: middle; text-align: center">'.__("Day").'</td>
                <td style="valign: middle; text-align: center">'.__("Date").'</td>
            </tr>
            '.$fullContent.'
        </table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        // reset pointer to the last page
        $pdf->lastPage();

        //Close and output PDF documentnt
        $pdf->Output(BASE_PATH . '\files\printLeaveReport.pdf', 'F');

        $this->records = "sukses";
        $this->getUser()->setFlash('jobdetails.success', __(TopLevelMessages::VALIDATION_FAILED), false);
    }

    
    protected function printLeaveRequestsReport($searchParams, $page, $prefetchLeave = true) {
        $result = $this->getLeaveRequestService()->printLeaveRequestsReport($searchParams, $page, false, false, $prefetchLeave, true, false);
        return $result;
    }

    protected function setListComponent($leaveList, $count, $page) {

        ohrmListComponent::setConfigurationFactory($this->getListConfigurationFactory());
        ohrmListComponent::setActivePlugin('orangehrmLeavePlugin');
        ohrmListComponent::setListData($leaveList);
        ohrmListComponent::setItemsPerPage(sfConfig::get('app_items_per_page'));
        ohrmListComponent::setNumberOfRecords($count);
        ohrmListComponent::setPageNumber($page);
    }

    protected function getListConfigurationFactory() {
        $loggedInEmpNumber = $this->getUser()->getAttribute('auth.empNumber');
        LeaveListConfigurationFactory::setListMode($this->mode);
        LeaveListConfigurationFactory::setLoggedInEmpNumber($loggedInEmpNumber);
        $configurationFactory = new LeaveListConfigurationFactory();

        return $configurationFactory;
    }

    protected function getLeaveListForm($mode) {
        $this->form = new LeaveListForm($mode);
        return $this->form;
    }

    /**
     * Get employee number search filter
     * 
     * @param string $mode Leave list mode.
     * @param int $empNumber employee number
     * @return mixed Array of employee numbers or an employee number.
     */
    protected function getEmployeeFilter($mode, $empNumber) {

        $loggedInEmpNumber = $this->getUser()->getAttribute('auth.empNumber');

        // default filter to null. Will fetch all employees
        $employeeFilter = null;

        if ($mode == LeaveListForm::MODE_MY_LEAVE_LIST) {

            $employeeFilter = $loggedInEmpNumber;
        } else {
            $manager = $this->getContext()->getUserRoleManager();
            $requiredPermissions = array(
                BasicUserRoleManager::PERMISSION_TYPE_ACTION => array('view_leave_list'));

            $accessibleEmpIds = $manager->getAccessibleEntityIds('Employee', null, null, array(), array(), $requiredPermissions);

            if (empty($empNumber)) {
                $employeeFilter = $accessibleEmpIds;
            } else {
                if (in_array($empNumber, $accessibleEmpIds)) {
                    $employeeFilter = $empNumber;
                } else {
                    // Requested employee is not accessible. 
                    $employeeFilter = array();
                }
            }
        }

        return $employeeFilter;
    }

    protected function getEmployeeList() {

        $employeeService = new EmployeeService();
        $employeeList = array();

        if (Auth::instance()->hasRole(Auth::ADMIN_ROLE)) {
            $properties = array("empNumber", "firstName", "middleName", "lastName", 'termination_id');
            $employeeList = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityProperties('Employee', $properties);
        }

        if ($_SESSION['isSupervisor'] && trim(Auth::instance()->getEmployeeNumber()) != "") {
            $employeeList = $employeeService->getSubordinateList(Auth::instance()->getEmployeeNumber());
        }

        return $employeeList;
    }

    /**
     * Set's the current page number in the user session.
     * @param $page int Page Number
     * @return None
     */
    protected function _setPage($mode, $page) {
        $this->getUser()->setAttribute($mode . '.page', $page, 'leave_list');
    }

    /**
     * Get the current page number from the user session.
     * @return int Page number
     */
    protected function _getPage($mode) {
        return $this->getUser()->getAttribute($mode . '.page', 1, 'leave_list');
    }

    /**
     * Remember filter values in session.
     * 
     * Dates are expected in standard date format (yy-dd-mm, 2012-21-02).
     * 
     * @param mode Leave list mode. One of (LeaveListForm::MODE_ADMIN_LIST,
     *                                      LeaveListForm::MODE_MY_LEAVE_LIST)                            
     * @param array $filters Filters
     * @return unknown_type
     */
    protected function _setFilters($mode, array $filters) {
        return $this->getUser()->setAttribute($mode . '.filters', $filters, 'leave_list');
    }

    /**
     *
     * @return unknown_type
     */
    protected function _getFilters($mode) {
        return $this->getUser()->getAttribute($mode . '.filters', null, 'leave_list');
    }

    protected function _getFilterValue($filters, $parameter, $default = null) {
        $value = $default;
        if (isset($filters[$parameter])) {
            $value = $filters[$parameter];
        }

        return $value;
    }

    protected function _isRequestFromLeaveSummary($request) {

        $txtEmpID = $request->getGetParameter('txtEmpID');

        if (!empty($txtEmpID)) {
            return true;
        }

        return false;
    }

    protected function _getStandardDate($localizedDate) {
        $localizationService = new LocalizationService();
        $format = sfContext::getInstance()->getUser()->getDateFormat();
        $trimmedValue = trim($localizedDate);
        $result = $localizationService->convertPHPFormatDateToISOFormatDate($format, $trimmedValue);
        return $result;
    }
    
    protected function getPermissions(){
        return $this->getDataGroupPermissions('leave_list', false);
    }
    
    protected function getCommentPermissions(){
        return $this->getDataGroupPermissions('leave_list_comments', false);
    }    

    /**
     *
     * @return LeaveTypeService
     */
    protected function getLeaveTypeService() {
        if (!($this->leaveTypeService instanceof LeaveTypeService)) {
            $this->leaveTypeService = new LeaveTypeService();
        }
        return $this->leaveTypeService;
    }

    /**
     *
     * @param LeaveTypeService $service 
     */
    protected function setLeaveTypeService(LeaveTypeService $service) {
        $this->leaveTypeService = $service;
    }
    
    /**
     * 
     * @param type $dataGroups
     * @return type
     */
    protected function getDataGroupPermissions($dataGroups, $self = false) {
        return $this->getContext()->getUserRoleManager()->getDataGroupPermissions($dataGroups, array(), array(), $self, array());
    }
}
?>