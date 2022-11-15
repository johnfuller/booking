<?php
/**
 * hungerbook module for Craft CMS 3.x
 *
 * Hungerbook
 *
 * @link      https://hungerandransom.com
 * @copyright Copyright (c) 2022 John Fuller
 */

namespace modules\hungerbookmodule\services;

use modules\hungerbookmodule\HungerbookModule;

use Craft;
use craft\base\Component;
use USHolidays\Carbon;
use Carbon\CarbonPeriod;

/**
 * @author    John Fuller
 * @package   HungerbookModule
 */
class DateService extends Component
{
    // Public Methods
    // =========================================================================

    /* *
     * Does date range cover a weekend?
     * 
     * @return bool
     */
    public function isItWeekend($checkin, $checkout)
    {
        Carbon::macro('isBookingWeekend', function ($date) {
            return ($date->isFriday() OR $date->isSaturday());
        });
        $period = CarbonPeriod::create($checkin, $checkout);
        $weekend = false;
        foreach ($period as $date) {
            $carbon = Carbon::create($date);
            if($carbon->isBookingWeekend($date))
            {
                $weekend = true;
            }
        }
        return $weekend;
    }

    /* *
     * Does date range cover a holiday?
     * 
     * @return bool
     */
    public function isItHoliday($checkin, $checkout)
    {
        $period = CarbonPeriod::create($checkin, $checkout);
        $holiday = false;
        foreach ($period as $date) {
            $carbon = Carbon::create($date);
            if($carbon->isHoliday($date))
            {
                $holiday = true;
            }
        }
        return $holiday;
    }

    /* *
     * Does date range cover in-season pricing?
     * 
     * @return bool
     */
    public function isItSeason($checkin, $checkout) {
        $seasonStart = 'May';
        $seasonEnd = 'October';
        $season = false;
        if (Carbon::create($checkin)->betweenIncluded($seasonStart, $seasonEnd))
        {
            $season = true;
        }
        if (Carbon::create($checkout)->betweenIncluded($seasonStart, $seasonEnd))
        {
            $season = true;
        }
        return $season;
    }

    public function getHolidayName($checkin, $checkout) {
        $period = CarbonPeriod::create($checkin, $checkout);
        $holidayName = '';
        foreach ($period as $date) {
            $carbon = Carbon::create($date);
            if($carbon->isHoliday($date))
            {
                $holidayName = $carbon->getHolidayName($date);
            }
        }
        return $holidayName;
    }

}
