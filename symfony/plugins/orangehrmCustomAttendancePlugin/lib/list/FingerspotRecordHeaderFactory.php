<?php

class FingerspotRecordHeaderFactory extends ohrmListConfigurationFactory {

    protected function init() {

        $header1 = new ListHeader();
        $header2 = new ListHeader();
        $header3 = new ListHeader();

		$header1->populateFromArray(array(
            'name' => 'Tanggal',
            'width' => '20%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getdate_ymd'),
        ));

        $header2->populateFromArray(array(
            'name' => 'Employee Name',
            'width' => '30%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => array('getEmployee', 'getFirstAndLastNames')),
        ));
        
        $header3->populateFromArray(array(
            'name' => 'Scan log',
            'width' => '50%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getscan_date'),
        ));
        
        $this->headers = array($header1, $header2, $header3);
    }

    public function getClassName() {
        return 'FingerspotRecordList';
    }

}



class FingerspotDevicesHeaderFactory extends ohrmListConfigurationFactory {

    protected function init() {

        $header1 = new ListHeader();
        $header2 = new ListHeader();
        $header3 = new ListHeader();
        $header4 = new ListHeader();

		$header1->populateFromArray(array(
            'name' => 'No',
            'width' => '10%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getNo'),
        ));

        $header2->populateFromArray(array(
            'name' => 'Server IP',
            'width' => '30%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getserver_IP'),
        ));
        
        $header3->populateFromArray(array(
            'name' => 'Server Port',
            'width' => '30%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getserver_port'),
        ));
        
        $header4->populateFromArray(array(
            'name' => 'Serial Number',
            'width' => '30%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getdevices_sn'),
        ));
        
        $this->headers = array($header1, $header2, $header3);
    }

    public function getClassName() {
        return 'FingerspotDevicesList';
    }

}
?>