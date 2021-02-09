<?php
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PHPWord\Style\ListItem;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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

            //Detail Kontak
            $templateProcessor->setValue('Alamat', $employee->getStreet1());
            $templateProcessor->setValue('Kota', $employee->getCity());
            $templateProcessor->setValue('Provinsi', $employee->getProvince());
            $templateProcessor->setValue('Zip', $employee->getEmpZipcode());
            $templateProcessor->setValue('Negara', $employee->getEmployeeCountry()->getName());
            $templateProcessor->setValue('TlpnRumah', $employee->getEmpHmTelephone());
            $templateProcessor->setValue('Handphone', $employee->getEmpMobile());
            $templateProcessor->setValue('Email1', $employee->getEmpWorkEmail());
            $templateProcessor->setValue('Email2', $employee->getEmpOthEmail());

            $EmgContactList = array();
            $EmgContactIdx = 0;
            foreach($employee->getEmergencyContacts() as $item):
                $EmgContactIdx ++;
                $telphone = '';
                if(!$item->getHomePhone())
                {
                    $telphone = $item->getHomePhone();
                }
                else if(!$item->getMobilePhone())
                {
                    $telphone = $item->getMobilePhone();
                }
                else 
                {
                    $telphone = $item->getOfficePhone();
                }
                array_push($EmgContactList, array("emgKontakNo"=> $EmgContactIdx ,"emgKontakName"=>$item->getName() ,"emgKontakRealtionship"=>$item->getRelationship(),"emgKontakHomeTelphone"=>$telphone));
            endforeach;
            
            $templateProcessor->cloneRowAndSetValues('emgKontakNo', $EmgContactList);

            $templateProcessor->saveAs('files/data.docx');
            $this->records = "sukses";
            $this->getUser()->setFlash('personaldetails.success',"Sukses Print To Pdf" );
        }
        catch (Exception $ex) {
            $this->records = "Error";
            $this->getUser()->setFlash('personaldetails.warning',"Error ".$ex->getMessage());
        }  
                
    }

    public function executeExportEmployeeListToExcel($request) { 
        try {
            $this->empsearch_employee_name_empId = $request->getParameter('empsearch_employee_name_empId');
            $this->empsearch_employee_status = $request->getParameter('empsearch_employee_status');
            $this->empsearch_termination = $request->getParameter('empsearch_termination');
            $this->empsearch_job_title = $request->getParameter('empsearch_job_title');
            $this->empsearch_status = $request->getParameter('empsearch_employee_status');
            $this->empsearch_sub_unit = $request->getParameter('empsearch_sub_unit');
            $this->empsearch__csrf_token = $request->getParameter('empsearch__csrf_token');
            
            $userRoleManager = $this->getContext()->getUserRoleManager();   
            $this->employeeId = $this->getContext()->getUser()->getEmployeeNumber();

            $empRecords = array();
            $empRecords = $this->employeeService->getEmployee($this->employeeId);
            $empRecords = array($empRecords);

            $arrPin =array();
            foreach ($empRecords as $employee) {
                array_push($arrPin,$employee->getpin());
            }
            $attendanceRecords = $this->fingerspotService->getFingerspotRecord($arrPin, $this->fromDate, $this->toDate);
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $header = array("Id", "Employee Name","Job Title","Address", "Employment Status", "Sub Unit", "", "Birth");
            $sheet->fromArray([$header], NULL, 'A1');
            $count = 2;
            foreach($attendanceRecords as $attendance)
            {
                $sheet->setCellValue("A".$count,$attendance->getdate_ymd());
                $sheet->setCellValue("B".$count,$attendance->getEmployee()->getFullName());
                $sheet->setCellValue("C".$count,$attendance->getjobTitleName());
                $sheet->setCellValue("D".$count,$attendance->getsubUnit());
                $sheet->setCellValue("E".$count,$attendance->getscan_date());
                $count++;
            }
            $writer = new Xlsx($spreadsheet);
            $writer->save('files/data.xlsx');
            $this->records = "sukses";
            $this->getUser()->setFlash('fingerspotDevices.success',"Sukses Export Data");
        }catch (Exception $ex) {
            $this->records = "error";
            $this->getUser()->setFlash('fingerspotDevices.warning',"Error ".$ex->getMessage());
        }  
    }
}
?>
