<?php
/**
 * File containing ezpRestControllerTest class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

/**
 * Unit test for REST controller
 */
class ezpRestControllerTest extends ezpRestTestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct( $name, $data, $dataName );
    }

    /**
     * @group restResponseGroups
     * @group restController
     */
    public function testHasResponseGroup()
    {
        $r = new ezpRestRequest();
        $r->variables['ResponseGroups'] = array( 'foo', 'bar' );
        $controller = new ezpRestTestController( 'test', $r );

        $refObj = new ReflectionObject( $controller );
        $refMethod = $refObj->getMethod( 'hasResponseGroup' );
        $refMethod->setAccessible( true ); // Make the method public so we can test it individually. Won't work with PHP < 5.3.2

        self::assertTrue( $refMethod->invoke( $controller, 'foo' ) );
        self::assertTrue( $refMethod->invoke( $controller, 'bar' ) );
        self::assertFalse( $refMethod->invoke( $controller, 'baz' ) );
    }

    /**
     * @group restResponseGroups
     * @group restController
     */
    public function testGetResponseGroups()
    {
        $r = new ezpRestRequest();
        $r->variables['ResponseGroups'] = array( 'foo', 'bar' );
        $controller = new ezpRestTestController( 'test', $r );

        $refObj = new ReflectionObject( $controller );
        $refMethod = $refObj->getMethod( 'getResponseGroups' );
        $refMethod->setAccessible( true ); // Make the method public so we can test it individually. Won't work with PHP < 5.3.2

        $res = $refMethod->invoke( $controller );
        self::assertInternalType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $res );
        self::assertContains( 'foo', $res );
        self::assertContains( 'bar', $res );
    }

    /**
     * @group restResponseGroups
     * @group restController
     */
    public function testDefaultResponseGroups()
    {
        $r = new ezpRestRequest();
        $r->variables['ResponseGroups'] = array( 'foo', 'bar' );
        $controller = new ezpRestTestController( 'test', $r );

        $refObj = new ReflectionObject( $controller );
        $setDefaultRefMethod = $refObj->getMethod( 'setDefaultResponseGroups' );
        $setDefaultRefMethod->setAccessible( true );
        // Add a default response group which is not it provided ones
        // This response group should be registered as a valid response group
        $defaultResponseGroup = 'baz';
        $setDefaultRefMethod->invoke( $controller, array( $defaultResponseGroup ) );

        $getResponseGroupsRefMethod = $refObj->getMethod( 'getResponseGroups' );
        $getResponseGroupsRefMethod->setAccessible( true );
        $res = $getResponseGroupsRefMethod->invoke( $controller );
        self::assertInternalType( PHPUnit_Framework_Constraint_IsType::TYPE_ARRAY, $res );
        self::assertContains( $defaultResponseGroup, $res, 'Default response groups must be considered as valid response groups, even if not provided in URI string' );
    }

    /**
     * @group restContentVariables
     * @group restController
     */
    public function testHasContentVariable()
    {
        $r = new ezpRestRequest();
        $translation = 'eng-GB';
        $r->contentVariables = array( 'Translation' => $translation );
        $controller = new ezpRestTestController( 'test', $r );

        $refObj = new ReflectionObject( $controller );
        $refMethod = $refObj->getMethod( 'hasContentVariable' );
        $refMethod->setAccessible( true );
        self::assertTrue( $refMethod->invoke( $controller, 'Translation' ) );
        self::assertFalse( $refMethod->invoke( $controller, 'Foo' ) );
    }

    /**
     * @group restContentVariables
     * @group restController
     */
    public function testGetContentVariable()
    {
        $r = new ezpRestRequest();
        $translation = 'eng-GB';
        $r->contentVariables = array( 'Translation' => $translation );
        $controller = new ezpRestTestController( 'test', $r );

        $refObj = new ReflectionObject( $controller );
        $refMethod = $refObj->getMethod( 'getContentVariable' );
        $refMethod->setAccessible( true );
        self::assertEquals( $translation, $refMethod->invoke( $controller, 'Translation' ) );
        self::assertNull( $refMethod->invoke( $controller, 'NonExistentContentVariable' ) );
    }

    /**
     * @group restContentVariables
     * @group restController
     */
    public function testGetAllContentVariables()
    {
        $r = new ezpRestRequest();
        $providedContentVariables = array(
            'Translation' => 'eng-GB',
            'Foo' => 'FooValue',
            'Bar' => 'BarValue'
        );
        $r->contentVariables = $providedContentVariables;
        $controller = new ezpRestTestController( 'test', $r );

        $refObj = new ReflectionObject( $controller );
        $refMethod = $refObj->getMethod( 'getAllContentVariables' );
        $refMethod->setAccessible( true );
        self::assertSame( $providedContentVariables, $refMethod->invoke( $controller ) );
    }

}
?>
