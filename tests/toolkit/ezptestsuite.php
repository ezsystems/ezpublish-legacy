<?php
/**
 * File containing the ezpTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class ezpTestSuite extends PHPUnit_Framework_TestSuite
{
    protected $sharedFixture;

    /**
     * @var eZScript
     */
    protected static $script;

    public function __construct($theClass = '', $name = '')
    {
        parent::__construct($theClass, $name);

        if ( !( self::$script instanceof eZScript ) )
        {
            self::$script = eZScript::instance(
                array(
                    'description' => "eZ Publish Test Runner\n\nsets up an eZ Publish testing environment\n",
                    'use-session' => false,
                    'use-modules' => true,
                    'use-extensions' => true
                )
            );

            // Override INI override folder from settings/override to
            // tests/settings to not read local override settings
            $ini = eZINI::instance();
            $ini->setOverrideDirs( array( array( 'tests/settings', true ) ), 'override' );
            $ini->loadCache();

            // Be sure to have clean content language data
            eZContentLanguage::expireCache();

            self::$script->startup();
            self::$script->initialize();

            // Avoids Fatal error: eZ Publish did not finish its request if die() is used.
            eZExecution::setCleanExit();
        }
    }

    public function __destruct()
    {
        if ( self::$script instanceof eZScript )
        {
            self::$script->shutdown(0);
        }
    }
}

?>
