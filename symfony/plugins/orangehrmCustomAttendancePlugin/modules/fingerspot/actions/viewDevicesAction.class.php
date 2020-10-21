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
class viewDevicesAction extends ohrmBaseAction {
	  
    private $fingerspotDevicesService;
    
	public function getFingerspotDevicesService() {

        if (is_null($this->fingerspotDevicesService)) {

            $this->fingerspotDevicesService = new FingerspotDevicesService();
        }

        return $this->fingerspotDevicesService;
    }

    public function setFingerspotService(FingerspotDevicesService $fingerspotDevicesService) {

        $this->fingerspotDevicesService = $fingerspotDevicesService;
    }
	
    public function execute($request) {
        $this->fingerspotDevicesService = $this->getFingerspotDevicesService();

        $devices =  $this->fingerspotDevicesService->getFingerspotDevices();

        $this->serverIp = $this->request->getParameter('serverIp');
        $this->serverPort = $this->request->getParameter('serverPort');
        $this->devicesSn = $this->request->getParameter('devicesSn');
        
        if( $devices != null)
        {
            $this->serverIp = $devices[0]->getserver_IP();
            $this->serverPort = $devices[0]->getserver_port();
            $this->devicesSn = $devices[0]->getdevice_sn();
        }

        $this->trigger = $request->getParameter('trigger');
        $this->actionRecorder="ViewFingerspotDevices";
        $values = array('serverIp' => $this->serverIp, 'serverPort' => $this->serverPort , 'devicesSn' => $this->devicesSn, 'trigger' => $this->trigger);
        $this->form = new FingerspotDevicesForm(array(), $values);

        if (!($this->trigger)) {
            if ($request->isMethod('post')) {
                $this->form->bind($request->getParameter('fingerspotDevices'));
                if ($this->form->isValid()) {
                    $saveDevices =  $devices[0];
                    $post = $this->form->getValues();
                    $saveDevices->No = 1;
                    $saveDevices->server_IP = $post['serverIp'];
                    $saveDevices->server_port = $post['serverPort'];
                    $saveDevices->device_sn= $post['devicesSn'];
                    
                    $this->fingerspotDevicesService->saveDevices($saveDevices);
                    $this->getUser()->setFlash('fingerspotDevices.success',"Save Success");
                    $this->redirect('fingerspot/viewDevices');
                } else {
                    $this->handleBadRequest();
                    $this->getUser()->setFlash('warning', __(TopLevelMessages::VALIDATION_FAILED), false);
                }
            }
        }
    }
}

?>
