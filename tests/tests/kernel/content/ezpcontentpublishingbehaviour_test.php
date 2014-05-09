<?php
/**
 * File containing the eZContentClassRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class ezpContentPublishingBehaviourTest extends ezpTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "ezpContentPublishingBehaviour Tests" );
    }

    /**
     * Tests requesting the default behaviour
     *
     * Expected:
     * - getBehaviour returns an ezpContentPublishingBehaviour
     * - isTemporary = false
     * - disableAsynchronousPublishing = true
     */
    public function testGetDefaultBehaviour()
    {
        $behaviour = ezpContentPublishingBehaviour::getBehaviour();
        self::assertInstanceOf( 'ezpContentPublishingBehaviour', $behaviour );
        self::assertEquals( true, $behaviour->disableAsynchronousPublishing, "disableAsynchronousPublishing should be true by default" );
        self::assertEquals( false, $behaviour->isTemporary, "isTemporary should be false by default" );
    }

    /**
     * Tests setting a temporary the default behaviour
     *
     * Expected:
     * - 1st call to getBehaviour returns the assigned behaviour
     * - 2nd call to getBehaviour returns the default behaviour
     */
    public function testSetTemporaryBehaviour()
    {
        $newBehaviour = new ezpContentPublishingBehaviour();
        $newBehaviour->disableAsynchronousPublishing = false;
        $newBehaviour->isTemporary = true;

        ezpContentPublishingBehaviour::setBehaviour( $newBehaviour );

        $behaviour = ezpContentPublishingBehaviour::getBehaviour();
        self::assertEquals( $newBehaviour, $behaviour );

        $behaviour = ezpContentPublishingBehaviour::getBehaviour();
        self::assertEquals( new ezpContentPublishingBehaviour, $behaviour );
    }

    /**
     * Tests setting a non-temporary the default behaviour
     *
     * Expected:
     * - 1st call to getBehaviour returns the assigned behaviour
     * - 2nd call to getBehaviour returns the assigned behaviour
     */
    public function testSetBehaviour()
    {
        $newBehaviour = new ezpContentPublishingBehaviour();
        $newBehaviour->disableAsynchronousPublishing = false;
        $newBehaviour->isTemporary = false;

        ezpContentPublishingBehaviour::setBehaviour( $newBehaviour );

        $behaviour = ezpContentPublishingBehaviour::getBehaviour();
        self::assertEquals( $newBehaviour, $behaviour );

        $behaviour = ezpContentPublishingBehaviour::getBehaviour();
        self::assertEquals( $newBehaviour, $behaviour );
    }
}
?>
