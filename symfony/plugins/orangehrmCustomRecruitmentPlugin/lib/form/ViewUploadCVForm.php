<?php

class ViewUploadCVForm extends BaseForm {

    public function configure() {

        $vacancyPosition = $this->getOption('vacancyPosition');
        $uploadDate = $this->getOption('uploadDate');
        $ufile = $this->getOption('ufile');

        $this->setWidgets(array(
            'vacancyPosition' => new sfWidgetFormInputText(array(), array("class" => "formInputText", "maxlength" => 50)),
            'uploadDate' => new ohrmWidgetDatePicker(array(), array('id' => 'uploadDate'), array('class' => 'date')),
		    'ufile' => new sfWidgetFormInputFileMulti(array('needs_multipart'=>true),array()),
		    
        ));
        
        $this->widgetSchema->setNameFormat('viewUploadCV[%s]');
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        $this->setValidators(array(
            'vacancyPosition' => new sfValidatorString(array('required' => true, 'max_length' => 50, 'trim' => true)),
            'uploadDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'ufile' => new sfValidatorFileMulti(array('required' => true,
            'max_size' => 5242880), array('max_size' => __('Attachment Size Exceeded.'))),
                    
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
            'vacancyPosition' => __('Vacancy Position'). $requiredMarker,
            'uploadDate' => __('Upload Date') . $requiredMarker,
            'ufile' => __('ufile'). $requiredMarker,

        );

        return $labels;
    }

    public function save() {
		$vacancy_position = $this->getValue('vacancyPosition');
		$uploadDate = $this->getValue('uploadDate');
		$file = $this->getValue('ufile');
        
        $empAttachment = false;
        $newFile = false;

        foreach($file as $file)
        {
            if (($file instanceof sfValidatedFile) && $file->getOriginalName() != "") {
                $empAttachment = new CustomRecruitmentCandidateAttachment();
                $empAttachment->vacancy_position = $vacancy_position;
                $empAttachment->uploadDate = $uploadDate;
                $tempName = $file->getTempName();
                $empAttachment->fileContent = file_get_contents($tempName);
                $empAttachment->fileName = $file->getOriginalName();
                $empAttachment->fileType = $file->getType();
                $empAttachment->fileSize = $file->getSize();
                $empAttachment->save();
            }
        }
	}
}
?>