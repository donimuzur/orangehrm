<?php 
class LeaveActions extends sfActions 
{
    public function executePrintLeaveReport ($request)
    {
        try {
            $this->records = "sukses";
            $this->getUser()->setFlash('jobdetails.success', __(TopLevelMessages::VALIDATION_FAILED), false);
        }
        catch (Exception $ex) {
            $response = ($ex);
            $this->getUser()->setFlash('jobdetails.warning', $response, false);
        }
    }
}
?>