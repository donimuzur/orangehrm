<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomRecruitmentCandidateAttachmentRecordHeaderFactory
 *
 * @author Muhamamd Zulfi Rusdani
 */
class CustomRecruitmentCandidateAttachmentRecordHeaderFactory extends ohrmListConfigurationFactory {

    protected function init() {

        $header1 = new ListHeader();
        $header2 = new ListHeader();
        $header3 = new ListHeader();

		$header1->populateFromArray(array(
            'name' => 'Vacancy Position',
            'width' => '40%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getVacancyPosition'),
        ));

        $header2->populateFromArray(array(
            'name' => 'Upload Date',
            'width' => '20%',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getdate_ymd'),
        ));
        
        $header3->populateFromArray(array(
            'name' => 'File Name',
            'width' => '40%',
            'elementType' => 'link',
            'elementProperty' => array(
                'labelGetter' => array('getFileName'),
                'placeholderGetters' =>array('id' => 'getId'),
                'urlPattern' => public_path('index.php/customRecruitment/viewAttachment/id/{id}'),
            ),
        ));
        
        $this->headers = array($header1, $header2, $header3);
    }

    public function getClassName() {
        return 'CustomRecruitmentCandidateAttachmentList';
    }

}
?>