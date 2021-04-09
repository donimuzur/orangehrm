<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of resourceAction
 *
 * @author MUHAMMAD ZULFI RUSDANI
 */
class resourceAction extends baseOAuthAction {
    public function execute($request) {
        $server = $this->getOAuthServer();
        $oauthRequest = $this->getOAuthRequest();
        $oauthResponse = $this->getOAuthResponse();
        
        if (!$server->verifyResourceRequest($oauthRequest, $oauthResponse)) {
            $server->getResponse()->send();
            exit;
        }
        $api_response = array(
            'friends' => array(
                'john',
                'matt',
                'jane'
            )
        );
        echo json_encode($api_response);
        return sfView::NONE;
    }
}

?>
