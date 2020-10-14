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
class FingerspotService {

    private $fingerspotDao;

    /**
     * Get Attendance Data Access Object
     * @return AttendanceDao
     */
    public function getFingerspotDao() {

        if (is_null($this->fingerspotDao)) {

            $this->fingerspotDao = new FingerspotDao();
        }

        return $this->fingerspotDao;
    }

    /**
     * Set Attendance Data Access Object
     * @param AttendanceDao $AttendanceDao
     * @return void
     */
    public function setFingerspotDao(FingerspotDao $fingerspotDao) {

        $this->fingerspotDao = $fingerspotDao;
    }

    /**
     * get Attendance record 
     * @param $pin, $fromDate, $toDate
     * @return array of records 
     */
    public function getFingerspotRecord($pin, $fromDate, $toDate) {

        return $this->getFingerspotDao()->getFingerspotRecord($pin, $fromDate, $toDate);
    }

    /**
     * Get Timezone Array 
     * @param 
     * @return time zone values array
     */
    public function getTimezoneArray() {


        $this->timezoneArray[0] = 'GMT';
        $this->timezoneArray[1] = '1.0';
        $this->timezoneArray[2] = '2.0';
        $this->timezoneArray[3] = '3.0';
        $this->timezoneArray[4] = '4.0';
        $this->timezoneArray[5] = '5.0';
        $this->timezoneArray[6] = '5.5';
        $this->timezoneArray[7] = '6.0';
        $this->timezoneArray[8] = '7.0';
        $this->timezoneArray[9] = '8.0';
        $this->timezoneArray[10] = '9.0';
        $this->timezoneArray[11] = '9.5';
        $this->timezoneArray[12] = '+10.00';
        $this->timezoneArray[13] = '+11.00';
        $this->timezoneArray[14] = '+12.00';
        $this->timezoneArray[15] = '-11.00';
        $this->timezoneArray[16] = '-10.00';
        $this->timezoneArray[17] = '-9.00';
        $this->timezoneArray[18] = '-8.00';
        $this->timezoneArray[19] = '-7.00';
        $this->timezoneArray[20] = '-6.00';
        $this->timezoneArray[21] = '-5.00';
        $this->timezoneArray[22] = '-4.00';
        $this->timezoneArray[23] = '-3.50';
        $this->timezoneArray[24] = '-3.00';
        $this->timezoneArray[25] = '-2.00';
        $this->timezoneArray[26] = '-1.00';

        return $this->timezoneArray;
    }

    /**
     * Get etLocal Timezone
     * @param $clientTimeZoneOffset
     * @return time zone string
     */
    public function getLocalTimezone($clientTimeZoneOffset) {


        $offset = $clientTimeZoneOffset;
        $zonelist =
                array
                    (
                    'Kwajalein' => -12.00,
                    'Pacific/Midway' => -11.00,
                    'Pacific/Honolulu' => -10.00,
                    'America/Anchorage' => -9.00,
                    'America/Los_Angeles' => -8.00,
                    'America/Denver' => -7.00,
                    'America/Tegucigalpa' => -6.00,
                    'America/New_York' => -5.00,
                    'America/Caracas' => -4.50,
                    'America/Halifax' => -4.00,
                    'America/St_Johns' => -3.50,
                    'America/Argentina/Buenos_Aires' => -3.00,
                    'America/Sao_Paulo' => -3.00,
                    'Atlantic/South_Georgia' => -2.00,
                    'Atlantic/Azores' => -1.00,
                    'Europe/Dublin' => 0,
                    'Europe/Belgrade' => 1.00,
                    'Europe/Minsk' => 2.00,
                    'Asia/Kuwait' => 3.00,
                    'Asia/Tehran' => 3.50,
                    'Asia/Muscat' => 4.00,
                    'Asia/Yekaterinburg' => 5.00,
                    'Asia/Kolkata' => 5.50,
                    'Asia/Katmandu' => 5.45,
                    'Asia/Dhaka' => 6.00,
                    'Asia/Rangoon' => 6.50,
                    'Asia/Krasnoyarsk' => 7.00,
                    'Asia/Brunei' => 8.00,
                    'Asia/Seoul' => 9.00,
                    'Australia/Darwin' => 9.50,
                    'Australia/Canberra' => 10.00,
                    'Asia/Magadan' => 11.00,
                    'Pacific/Fiji' => 12.00,
                    'Pacific/Tongatapu' => 13.00
        );
        $index = array_keys($zonelist, $offset);
        if (sizeof($index) != 1)
            return false;
        return $index[0];
    }
}

?>
