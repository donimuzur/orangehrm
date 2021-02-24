<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of updateUploadCVAction
 *
 * @author Muhammad Zulfi Rusdani
 */
class updateUploadCVAction extends  ohrmBaseAction{
    
    protected function getDataGroupPermissions($dataGroups, $empNumber) { 
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        
        $entities = array('Employee' => $empNumber);
        
        $self = $empNumber == $loggedInEmpNum;
        
         return $this->getContext()->getUserRoleManager()->getDataGroupPermissions($dataGroups, array(), array(), $self, $entities);
    }
      
	
    public function execute($request) {

        $this->empNumber = $request->getParameter('empNumber');

        $values = array('vacancyPosition' => $this->vacancyPosition, 'uploadDate' => $this->uploadDate );

        $this->form = new ViewUploadCVForm(array(), $values);

        if (!($this->trigger)) {
            if ($request->isMethod('post')) {
                $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
                if ($this->form->isValid()) {
                    $this->form->save();
                    $this->getUser()->setFlash('uploadCV.success',"Save Success");
                } else {
                    $this->handleBadRequest();
                    $this->getUser()->setFlash('uploadCV.warning', __(TopLevelMessages::VALIDATION_FAILED), false);
                }
            }
        }
        $this->redirect('customRecruitment/viewCandidateAttachmentList');
    }
}

?>
