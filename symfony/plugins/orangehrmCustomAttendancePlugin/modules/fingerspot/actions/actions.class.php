<?php

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
class FingerspotActions extends sfActions {
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
	
	public function getFingerspotService() {

        if (is_null($this->fingerspotService)) {

            $this->fingerspotService = new FingerspotService();
        }

        return $this->fingerspotService;
    }

    public function setFingerspotService(FingerspotService $fingerspotService) {

        $this->fingerspotService = $fingerspotService;
    }
    
    
	public function getfingerspotRecordTempService() {

        if (is_null($this->fingerspotRecordTempService)) {

            $this->fingerspotRecordTempService = new FingerspotRecordTempService();
        }

        return $this->fingerspotRecordTempService;
    }

    public function setfingerspotRecordTempService(FingerspotRecordTempService $fingerspotRecordTempService) {

        $this->fingerspotRecordTempService = $fingerspotRecordTempService;
    }
	
	public function getFingerspotDevicesService() {

        if (is_null($this->fingerspotDevicesService)) {

            $this->fingerspotDevicesService = new FingerspotDevicesService();
        }

        return $this->fingerspotDevicesService;
    }

    public function setFingerspotDevicesService(FingerspotDevicesService $fingerspotDevicesService) {

        $this->fingerspotDevicesService = $fingerspotDevicesService;
    }
	
