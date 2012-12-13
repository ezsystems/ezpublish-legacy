<?php
/**
 * File containing the eZLocaleRegression class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 *
 */

class eZLocaleRegression extends ezpTestCase
{
    protected $defaultTimezone;

    public function setUp()
    {
        $this->defaultTimezone = date_default_timezone_get();
        date_default_timezone_set( 'Europe/Paris' );
        parent::setUp();
    }

    public function tearDown()
    {
        date_default_timezone_set( $this->defaultTimezone );
        parent::tearDown();
    }

    /**
     * Test regression for issue #14155: Warning given when empty string given
     * as input to formatDateType() and formatDateTimeType() in eZLocale.
     *
     * @link http://issues.ez.no/14155
     */
    public function testShortDateEmptyString()
    {
        $locale = eZLocale::instance( "eng-GB" );
        $result = $locale->formatShortDate( '' );
        self::assertEquals( "01/01/1970", $result );
    }

    /**
     * Test regression for issue #14155: Warning given when empty string given
     * as input to formatDateType() and formatDateTimeType() in eZLocale.
     *
     * @link http://issues.ez.no/14155
     */
    public function testShortDateTimeEmptyString()
    {
        $locale = eZLocale::instance( "eng-GB" );
        $result = $locale->formatShortDateTime( '' );
        self::assertEquals( "01/01/1970 1:00 am", $result );
    }
}

?>
