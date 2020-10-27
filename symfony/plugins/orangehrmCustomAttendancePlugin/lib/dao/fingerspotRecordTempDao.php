<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
class FingerspotRecordTempDao {
    /**
     * Get Attendance Record
     * @param $$pin,$fromDate,$toDate
     * @return attendance records
     */
    public function getFingerspotRecord() {

        try {

            $query = Doctrine_Query::create()
                    ->from("FingerspotRecordTemp");
                    
            $records = $query->execute();
            if (is_null($records[0]->getpin())) {

                return null;
            } else {

                return $records;
            }
        } catch (Exception $ex) {
            throw new DaoException($ex->getMessage());
        }
    }
    public function getFingerspotRecordCount()
    {
        try {

            $query = Doctrine_Query::create()
                    ->from("FingerspotRecordTemp");
                    
            $records = $query->execute();
            
            if (is_null($records)) {
                return 0;
            } else {
                return count($records);
            }
        } catch (Exception $ex) {
            throw new DaoException($ex->getMessage());
        }
    }   
    public function getFingerspotRecordWithLimit($arrpin, $fromDate, $toDate, $limit, $offset) {

        $from = $fromDate . " " . "00:" . "00:" . "00";
        $end = $toDate . " " . "23:" . "59:" . "59";

        try {

            $query = Doctrine_Query::create()
                    ->from("FingerspotRecordTemp")
                    ->whereIn("pin", $arrpin)
                    ->andWhere("scan_date >= ?", $from)
                    ->andWhere("scan_date <= ?",$end)
                    ->limit($limit)
                    ->offset($offset);
                    
            $records = $query->execute();
            if (is_null($records[0]->getpin())) {

                return null;
            } else {

                return $records;
            }
        } catch (Exception $ex) {
            throw new DaoException($ex->getMessage());
        }
    }
    
    public function saveFingerspotRecord(FingerspotRecordTemp $fingerspotRecordTemp) {
        
        try {
            
            $fingerspotRecordTemp->save();
            
            return $fingerspotRecordTemp;
            
        // @codeCoverageIgnoreStart
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }
    public function deleteFingerspotRecordTemp() {
        try {

            $query = Doctrine_Query::create()
                    ->delete("FingerspotRecordTemp");
                     
            $records = $query->execute();
            if (!empty($records)) {
                return true;
            } else {
                return false;
            }      
        } catch (Exception $ex) {
            throw new DaoException($ex->getMessage());
        }
    }
}


