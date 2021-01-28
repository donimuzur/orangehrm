<?php
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\IOFactory;

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
class PimActions extends sfActions {
    private $fingerspotDevicesService;
    private $fingerspotRecordTempService;
	private $fingerspotService;
    private $employeeService;
    
	public function getEmployeeService() {

        if (is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
        }

        return $this->employeeService;
    }

    public function setEmployeeService(EmployeeService $employeeService) {

        $this->employeeService = $employeeService;
    }
	
    protected function _getThemeName() {
        
        $sfUser = $this->getUser();

        if (!$sfUser->hasAttribute('meta.themeName')) {
            $sfUser->setAttribute('meta.themeName', OrangeConfig::getInstance()->getAppConfValue(ConfigService::KEY_THEME_NAME));
        }

        return $sfUser->getAttribute('meta.themeName');     
        
    }
    public function executePrintToPdf($request) { 
        try{
            
            $this->empNumber = $request->getParameter('empNumber');
            $employeeService = $this->getEmployeeService();
            $employee = $employeeService->getEmployee($this->empNumber);
            
            $templateProcessor = new TemplateProcessor('files/template.docx');
            $templateProcessor->setValue('EmployeeID', $employee->getEmployeeId());
            $templateProcessor->setValue('Name', $employee->getFullName());
            $templateProcessor->setValue('TglLahir', $employee->getEmpBirthday());
            $templateProcessor->setValue('Bpjs', $employee->getbpjs());
            $templateProcessor->setValue('Kewarganegaraan', $employee->getNationality()->getName());
            
            $templateProcessor->setValue('Marital', $employee->getemp_marital_status());
            $templateProcessor->setValue('Kelamin', $employee->getEmpGender() == 1? "Laki-laki":"Perempuan");
            
            $empPicture = $employeeService->getEmployeePicture($this->empNumber);
            if($empPicture)
            {
                $contents = $empPicture->picture;
                $contentType = $empPicture->file_type;
                $fileSize = $empPicture->size;
                $fileName = $empPicture->filename;
                file_put_contents('files/tmp_image.png', $contents);
                $templateProcessor->setImageValue('Picture', array('path' =>  BASE_PATH . '\files\tmp_image.png', 'width' => 200, 'height'=> 400,'ratio' => true));
            
            }
            else {
                $templateProcessor->setImageValue('Picture', array('path' =>   ROOT_PATH . '/symfony/web/themes/' . $this->_getThemeName() . '/images/default-photo.png', 'width' => 200,'height'=> 400, 'ratio' => true));    
            }
            
            
            $templateProcessor->setValue('Npwp', $employee->getnpwp());
            $templateProcessor->saveAs('files/data.docx');
            $this->records = "sukses";
            $this->getUser()->setFlash('personaldetails.success',"Sukses Print To Pdf" );

        }
        catch (Exception $ex) {
            $this->records = "Error";
            $this->getUser()->setFlash('personaldetails.warning',"Error ".$ex->getMessage());
        }  
                
    }
}
?>
