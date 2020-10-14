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
            'width' => '40%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getscan_date'),
        ));
        
        $this->headers = array($header1, $header2, $header3);
    }

    public function getClassName() {
        return 'FingerspotRecordList';
    }

}