<?php

/**
 * File containing the eZNodeAssignmentTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */
class eZNodeAssignmentTest extends ezpDatabaseTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZNodeAssignment Unit Tests" );
    }

     /**
     * test fetchChildListByVersionStatus
     */
    public function testFetchChildListByVersionStatus()
    {
        //create object
        $top = new ezpObject( 'article', 2 );
        $top->name = 'TOP ARTICLE';
        $top->publish();
        $child = new ezpObject( 'article', $top->mainNode->node_id );
        $child->name = 'THIS IS AN ARTICLE';
        $child->publish();
        $child2 = new ezpObject( 'article', $top->mainNode->node_id );
        $child2->name = 'THIS IS AN ARTICLE2';
        $child2->publish();
        $pendingChild = new ezpObject( 'article', $top->mainNode->node_id );
        $pendingChild->name = 'THIS IS A PENDING ARTICLE';
        $pendingChild->publish();
        $version = $pendingChild->currentVersion();
        $version->setAttribute( 'status', eZContentObjectVersion::STATUS_PENDING );
        $version->store();

        $idList = array( $top->mainNode->node_id );
        $arrayResult = eZNodeAssignment::fetchChildListByVersionStatus( $idList, eZContentObjectVersion::STATUS_PENDING, false );
        $this->assertEquals( $pendingChild->id, $arrayResult[0]['contentobject_id'] );
        $arrayResult = eZNodeAssignment::fetchChildListByVersionStatus( $idList, eZContentObjectVersion::STATUS_PUBLISHED, true );
        $this->assertEquals( $child->id, $arrayResult[0]->attribute( 'contentobject_id') );

        $countResult = eZNodeAssignment::fetchChildCountByVersionStatus( $idList, eZContentObjectVersion::STATUS_PENDING );
        $this->assertEquals( 1, $countResult );
        $countResult = eZNodeAssignment::fetchChildCountByVersionStatus( $idList, eZContentObjectVersion::STATUS_PUBLISHED );
        $this->assertEquals( 2, $countResult );
    }
}
?>