<?php
/**
 * Form class for employee contact detail
 */
class EmployeeContractNewForm extends BaseForm {
    public $fullName;
    private $employeeService;
    
    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    /**
     * Set EmployeeService
     * @param EmployeeService $employeeService
     */
    public function setEmployeeService(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }
    
    private function getEmployeeEventService()
    {

        if (is_null($this->employeeEventService)) {
            $this->employeeEventService = new EmployeeEventService();
        }

        return $this->employeeEventService;
    }

    /**
     * @param $employeeEventService
     */
    public function setEmployeeEventService($employeeEventService)
    {
        $this->employeeEventService = $employeeEventService;
    }
    public function configure() {
        $this->empContractNewPermissions = $this->getOption('empContractNewPermissions');

        $empNumber = $this->getOption('empNumber');
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $this->fullName = $employee->getFullName();
        
        // Note: Widget names were kept from old non-symfony version
        $widgets = array('emp_number' => new sfWidgetFormInputHidden(array(), array('value' => $empNumber)));
        $validators = array('emp_number' => new sfValidatorString(array('required' => true)));
        
        if ($this->empContractNewPermissions->canRead()) {
            $employeeContractNewWidgets = $this->getEmployeeContractNewWidgets();
            $employeeContractNewValidators = $this->getEmployeeContractNewValidators();

            if (!($this->empContractNewPermissions->canUpdate() || $this->empContractNewPermissions->canCreate())) {
                foreach ($employeeContractNewWidgets as $widgetName => $widget) {
                    $widget->setAttribute('disabled', 'disabled');
                }
            }
            $widgets = array_merge($widgets, $employeeContractNewWidgets);
            $validators = array_merge($validators, $employeeContractNewValidators);
        }

        $this->setWidgets($widgets);
        $this->setValidators($validators);


        // // set up your post validator method
        // $this->validatorSchema->setPostValidator(
        //   new sfValidatorCallback(array(
        //     'callback' => array($this, 'postValidate')
        //   ))
        // );

        $this->widgetSchema->setNameFormat('empContractNew[%s]');
    }

    // public function postValidate($validator, $values) {

    //     $homePhone = $values['homePhone'];
    //     $mobile = $values['mobilePhone'];
    //     $workPhone = $values['workPhone'];


    //     if (empty($homePhone) && empty($mobile) && empty($workPhone)) {

    //         $message = sfContext::getInstance()->getI18N()->__('Specify at least one phone number.');
    //         $error = new sfValidatorError($validator, $message);
    //         throw new sfValidatorErrorSchema($validator, array('' => $error));

    //     }
        
    //     return $values;
    // }
    
    
    /*
     * Tis fuction will return the widgets of the form
     */
    public function getEmployeeContractNewWidgets(){
        $widgets = array();
        
        //creating widgets
        $widgets['emp_contract_number'] = new sfWidgetFormInputHidden();
        $widgets['emp_contract_start_date'] = new ohrmWidgetDatePicker(array(), array('id' => 'empContractNew_emp_contract_start_date'));
        $widgets['emp_contract_end_date'] = new  ohrmWidgetDatePicker(array(), array('id' => 'empContractNew_emp_contract_end_date'));
        $widgets['keterangan'] = new sfWidgetFormTextarea();        
        return $widgets;
    }
    
    
    /*
     * Tis fuction will return the form validators
     */
    
    public function getEmployeeContractNewValidators(){
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $validators = array(
            'emp_contract_number' => new sfValidatorNumber(array('required' => false, 'min' => 1)),
            'emp_contract_start_date' =>new ohrmDateValidator(array('date_format'=>$inputDatePattern, 'required'=>true),
                                        array('invalid'=>'Date format should be '. $inputDatePattern)),
            'emp_contract_end_date' => new ohrmDateValidator(array('date_format'=>$inputDatePattern, 'required'=>true),
                                        array('invalid'=>'Date format should be '. $inputDatePattern)),
            'keterangan' => new sfValidatorString(array('required' => false))
        );
        
        return $validators;
    }


    /**
     * Save employee contract New
     */
    
    public function save() {

        $empNumber = $this->getValue('emp_number');
        $empContractNumber = $this->getValue('emp_contract_number');

        $employeeContract = false;

        if (empty($empContractNumber)) {

            $q = Doctrine_Query::create()
                    ->select('MAX(ecn.emp_contract_number)')
                    ->from('EmpContractNew ecn')
                    ->where('ecn.emp_number = ?', $empNumber);
            $result = $q->execute(array(), Doctrine::HYDRATE_ARRAY);

            if (count($result) != 1) {
                throw new PIMServiceException('MAX(emp_contract_number) failed.');
            }
            $empContractNumber = is_null($result[0]['MAX']) ? 1 : $result[0]['MAX'] + 1;

        } else {
            $employeeContract = Doctrine::getTable('EmpContractNew')->find(array('emp_number' => $empNumber,
            'emp_contract_number' => $empContractNumber));

            if ($employeeContract == false) {
                throw new PIMServiceException('Invalid emergency contact');
            }
        }

        if ($employeeContract === false) {
            $employeeContract = new EmpContractNew();
            $employeeContract->emp_number = $empNumber;
            $employeeContract->emp_contract_number = $empContractNumber;
        }

        $employeeContract->emp_contract_start_date = $this->getValue('emp_contract_start_date');
        $employeeContract->emp_contract_end_date = $this->getValue('emp_contract_end_date');
        $employeeContract->keterangan = $this->getValue('keterangan');

        $employeeContract->save();
        $this->getEmployeeEventService()->saveEvent($empNumber,PluginEmployeeEvent::EVENT_TYPE_EMP_CONTRACT,PluginEmployeeEvent::EVENT_UPDATE,'Employee Contract Changed',$this->getEmployeeEventService()->getUserRole());

    }

}

