<?php 

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomRecruitmentCandidateDao
 *
 * @author Muhamamd Zulfi Rusdani
 */


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
    
    public function getCandidateAttachmentListWithLimit($vacancy, $uploadDate, $limit, $offset) {
        try {
            $query = Doctrine_Query::create()
                    ->from("CustomRecruitmentCandidateAttachment b");
            if(!is_null($vacancy))
            {
                $query->where("b.vacancy_position like ? ", "%".$vacancy."%" );
            }

            if(!is_null($uploadDate))
            {
                $query->where("b.uploadDate = ?",$uploadDate );
            }       
                    
            if(!is_null($vacancy) && !is_null($uploadDate))
            {
                $query->where("b.vacancy_position like ? ", "%".$vacancy."%" )
                        ->andwhere("b.uploadDate = ?",$uploadDate );
            }     
         
                
            $query->limit($limit)
                    ->offset($offset);
                            
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

    public function getCandidateAttachmentListCount($vacancy, $uploadDate) {
        try {
            $query = Doctrine_Query::create()
                    ->from("CustomRecruitmentCandidateAttachment b");

            if(!is_null($vacancy))
            {
                $query->where("b.vacancy_position like ? ", "%".$vacancy."%" );
            }

            if(!is_null($uploadDate))
            {
                $query->where("b.uploadDate = ?",$uploadDate );
            }       
                 
            if(!is_null($vacancy) && !is_null($uploadDate))
            {
                $query->where("b.vacancy_position like ? ", "%".$vacancy."%" )
                        ->andwhere("b.uploadDate = ?",$uploadDate );
            }     

            $records = $query->execute();
          
            if (is_null($records[0]->getId())) {
                return null;
            } else {

                return count($records);
            }
        } catch (Exception $ex) {
            throw new DaoException($ex->getMessage());
        }
        
    }
}
?>