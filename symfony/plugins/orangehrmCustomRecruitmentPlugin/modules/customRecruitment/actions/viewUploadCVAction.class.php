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
class viewUploadCVAction extends ohrmBaseAction {
    
    protected function getDataGroupPermissions($dataGroups, $empNumber) { 
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        
        $entities = array('Employee' => $empNumber);
        
        $self = $empNumber == $loggedInEmpNum;
        
         return $this->getContext()->getUserRoleManager()->getDataGroupPermissions($dataGroups, array(), array(), $self, $entities);
    }
      
	
    public function execute($request) {

        $this->attEditPane = false;
        $this->attSeqNO = false;
        $this->attComments = '';
        $this->scrollToAttachments = false;
        
        $this->uploadDate = $this->request->getParameter('uploadDate');
        $this->uFile = $request->getFiles("ufile");
        $this->vacancyPosition = $this->request->getParameter('vacancyPosition');
        $this->empNumber = $request->getParameter('empNumber');

        $values = array('vacancyPosition' => $this->vacancyPosition, 'vacancyPosition' => $this->vacancyPosition );

        $this->form = new ViewUploadCVForm(array(), $values);
        $this->deleteForm = new viewUploadCVDeleteForm(array(), array(), true);
        $this->permission = $this->getDataGroupPermissions('upload_cv',  $this->empNumber);

        $this->form->setDefault('uFile',  $this->uFile);
        
        if (!($this->trigger)) {
            if ($request->isMethod('post')) {
                $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
                if ($this->form->isValid()) {
                    
                    $file = $this->getValue('ufile');
                    $asd = array_keys($array);
                    // $this->form->save();
                    // $this->fingerspotDevicesService->saveDevices($saveDevices);
                    $this->getUser()->setFlash('uploadCV.success',"Save Success ". $asd);
                } else {
                    $this->handleBadRequest();
                    $this->getUser()->setFlash('uploadCV.warning', __(TopLevelMessages::VALIDATION_FAILED), false);
                }
            }
        }
          
        
    }
}

?>
