<?php

class FingerspotDevicesForm extends sfForm {

    public function configure() {

        $serverIp = $this->getOption('serverIp');
        $serverPort = $this->getOption('serverPort');
        $devicesSn = $this->getOption('devicesSn');
        $trigger = $this->getOption('trigger');

        $this->setWidgets(array(
            'serverIp' => new sfWidgetFormInputText(array(), array("class" => "formInputText", "maxlength" => 50)),
            'serverPort' => new sfWidgetFormInputText(array(), array("class" => "formInputText", "maxlength" => 50)),
            'devicesSn' => new sfWidgetFormInputText(array(), array("class" => "formInputText", "maxlength" => 50)),
        ));
        
        $this->setDefault('serverIp', $serverIp);
        $this->setDefault('serverPort', $serverPort);
        $this->setDefault('devicesSn', $devicesSn);

        $this->widgetSchema->setNameFormat('fingerspotDevices[%s]');
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        $this->setValidators(array(
            'serverIp' => new sfValidatorString(array('required' => true, 'max_length' => 50, 'trim' => true)),
            'serverPort' => new sfValidatorString(array('required' => true, 'max_length' => 50, 'trim' => true)),
            'devicesSn' => new sfValidatorString(array('required' => true, 'max_length' => 50, 'trim' => true)),
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
            'serverIp' => __('Server IP'). $requiredMarker,
            'serverPort' => __('Server Port') . $requiredMarker,
            'devicesSn' => __('Devices SN') . $requiredMarker
        );

        return $labels;
    }
}
?>