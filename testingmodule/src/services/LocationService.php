<?php
/**
 * testing module for Craft CMS 3.x
 *
 * Helper module for testing
 *
 * @link      https://hungerandransom.com
 * @copyright Copyright (c) 2022 John Fuller
 */

namespace modules\testingmodule\services;

use modules\testingmodule\TestingModule;

use Craft;
use craft\base\Component;

/**
 * @author    John Fuller
 * @package   TestingModule
 */
class LocationService extends Component
{

    public function connect() {
        try {
            $myPDO = new \PDO('sqlite:../data/bookings.db');
            $myPDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }
        return $myPDO;
    }

    public function getLocations() {
        $myPDO = $this->connect();
        $getProperties = $myPDO->query('SELECT * FROM `Properties`');
        $properties = array();
        while ($row = $getProperties->fetch(\PDO::FETCH_ASSOC)) {
            $properties[] = $row['property_id'];
        }
        // print_r($properties);
        // exit();
        return $properties;
    }

    public function getBookingsFromDate($start, $end) {
        $myPDO = $this->connect();
        $formatStart = date("Ymd", strtotime($start)); 
        $formatEnd = date("Ymd", strtotime($end)); 
        $getBookings = $myPDO->query('SELECT * FROM `Bookings` WHERE (`start` >= "'.$formatStart.'" AND `start` <= "'.$formatEnd.'") OR (`end` >= "'.$formatStart.'" AND `end` <= "'.$formatEnd.'")');
        $bookings = array();
        while ($row = $getBookings->fetch(\PDO::FETCH_ASSOC)) {
            $bookings[] = $row['property_id'];
        }
        // print_r($bookings);
        // print_r($formatEnd);
        // print_r($formatStart);
        // exit();
        return $bookings;
    }

    public function listBookingsOnCalendar() {
        $myPDO = $this->connect();
        $getBookings = $myPDO->query('SELECT * FROM `Bookings`');
        $bookingSchedule = array();
        while ($row = $getBookings->fetch(\PDO::FETCH_ASSOC)) {
            $formatStart = strtotime($row['start']); 
            $formatEnd = strtotime($row['end']); 
            $bookingSchedule[] = [
                "title" => $row['property_id'],
                "start" => date('Y-m-d', $formatStart),
                "end" => date('Y-m-d', $formatEnd),
                "color" => "#008000"
            ];
        }
        // print_r($bookings);
        // exit();
        return $bookingSchedule;
    }

}