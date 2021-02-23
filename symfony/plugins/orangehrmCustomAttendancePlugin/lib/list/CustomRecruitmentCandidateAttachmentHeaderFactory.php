<?php

class CustomRecruitmentCandidateAttachmentRecordHeaderFactory extends ohrmListConfigurationFactory {

    protected function init() {

        $header1 = new ListHeader();
        $header4 = new ListHeader();
        $header5 = new ListHeader();
        $header2 = new ListHeader();

		$header1->populateFromArray(array(
            'name' => 'Vacancy Position',
            'width' => '20%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getdate_ymd'),
        ));

        $header2->populateFromArray(array(
            'name' => 'Upload Date',
            'width' => '20%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => array('getEmployee', 'getFullName')),
        ));
        
        $header4->populateFromArray(array(
            'name' => 'File Name',
            'width' => '30%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getjobTitleName'),
        ));
        $header5->populateFromArray(array(
            'name' => 'File Type',
            'width' => '30%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getsubUnit'),
        ));
        
        
        $this->headers = array($header1, $header2, $header4,$header5);
    }

    public function getClassName() {
        return 'CustomRecruitmentCandidateAttachmentList';
    }

}
?>