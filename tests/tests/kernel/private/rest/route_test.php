<?php
/**
 * File containing the ezpRestVersionRouteTest class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class ezpRestVersionRouteTest extends ezpTestCase
{
    public function testMatchSameAsInternalRoute()
    {
        $request = new ezcMvcRequest();
        $request->uri = '/foo';

        $internalRoute = new ezcMvcRailsRoute( '/foo', 'testDummyController', 'testDummyAction' );
        $versionRoute = new ezpRestVersionedRoute( $internalRoute, 1 );


        $internalRouteInfo = $internalRoute->matches( $request );
        $versionRouteInfo = $versionRoute->matches( $request );

        self::assertSame( '/foo', $internalRouteInfo->matchedRoute );
        self::assertSame( '/foo', $versionRouteInfo->matchedRoute );

        self::assertSame( 'testDummyController', $internalRouteInfo->controllerClass );
        self::assertSame( 'testDummyController', $versionRouteInfo->controllerClass );

        self::assertSame( 'testDummyAction', $internalRouteInfo->action );
        self::assertSame( 'testDummyAction', $versionRouteInfo->action );
    }

    public function testPrefix()
    {
        $request = new ezcMvcRequest();
        $request->uri = '/foo';

        $internalRoute = new ezcMvcRailsRoute( '/foo', 'testDummyController', 'testDummyAction' );
        $versionRoute = new ezpRestVersionedRoute( $internalRoute, 1 );

        $versionRoute->prefix( '/api' );

        // Test that our original request does not match against the versioned
        // route, that is, the internal route.
        //
        // Then we will alter our request object, with the correct prefix, and
        // check for a match.

        self::assertSame( null, $versionRoute->matches( $request ), 'The request should not match without a prefix.' );
        $request->uri = '/api/foo';
        self::assertInstanceOf( 'ezcMvcRoutingInformation', $versionRoute->matches( $request ), 'The request with api prefix, did not match the route as expected.' );
    }

    /**
     * @expected If version not found fall back to version 1
     */
    public function testApiVersionFallback()
    {
        self::markTestIncomplete( );
        $request = new ezcMvcRequest();
        // We don't specify version token in the URI
        $request->uri = '/api/foo';

        $versionTokenOptions = new ezpExtensionOptions();
        $versionTokenOptions->iniFile = 'rest.ini';
        $versionTokenOptions->iniSection = 'System';
        $versionTokenOptions->iniVariable = 'VersionTokenClass';
        $versionTokenOptions->handlerParams = array( $request, '/api' );


        $versionInfo = eZExtension::getHandlerClass( $versionTokenOptions );
        $versionInfo->filter();
        $versionInfo->filterRequestUri();

        self::assertSame( 1, ezpRestVersionTokenInterface::getApiVersion() );
    }

    public function testApiVersionMatchIfFound()
    {
        self::markTestIncomplete( );
    }

    public function testDifferentVersionsWithSameInternalRoute()
    {
        self::markTestIncomplete( );
    }

    public function testNewVersionYieldsAnotherController()
    {
        self::markTestIncomplete( );
    }

    public function testNewVersionYieldsAnotherAction()
    {
        self::markTestIncomplete( );
    }

    /**
     * what should happen in this case?
     * @return void
     */
    public function testVersionedRequestAgainstUnversionedRoute()
    {
        self::markTestIncomplete();
    }


}