    public function executeGetDeviceInfo($request) {
     
        $userRoleManager = $this->getContext()->getUserRoleManager();        
        $userEmployeeNumber = $userRoleManager->getUser()->getEmpNumber();

        $this->ServerIp = $request->getParameter('ServerIp');
        $this->ServerPort = $request->getParameter('ServerPort');
        $this->SerialNumber = $request->getParameter('SerialNumber');
        $this->actionRecorder = $request->getParameter('actionRecorder');
        
        $this->listForm = new DefaultListForm();

        //Get Device Info
        $curl = curl_init();
        set_time_limit(0);
        curl_setopt_array($curl, array(
            CURLOPT_PORT => $this->ServerPort,
            CURLOPT_URL => "http://".$this->ServerIp."/dev/info",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "sn=".$this->SerialNumber,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
                ),
            )
        );
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $response = ($err);
            $this->Errors = $response ;
            $this->records = null;
        }
        else
        {
            $json=json_decode($response,true);
            if($json["Result"])
            {
                $this->records =  $response;
                $this->Jam =$json["DEVINFO"]["Jam"];
                $this->Admin =$json["DEVINFO"]["Admin"];
                $this->User =$json["DEVINFO"]["User"];
                $this->Fp =$json["DEVINFO"]["FP"];
                $this->Face =$json["DEVINFO"]["Face"];
                $this->Vein =$json["DEVINFO"]["Vein"];
                $this->Card =$json["DEVINFO"]["CARD"];
                $this->Pwd =$json["DEVINFO"]["PWD"];
                $this->AllOperasional =$json["DEVINFO"]["All Operasional"];
                $this->AllPresensi =$json["DEVINFO"]["All Presensi"];
                $this->NewOperasional =$json["DEVINFO"]["New Operasional"];
                $this->NewPresensi =$json["DEVINFO"]["New Presensi"];
            }
            else{
                $this->Errors = "Return is False" ;
                $this->records = null;
            }
        }
    }

    
    public function executeSyncTime($request) { 
        try {

            $userRoleManager = $this->getContext()->getUserRoleManager();        
            $userEmployeeNumber = $userRoleManager->getUser()->getEmpNumber();

            $this->ServerIp = $request->getParameter('ServerIp');
            $this->ServerPort = $request->getParameter('ServerPort');
            $this->SerialNumber = $request->getParameter('SerialNumber');
            $this->actionRecorder = $request->getParameter('actionRecorder');
            
            $this->listForm = new DefaultListForm();

            //Get Device Info
            $curl = curl_init();
            set_time_limit(0);
            curl_setopt_array($curl, array(
                CURLOPT_PORT => $this->ServerPort,
                CURLOPT_URL => "http://".$this->ServerIp."/dev/settime",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "sn=".$this->SerialNumber,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                    ),
                )
            );
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $response = ($err);
                $this->Errors = $response ;
                $this->records = null;
                $this->getUser()->setFlash('fingerspotDevices.warning',$response);
            }
            else
            {
                $this->getUser()->setFlash('fingerspotDevices.success',"Sync Date Time Success");
            }
        } catch (Exception $ex) {
            $response = ($ex);
            $this->Errors = $response ;
            $this->records = null;
            $this->getUser()->setFlash('fingerspotDevices.warning',$response);
        }
    }

    
    public function executeGetAllScanlog($request) { 
        $this->fingerspotRecordTempService = $this->getfingerspotRecordTempService();

        $userRoleManager = $this->getContext()->getUserRoleManager();        
        $userEmployeeNumber = $userRoleManager->getUser()->getEmpNumber();

        $this->ServerIp = $request->getParameter('ServerIp');
        $this->ServerPort = $request->getParameter('ServerPort');
        $this->SerialNumber = $request->getParameter('SerialNumber');
        $this->actionRecorder = $request->getParameter('actionRecorder');
        
        $session=true;
        $delSession=true;
        $pagingLimit= 100;
       
        while ($session)
        { 
            $curl = curl_init();
            set_time_limit(0);
            curl_setopt_array($curl, array(
                CURLOPT_PORT => $this->ServerPort,
                CURLOPT_URL => "http://".$this->ServerIp."/scanlog/all/paging",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "sn=".$this->SerialNumber."&limit=".$pagingLimit,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/x-www-form-urlencoded"
                    ),
                )
            );
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $response = ($err);
                $this->Errors = $response ;
                $this->records = null;
                $this->getUser()->setFlash('fingerspotDevices.warning',$response);
            }
            else
            {
                $content = json_decode($response);
                if(!($content->Result))
                {
                    $this->Errors = "Return is false";
                    $this->records = null;
                    $this->getUser()->setFlash('fingerspotDevices.warning',"Download scanlo gagal");
                }
                elseif(($content->Data)>0){	
                    if($delSession){
                        $deleteRecord = $this->fingerspotRecordTempService->deleteFingerspotRecord();
                    
                        if($deleteRecord){}else{
                            $this->Errors = "Error Delete scanlog data" ;
                            $this->records = null;
                            $this->getUser()->setFlash('fingerspotDevices.warning',"download all scanlog data");
                        }
                        $delSession=false;
                    }  
                    
                  
                    foreach($content->Data as $entry){
                        $FingerspotTempRecord = new FingerspotRecordTemp();
                        $FingerspotTempRecord->setsn($entry->SN);
                        $FingerspotTempRecord->setscan_date($entry->ScanDate);
                        $FingerspotTempRecord->setpin($entry->PIN);
                        $FingerspotTempRecord->setverifymode($entry->VerifyMode);
                        $FingerspotTempRecord->setiomode($entry->IOMode);
                        $FingerspotTempRecord->setworkcode($entry->WorkCode);
                        $Jsn = $entry->SN;
                        $Jsd = $entry->ScanDate;
                        $Jpin = $entry->PIN;
                        $Jvm = $entry->VerifyMode;
                        $Jim = $entry->IOMode;
                        $Jwc = $entry->WorkCode;
                        try {
                            $queryinsert = $this->fingerspotRecordTempService->saveFingerspotRecord($FingerspotTempRecord);
                        }catch (Exception $ex) {}                        
                        /*if($queryinsert){
                        }else{
                            echo '<script>alert ("Failed !")</script>';
                        }*/
                    }
                    $session=$content->IsSession;
                    $this->Errors ="";
                    $this->records =$this->fingerspotRecordTempService->getFingerspotRecordCount();
                    $this->getUser()->setFlash('fingerspotDevices.success',"Get All Scanlog Success");
                }  
               
            }
          
        }
    }

    public function executeGetNewScanlog($request) { 
        $this->fingerspotRecordTempService = $this->getfingerspotRecordTempService();

        $userRoleManager = $this->getContext()->getUserRoleManager();        
        $userEmployeeNumber = $userRoleManager->getUser()->getEmpNumber();

        $this->ServerIp = $request->getParameter('ServerIp');
        $this->ServerPort = $request->getParameter('ServerPort');
        $this->SerialNumber = $request->getParameter('SerialNumber');
        $this->actionRecorder = $request->getParameter('actionRecorder');
        
        $delSession=true;
        $curl = curl_init();
        set_time_limit(0);
        curl_setopt_array($curl, array(
            CURLOPT_PORT => $this->ServerPort,
            CURLOPT_URL => "http://".$this->ServerIp."/scanlog/new",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "sn=".$this->SerialNumber,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded"
                ),
            )
        );
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $response = ($err);
            $this->Errors = $response ;
            $this->records = null;
            $this->getUser()->setFlash('fingerspotDevices.warning',$response);
        }
        else
        {
            $content = json_decode($response);
            if(!($content->Result))
            {
                $this->Errors = "Return is false";
                $this->records = null;
                $this->getUser()->setFlash('fingerspotDevices.warning',"Download scanlo gagal");
            }
            elseif(($content->Data)>0){	
                if($delSession){
                    $deleteRecord = $this->fingerspotRecordTempService->deleteFingerspotRecord();
                
                    if($deleteRecord){}else{
                        $this->Errors = "Error Delete data scanlog temp" ;
                        $this->records = null;
                        $this->getUser()->setFlash('fingerspotDevices.warning',"gagal download all scanlog data");
                    }
                    $delSession=false;
                }  
                
                
                foreach($content->Data as $entry){
                    $FingerspotTempRecord = new FingerspotRecordTemp();
                    $FingerspotTempRecord->setsn($entry->SN);
                    $FingerspotTempRecord->setscan_date($entry->ScanDate);
                    $FingerspotTempRecord->setpin($entry->PIN);
                    $FingerspotTempRecord->setverifymode($entry->VerifyMode);
                    $FingerspotTempRecord->setiomode($entry->IOMode);
                    $FingerspotTempRecord->setworkcode($entry->WorkCode);
                    try {
                        $queryinsert = $this->fingerspotRecordTempService->saveFingerspotRecord($FingerspotTempRecord);
                    }catch (Exception $ex) {}    
                }
                $session=$content->IsSession;
                $this->Errors ="";
                $this->records =$this->fingerspotRecordTempService->getFingerspotRecordCount();
                $this->getUser()->setFlash('fingerspotDevices.success',"Get All Scanlog Success");
            }  
            
        }
          
    }

    public function executeSaveScanlog($request) { 
        $this->fingerspotRecordTempService = $this->getfingerspotRecordTempService();
        $this->fingerspotService = $this->getFingerspotService();

        $userRoleManager = $this->getContext()->getUserRoleManager();        
        $userEmployeeNumber = $userRoleManager->getUser()->getEmpNumber();

        $delSession=true;
        
        $getAllFingerspotRecordTemp = $this->fingerspotRecordTempService->getFingerspotRecord();
        foreach ($getAllFingerspotRecordTemp as $attendance) {
            $fingerspotAttendance = new FingerspotRecord();
            $fingerspotAttendance->setsn($attendance->getsn());
            $fingerspotAttendance->setscan_date($attendance->getscan_date());
            $fingerspotAttendance->setpin($attendance->getpin());
            $fingerspotAttendance->setverifymode($attendance->getverifymode());
            $fingerspotAttendance->setinoutmode($attendance->getiomode());
            $fingerspotAttendance->setwork_code($attendance->getworkcode());
            $fingerspotAttendance->setatt_id(0);
            $fingerspotAttendance->setreserved(0);
            try {
                $queryinsert = $this->fingerspotService->saveFingerspotRecord($fingerspotAttendance);
            }catch (Exception $ex) {}  
        }

        if($delSession){
            $deleteRecord = $this->fingerspotRecordTempService->deleteFingerspotRecord();
        
            if($deleteRecord){}else{
                $this->Errors = "Error Delete data scanlog Temp " ;
                $this->records = null;
                $this->getUser()->setFlash('fingerspotDevices.warning',"gagal download all scanlog data");
            }
            $delSession=false;
        }  

        $this->Errors ="";
        $this->records =count($getAllFingerspotRecordTemp);
        $this->getUser()->setFlash('fingerspotDevices.success',$this->records." data berhasil tersimpan");
          
    }

    public function executeExportMyAttendanceToExcel($request) { 
        try {
            $this->fingerspotRecordTempService = $this->getfingerspotRecordTempService();
            $this->fingerspotService = $this->getFingerspotService();
            $this->employeeService = $this->getEmployeeService();

            $this->fromDate = $request->getParameter('fromDate');
            $this->toDate = $request->getParameter('toDate');

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

            $header = array("Tanggal", "Employee Name","Job Title", "Sub Unit", "Scan Date");
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
            $this->getUser()->setFlash('fingerspotDevices.success',"Sukses Export Data");
        }catch (Exception $ex) {
            $this->getUser()->setFlash('fingerspotDevices.warning',"Error ".$ex->getMessage());
        }  
    }
    public function executeExportAttendanceToExcel($request) { 
        try {
            $this->fingerspotRecordTempService = $this->getfingerspotRecordTempService();
            $this->fingerspotService = $this->getFingerspotService();
            $this->employeeService = $this->getEmployeeService();
            
            $this->fromDate = $request->getParameter('fromDate');
            $this->toDate = $request->getParameter('toDate');
            $this->employeeId = $request->getParameter('employeeId');

            if (!$this->employeeId) {
                $empRecords = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntities('Employee');
            } else {
                $empRecords = $this->employeeService->getEmployee($this->employeeId);
                $empRecords = array($empRecords);
            }
            
            $arrPin =array();
            foreach ($empRecords as $employee) {
                array_push($arrPin,$employee->getpin());
            }
            $attendanceRecords = $this->fingerspotService->getFingerspotRecord($arrPin, $this->fromDate, $this->toDate);
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $header = array("Tanggal", "Employee Name","Job Title", "Sub Unit", "Scan Date");
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
            $this->getUser()->setFlash('fingerspotDevices.success',"Sukses Export Data");
        }catch (Exception $ex) {
            $this->getUser()->setFlash('fingerspotDevices.warning',"Error ".$ex->getMessage());
        }  
    }
}
?>
