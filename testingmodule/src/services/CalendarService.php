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
use USHolidays\Carbon;
use Sabre\VObject;

/**
 * @author    John Fuller
 * @package   TestingModule
 */
class CalendarService extends Component
{
    // Public Methods
    // =========================================================================

    /* *
     * Get bookings from ICS file
     * 
     * @return array
     */
    public function getBookings() {
        $vcalendar = VObject\Reader::read(
            fopen('../data/myevents.ics','r')
        );
        $bookingSchedule = array();
        foreach($vcalendar->VEVENT as $event) {
            $startFormat = strtotime((string)$event->DTSTART);
            $start = date('Y-m-d',$startFormat);
            $endFormat = strtotime((string)$event->DTEND);
            $end = date('Y-m-d',$endFormat);
            $bookingSchedule[] = [
                'start' => $start,
                'end' => $end,
            ];
        }
        return $bookingSchedule;
    }

    /* *
     * Get US holidays
     * 
     * @return array
     */
    public function getHolidays() {
        $carbon = Carbon::create(2022, 1, 1);
        $holidays = $carbon->getHolidaysByYear('all');

        $holidaySchedule = array();

        foreach ($holidays as $holiday) {
            $date = $holiday->date;
            $holidaySchedule[] = [
                "title" => $holiday->name,
                "start" => $holiday->date->format('Y-m-d'),
                "color" => "#800080",
            ];
        }
        return $holidaySchedule;
    }

}
