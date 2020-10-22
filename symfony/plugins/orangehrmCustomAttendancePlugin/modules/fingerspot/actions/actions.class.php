<?php

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
                $this->getUser()->setFlash('fingerspotDevices.success',"server IP: ".$this->ServerIp."|Server Port".$this->ServerPort."|Serial Number".$this->SerialNumber."|".$response);
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
}

