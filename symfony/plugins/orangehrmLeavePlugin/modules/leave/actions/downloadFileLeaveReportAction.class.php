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
class downloadFileLeaveReportAction extends basePimAction {
	  
    public function execute($request) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename('files/printLeaveReport.pdf').'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('files/printLeaveReport.pdf'));
        readfile('files/printLeaveReport.pdf');
    }
}
?>
