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
    
    public function getCandidateAttachmentListWithLimit($toDate, $limit, $offset) {
        try {

            $query = Doctrine_Query::create()
                    ->from("CustomRecruitmentCandidateAttachment")
                    ->limit($limit)
                    ->offset($offset)
                    ->addOrderBy("vacancy_position");
                    
            $records = $query->execute();
            if (is_null($records[0]->getId())) {
                return null;
            } else {

                return $records;
            }
        } catch (Exception $ex) {
            throw new DaoException($ex->getMessage());
        }
    }
}
?>