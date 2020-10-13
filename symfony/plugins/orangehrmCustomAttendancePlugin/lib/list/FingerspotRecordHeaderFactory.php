<?php

class FingerspotRecordHeaderFactory extends ohrmListConfigurationFactory {

    protected function init() {

		$header0 = new ListHeader();
        $header1 = new ListHeader();
        $header2 = new ListHeader();
        $header3 = new ListHeader();
        $header4 = new ListHeader();
        $header5 = new ListHeader();
        $header6 = new ListHeader();
        $header7 = new ListHeader();

		$header1->populateFromArray(array(
            'name' => 'Tanggal',
            'width' => '20%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => array('getEmployee', 'getFirstAndLastNames')),
        ));

        $header2->populateFromArray(array(
            'name' => 'Employee Name',
            'width' => '30%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getPunchInUserTimeAndZone'),
        ));
        
        $header3->populateFromArray(array(
            'name' => 'Check In',
            'width' => '20%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getPunchInNote'),
        ));
        
        $header4->populateFromArray(array(
            'name' => 'Check Out',
            'width' => '20%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getPunchOutUserTimeAndZone'),
        ));
        
        $this->headers = array($header1, $header2, $header3, $header4 );
    }

    public function getClassName() {
        return 'FingerspotRecordList';
    }

}