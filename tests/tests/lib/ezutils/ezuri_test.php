<?php
/**
 * File containing the eZURITest class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZURITest extends ezpTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZURI Unit Tests" );
    }

    public function setUp()
    {
        parent::setUp();

        // Set the RequestURI to a known value so that eZSys::requestURI()
        // returns something known and useful.
        $ezsys = eZSys::instance();
        $this->originalRequestURI = $ezsys->RequestURI;
        $ezsys->RequestURI = "/all/work/and/no/sleep/makes/(ole)/a/(dull)/boy";
    }

    public function tearDown()
    {
        // Make sure to restore the RequestURI in case other tests depends on
        // on the RequestURI variable.
        $ezsys = eZSys::instance();
        $ezsys->RequestURI = $this->originalRequestURI;
    }

    public function testInstanceRepeatedCallsWithoutUri()
    {
        $docstring = "Calling eZURI::instance() twice with eZSys::requestURI()"
                     . " as the URI should return the same instance of eZURI.";

        $ezuri = eZURI::instance();
        $ezuri2 = eZURI::instance();

        $this->assertSame( $ezuri2, $ezuri, $docstring );
    }

    public function testInstanceRepeatCallsWithSameUri()
    {
        $docstring = "Calling eZURI::instance( \$uri ) twice should return two seperate"
                     . " instances of eZURI when \$uri is not equal eZSys::requestURI()."
                     . " No caching in \$_GLOBALS should be done.";

        $uri = "/lorem/ipsum/dolor/(sit)/amet";

        $ezuri = eZURI::instance( $uri );
        $ezuri2 = eZURI::instance( $uri );

        $this->assertNotSame( $ezuri, $ezuri2, $docstring );
    }

    public function testInstanceRepeatCallsWithDifferentUri()
    {
        $docstring = "Calling eZURI::instance( \$uri ) with different \$uri"
                     . " each time should return seperate instances of eZURI"
                     . " when \$uri is not equal eZSys::requestURI(). No caching"
                     . " in \$_GLOBALS should be done.";

        $uri1 = "/i/have/(no)/imageination";
        $uri2 = "/lorem/ipsum/dolor/(sit)/amet";

        $ezuri = eZURI::instance();
        $ezuri1 = eZURI::instance( $uri1 );
        $ezuri2 = eZURI::instance( $uri2 );

        $this->assertNotSame( $ezuri, $ezuri1, $docstring );
        $this->assertNotSame( $ezuri, $ezuri2, $docstring );
        $this->assertNotSame( $ezuri1, $ezuri2, $docstring );
    }
}

?>