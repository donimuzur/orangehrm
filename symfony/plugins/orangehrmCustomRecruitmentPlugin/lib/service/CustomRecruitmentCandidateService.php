<?php 
class CustomRecruitmentCandidateService {
    private $CustomRecruitmentCandidateDao;

    /**
     * Get Attendance Data Access Object
     * @return AttendanceDao
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

    public function getCandidateAttachmentListWithLimit($toDate, $limit, $offset){
        return $this->getCustomRecruitmentCandidateDaoDao()->getCandidateAttachmentListWithLimit($toDate, $limit, $offset);
    }
}
?>