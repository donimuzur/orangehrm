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
    private $employeeService;
    private $jobTitleService;
    
	public function getEmployeeService() {

        if (is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
        }

        return $this->employeeService;
    }

    public function setEmployeeService(EmployeeService $employeeService) {

        $this->employeeService = $employeeService;
    }
	
    public function getJobTitleService() {

        if (is_null($this->jobTitleService)) {
            $this->jobTitleService = new JobTitleService();
        }

        return $this->jobTitleService;
    }

    public function setJobTitleService(JobTitleService $jobTitleService) {

        $this->jobTitleService = $jobTitleService;
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
            
            \PhpOffice\PhpWord\Settings::setPdfRendererPath(ROOT_PATH . '/symfony/lib/vendor/tecnickcom/tcpdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_TCPDF);

            $phpWord = \PhpOffice\PhpWord\IOFactory::load('files/data.docx'); 
            $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
            $xmlWriter->save('files/data.pdf');  
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
            if ($this->getUser()->hasFlash('templateMessage')) {
                list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
            }
            
            $empNumber = $request->getParameter('empNumber');
            $isPaging = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);
    
            $pageNumber = $isPaging;
            if (!empty($empNumber) && $this->getUser()->hasAttribute('pageNumber')) {
                $pageNumber = $this->getUser()->getAttribute('pageNumber');
            }

            $noOfRecords = sfConfig::get('app_items_per_page');

            $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;
    
             // Reset filters if requested to
            if ($request->hasParameter('reset')) {
                $this->setFilters(array());
                $this->setSortParameter(array("field"=> NULL, "order"=> NULL));
                $this->setPage(1);
            }
    
            $this->form = new EmployeeSearchForm($this->getFilters());
            if ($request->isMethod('post')) {
    
                $this->form->bind($request->getParameter($this->form->getName()));
    
                if ($this->form->isValid()) {
                    
                    if($this->form->getValue('isSubmitted')=='yes'){
                        $this->setSortParameter(array("field"=> NULL, "order"=> NULL));
                    }         
                    
                    $this->setFilters($this->form->getValues());
                    
                } else {
                    $this->setFilters(array());
                    $this->handleBadRequest();
                    $this->getUser()->setFlash('search.warning', __(TopLevelMessages::VALIDATION_FAILED), false);
                }
    
                $this->setPage(1);
            }
            
            if ($request->isMethod('get')) {
                $sortParam = array("field"=>$request->getParameter('sortField'), 
                                   "order"=>$request->getParameter('sortOrder'));
                $this->setSortParameter($sortParam);
                $this->setPage(1);
            }
            
            $sort = $this->getSortParameter();
            $sortField = $sort["field"];
            $sortOrder = $sort["order"];
            $filters = $this->getFilters();
            
            if( isset(  $filters['employee_name'])){
                $filters['employee_name'] = str_replace(' (' . __('Past Employee') . ')', '', $filters['employee_name']['empName']);
            }
            
            if (isset($filters['supervisor_name'])) {
                $filters['supervisor_name'] = str_replace(' (' . __('Past Employee') . ')', '', $filters['supervisor_name']);
            }
            
            $this->filterApply = !empty($filters);
    
            $accessibleEmployees = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('Employee');
            $countEmp;
            if (count($accessibleEmployees) > 0) {
                $filters['employee_id_list'] = $accessibleEmployees;
                $count = $this->getEmployeeService()->getSearchEmployeeCount( $filters );
                $countEmp = $count;
                $parameterHolder = new EmployeeSearchParameterHolder();
                $parameterHolder->setOrderField($sortField);
                $parameterHolder->setOrderBy($sortOrder);
                $parameterHolder->setLimit($count);
                $parameterHolder->setOffset(0);
                $parameterHolder->setFilters($filters);
    
                $list = $this->getEmployeeService()->searchEmployees($parameterHolder);
                
            } else {
                $count = 0;
                $list = array();
            }
    

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $header = array(__("No"),__("NIK"), __("Employee Name"), __("Job Title"), __("Address"), __("Employment Status"), __("Sub Unit"), __("Place of Birth"), __("Date of Birth"),
                __("Gender"), __("Marrital Status"), __("Wife/Husband"), __("Child 1"), __("Child 2"), __("Child 3"), __("Child 4"), __("Child 5") );
            $sheet->fromArray([$header], NULL, 'A1');
            $count = 2;
            $idx = 1;
            foreach($list as $list)
            {
                $Employee = $this->getEmployeeService()->getEmployee($list->getEmpNumber());
                $jobTitle = $this->getJobTitleService()->getJobTitleById($list->getJobTitle());
                $sheet->setCellValue("A".$count,$idx);
                $sheet->setCellValue("B".$count,$list->getEmployeeId());
                $sheet->setCellValue("C".$count,$list->getFullName());
                $sheet->setCellValue("D".$count,$list->getJobTitle()->getJobTitleName());
                $sheet->setCellValue("E".$count,$list->getStreet1());
                $sheet->setCellValue("F".$count,$list->getEmpStatus());
                $sheet->setCellValue("G".$count,$list->getSubDivision());
                $sheet->setCellValue("H".$count,$Employee->getEmpPlaceofbirth());
                $sheet->setCellValue("I".$count,$Employee->getEmpBirthday());
                $sheet->getStyle('I'.$count)
                        ->getNumberFormat()
                        ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                $sheet->setCellValue("J".$count,"");
                
                if($Employee->getEmpGender()==1)
                {
                    $sheet->setCellValue("J".$count,__("Male"));
                }
                else if($Employee->getEmpGender()==2)
                {
                    $sheet->setCellValue("J".$count,__("Female"));
                }
                
                $sheet->setCellValue("K".$count,$Employee->getEmpMaritalStatus());
                $count++;
                $idx++;
            }

            $writer = new Xlsx($spreadsheet);
            $writer->save('files/data.xlsx');

            $this->records = "sukses";
            $this->getUser()->setFlash('search.success',"Sukses Export Data".$countEmp );

        }
        catch (Exception $ex) {
            $this->records = "error";
            $this->getUser()->setFlash('search.warning',"Error ".$ex->getMessage());

        }  
    }

    
    /**
     * Set's the current page number in the user session.
     * @param $page int Page Number
     * @return None
     */
    protected function setPage($page) {
        $this->getUser()->setAttribute('emplist.page', $page, 'pim_module');
    }

    /**
     * Get the current page number from the user session.
     * @return int Page number
     */
    protected function getPage() {
        return $this->getUser()->getAttribute('emplist.page', 1, 'pim_module');
    }
    
    /**
     * Sets the current sort field and order in the user session.
     * @param type Array $sort 
     */
    protected function setSortParameter($sort) {
        $this->getUser()->setAttribute('emplist.sort', $sort, 'pim_module');
    }

    /**
     * Get the current sort feild&order from the user session.
     * @return array ('field' , 'order')
     */
    protected function getSortParameter() {
        return $this->getUser()->getAttribute('emplist.sort', null, 'pim_module');
    }
    
    /**
     *
     * @param array $filters
     * @return unknown_type
     */
    protected function setFilters(array $filters) {
        return $this->getUser()->setAttribute('emplist.filters', $filters, 'pim_module');
    }

    /**
     *
     * @return unknown_type
     */
    protected function getFilters() {
        return $this->getUser()->getAttribute('emplist.filters', null, 'pim_module');
    }

    protected function _getFilterValue($filters, $parameter, $default = null) {
        $value = $default;
        if (isset($filters[$parameter])) {
            $value = $filters[$parameter];
        }

        return $value;
    }
}
?>
