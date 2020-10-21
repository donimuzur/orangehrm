<?php

class viewFingerspotModuleAction extends sfAction {
    
    protected $homePageService;
    
    public function getHomePageService() {
        
        if (!$this->homePageService instanceof HomePageService) {
            $this->homePageService = new HomePageService($this->getUser());
        }
        
        return $this->homePageService;
        
    }

    public function setHomePageService($homePageService) {
        $this->homePageService = $homePageService;
    }    

    public function execute($request) {

        $this->redirect($this->getHomePageService()->getFingerspotModuleDefaultPath());
        
    }

}
?>