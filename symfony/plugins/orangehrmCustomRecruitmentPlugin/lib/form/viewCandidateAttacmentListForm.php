<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ViewCandidateAttachmentListForm
 *
 * @author Muhamamd Zulfi Rusdani
 */

class ViewCandidateAttachmentListForm extends BaseForm {

    public function configure() {

        $vacancyPosition = $this->getOption('search_vacancyPosition');
        $uploadDate = $this->getOption('search_uploadDate');
        
        $this->setWidgets(array(
            'search_vacancyPosition' => new sfWidgetFormInputText(array(), array("class" => "formInputText", "maxlength" => 50)),
            'search_uploadDate' => new ohrmWidgetDatePicker(array(), array('id' => 'search_uploadDate'), array('class' => 'date')),
        ));
        
        $this->widgetSchema->setNameFormat('viewCandidateAttachmentList[%s]');
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        $this->setValidators(array(
            'search_vacancyPosition' => new sfValidatorString(array('required' => false, 'max_length' => 50, 'trim' => true)),
            'search_uploadDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
        ));

        $this->getWidgetSchema()->setLabels($this->getFormLabels());
    }
    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $requiredMarker = ' <em> *</em>';

        $labels = array(
            'search_vacancyPosition' => __('Vacancy Position'),
            'search_uploadDate' => __('Upload Date'),
        );

        return $labels;
    }
}
?>