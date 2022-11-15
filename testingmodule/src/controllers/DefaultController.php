<?php
/**
 * testing module for Craft CMS 3.x
 *
 * Helper module for testing
 *
 * @link      https://hungerandransom.com
 * @copyright Copyright (c) 2022 John Fuller
 */

namespace modules\testingmodule\controllers;

use modules\testingmodule\TestingModule;

use Craft;
use craft\web\Controller;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your module’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    John Fuller
 * @package   TestingModule
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [
        'index',
        'do-something',
        'category', 
        'calendar-helper',
        'available-bookings',
        'parse-parameters'
    ];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our module's index action URL,
     * e.g.: actions/testing-module/default
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $result = 'Welcome to the DefaultController actionIndex() method';

        return $result;
    }

    /**
     * Handle a request going to our module's actionDoSomething URL,
     * e.g.: actions/testing-module/default/do-something
     *
     * @return mixed
     */
    public function actionDoSomething()
    {
        testingmodule::$instance->locationService->getLocations();
        // $holidays = testingmodule::$instance->locationService->getLocations();
        // $holidays = \modules\testingmodule\TestingModule::getInstance();
        print_r('done');
        exit();
    }

    public function actionCategory()
    {
        $booking = Craft::$app->request->queryParams;
        $error = true;
        if (isset($booking['start'])) {
            $error = false;
            $start = $booking['start'];
        }
        if (isset($booking['end'])) {
            $error = false;
            $end = $booking['end'];
        }
        if ($error) {
            return "Use start and end followed by dates as query strings";
        }
        $category = \modules\hungerbookmodule\HungerbookModule::getInstance()->hungerbookModuleService->dateCategory($start, $end);
        return $category;
    }

    public function actionCalendarHelper()
    {
        $holidays = testingmodule::$instance->calendarService->getholidays();
        $bookings = TestingModule::$instance->calendarService->getBookings();
        $bookingsFromDB = TestingModule::$instance->locationService->listBookingsOnCalendar();
        $schedule = array_merge($holidays, $bookings, $bookingsFromDB);

        return $this->asJson(
            $schedule
        );
    }

    public function actionAvailableBookings () {
        $booking = Craft::$app->request->queryParams;
        $error = true;
        if (isset($booking['start'])) {
            $error = false;
            $start = $booking['start'];
        }
        if (isset($booking['end'])) {
            $error = false;
            $end = $booking['end'];
        }
        if ($error) {
            return "Use start and end followed by dates as query strings";
        }
        $bookings = TestingModule::$instance->locationService->getBookingsFromDate($start, $end);
        $properties = TestingModule::$instance->locationService->getLocations();

        $available = array_diff($properties, $bookings);

        // @TODO JF run some checks for correct output

        return $this->asJson(
            $available
        );
    }

    public function actionParseParameters() {
        $dates = Craft::$app->request->getQueryParam('dates');
        $guests = Craft::$app->request->getQueryParam('guests', 0);
        $pets = Craft::$app->request->getQueryParam('pets', 0);
        $param = array();
        if (isset($dates)) {
            $prep = [explode(' - ', $dates)];
            $param['dates'] = $prep;
        }
        $param['guests'] = $guests;
        $param['pets'] = $pets;
        return $this->asJson($param);
    }


}
