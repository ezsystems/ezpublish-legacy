<?php
/**
 * File containing eZURLTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
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
