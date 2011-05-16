<?php
/**
 * File containing eZURLTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZURLTest extends ezpDatabaseTestCase
{
    protected $backupGlobals = false;

    /**
     * @group ezurl
     */
    public function testFetchByUrl()
    {
        $url = 'http://ez.no/ezpublish';
        $urlObj = eZURL::create( $url );
        $urlObj->store();

        $urlObj2 = eZURL::fetchByUrl( $url );
        self::assertType( 'eZURL', $urlObj2 );
    }
}
?>
