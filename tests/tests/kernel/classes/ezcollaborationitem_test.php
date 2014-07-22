<?php
/**
 * File containing the eZCollaborationItemTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

class eZCollaborationItemTest extends ezpDatabaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        $participantsList = array(
            $this->createParticipantLinkPartialMock(
                array(
                    'collaboration_id' => 1,
                    'participant_id' => 1,
                    'participant_type' => eZCollaborationItemParticipantLink::TYPE_USER,
                )
            ),
            $this->createParticipantLinkPartialMock(
                array(
                    'collaboration_id' => 1,
                    'participant_id' => 2,
                    'participant_type' => eZCollaborationItemParticipantLink::TYPE_USERGROUP,
                )
            )
        );

        $this->getCollaborationItemPartialMock()
            ->expects( $this->any() )
            ->method( 'participantList' )
            ->will( $this->returnValue( $participantsList ) );
    }

    /**
     * @dataProvider providerForIsParticipant
     */
    public function testIsParticipant( $expectedResult, $userId, array $groupIdArray = array() )
    {
        $user = $this->createUserMock( $userId, $groupIdArray );
        self::assertEquals(
            $expectedResult,
            $this->getCollaborationItemPartialMock()->userIsParticipant( $user )
        );
    }

    public function providerForIsParticipant()
    {
        return array(
            array( true, 1 ),
            array( false, 2 ),

            array( true, 3, array( 2 ) ),
            array( true, 3, array( 2, 3 ) ),
            array( false, 3, array( 3 ) ),
        );
    }


    /**
     * @return eZUser|PHPUnit_Framework_MockObject_MockObject
     */
    protected function createUserMock( $userId, array $groupIdArray = array() )
    {
        $mock = $this->getMockBuilder( 'eZUser' )
            ->setMethods( array( 'groups', 'attribute' ) )
            ->setConstructorArgs( array( 'contentobject_id' => $userId ) )
            ->getMock();

        $groups = array();
        foreach ( $groupIdArray as $groupId )
        {
            $groups[] = new eZContentObject( array( 'id' => $groupId ) );
        }

        $mock->expects( $this->any() )
            ->method( 'groups' )
            ->will( $this->returnValue( $groups ) );

        $mock->expects( $this->any() )
            ->method( 'attribute' )
            ->with( 'contentobject_id' )
            ->will( $this->returnValue( $userId ) );

        return $mock;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|eZCollaborationItem
     */
    protected function getCollaborationItemPartialMock()
    {
        if ( !isset( $this->collaborationItemPartialMock ) )
        {
            $this->collaborationItemPartialMock = $this->getMockBuilder( 'eZCollaborationItem' )
                ->setMethods( array( 'participantList' ) )
                ->disableOriginalConstructor()
                ->getMock();
        }
        return $this->collaborationItemPartialMock;
    }

    /**
     * @return eZCollaborationItemParticipantLink|PHPUnit_Framework_MockObject_MockObject
     */
    protected function createParticipantLinkPartialMock( $row )
    {
        $mock = $this->getMockBuilder( 'eZCollaborationItemParticipantLink' )
            ->setMethods( array( 'participant' ) )
            ->setConstructorArgs( array( $row ) )
            ->getMock();

        $returnValue = $row['participant_type'] == eZCollaborationItemParticipantLink::TYPE_USER
            ? new eZUser( array( 'contentobject_id' => $row['participant_id'] ) )
            : new eZContentObject( array( 'id' => $row['participant_id'] ) );

        $mock->expects( $this->any() )
            ->method( 'participant' )
            ->will( $this->returnValue( $returnValue ) );

        return $mock;
    }

    /** @var $collaborationItemPartialMock PHPUnit_Framework_MockObject_MockObject|eZCollaborationItem */
    protected $collaborationItemPartialMock;
}
