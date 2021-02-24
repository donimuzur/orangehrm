<?php 
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomRecruitmentCandidateService
 *
 * @author Muhamamd Zulfi Rusdani
 */
class CustomRecruitmentCandidateService {
    private $CustomRecruitmentCandidateDao;

    /**
     * Get Attendance Data Access Object
     * @return AttendanceDao
     * @author Muhamamd Zulfi Rusdani
     */
    public function getCustomRecruitmentCandidateDaoDao() {

        if (is_null($this->CustomRecruitmentCandidateDao)) {

            $this->CustomRecruitmentCandidateDao = new CustomRecruitmentCandidateDao();
        }

        return $this->CustomRecruitmentCandidateDao;
    }

    /**
     * Set Attendance Data Access Object
     * @param AttendanceDao $AttendanceDao
     * @return void
     */
    public function setCustomRecruitmentCandidateDaoDao(CustomRecruitmentCandidateDao $CustomRecruitmentCandidateDao) {

        $this->CustomRecruitmentCandidateDao = $CustomRecruitmentCandidateDao;
    }

    public function getCandidateAttachmentListWithLimit($limit, $offset){
        return $this->getCustomRecruitmentCandidateDaoDao()->getCandidateAttachmentListWithLimit($limit, $offset);
    }

    public function getCandidateAttachmentListCount(){
        return $this->getCustomRecruitmentCandidateDaoDao()->getCandidateAttachmentListCount();
    }

    public function getCandidateAttachment($id){
        return $this->getCustomRecruitmentCandidateDaoDao()->getCandidateAttachment($id);
    }
}
?>