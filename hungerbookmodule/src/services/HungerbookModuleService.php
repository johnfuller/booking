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

/**
 * HungerbookModuleService Service
 *
 * @author    John Fuller
 * @package   HungerbookModule
 * @since     1.0.0
 */
class HungerbookModuleService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Categegorize the booking date for proper pricing
     *
     * @return mixed
     */
    public function dateCategory($checkin, $checkout)
    {
        // Note order of importance
        $category = 'off-season';
        if (HungerbookModule::getInstance()->dateService->isItHoliday($checkin, $checkout)) {
            $name = HungerbookModule::getInstance()->dateService->getHolidayName($checkin, $checkout);
            return 'holiday';
        }
        if (HungerbookModule::getInstance()->dateService->isItWeekend($checkin, $checkout)) {
            return 'weekend';
        }
        if (HungerbookModule::getInstance()->dateService->isItSeason($checkin, $checkout)) {
            return 'season';
        }
    }
}
