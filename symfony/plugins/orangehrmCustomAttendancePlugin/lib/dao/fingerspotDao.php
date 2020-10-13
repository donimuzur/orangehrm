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
class FingerspotDao {
    /**
     * Get Attendance Record
     * @param $$employeeId,$date
     * @return attendance records
     */
    public function getFingerstpotRecord($pin, $fromDate, $toDate) {

        $from = $fromDate . " " . "00:" . "00:" . "00";
        $end = $toDate . " " . "23:" . "59:" . "59";

        try {

            $query = Doctrine_Query::create()
                    ->from("FingerspotAttendance")
                    ->where("pin = ?", $pin)
                    ->andWhere("scan_date >= ?", $from)
                    ->andWhere("scan_date <= ?", $end);
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


