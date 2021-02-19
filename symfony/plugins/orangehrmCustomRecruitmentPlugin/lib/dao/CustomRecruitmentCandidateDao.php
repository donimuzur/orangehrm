<?php 

class CustomRecruitmentCandidateDao extends BaseDao {
     /**
     *
     * @param <type> $attachId
     * @return <type>
     */
    public function getCandidateAttachment($attachId) {
        try {
            $q = Doctrine_Query:: create()
                            ->from('CustomRecruitmentCandidateAttachment a')
                            ->where('a.id = ?', $attachId);
            return $q->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }
}
?>