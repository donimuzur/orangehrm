<?php 

class viewCandidateAttachmentListComponent extends sfComponent {
  
    private $CustomRecruitmentCandidateService;

    private function GetCustomRecruitmentCandidateService ()
    {
        if(is_null($this->CustomRecruitmentCandidateService)) {
            $this->CustomRecruitmentCandidateService = new CustomRecruitmentCandidateService();
        }

        return $this->CustomRecruitmentCandidateService;
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
        $isPaging = $request->getParameter('pageNo');
        $pageNumber = $isPaging;
		
        $noOfRecords = $noOfRecords = sfConfig::get('app_items_per_page');
        $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;

        $records = array();

        $CandidateAttachmentList = $request->getParameter('CandidateAttachmentList');
        $empNumber = (isset($contact['empNumber']))?$contact['empNumber']:$request->getParameter('empNumber');
        $this->empNumber = $empNumber;
              
        $this->permission = $this->getDataGroupPermissions('upload_cv', $empNumber);
        
        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }

        $param = array('empNumber' => $empNumber,  'permission' => $this->permission);
        $this->setForm(new EmployeeContactDetailsForm(array(), $param, true));

        $this->_setListComponent($records, $noOfRecords, $pageNumber, null);
    }

    protected function getDataGroupPermissions($dataGroups, $empNumber) { 
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        
        $entities = array('Employee' => $empNumber);
        
        $self = $empNumber == $loggedInEmpNum;
        
         return $this->getContext()->getUserRoleManager()->getDataGroupPermissions($dataGroups, array(), array(), $self, $entities);
    }

    private function _setListComponent($records, $noOfRecords, $pageNumber, $count = null) {

        $configurationFactory = new CustomRecruitmentCandidateAttachmentRecordHeaderFactory();
        $userRoleManager = $this->getContext()->getUserRoleManager();
        $loggedInEmpNumber = $this->getUser()->getEmployeeNumber();

        ohrmListComponent::setActivePlugin('orangehrmCustomRecruitmentPlugin');
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        
        ohrmListComponent::setListData($records);
        ohrmListComponent::setPageNumber($pageNumber);
        ohrmListComponent::setItemsPerPage($noOfRecords);
        ohrmListComponent::setNumberOfRecords($count);
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
?>
