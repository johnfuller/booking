<?php
/**
 * testing module for Craft CMS 3.x
 *
 * Helper module for testing
 *
 * @link      https://hungerandransom.com
 * @copyright Copyright (c) 2022 John Fuller
 */

namespace modules\testingmodule\variables;

use modules\testingmodule\TestingModule;

use Craft;

/**
 * testing Variable
 *
 * Craft allows modules to provide their own template variables, accessible from
 * the {{ craft }} global variable (e.g. {{ craft.testingModule }}).
 *
 * https://craftcms.com/docs/plugins/variables
 *
 * @author    John Fuller
 * @package   TestingModule
 * @since     1.0.0
 */
class TestingModuleVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Whatever you want to output to a Twig template can go into a Variable method.
     * You can have as many variable functions as you want.  From any Twig template,
     * call it like this:
     *
     *     {{ craft.testingModule.exampleVariable }}
     *
     * Or, if your variable requires parameters from Twig:
     *
     *     {{ craft.testingModule.exampleVariable(twigValue) }}
     *
     * @param null $optional
     * @return string
     */
    public function exampleVariable($optional = null)
    {
        $result = "And away we go to the Twig template...";
        if ($optional) {
            $result = "I'm feeling optional today...";
        }
        return $result;
    }
}
