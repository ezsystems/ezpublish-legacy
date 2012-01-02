<?php
/**
 * File containing the ezpTestCase class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class ezpTestCase extends PHPUnit_Framework_TestCase
{
    protected $sharedFixture;

    protected $backupGlobals = false;


    protected function setUp()
    {
        //echo("Running test : ". $this->getName() . "\n");
        parent::setUp();
    }

}

?>
