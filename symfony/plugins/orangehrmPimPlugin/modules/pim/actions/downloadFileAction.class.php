<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewMyAttendanceRecordAction
 *
 * @author orangehrm
 */
class downloadFileAction extends basePimAction {
	  
    public function execute($request) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename('files/data.docx').'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('files/data.docx'));
        readfile('files/data.docx');
    }
}
?>
