<?php
/**
 * File containing eZURLTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
        self::assertInstanceOf( 'eZURL', $urlObj2 );
    }
}
?>
