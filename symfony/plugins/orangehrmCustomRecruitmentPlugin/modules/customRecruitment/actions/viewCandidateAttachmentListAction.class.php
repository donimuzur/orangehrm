<?php 

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewCandidateAttachmentListAction
 *
 * @author Muhamamd Zulfi Rusdani
 */
class viewCandidateAttachmentListAction extends ohrmBaseAction {
  
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
        if (is_null($this->listForm)) {
            $this->listForm = $form;
        }
    }
    
    public function execute($request) {
        
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();

        $isPaging = $request->getParameter('pageNo');
        $pageNumber = $isPaging;
		
        $noOfRecords = $noOfRecords = sfConfig::get('app_items_per_page');
        $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;

        $records = array();

        $this->parmetersForListCompoment = array();

        $CandidateAttachmentList = $request->getParameter('CandidateAttachmentList');
        $empNumber = (isset($contact['empNumber']))?$contact['empNumber']:$request->getParameter('empNumber');
        $this->empNumber = $empNumber;
              
        $this->permission = $this->getDataGroupPermissions('upload_cv', $empNumber);
      
        $param = array('empNumber' => $empNumber,  'permission' => $this->permission);
        $this->setForm(new ViewCandidateAttachmentListForm(array(), $param, true));
        
        $records = $this->GetCustomRecruitmentCandidateService()->getCandidateAttachmentListWithLimit( $noOfRecords,$offset);
        $count = $this->GetCustomRecruitmentCandidateService()->getCandidateAttachmentListCount();

        $this->_setListComponent($records, $noOfRecords, $pageNumber, $count);
        if (!($this->trigger)){
            if ($request->isMethod('post')) {
                $this->listForm->bind($request->getParameter($this->listForm->getName()));
                if ($this->listForm->isValid()) {
                    
                    $this->_setListComponent($records, $noOfRecords, $pageNumber, $count);
                } else {
                    $this->handleBadRequest();
                    $this->getUser()->setFlash('viewCandidateAttachmentList.warning', __(TopLevelMessages::VALIDATION_FAILED), false);
                }
            }
        }
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
    
}
?>
