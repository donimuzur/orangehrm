<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewUploadCVComponent
 *
 * @author Muhamamd Zulfi Rusdani
 */
class viewUploadCVComponent extends  sfComponent{
    
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
        $this->deleteForm = new viewUploadCVDeleteForm(array(), array(), true);
        $this->permission = $this->getDataGroupPermissions('upload_cv',  $this->empNumber);
        
    }
}

?>
